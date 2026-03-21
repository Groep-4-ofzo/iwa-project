@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
            <a href="{{ route('admin.users.index') }}" class="inline-block mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        ← Back
    </a>
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Edit User</h1>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full border rounded px-3 py-2">
                @error('first_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Initials</label>
                <input type="text" name="initials" value="{{ old('initials', $user->initials) }}" class="w-full border rounded px-3 py-2">
                @error('initials') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Prefix</label>
                <input type="text" name="prefix" value="{{ old('prefix', $user->prefix) }}" class="w-full border rounded px-3 py-2">
                @error('prefix') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2">
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Employee Code</label>
                <input type="text" name="employee_code" value="{{ old('employee_code', $user->employee_code) }}" class="w-full border rounded px-3 py-2">
                @error('employee_code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Password <span class="text-gray-500">(leave blank to keep current)</span></label>
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
                        <option value="{{ $role->id }}" {{ old('user_role', $user->user_role) == $role->id ? 'selected' : '' }}>
                            {{ $role->role }}
                        </option>
                    @endforeach
                </select>
                @error('user_role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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