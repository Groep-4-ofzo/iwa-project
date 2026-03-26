@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <a href="{{ route('admin.subscriptionTypes.index') }}" class="inline-block mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        ← Back
    </a>
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">

        <h1 class="text-xl font-bold mb-4">Add Subscription Type</h1>

        <form action="{{ route('admin.subscriptionTypes.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Description <span class="text-gray-500">(optional)</span></label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Number of Stations <span class="text-gray-500">(optional)</span></label>
                <input type="number" name="nr_stations" value="{{ old('nr_stations') }}" class="w-full border rounded px-3 py-2">
                @error('nr_stations') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Frequency in Hours <span class="text-gray-500">(optional)</span></label>
                <input type="number" name="frequency_in_hours" value="{{ old('frequency_in_hours') }}" class="w-full border rounded px-3 py-2">
                @error('frequency_in_hours') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Frequency in Days <span class="text-gray-500">(optional)</span></label>
                <input type="number" name="frequency_in_days" value="{{ old('frequency_in_days') }}" class="w-full border rounded px-3 py-2">
                @error('frequency_in_days') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Continuous</label>
                <input type="checkbox" name="continuous" value="1" {{ old('continuous') ? 'checked' : '' }} class="w-4 h-4">
                @error('continuous') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Price per Station</label>
                <input type="number" step="0.01" name="price_per_station" value="{{ old('price_per_station') }}" class="w-full border rounded px-3 py-2">
                @error('price_per_station') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Valid Through <span class="text-gray-500">(optional)</span></label>
                <input type="date" name="valid_through" value="{{ old('valid_through') }}" class="w-full border rounded px-3 py-2">
                @error('valid_through') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@endsection