<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
        public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();

        return view('admin.users.index', compact('users'));
    }

    public function promote(User $user)
    {
        if ($user->role !== 'buyer') {
            return back()->with('error', 'User tidak bisa dipromosikan');
        }

        $user->update([
            'role' => 'seller',
        ]);

        return back()->with('success', 'User berhasil dipromosikan menjadi seller');
    }

    public function demote(User $user)
    {
        if ($user->role !== 'seller') {
            return back()->with('error', 'User tidak bisa dipromosikan');
        }

        $user->update([
            'role' => 'buyer',
        ]);

        return back()->with('success', 'User berhasil dipromosikan menjadi buyer');
    }
}
