<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class ReshareController extends Controller
{
    public function home(Request $request)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $kategoriFilter = $request->query('kategori');
        $search = trim((string) $request->query('search', ''));

        $query = Barang::with(['kategori', 'user'])->where('status', 'Tersedia');
        if ($kategoriFilter) {
            $query->where('kategori_id', $kategoriFilter);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }
        $barangs = $query->latest()->take(8)->get();
        $total_didonasikan = Barang::count();
        $pengajuan_diterima = Pengajuan::where('status', 'Disetujui')->count();
        $barang_diterima = Barang::where('status', 'Disalurkan')->count();

        return view('home', compact('barangs', 'kategoris', 'kategoriFilter', 'search', 'total_didonasikan', 'pengajuan_diterima', 'barang_diterima'));
    }

    public function barang(Request $request)
    {
        $query = Barang::with(['kategori', 'user'])->latest();
        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }
        $barangs = $query->get();
        return view('barang', compact('barangs'));
    }

    public function detail(Barang $barang)
    {
        $barang->load(['kategori', 'user']);
        return view('detail', compact('barang'));
    }

    public function simulasi()
    {
        return view('simulasi-donasi');
    }

    public function prosesSimulasi(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:100'],
            'kondisi' => ['required', 'in:Sangat Baik,Baik,Cukup'],
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kondisi.in' => 'Pilih kondisi yang tersedia.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
        ]);
        return view('hasil-simulasi', compact('data'));
    }

    public function dashboard()
    {
        $jumlahBarang = Barang::count();
        $jumlahPengajuan = Pengajuan::where('user_id', auth()->id())->count();
        $barangs = Barang::with('kategori')->latest()->take(5)->get();
        return view('dashboard', compact('jumlahBarang', 'jumlahPengajuan', 'barangs'));
    }

    public function createDonasi()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('donasi.create', compact('kategoris'));
    }

    public function storeDonasi(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'kondisi_barang' => ['required', 'in:Sangat Baik,Baik,Cukup'],
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'foto_barang' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'foto_barang.image' => 'File harus berupa gambar.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_barang.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $fotoName = null;
        if ($request->hasFile('foto_barang')) {
            $foto = $request->file('foto_barang');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('reshare/uploads/barang'), $fotoName);
        }

        $barang = Barang::create([
            'user_id' => auth()->id(),
            'kategori_id' => $data['kategori_id'],
            'nama_barang' => $data['nama_barang'],
            'kondisi_barang' => $data['kondisi_barang'],
            'lokasi' => $data['lokasi'],
            'deskripsi' => $data['deskripsi'],
            'foto_barang' => $fotoName,
            'status' => 'Tersedia',
        ]);

        return redirect()->route('barang.detail', $barang->id)->with('success', 'Barang donasi berhasil disimpan.');
    }

    public function editDonasi(Barang $barang)
    {
        // Hanya pemilik barang yang bisa edit
        abort_unless($barang->user_id === auth()->id(), 403, 'Anda tidak berhak mengedit barang ini.');
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('donasi.edit', compact('barang', 'kategoris'));
    }

    public function updateDonasi(Request $request, Barang $barang)
    {
        abort_unless($barang->user_id === auth()->id(), 403, 'Anda tidak berhak mengedit barang ini.');

        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'kondisi_barang' => ['required', 'in:Sangat Baik,Baik,Cukup'],
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'foto_barang' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'foto_barang.image' => 'File harus berupa gambar.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_barang.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada
            if ($barang->foto_barang && file_exists(public_path('reshare/uploads/barang/' . $barang->foto_barang))) {
                unlink(public_path('reshare/uploads/barang/' . $barang->foto_barang));
            }
            $foto = $request->file('foto_barang');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('reshare/uploads/barang'), $fotoName);
            $barang->foto_barang = $fotoName;
        }

        $barang->update([
            'nama_barang' => $data['nama_barang'],
            'kategori_id' => $data['kategori_id'],
            'kondisi_barang' => $data['kondisi_barang'],
            'lokasi' => $data['lokasi'],
            'deskripsi' => $data['deskripsi'],
            'foto_barang' => $barang->foto_barang,
        ]);

        return redirect()->route('barang.detail', $barang->id)->with('success', 'Barang donasi berhasil diperbarui.');
    }

    public function destroyDonasi(Barang $barang)
    {
        abort_unless($barang->user_id === auth()->id(), 403, 'Anda tidak berhak menghapus barang ini.');

        // Hapus foto dari storage
        if ($barang->foto_barang && file_exists(public_path('reshare/uploads/barang/' . $barang->foto_barang))) {
            unlink(public_path('reshare/uploads/barang/' . $barang->foto_barang));
        }

        $barang->delete();
        return redirect()->route('dashboard')->with('success', 'Barang donasi berhasil dihapus.');
    }

    public function pengajuan()
    {
        $barangs = Barang::with('kategori')->where('status', 'Tersedia')->latest()->get();
        return view('pengajuan', compact('barangs'));
    }

    public function riwayat()
    {
        $pengajuans = Pengajuan::with(['barang', 'user'])->where('user_id', auth()->id())->latest()->get();
        return view('riwayat', compact('pengajuans'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function admin()
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Halaman ini hanya untuk admin.');
        $jumlahBarang = Barang::count();
        $jumlahUser = \App\Models\User::count();
        $jumlahPengajuan = Pengajuan::count();
        $barangs = Barang::with(['kategori', 'user'])->latest()->take(10)->get();
        return view('admin', compact('jumlahBarang', 'jumlahUser', 'jumlahPengajuan', 'barangs'));
    }
}
