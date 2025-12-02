<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement - {{ $payment->receipt_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .receipt-info { margin: 20px 0; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f5f5f5; }
        .total { font-weight: bold; font-size: 14px; }
        .footer { margin-top: 30px; border-top: 1px solid #333; padding-top: 10px; text-align: center; font-size: 10px; }
        .signature { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ACADÉMIE DE LANGUES</h1>
        <h2>REÇU DE PAIEMENT</h2>
        <p>N°: <strong>{{ $payment->receipt_number }}</strong></p>
    </div>

    <div class="receipt-info">
        <table width="100%">
            <tr>
                <td width="50%">
                    <strong>ÉTUDIANT:</strong><br>
                    {{ $payment->registration->student->name }}<br>
                    {{ $payment->registration->student->email }}<br>
                    {{ $payment->registration->student->phone }}
                </td>
                <td width="50%">
                    <strong>DATE:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}<br>
                    <strong>COURS:</strong> {{ $payment->registration->languageLevel->name }}<br>
                    <strong>MÉTHODE:</strong> {{ strtoupper($payment->payment_method) }}
                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Paiement pour cours de {{ $payment->registration->languageLevel->name }}</td>
                <td>{{ number_format($payment->amount_paid, 0) }} XOF</td>
            </tr>
            <tr class="total">
                <td><strong>TOTAL PAYÉ</strong></td>
                <td><strong>{{ number_format($payment->amount_paid, 0) }} XOF</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="receipt-info">
        <p><strong>Informations complémentaires:</strong></p>
        <p>
            Prix total du cours: {{ number_format($payment->registration->languageLevel->price, 0) }} XOF<br>
            Total payé à ce jour: {{ number_format($payment->registration->total_paid, 0) }} XOF<br>
            Reste à payer: {{ number_format($payment->registration->remaining_amount, 0) }} XOF
        </p>
    </div>

    <div class="signature">
        <table width="100%">
            <tr>
                <td width="50%" align="center">
                    <p>Signature du responsable</p>
                    <p style="margin-top: 40px;">_________________________</p>
                    <p>{{ $payment->secretary->name }}</p>
                </td>
                <td width="50%" align="center">
                    <p>Signature de l'étudiant</p>
                    <p style="margin-top: 40px;">_________________________</p>
                    <p>Pour accord</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Académie de Langues - {{ config('app.url') }} - Tél: +33 1 23 45 67 89</p>
        <p>Ce reçu est généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>