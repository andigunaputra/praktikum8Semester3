<?php

namespace Modules\Peminjaman\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Peminjaman\Models\Peminjaman;
use Modules\Peminjaman\Http\Requests\StorePeminjamanRequest;
use Modules\Peminjaman\Http\Requests\UpdatePeminjamanRequest;

class PeminjamanController extends Controller
{
    // Menampilkan seluruh data peminjaman
    public function index()
    {
        $peminjamans = Peminjaman::latest()->get();

        if (request()->expectsJson()) {
            return response()->json($peminjamans);
        }

        return view('peminjaman::index', compact('peminjamans'));
    }

    // Menambahkan data peminjaman
    public function store(StorePeminjamanRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file_bukti')) {
            $validated['file_bukti'] = $request->file('file_bukti')
                ->store('bukti-peminjaman', 'public');
        }

        $peminjaman = Peminjaman::create($validated);

        return response()->json([
            'message' => 'Peminjaman berhasil ditambahkan!',
            'data' => $peminjaman
        ], 201);
    }

    // Mengubah data peminjaman
    public function update(UpdatePeminjamanRequest $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('file_bukti')) {
            if ($peminjaman->file_bukti) {
                Storage::disk('public')->delete($peminjaman->file_bukti);
            }

            $validated['file_bukti'] = $request->file('file_bukti')
                ->store('bukti-peminjaman', 'public');
        }

        $peminjaman->update($validated);

        return response()->json([
            'message' => 'Peminjaman berhasil diperbarui!',
            'data' => $peminjaman
        ]);
    }

    // Menghapus data peminjaman
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->file_bukti) {
            Storage::disk('public')->delete($peminjaman->file_bukti);
        }

        $peminjaman->delete();

        return response()->json([
            'message' => 'Peminjaman berhasil dihapus!'
        ]);
    }
}
