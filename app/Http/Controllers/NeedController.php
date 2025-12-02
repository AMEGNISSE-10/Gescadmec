<?php

namespace App\Http\Controllers;

use App\Models\Need;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NeedController extends Controller
{
    // Liste des besoins
    public function index()
    {
        $needs = Need::with(['student', 'secretary'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('needs.index', compact('needs'));
    }

    // Formulaire d'ajout de besoin
    public function create()
    {
        $students = Student::all();
        return view('needs.create', compact('students'));
    }

    // Enregistrer un besoin
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'description' => 'required|string'
        ]);

        Need::create([
            'student_id' => $request->student_id,
            'secretary_id' => Auth::guard('secretary')->id(),
            'description' => $request->description
        ]);

        return redirect()->route('needs.index')
            ->with('success', 'Besoin enregistré avec succès!');
    }

    // Mettre à jour le statut d'un besoin
    public function updateStatus(Request $request, Need $need)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
            'response' => 'nullable|string'
        ]);

        $need->update([
            'status' => $request->status,
            'response' => $request->response
        ]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès!');
    }

    // Formulaire d'édition d'un besoin
    public function edit(Need $need)
    {
        $students = Student::all();
        return view('needs.edit', compact('need', 'students'));
    }

    // Mettre à jour un besoin
    public function update(Request $request, Need $need)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'description' => 'required|string',
            'status' => 'nullable|in:pending,in_progress,resolved',
            'response' => 'nullable|string'
        ]);

        $need->update([
            'student_id' => $request->student_id,
            'description' => $request->description,
            'status' => $request->status ?? $need->status,
            'response' => $request->response
        ]);

        return redirect()->route('needs.index')
            ->with('success', 'Besoin modifié avec succès!');
    }

    // Supprimer un besoin
    public function destroy(Need $need)
    {
        $need->delete();
        
        return redirect()->route('needs.index')
            ->with('success', 'Besoin supprimé avec succès!');
    }
}