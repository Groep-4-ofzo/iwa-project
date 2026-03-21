<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('userRole')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = UserRole::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'first_name'    => 'nullable|string|max:45',
            'initials'      => 'nullable|string|max:12',
            'prefix'        => 'nullable|string|max:10',
            'email'         => 'required|email|unique:users|max:100',
            'employee_code' => 'required|string|unique:users|max:10',
            'password'      => 'required|confirmed|min:6',
            'user_role'     => 'required|exists:userroles,id',
        ]);

        User::create([
            'name'          => $request->name,
            'first_name'    => $request->first_name,
            'initials'      => $request->initials,
            'prefix'        => $request->prefix,
            'email'         => $request->email,
            'employee_code' => $request->employee_code,
            'password'      => Hash::make($request->password),
            'user_role'     => $request->user_role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = UserRole::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'first_name'    => 'nullable|string|max:45',
            'initials'      => 'nullable|string|max:12',
            'prefix'        => 'nullable|string|max:10',
            'email'         => 'required|email|max:100|unique:users,email,' . $user->id,
            'employee_code' => 'required|string|max:10|unique:users,employee_code,' . $user->id,
            'password'      => 'nullable|confirmed|min:6',
            'user_role'     => 'required|exists:userroles,id',
        ]);

        $user->name          = $request->name;
        $user->first_name    = $request->first_name;
        $user->initials      = $request->initials;
        $user->prefix        = $request->prefix;
        $user->email         = $request->email;
        $user->employee_code = $request->employee_code;
        $user->user_role     = $request->user_role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}