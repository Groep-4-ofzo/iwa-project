<?php

namespace App\Services;

use App\Models\Measurement;
use App\Models\OriginalMeasurement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MeasurementIngestService
{
    private const MEASUREMENT_FIELDS = [
        "station",
        "date",
        "time",
        "temperature",
        "dewpoint_temperature",
        "air_pressure_station",
        "air_pressure_sea_level",
        "visibility",
        "wind_speed",
        "percipation",
        "snow_depth",
        "conditions",
        "cloud_cover",
        "wind_direction",
    ];

    public function handleOne(array $sanitizedItem): Measurement
    {
        return DB::transaction(function () use ($sanitizedItem) {
            $measurement = new Measurement();
            $measurement->fill([
                "station" => $sanitizedItem["STN"] ?? null,
                "date" => $sanitizedItem["DATE"] ?? null,
                "time" => $sanitizedItem["TIME"] ?? null,
                "temperature" => $this->toFloatOrNull($sanitizedItem["TEMP"] ?? null),
                "dewpoint_temperature" => $this->toFloatOrNull($sanitizedItem["DEWP"] ?? null),
                "air_pressure_station" => $this->toFloatOrNull($sanitizedItem["STP"] ?? null),
                "air_pressure_sea_level" => $this->toFloatOrNull($sanitizedItem["SLP"] ?? null),
                "visibility" => $this->toFloatOrNull($sanitizedItem["VISIB"] ?? null),
                "wind_speed" => $this->toFloatOrNull($sanitizedItem["WDSP"] ?? null),
                "percipation" => $this->toFloatOrNull($sanitizedItem["PRCP"] ?? null),
                "snow_depth" => $this->toFloatOrNull($sanitizedItem["SNDP"] ?? null),
                "conditions" => $sanitizedItem["FRSHTT"] ?? null,
                "cloud_cover" => $this->toFloatOrNull($sanitizedItem["CLDC"] ?? null),
                "wind_direction" => $this->toIntOrNull($sanitizedItem["WNDDIR"] ?? null),
            ]);
            $measurement->save();

            [$hasExtremeTemperature, $interpolatedTemperature] = $this->isTemperatureExtreme($measurement);
            if ($hasExtremeTemperature) {
                $invalidTemperature = $measurement->temperature;

                $originalMeasurement = new OriginalMeasurement();
                $originalMeasurement->invalid_temperature = $invalidTemperature;
                $originalMeasurement->corrected_measurement = $measurement->id;
                $originalMeasurement->save();

                $measurement->temperature = $interpolatedTemperature;
            }

            $missingFields = $this->missingFields($measurement);
            foreach ($missingFields as $field) {
                $extrapolatedValue = $this->extrapolateFromPrevious30($measurement, $field);

                $measurement->{$field} = $extrapolatedValue;
                $originalMeasurement = new OriginalMeasurement();
                $originalMeasurement->missing_field = $field;
                $originalMeasurement->corrected_measurement = $measurement->id;
                $originalMeasurement->save();
            }

            $measurement->save();

            return $measurement;
        });
    }

    private function missingFields(Measurement $measurement): array
    {
        $missing = [];
        foreach (self::MEASUREMENT_FIELDS as $field) {
            if ($measurement->{$field} === null) {
                $missing[] = $field;
            }
        }
        return $missing;
    }

    private function isTemperatureExtreme(Measurement $measurement): array
    {
        if ($measurement->temperature === null) {
            return [false, null];
        }

        $expected = $this->extrapolateFromPrevious30($measurement, "temperature");

        if ($expected === null || !$this->isFiniteNumber($expected) || !$this->isFiniteNumber($measurement->temperature)) {
            return [false, null];
        }

        $v = abs((float) $expected);
        if ($v < 1e-9) {
            return [false, null];
        }

        $deviation = abs(((float) $measurement->temperature) - ((float) $expected)) / $v;

        return [$deviation >= 0.2, $expected];
    }

    private function extrapolateFromPrevious30(Measurement $measurement, string $field): ?float
    {
        $station = $measurement->station !== null ? (string) $measurement->station : null;

        $history = Measurement::query()
            ->when($station !== null, fn($q) => $q->where("station", $station))
            ->where(function ($q) use ($measurement) {
                $q->where("date", "<", $measurement->date)
                    ->orWhere(function ($q2) use ($measurement) {
                        $q2->where("date", "=", $measurement->date)
                            ->where("time", "<", $measurement->time);
                    })
                    ->orWhere(function ($q3) use ($measurement) {
                        $q3->where("date", "=", $measurement->date)
                            ->where("time", "=", $measurement->time);
                    });
            })
            ->orderBy("date", "desc")
            ->orderBy("time", "desc")
            ->orderBy("id", "desc")
            ->limit(30)
            ->get();

        if (count($history) < 30) {
            return null;
        }

        $count = $history->count();

        if ($count === 0) {
            return null;
        }

        if ($count < 2) {
            return (float) $history->first()->{$field};
        }

        $diffs = [];
        for ($i = 1; $i < $count; $i++) {
            $valCurrent = (float) $history[$i]->{$field};
            $valPrevious = (float) $history[$i - 1]->{$field};
            $diffs[] = $valCurrent - $valPrevious;
        }

        $avgDiff = array_sum($diffs) / count($diffs);

        return (float) $history->last()->{$field} + $avgDiff;
    }

    private function toFloatOrNull(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }
        if (is_float($value)) {
            return $value;
        }
        if (is_int($value)) {
            return (float) $value;
        }
        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === "") {
                return null;
            }
            if (!is_numeric($trimmed)) {
                return null;
            }
            return (float) $trimmed;
        }

        return null;
    }

    private function toIntOrNull(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === "") {
                return null;
            }
            if (!is_numeric($trimmed)) {
                return null;
            }
            return (int) $trimmed;
        }

        return null;
    }

    private function isFiniteNumber(mixed $value): bool
    {
        if (!is_float($value) && !is_int($value)) {
            return false;
        }

        $f = (float) $value;

        return is_finite($f);
    }
}
