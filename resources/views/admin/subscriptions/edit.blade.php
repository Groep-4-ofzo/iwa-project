@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
        <a href="{{ route('admin.subscriptions.index') }}" class="inline-block mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        ← Back
    </a>
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Edit Subscription</h1>

        <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 font-medium">Identifier</label>
                <input type="text" name="identifier" value="{{ old('identifier', $subscription->identifier) }}" class="w-full border rounded px-3 py-2">
                @error('identifier') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Company</label>
                <select name="company" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select a company --</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company', $subscription->company) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Subscription Type</label>
                <select name="type" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select a type --</option>
                    @foreach($subscriptionTypes as $type)
                        <option value="{{ $type->id }}" {{ old('type', $subscription->type) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', $subscription->start_date) }}" class="w-full border rounded px-3 py-2">
                @error('start_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">End Date <span class="text-gray-500">(optional)</span></label>
                <input type="date" name="end_date" value="{{ old('end_date', $subscription->end_date) }}" class="w-full border rounded px-3 py-2">
                @error('end_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $subscription->price) }}" class="w-full border rounded px-3 py-2">
                @error('price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Notes <span class="text-gray-500">(optional)</span></label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes', $subscription->notes) }}</textarea>
                @error('notes') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection