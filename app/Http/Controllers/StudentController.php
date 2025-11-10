<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\LanguageLevel;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Liste des étudiants
    public function index()
    {
        $students = Student::with(['registrations.languageLevel', 'registrations.payments'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('students.index', compact('students'));
    }

    // Formulaire d'inscription
    public function create()
    {
        $languageLevels = LanguageLevel::all();
        return view('students.create', compact('languageLevels'));
    }

    // Enregistrer un étudiant
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'language_level_id' => 'required|exists:language_levels,id',
            'start_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,transfer'
        ]);

        // Créer l'étudiant
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        // Créer l'inscription
        $languageLevel = LanguageLevel::find($request->language_level_id);
        // Vérifier si duration_days existe, sinon utiliser une valeur par défaut
        $durationDays = $languageLevel && isset($languageLevel->duration_days) ? $languageLevel->duration_days : 30;
        $endDate = date('Y-m-d', strtotime($request->start_date . " + {$durationDays} days"));

        $registration = Registration::create([
            'student_id' => $student->id,
            'language_level_id' => $request->language_level_id,
            'secretary_id' => Auth::guard('secretary')->id(),
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'status' => 'active'
        ]);

        // Enregistrer le paiement initial
        if ($request->amount_paid > 0) {
            Payment::create([
                'registration_id' => $registration->id,
                'secretary_id' => Auth::guard('secretary')->id(),
                'amount_paid' => $request->amount_paid,
                'payment_method' => $request->payment_method,
                'payment_date' => now()
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Étudiant inscrit avec succès!');
    }

    // Détails d'un étudiant
    public function show(Student $student)
    {
        $student->load(['registrations.languageLevel', 'registrations.payments', 'needs']);
        return view('students.show', compact('student'));
    }
}