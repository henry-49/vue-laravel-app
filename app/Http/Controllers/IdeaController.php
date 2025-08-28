<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Idea;
use App\Jobs\GenerateIdeaFeedback;

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
            'category' => 'nullable|string|max:255',
        ]);
        
        $idea = Idea::create($validated);

         // Job dispatchen (läuft asynchron über Queue)
        GenerateIdeaFeedback::dispatch($idea);

        return redirect()->route('ideen.index');
    }

    public function edit(Idea $idea)
    {
        return Inertia::render('Ideen/Edit', [
            'idea' => $idea,
        ]);
    }

    public function update(Request $request, Idea $idea)
    {
        $validated = $request->validate([
            'title' => 'required|min:3',
            'description' => 'required',
            'category' => 'nullable|string|max:255',
        ]);

        $idea->update($validated);

        return redirect()->route('ideen.index');
    }

    public function destroy(Idea $idea)
    {
        $idea->delete();

        return redirect()->route('ideen.index');
    }
}