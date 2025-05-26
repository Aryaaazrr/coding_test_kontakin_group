<?php

namespace App\Http\Controllers;

use App\Exports\ArticlesExport;
use App\Imports\ArticlesImport;
use App\Jobs\ExportArticlesJob;
use App\Jobs\ImportArticlesJob;
use Inertia\Inertia;
use App\Models\Dosen;
use App\Models\Article;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $article = Article::with(['mahasiswa', 'dosen'])->withTrashed()->get()
        ->map(function ($item) {
            return [
                'id_article' => $item->id_article,
                'nama_lengkap' => $item->mahasiswa->nama_lengkap ?? '-',
                'dosen' => $item->dosen->nama_lengkap ?? [],
                'judul' => $item->judul,
                'tipe' => $item->tipe,
                'disetujui' => $item->disetujui,
                'deleted_at' => $item->deleted_at,
            ];
        });

    return Inertia::render('Article/Index', [
        'article' => $article,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosens = Dosen::select('id_dosen', 'nama_lengkap')->get();
    $mahasiswas = Mahasiswa::select('id_mahasiswa', 'nama_lengkap')->get();

    return Inertia::render('Article/Create', [
        'dosen' => $dosens,
        'mahasiswa' => $mahasiswas,
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_mahasiswa' => ['required', 'uuid', 'exists:mahasiswa,id_mahasiswa'],
            'id_dosen' => ['required', 'uuid', 'exists:dosen,id_dosen'],
            'judul' => ['required', 'string', 'min:3'],
            'tipe' => ['required', 'in:Laporan PKL,Skripsi'],
            'file' => [
                'required',
                'file',
                'mimes:pdf', // hanya file pdf
                'min:100',   // ukuran minimal dalam kilobytes
                'max:500',   // ukuran maksimal dalam kilobytes
            ],
        ], [
            'file.mimes' => 'File harus berformat PDF.',
            'file.min' => 'Ukuran file minimal 100 KB.',
            'file.max' => 'Ukuran file maksimal 500 KB.',
        ]);

        $path = $request->file('file')->store('articles');

        $article = new Article();
        $article->id_article = Str::uuid();
        $article->id_mahasiswa = $validatedData['id_mahasiswa'];
        $article->id_dosen = $validatedData['id_dosen'];
        $article->judul = $validatedData['judul'];
        $article->tipe = $validatedData['tipe'];
        $article->file = $path;
        $article->save();

        return redirect()->route('article.index')->with('success', 'Artikel berhasil dibuat.');
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
public function edit($id)
{
    $article = Article::findOrFail($id);
    $dosens = Dosen::select('id_dosen', 'nama_lengkap')->get();
    $mahasiswas = Mahasiswa::select('id_mahasiswa', 'nama_lengkap')->get();

    return Inertia::render('Article/Edit', [
        'article' => $article,
        'dosen' => $dosens,
        'mahasiswa' => $mahasiswas,
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validatedData = $request->validate([
        'id_mahasiswa' => ['required', 'uuid', 'exists:mahasiswa,id_mahasiswa'],
        'id_dosen' => ['required', 'uuid', 'exists:dosen,id_dosen'],
        'judul' => ['required', 'string', 'min:3'],
        'tipe' => ['required', 'in:Laporan PKL,Skripsi'],
        'file' => [
            'nullable',
            'file',
            'mimes:pdf',
            'min:100',
            'max:500',
        ],
    ], [
        'file.mimes' => 'File harus berformat PDF.',
        'file.min' => 'Ukuran file minimal 100 KB.',
        'file.max' => 'Ukuran file maksimal 500 KB.',
    ]);

    $article = Article::findOrFail($id);

    if ($request->hasFile('file')) {
        // Hapus file lama jika ada
        if ($article->file && Storage::exists($article->file)) {
            Storage::delete($article->file);
        }

        // Simpan file baru
        $path = $request->file('file')->store('articles');
        $article->file = $path;
    }

    $article->id_mahasiswa = $validatedData['id_mahasiswa'];
    $article->id_dosen = $validatedData['id_dosen'];
    $article->judul = $validatedData['judul'];
    $article->tipe = $validatedData['tipe'];
    $article->save();

    return redirect()->route('article.index')->with('success', 'Artikel berhasil diperbarui.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
{
      $article = Article::findOrFail($id);
    $article->delete();

    return back()->with('success', 'Artikel berhasil dihapus (soft delete).');
}

public function restore($id)
{
    $article = Article::withTrashed()->findOrFail($id);
    $article->restore();

    return back()->with('success', 'Artikel berhasil dipulihkan.');
}

public function forceDelete($id)
{
    $article = Article::withTrashed()->findOrFail($id);

    if ($article->file && Storage::exists($article->file)) {
        Storage::delete($article->file);
    }

    $article->forceDelete();

    return back()->with('success', 'Artikel dihapus permanen.');
}

public function export(Request $request)
{
 $fields = $request->input('fields');

    if (!is_array($fields) || empty($fields)) {
        return response()->json(['message' => 'Kolom tidak valid.'], 422);
    }

    return Excel::download(new ArticlesExport($fields), 'articles.xlsx');

    return response()->json(['message' => 'Export diproses di background.']);
}

public function import(Request $request)
{
   $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
        'fields' => 'required|array',
    ]);

    $fields = $request->input('fields');
    $file = $request->file('file');

    try {
        Excel::import(new ArticlesImport($fields), $file);
        return response()->json(['message' => 'Import berhasil']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Import gagal: ' . $e->getMessage()], 500);
    }
}

}