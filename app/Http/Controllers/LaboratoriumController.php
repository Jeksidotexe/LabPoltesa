<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PengajuanPraktikum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LaboratoriumController extends Controller
{
    public function index()
    {
        return view('super_admin.lab.index');
    }

    public function data()
    {
        $lab = Laboratorium::orderBy('id_lab', 'desc')->get();

        return datatables()
            ->of($lab)
            ->addIndexColumn()
            ->addColumn('foto', function ($lab) {
                $url = $lab->foto && Storage::disk('public')->exists($lab->foto)
                    ? asset('storage/' . $lab->foto)
                    : asset('master/assets/images/placeholder-lab.jpg');
                return '<img src="' . $url . '" alt="foto" class="rounded" width="60" height="40" style="object-fit: cover;">';
            })
            ->addColumn('status_badge', function ($lab) {
                if ($lab->status == 'Nonaktif') {
                    return '<span class="badge bg-danger text-white px-2 py-1">Nonaktif (Tutup)</span>';
                }

                $now = Carbon::now();
                $sedangDipakai = PengajuanPraktikum::where('id_lab', $lab->id_lab)
                    ->where('tanggal', $now->toDateString())
                    ->where('jam_mulai', '<=', $now->toTimeString())
                    ->where('jam_selesai', '>=', $now->toTimeString())
                    ->where('status', 'Disetujui')
                    ->exists();

                if ($sedangDipakai) {
                    return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Sedang Digunakan</span>';
                }
                return '<span class="badge bg-success text-white px-2 py-1">Tersedia (Aktif)</span>';
            })
            ->addColumn('aksi', function ($lab) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('lab.show', $lab->id_lab) . '" class="btn btn-rounded btn-sm btn-info me-1 mr-1" title="Detail"><i class="fa fa-eye"></i></a>
                    <a href="' . route('lab.edit', $lab->id_lab) . '" class="btn btn-rounded btn-sm btn-dark me-1 mr-1" title="Edit"><i class="fa fa-edit"></i></a>
                    <button onclick="deleteData(`' . route('lab.destroy', $lab->id_lab) . '`)" class="btn btn-rounded btn-sm btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['foto', 'status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        // Ambil Data Admin
        $admins = User::where('status', 'Aktif')->whereIn('role', ['Admin', 'Super Admin'])->get();

        foreach ($admins as $admin) {
            $nama = $admin->nama ?? $admin->username;
            if ($admin->gelar_depan) $nama = $admin->gelar_depan . ' ' . $nama;
            if ($admin->gelar_belakang) $nama .= ', ' . $admin->gelar_belakang;

            $admin->nama_lengkap = $nama;
        }

        return view('super_admin.lab.create', compact('admins'));
    }
    public function store(Request $request)
    {
        $rules = [
            'nama'      => 'required|string|max:20',
            'kode'      => 'required|string|max:100|unique:laboratorium,kode',
            'lokasi'    => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
            'status'    => 'required|in:Aktif,Nonaktif',
            'id_admin'  => 'nullable|exists:users,id',
            'foto'      => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            if ($request->hasFile('foto')) {
                $validatedData['foto'] = $request->file('foto')->store('laboratorium/images', 'public');
            }

            Laboratorium::create($validatedData);
            return redirect()->route('lab.index')->with('success', 'Data Laboratorium berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating lab: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function show($id)
    {
        $lab = Laboratorium::with('admin')->findOrFail($id);

        $urlFoto = $lab->foto && Storage::disk('public')->exists($lab->foto)
            ? asset('storage/' . $lab->foto)
            : 'https://placehold.co/600x400/eeeeee/999999?text=Tidak+Ada+Foto';

        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        $sedangDipakai = PengajuanPraktikum::where('id_lab', $lab->id_lab)
            ->whereDate('tanggal', $now->toDateString())
            ->where('status', 'Disetujui')
            ->where(function ($query) use ($currentTime) {
                $query->whereTime('jam_mulai', '<=', $currentTime)
                    ->whereTime('jam_selesai', '>=', $currentTime);
            })
            ->exists();

        $namaAdminLengkap = null;
        if ($lab->admin) {
            $nama = $lab->admin->nama ?? $lab->admin->username;
            if ($lab->admin->gelar_depan) $nama = $lab->admin->gelar_depan . ' ' . $nama;
            if ($lab->admin->gelar_belakang) $nama .= ', ' . $lab->admin->gelar_belakang;
            $namaAdminLengkap = $nama;
        }

        return view('super_admin.lab.show', compact('lab', 'urlFoto', 'sedangDipakai', 'namaAdminLengkap'));
    }

    public function edit($id)
    {
        $lab = Laboratorium::findOrFail($id);

        // Ambil Data Admin
        $admins = User::where('status', 'Aktif')->whereIn('role', ['Admin', 'Super Admin'])->get();

        foreach ($admins as $admin) {
            $nama = $admin->nama ?? $admin->username;
            if ($admin->gelar_depan) $nama = $admin->gelar_depan . ' ' . $nama;
            if ($admin->gelar_belakang) $nama .= ', ' . $admin->gelar_belakang;

            $admin->nama_lengkap = $nama;
        }

        return view('super_admin.lab.edit', compact('lab', 'admins'));
    }

    public function update(Request $request, $id)
    {
        $lab = Laboratorium::findOrFail($id);

        $rules = [
            'nama'      => 'required|string|max:20',
            'kode'      => ['required', 'string', 'max:100', Rule::unique('laboratorium', 'kode')->ignore($lab->id_lab, 'id_lab')],
            'lokasi'    => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
            'status'    => 'required|in:Aktif,Nonaktif',
            'id_admin'  => 'nullable|exists:users,id',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            if ($request->hasFile('foto')) {
                if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
                    Storage::disk('public')->delete($lab->foto);
                }
                $validatedData['foto'] = $request->file('foto')->store('laboratorium/images', 'public');
            }

            $lab->update($validatedData);
            return redirect()->route('lab.index')->with('success', 'Data Laboratorium berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating lab: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $lab = Laboratorium::findOrFail($id);
            if ($lab->foto && Storage::disk('public')->exists($lab->foto)) {
                Storage::disk('public')->delete($lab->foto);
            }
            $lab->delete();
            return response()->json(['message' => 'Data Laboratorium berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting lab: ' . $e->getMessage());
            return response()->json(['message' => 'Tidak dapat menghapus Lab karena masih terikat dengan data Alat / Praktikum.'], 500);
        }
    }
}
