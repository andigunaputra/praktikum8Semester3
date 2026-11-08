<?php

namespace Modules\Buku\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Buku\Models\Buku;
use Modules\Buku\Http\Requests\StoreBukuRequest;
use Modules\Buku\Http\Requests\UpdateBukuRequest;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $bukus = Buku::when($request->search, function ($query, $search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
        })->latest()->paginate(5)->withQueryString();

        return view('buku::index', compact('bukus'));
    }

    public function create()
    {
        return view('buku::create');
    }

    public function store(StoreBukuRequest $request)
    {
    Buku::create($request->validated());

    return redirect()->route('buku.index')
        ->with('success', 'Buku berhasil ditambahkan.');
}

    public function edit(Buku $buku)
    {
        return view('buku::edit', compact('buku'));
    }

    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $buku->update($request->validated());

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}