<?php

namespace Modules\UserManagement\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Rule;
use Modules\UserManagement\Http\Requests\StoreUserRequest;
use Modules\UserManagement\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserManagementController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        $users = User::latest()->get();

        return view('usermanagement::index', compact('users'));
    }

    // Menambahkan pengguna
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('usermanagement.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    // Mengedit pengguna
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();

        // Mencegah admin menurunkan role akunnya sendiri
        if (
            (int) $user->id === (int) $request->user()->id &&
            $validated['role'] !== 'admin'
        ) {
            return back()->withErrors([
                'role' => 'Anda tidak dapat menurunkan role akun sendiri.'
            ]);
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('usermanagement.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus akun sendiri
        if ((int) $user->id === (int) request()->user()->id) {
            return back()->withErrors([
                'user' => 'Anda tidak dapat menghapus akun sendiri.'
            ]);
        }

        $user->delete();

        return redirect()->route('usermanagement.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
