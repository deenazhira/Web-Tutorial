<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;

class AdminController extends Controller
{
    public function index()
{
    $users = User::with('role')->get();
    return view('admin.dashboard', compact('users'));
}

public function assignRole(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'role_name' => 'required|string'
    ]);

    UserRole::updateOrCreate(
        ['user_id' => $request->user_id],
        ['role_name' => $request->role_name]
    );

    return back()->with('success', 'Role assigned successfully!');
}

public function toggleStatus($id)
{
    $user = User::findOrFail($id);
    $user->is_active = !$user->is_active;
    $user->save();

    return back()->with('success', 'User status updated.');
}
}
