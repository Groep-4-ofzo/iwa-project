<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AppUserController extends Controller
{
    public function index(Request $request)
    {
        $contractId = $request->route('identifier');
        $users = AppUser::where('contract_id', $contractId)->get();
        return response()->json($users);
    }

    public function show(Request $request)
    {
        $contractId = $request->route('identifier');
        $userIdentifier = $request->route('user_identifier');
        $user = AppUser::where('contract_id', $contractId)->where('identifier', $userIdentifier)->first();

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $contractId = $request->route('identifier');
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|unique:app_users',
            'identifier' => 'required|string|max:10|unique:app_users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        AppUser::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'identifier' => $request->identifier,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'contract_id' => $contractId,
        ]);
        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function delete(Request $request)
    {
        $contractId = $request->route('identifier');
        $userIdentifier = $request->route('user_identifier');
        $user = AppUser::where('contract_id', $contractId)->where('identifier', $userIdentifier)->first();

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function update(Request $request)
    {
        $contractId = $request->route('identifier');
        $userIdentifier = $request->route('user_identifier');
        $user = AppUser::where('contract_id', $contractId)->where('identifier', $userIdentifier)->first();

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request->validate([
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|nullable|string|max:100',
            'email' => 'sometimes|required|email|unique:app_users,email',
            'identifier' => 'sometimes|required|string|max:10|unique:app_users,identifier',
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|required|in:admin,user',
            'contract_id' => 'sometimes|nullable|exists:contracts,id',
        ]);

        $user->update($request->only([
            'first_name',
            'last_name',
            'email',
            'identifier',
            'password',
            'role',
            'contract_id',
        ]));

        return response()->json(['message' => 'User updated successfully'], 200);
    }
}
