<?php

namespace Modules\Donasi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonasiRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses untuk request ini.
     * Pengguna yang sudah login (auth) berhak mendonasikan barang.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Aturan validasi data donasi barang.
     */
    public function rules(): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'kondisi_barang' => ['required', 'in:Sangat Baik,Baik,Cukup'],
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'status' => ['nullable', 'in:Tersedia,Diproses,Disalurkan'],
            'foto_barang' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Pesan kustom validasi dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang donasi wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 150 karakter.',
            'kategori_id.required' => 'Kategori barang wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'kondisi_barang.required' => 'Kondisi barang wajib dipilih.',
            'kondisi_barang.in' => 'Kondisi barang harus bernilai Sangat Baik, Baik, atau Cukup.',
            'lokasi.required' => 'Lokasi penjemputan/barang wajib diisi.',
            'deskripsi.required' => 'Deskripsi barang wajib diisi.',
            'status.in' => 'Status donasi tidak valid.',
            'foto_barang.image' => 'Berkas foto harus berupa gambar.',
            'foto_barang.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_barang.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
