<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Registration;
use App\Models\LanguageLevel;
use App\Models\Payment;
use App\Models\Need;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'active_registrations' => Registration::where('status', 'active')->count(),
            'total_income' => Payment::sum('amount_paid'),
            'pending_needs' => Need::where('status', 'pending')->count(),
        ];

        $recentRegistrations = Registration::with(['student', 'languageLevel'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['registration.student'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentRegistrations', 'recentPayments'));
    }
}