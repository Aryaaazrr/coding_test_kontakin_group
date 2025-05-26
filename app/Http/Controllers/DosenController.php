<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Dosen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosen = Dosen::withTrashed()->get();

        return Inertia::render('Dosen/Index', [
            'dosen' => $dosen,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Dosen/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'keahlian' => 'required|array|min:1',
                'keahlian.*' => 'string|max:255',
            ]);

            Dosen::create([
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => $validated['nama_lengkap'],
                'keahlian' => $validated['keahlian'],
            ]);

            return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dosen = Dosen::findOrFail($id);

        return inertia('Dosen/Edit', [
            'dosen' => $dosen,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'string|max:255',
        ]);

        $dosen = Dosen::findOrFail($id);
        $dosen->update([
            'nama_lengkap' => $request->nama_lengkap,
            'keahlian' => $request->keahlian,
        ]);

        return redirect()->route('dosen.index')->with('success', 'Data dosen diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->back()->with('success', 'Dosen berhasil dihapus (soft delete).');
    }

    public function restore($id)
    {
        $dosen = Dosen::withTrashed()->findOrFail($id);
        $dosen->restore();

        return redirect()->back()->with('success', 'Dosen berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $dosen = Dosen::withTrashed()->findOrFail($id);
        $dosen->forceDelete();

        return redirect()->back()->with('success', 'Dosen dihapus permanen.');
    }
}
