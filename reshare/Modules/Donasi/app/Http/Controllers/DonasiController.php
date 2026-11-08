<?php

namespace Modules\Donasi\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Donasi\Http\Requests\StoreDonasiRequest;
use Modules\Donasi\Http\Requests\UpdateDonasiRequest;

class DonasiController extends Controller
{
    /**
     * Tampilkan data donasi (Web View atau Response JSON AJAX).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role === 'admin';

        // Jika request berupa AJAX / JSON
        if ($request->ajax() || $request->wantsJson()) {
            $query = Barang::with(['kategori', 'user'])->latest();

            // Hak akses data: Admin melihat semua data, User biasa hanya melihat miliknya sendiri
            if (! $isAdmin) {
                $query->where('user_id', $user->id);
            }

            // Pencarian
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

            $items = $query->get()->map(function ($item) use ($user, $isAdmin) {
                $fotoUrl = null;
                if ($item->foto_barang) {
                    if (Str::startsWith($item->foto_barang, 'donasi/')) {
                        $fotoUrl = asset('storage/' . $item->foto_barang);
                    } else {
                        $fotoUrl = asset('reshare/uploads/barang/' . $item->foto_barang);
                    }
                }

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'nama_barang' => $item->nama_barang,
                    'kategori_id' => $item->kategori_id,
                    'kategori_nama' => $item->kategori->nama_kategori ?? '-',
                    'kondisi_barang' => $item->kondisi_barang,
                    'lokasi' => $item->lokasi,
                    'deskripsi' => $item->deskripsi,
                    'status' => $item->status,
                    'foto_barang' => $item->foto_barang,
                    'foto_url' => $fotoUrl,
                    'donatur_nama' => $item->user->nama ?? $item->user->name ?? 'Anonim',
                    'donatur_email' => $item->user->email ?? '-',
                    'created_at' => $item->created_at ? $item->created_at->format('d M Y, H:i') : '-',
                    'can_manage' => $isAdmin || ($user && $item->user_id === $user->id),
                ];
            });

            return response()->json([
                'status' => 'success',
                'is_admin' => $isAdmin,
                'total' => $items->count(),
                'data' => $items,
            ]);
        }

        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('donasi::index', compact('kategoris', 'isAdmin'));
    }

    /**
     * Simpan data donasi barang baru via AJAX (JSON Response).
     */
    public function store(StoreDonasiRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'Tersedia';

        // Upload foto barang menggunakan Laravel Storage
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->storeAs('donasi', $filename, 'public');
            $validated['foto_barang'] = 'donasi/' . $filename;
        }

        $barang = Barang::create($validated);
        $barang->load(['kategori', 'user']);

        $fotoUrl = null;
        if ($barang->foto_barang) {
            $fotoUrl = asset('storage/' . $barang->foto_barang);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Donasi barang berhasil ditambahkan tanpa reload halaman!',
            'data' => array_merge($barang->toArray(), [
                'foto_url' => $fotoUrl,
                'kategori_nama' => $barang->kategori->nama_kategori ?? '-',
                'donatur_nama' => $barang->user->nama ?? $barang->user->name ?? '-',
                'created_at' => $barang->created_at ? $barang->created_at->format('d M Y, H:i') : '-',
            ]),
        ], 201);
    }

    /**
     * Tampilkan detail donasi via AJAX (JSON Response).
     */
    public function show($id)
    {
        $barang = Barang::with(['kategori', 'user'])->findOrFail($id);
        $user = auth()->user();

        // Pemeriksaan hak akses
        if ($user->role !== 'admin' && $barang->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak: Anda tidak memiliki izin untuk melihat donasi ini.',
            ], 403);
        }

        $fotoUrl = null;
        if ($barang->foto_barang) {
            if (Str::startsWith($barang->foto_barang, 'donasi/')) {
                $fotoUrl = asset('storage/' . $barang->foto_barang);
            } else {
                $fotoUrl = asset('reshare/uploads/barang/' . $barang->foto_barang);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $barang->id,
                'user_id' => $barang->user_id,
                'nama_barang' => $barang->nama_barang,
                'kategori_id' => $barang->kategori_id,
                'kategori_nama' => $barang->kategori->nama_kategori ?? '-',
                'kondisi_barang' => $barang->kondisi_barang,
                'lokasi' => $barang->lokasi,
                'deskripsi' => $barang->deskripsi,
                'status' => $barang->status,
                'foto_barang' => $barang->foto_barang,
                'foto_url' => $fotoUrl,
                'donatur_nama' => $barang->user->nama ?? $barang->user->name ?? '-',
                'donatur_email' => $barang->user->email ?? '-',
                'created_at' => $barang->created_at ? $barang->created_at->format('d M Y, H:i') : '-',
            ],
        ]);
    }

    /**
     * Perbarui data donasi barang via AJAX (JSON Response).
     */
    public function update(UpdateDonasiRequest $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $user = auth()->user();

        // Periksa kepemilikan data jika bukan admin
        if ($user->role !== 'admin' && $barang->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak: Anda tidak berhak mengubah donasi ini.',
            ], 403);
        }

        $validated = $request->validated();

        // Handle pergantian foto
        if ($request->hasFile('foto_barang')) {
            // Hapus file lama dari storage
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
        $barang->load(['kategori', 'user']);

        $fotoUrl = null;
        if ($barang->foto_barang) {
            if (Str::startsWith($barang->foto_barang, 'donasi/')) {
                $fotoUrl = asset('storage/' . $barang->foto_barang);
            } else {
                $fotoUrl = asset('reshare/uploads/barang/' . $barang->foto_barang);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data donasi berhasil diperbarui tanpa reload halaman!',
            'data' => array_merge($barang->toArray(), [
                'foto_url' => $fotoUrl,
                'kategori_nama' => $barang->kategori->nama_kategori ?? '-',
                'donatur_nama' => $barang->user->nama ?? $barang->user->name ?? '-',
                'created_at' => $barang->created_at ? $barang->created_at->format('d M Y, H:i') : '-',
            ]),
        ]);
    }

    /**
     * Hapus data donasi barang via AJAX (JSON Response).
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $user = auth()->user();

        // Periksa hak akses
        if ($user->role !== 'admin' && $barang->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak: Anda tidak berhak menghapus donasi ini.',
            ], 403);
        }

        // Hapus file foto dari storage
        if ($barang->foto_barang) {
            if (Storage::disk('public')->exists($barang->foto_barang)) {
                Storage::disk('public')->delete($barang->foto_barang);
            } elseif (file_exists(public_path('reshare/uploads/barang/' . $barang->foto_barang))) {
                @unlink(public_path('reshare/uploads/barang/' . $barang->foto_barang));
            }
        }

        $barang->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data donasi berhasil dihapus tanpa reload halaman!',
        ]);
    }
}
