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

        $user->update([
            'role' => 'seller',
            'seller_status' => 2
        ]);

        return back()->with('success', 'User berhasil dipromosikan menjadi seller');
    }

    public function reject(User $user)
    {

        $user->update([
            'seller_status' => 0
        ]);

        return back()->with('success', 'User ditolak untuk menjadi seller');
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

    public function activatedUser(User $user){
        if($user->status !== 0){
            return back()->with('error', ' User sudah aktif');
        }

        $user->update([
            'status' => 1
        ]);

        return back()->with('success', 'User berhasil diaktifkan');
    }

    public function deactivatedUser(User $user){
        if($user->status !== 1){
            return back()->with('error', ' User sudah nonaktif');
        }

        $user->update([
            'status' => 0
        ]);

        return back()->with('success', 'User berhasil di nonaktifkan');
    }
}
