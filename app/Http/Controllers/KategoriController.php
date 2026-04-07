<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return view('super_admin.kategori.index');
    }

    public function data()
    {
        $query = Kategori::orderBy('created_at', 'desc');

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<a href="' . route('kategori.edit', $row->id_kategori) . '" class="btn btn-sm btn-dark btn-rounded mr-1" title="Edit"><i class="fa fa-edit"></i></a>';
                $btnDelete = '<button type="button" onclick="deleteData(\'' . route('kategori.destroy', $row->id_kategori) . '\')" class="btn btn-sm btn-danger btn-rounded" title="Hapus"><i class="fa fa-trash"></i></button>';

                return '<div class="d-flex justify-content-center">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('super_admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('super_admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $kategori->id_kategori . ',id_kategori'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
