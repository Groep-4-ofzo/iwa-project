@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
        <a href="{{ route('admin.users.index') }}" class="inline-block mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        ← Back
    </a>
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">New User</h1>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border rounded px-3 py-2">
                @error('first_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Initials</label>
                <input type="text" name="initials" value="{{ old('initials') }}" class="w-full border rounded px-3 py-2">
                @error('initials') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Prefix</label>
                <input type="text" name="prefix" value="{{ old('prefix') }}" class="w-full border rounded px-3 py-2">
                @error('prefix') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2">
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Employee Code</label>
                <input type="text" name="employee_code" value="{{ old('employee_code') }}" class="w-full border rounded px-3 py-2">
                @error('employee_code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2">
                @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Role</label>
                <select name="user_role" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select a role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('user_role') == $role->id ? 'selected' : '' }}>
                            {{ $role->role }}
                        </option>
                    @endforeach
                </select>
                @error('user_role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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