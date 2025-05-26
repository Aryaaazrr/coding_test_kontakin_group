<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $audits = Audit::with('user')
        ->latest()
        ->get()
        ->map(function ($audit) {
            return [
                'event' => $audit->event,
                'auditable_type' => class_basename($audit->auditable_type),
                'auditable_id' => $audit->auditable_id,
                'user' => optional($audit->user)->username ?? 'System',
                'url' => $audit->url,
                'ip_address' => $audit->ip_address,
                'user_agent' => $audit->user_agent,
                'tags' => $audit->tags ?? [],
                'created_at' => $audit->created_at->format('d-m-Y H:i:s'),
            ];
        });

    return Inertia::render('History/Index', [
        'audits' => $audits,
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}