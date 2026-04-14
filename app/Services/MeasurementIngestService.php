<?php

namespace App\Services;

use App\Models\Measurement;
use App\Models\OriginalMeasurement;
use Illuminate\Support\Facades\DB;

class MeasurementIngestService
{
    private const EXTRAPOLATABLE_FIELDS = [
        'temperature',
        'dewpoint_temperature',
        'air_pressure_station',
        'air_pressure_sea_level',
        'visibility',
        'wind_speed',
        'percipation',
        'snow_depth',
        'cloud_cover',
        'wind_direction',
        'conditions',
    ];

    private const HISTORY_WINDOW = 30;

    public function handleOne(array $sanitizedItem): Measurement
    {
        return DB::transaction(function () use ($sanitizedItem) {
            $measurement = $this->createMeasurementFromSanitizedItem($sanitizedItem);
            $measurement->save();
            $filledMissingFields = $this->fillMissingMeasurementsUsingHistory($measurement);
            $this->correctTemperatureIfUnreal($measurement);
            $measurement->save();

            return $measurement;
        });
    }

    private function createMeasurementFromSanitizedItem(array $sanitizedItem): Measurement
    {
        $measurement = new Measurement;

        $measurement->fill([
            'station' => $sanitizedItem['STN'] ?? null,
            'date' => $sanitizedItem['DATE'] ?? null,
            'time' => $sanitizedItem['TIME'] ?? null,

            'temperature' => $this->toFloatOrNull($sanitizedItem['TEMP'] ?? null),
            'dewpoint_temperature' => $this->toFloatOrNull($sanitizedItem['DEWP'] ?? null),
            'air_pressure_station' => $this->toFloatOrNull($sanitizedItem['STP'] ?? null),
            'air_pressure_sea_level' => $this->toFloatOrNull($sanitizedItem['SLP'] ?? null),
            'visibility' => $this->toFloatOrNull($sanitizedItem['VISIB'] ?? null),
            'wind_speed' => $this->toFloatOrNull($sanitizedItem['WDSP'] ?? null),
            'percipation' => $this->toFloatOrNull($sanitizedItem['PRCP'] ?? null),
            'snow_depth' => $this->toFloatOrNull($sanitizedItem['SNDP'] ?? null),
            'cloud_cover' => $this->toFloatOrNull($sanitizedItem['CLDC'] ?? null),
            'wind_direction' => $this->toIntOrNull($sanitizedItem['WNDDIR'] ?? null),
            'conditions' => $sanitizedItem['FRSHTT'] ?? null,
        ]);

        return $measurement;
    }

    private function fillMissingMeasurementsUsingHistory(Measurement $measurement): array
    {
        $filled = [];

        foreach (self::EXTRAPOLATABLE_FIELDS as $field) {
            if ($measurement->{$field} !== null) {
                continue;
            }

            $extrapolated = $this->extrapolateFromPrevious30($measurement, $field);

            if ($extrapolated === null) {
                continue;
            }

            $measurement->{$field} = $extrapolated;
            $filled[] = $field;

            $this->recordMissingFieldCorrection($measurement, $field);
        }

        return $filled;
    }

    private function correctTemperatureIfUnreal(Measurement $measurement): void
    {
        if ($measurement->temperature === null) {
            return;
        }

        $expected = $this->extrapolateFromPrevious30($measurement, 'temperature');
        if ($expected === null) {
            return;
        }

        $actual = $measurement->temperature;

        if (! $this->isFiniteNumber($expected) || ! $this->isFiniteNumber($actual)) {
            return;
        }

        if ((float) $expected <= 0.0) {
            return;
        }

        $expectedF = (float) $expected;
        $actualF = (float) $actual;

        $expectedK = $expectedF + 273.15;
        $actualK = $actualF + 273.15;
        $deviation = abs($actualK - $expectedK) / abs($expectedK);
        if ($deviation < 0.2) {
            return;
        }

        $lower = $expectedF * 0.8;
        $upper = $expectedF * 1.2;
        $corrected = $this->clamp($actualF, $lower, $upper);

        $this->recordInvalidTemperatureCorrection($measurement, $actualF);

        $measurement->temperature = $corrected;
    }

    private function recordMissingFieldCorrection(Measurement $measurement, string $field): void
    {
        $originalMeasurement = new OriginalMeasurement;
        $originalMeasurement->missing_field = $field;
        $originalMeasurement->corrected_measurement = $measurement->id;
        $originalMeasurement->save();
    }

    private function recordInvalidTemperatureCorrection(Measurement $measurement, float $invalidTemperature): void
    {
        $originalMeasurement = new OriginalMeasurement;
        $originalMeasurement->invalid_temperature = $invalidTemperature;
        $originalMeasurement->corrected_measurement = $measurement->id;
        $originalMeasurement->save();
    }

    private function extrapolateFromPrevious30(Measurement $measurement, string $field): ?float
    {
        if (! $this->isNumericField($field)) {
            return null;
        }

        $station = $measurement->station !== null ? (string) $measurement->station : null;
        if ($station === null || $measurement->date === null || $measurement->time === null) {
            return null;
        }

        $query = new Measurement()->newModelQuery();

        $query->where('station', $station);

        $query->where(function ($q) use ($measurement) {
            $q->where('date', '<', $measurement->date)->orWhere(function ($q2) use ($measurement) {
                $q2->where('date', '=', $measurement->date)->where('time', '<', $measurement->time);
            });
        });

        if ($measurement->id !== null) {
            $query->where('id', '<>', $measurement->id);
        }

        $history = $query->orderBy('date', 'desc')->orderBy('time', 'desc')->orderBy('id', 'desc')->limit(self::HISTORY_WINDOW)->get();

        if ($history->count() < self::HISTORY_WINDOW) {
            return null;
        }

        $values = [];
        foreach ($history as $m) {
            $v = $m->{$field};

            if ($v === null) {
                continue;
            }
            if (! is_int($v) && ! is_float($v)) {
                continue;
            }
            $vf = (float) $v;
            if (! is_finite($vf)) {
                continue;
            }

            $values[] = $vf;
        }

        if (count($values) < 2) {
            return null;
        }

        $diffs = [];
        for ($i = 1; $i < count($values); $i++) {
            $diffs[] = $values[$i - 1] - $values[$i];
        }

        $avgDiff = array_sum($diffs) / count($diffs);

        return $values[0] + $avgDiff;
    }

    private function isNumericField(string $field): bool
    {
        return in_array(
            $field,
            ['temperature', 'dewpoint_temperature', 'air_pressure_station', 'air_pressure_sea_level', 'visibility', 'wind_speed', 'percipation', 'snow_depth', 'cloud_cover', 'wind_direction'],
            true,
        );
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
            if ($trimmed === '' || ! is_numeric($trimmed)) {
                return null;
            }
            $f = (float) $trimmed;

            return is_finite($f) ? $f : null;
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
            $i = (int) $value;

            return $i;
        }
        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '' || ! is_numeric($trimmed)) {
                return null;
            }

            return (int) $trimmed;
        }

        return null;
    }

    private function isFiniteNumber(mixed $value): bool
    {
        if (! is_float($value) && ! is_int($value)) {
            return false;
        }

        return is_finite((float) $value);
    }

    private function clamp(float $value, float $min, float $max): float
    {
        if ($value < $min) {
            return $min;
        }
        if ($value > $max) {
            return $max;
        }

        return $value;
    }
}
