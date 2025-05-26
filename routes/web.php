<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $articles = Article::with(['mahasiswa', 'dosen'])
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($article) {
            return [
                'judul' => $article->judul,
                'tipe' => $article->tipe,
                'mahasiswa' => optional($article->mahasiswa)->nama_lengkap,
                'dosen' => optional($article->dosen)->nama_lengkap,
                'created_at' => $article->created_at->format('d M Y'),
            ];
        });

    return Inertia::render('Home', [
        'articles' => $articles,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('dashboard', DashboardController::class);

    Route::resource('dosen', DosenController::class)
        ->middleware('permission:dosen-read');

    Route::post('/dosen/{id}/restore', [DosenController::class, 'restore'])
        ->middleware('permission:dosen-update')
        ->name('dosen.restore');

    Route::delete('/dosen/{id}/force-delete', [DosenController::class, 'forceDelete'])
        ->middleware('permission:dosen-delete')
        ->name('dosen.forceDelete');

    Route::resource('article', ArticleController::class)->except(['destroy', 'update', 'store'])
        ->middleware('permission:article-read');

    Route::post('/article', [ArticleController::class, 'store'])
        ->middleware('permission:article-create')
        ->name('article.store');

    Route::put('/article/{article}', [ArticleController::class, 'update'])
        ->middleware('permission:article-update')
        ->name('article.update');

    Route::delete('/article/{id}', [ArticleController::class, 'destroy'])
        ->middleware('permission:article-delete')
        ->name('article.destroy');

    Route::post('/article/{id}/restore', [ArticleController::class, 'restore'])
        ->middleware('permission:article-update')
        ->name('article.restore');

    Route::delete('/article/{id}/force-delete', [ArticleController::class, 'forceDelete'])
        ->middleware('permission:article-delete')
        ->name('article.forceDelete');

    Route::post('/article/export', [ArticleController::class, 'export'])
        ->middleware('permission:article-read');

    Route::post('/article/import', [ArticleController::class, 'import'])->name('article.import');

    Route::resource('history', HistoryController::class);

    Route::resource('user-account', UserController::class)->middleware('permission:mahasiswa-read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';