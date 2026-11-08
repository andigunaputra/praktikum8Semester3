<?php

namespace Modules\Barang\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Barang\Http\Requests\StoreBarangRequest;
use Modules\Barang\Http\Requests\UpdateBarangRequest;

class BarangController extends Controller
{
    /**
     * Tampilkan daftar data barang untuk dikelola admin (CRUD Synchronous).
     */
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'user'])->latest();

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->query('kategori_id'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $barangs = $query->paginate(8)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('barang::index', compact('barangs', 'kategoris'));
    }

    /**
     * Tampilkan form tambah barang baru.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('barang::create', compact('kategoris'));
    }

    /**
     * Simpan data barang baru ke database (Synchronous).
     */
    public function store(StoreBarangRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Handle upload foto jika ada
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->storeAs('donasi', $filename, 'public');
            $validated['foto_barang'] = 'donasi/' . $filename;
        }

        Barang::create($validated);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang donasi berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail barang donasi.
     */
    public function show($id)
    {
        $barang = Barang::with(['kategori', 'user'])->findOrFail($id);
        return view('barang::show', compact('barang'));
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('barang::edit', compact('barang', 'kategoris'));
    }

    /**
     * Perbarui data barang (Synchronous).
     */
    public function update(UpdateBarangRequest $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $validated = $request->validated();

        // Handle penggantian foto barang
        if ($request->hasFile('foto_barang')) {
            // Hapus file lama jika ada di storage/public
            if ($barang->foto_barang) {
                if (Storage::disk('public')->exists($barang->foto_barang)) {
                    Storage::disk('public')->delete($barang->foto_barang);
                } elseif (file_exists(public_path('reshare/uploads/barang/' . $barang->foto_barang))) {
                    @unlink(public_path('reshare/uploads/barang/' . $barang->foto_barang));
                }
            }

            $file = $request->file('foto_barang');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->storeAs('donasi', $filename, 'public');
            $validated['foto_barang'] = 'donasi/' . $filename;
        }

        $barang->update($validated);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang donasi berhasil diperbarui!');
    }

    /**
     * Hapus data barang beserta fotonya dari storage (Synchronous).
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Hapus file foto dari disk jika ada
        if ($barang->foto_barang) {
            if (Storage::disk('public')->exists($barang->foto_barang)) {
                Storage::disk('public')->delete($barang->foto_barang);
            } elseif (file_exists(public_path('reshare/uploads/barang/' . $barang->foto_barang))) {
                @unlink(public_path('reshare/uploads/barang/' . $barang->foto_barang));
            }
        }

        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang donasi berhasil dihapus!');
    }
}
