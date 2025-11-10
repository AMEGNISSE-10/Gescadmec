<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    // Liste des statistiques
    public function index()
    {
        $stats = Registration::with(['languageLevel', 'payments'])
            ->get()
            ->groupBy('languageLevel.name')
            ->map(function ($registrations, $levelName) {
                $totalPaid = $registrations->sum(function($registration) {
                    return $registration->payments->sum('amount_paid');
                });
                $totalPrice = $registrations->sum(function ($registration) {
                    return $registration->languageLevel->price;
                });
                $totalRemaining = $totalPrice - $totalPaid;

                return [
                    'total_paid' => $totalPaid,
                    'total_remaining' => $totalRemaining,
                    'student_count' => $registrations->count(),
                    'total_price' => $totalPrice
                ];
            });

        $recentPayments = Payment::with(['registration.student', 'registration.languageLevel'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.index', compact('stats', 'recentPayments'));
    }

    // Formulaire d'ajout de paiement
    public function create(Registration $registration)
    {
        $registration->load(['student', 'languageLevel']);
        return view('payments.create', compact('registration'));
    }

    // Enregistrer un paiement
    public function store(Request $request, Registration $registration)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,transfer'
        ]);

        $payment = Payment::create([
            'registration_id' => $registration->id,
            'secretary_id' => Auth::guard('secretary')->id(),
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_date' => now()
        ]);

        return redirect()->route('students.show', $registration->student_id)
            ->with('success', "Paiement de {$request->amount_paid}€ enregistré avec succès!");
    }

    // Générer un reçu PDF
    public function generateReceipt(Payment $payment)
    {
        $payment->load(['registration.student', 'registration.languageLevel', 'secretary']);
        
        $pdf = Pdf::loadView('payments.receipt', compact('payment'));
        return $pdf->download('recu-' . $payment->receipt_number . '.pdf');
    }
}