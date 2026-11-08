<?php

namespace Modules\Barang\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'kondisi_barang' => ['required', 'in:Sangat Baik,Baik,Cukup'],
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:Tersedia,Diproses,Disalurkan'],
            'foto_barang' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max' => 'Nama barang maksimal 150 karakter.',
            'kategori_id.required' => 'Kategori barang wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'kondisi_barang.required' => 'Kondisi barang wajib dipilih.',
            'kondisi_barang.in' => 'Kondisi barang harus bernilai Sangat Baik, Baik, atau Cukup.',
            'lokasi.required' => 'Lokasi barang wajib diisi.',
            'deskripsi.required' => 'Deskripsi barang wajib diisi.',
            'status.required' => 'Status barang wajib dipilih.',
            'status.in' => 'Status barang tidak valid.',
            'foto_barang.image' => 'Berkas foto harus berupa gambar.',
            'foto_barang.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'foto_barang.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
