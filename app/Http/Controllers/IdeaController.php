<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Idea;

class IdeaController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Ideen/Index', [
            'ideas' => Idea::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3',
            'description' => 'required',
            'category' => 'nullable|unique:ideas|max:255',
        ]);

        Idea::create($validated);

        return redirect()->route('ideen.index');
    }
}