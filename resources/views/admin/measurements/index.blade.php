@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Metingen</h1>

    <form action="{{ route('admin.measurements.index') }}" method="GET" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-5">
        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 mb-3">
            
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Station</label>
                <select name="station" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm bg-white">
                    <option value="">Alle stations</option>
                    @foreach($stations as $s)
                        <option value="{{ $s->name }}" 
                            @if(request('station') == $s->name) selected @endif>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Datum van</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Datum tot</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Tijd van</label>
                <input type="time" name="time_from" value="{{ request('time_from') }}" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Tijd tot</label>
                <input type="time" name="time_to" value="{{ request('time_to') }}" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Min temp °C</label>
                <input type="number" name="temp_min" step="0.1" value="{{ request('temp_min') }}" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Max temp °C</label>
                <input type="number" name="temp_max" step="0.1" value="{{ request('temp_max') }}" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Condities</label>
                <input type="text" name="conditions" value="{{ request('conditions') }}" placeholder="bijv. regen" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-1.5 rounded font-medium">Filteren</button>
            <a href="{{ route('admin.measurements.index') }}" class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 text-sm px-4 py-1.5 rounded text-center">Reset</a>
            <span class="ml-auto text-xs text-gray-400">{{ $measurements->total() }} resultaten</span>
        </div>
    </form>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Datum</th>
                    <th class="px-4 py-3 text-left">Tijd</th>
                    <th class="px-4 py-3 text-left">Station</th>
                    <th class="px-4 py-3 text-left">Temp °C</th>
                    <th class="px-4 py-3 text-left">Dauwpunt °C</th>
                    <th class="px-4 py-3 text-left">Luchtdruk</th>
                    <th class="px-4 py-3 text-left">Zichtb. km</th>
                    <th class="px-4 py-3 text-left">Wind km/u</th>
                    <th class="px-4 py-3 text-left">Windricht. °</th>
                    <th class="px-4 py-3 text-left">Neerslag mm</th>
                    <th class="px-4 py-3 text-left">Bewolking</th>
                    <th class="px-4 py-3 text-left">Condities</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($measurements as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->date ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->time ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap font-medium">
                        {{ $m->getStation->name ?? '-' }}
                    </td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->temperature ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->dewpoint_temperature ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->air_pressure_station ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->visibility ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->wind_speed ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->wind_direction ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->percipation ?? '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $m->cloud_cover ? $m->cloud_cover . '/8' : '-' }}</td>
                    <td class="px-4 py-2.5 whitespace-nowrap">
                        @if($m->conditions)
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                {{ $m->conditions }}
                            </span>
                        @else - @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="px-4 py-10 text-center text-gray-400">Geen metingen gevonden.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $measurements->links() }}
    </div>
</div>
@endsection