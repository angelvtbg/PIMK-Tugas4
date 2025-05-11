<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $mahasiswas = Mahasiswa::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('nim', 'like', "%$search%")
                    ->orWhere('prodi', 'like', "%$search%");
            })
            ->latest()
            ->get();

        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => ['required', 'regex:/^\\d{9}$/', 'unique:mahasiswas,nim'],
            'prodi' => 'required|string|max:255'
        ]);

        Mahasiswa::create($request->only(['name', 'nim', 'prodi']));

        return redirect()->route('mahasiswa.index')->with([
            'message' => 'Mahasiswa berhasil ditambahkan!',
            'alert-type' => 'success'
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => ['required', 'regex:/^\\d{9}$/', Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'prodi' => 'required|string|max:255'
        ]);

        $mahasiswa->update($request->only(['name', 'nim', 'prodi']));

        return redirect()->route('mahasiswa.index')->with([
            'message' => 'Data berhasil diperbarui!',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with([
            'message' => 'Data berhasil dihapus!',
            'alert-type' => 'success'
        ]);
    }
}
