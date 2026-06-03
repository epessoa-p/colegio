<?php

namespace App\Http\Controllers\Admin\CashRegisters;

use App\Http\Controllers\Controller;
use App\Models\CashRegisters\CashRegister;
use App\Models\CashRegisters\CashSession;
use App\Models\Personal;
use Illuminate\Http\Request;

class CashSessionController extends Controller
{
    public function openSession(Request $request, CashRegister $cashRegister)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personals,id',
            'opening_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $existingSession = CashSession::where('cash_register_id', $cashRegister->id)
            ->where('status', 'open')
            ->first();

        if ($existingSession) {
            return back()->with('error', 'La caja ya tiene una sesión abierta.');
        }

        $session = CashSession::create([
            'cash_register_id' => $cashRegister->id,
            'personal_id' => $validated['personal_id'],
            'opening_amount' => $validated['opening_amount'],
            'expected_amount' => $validated['opening_amount'],
            'status' => 'open',
            'opened_by' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Sesión de caja abierta exitosamente.');
    }

    public function closeSession(Request $request, CashSession $cashSession)
    {
        $validated = $request->validate([
            'closing_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($cashSession->isClosed()) {
            return back()->with('error', 'Esta sesión de caja ya ha sido cerrada.');
        }

        $difference = $validated['closing_amount'] - $cashSession->expected_amount;

        $cashSession->update([
            'closing_amount' => $validated['closing_amount'],
            'expected_amount' => $cashSession->expected_amount,
            'difference' => $difference,
            'status' => 'closed',
            'closed_by' => auth()->id(),
            'closed_at' => now(),
            'notes' => $validated['notes'] ?? $cashSession->notes,
        ]);

        return redirect()->back()
            ->with('success', 'Sesión de caja cerrada exitosamente.');
    }

    public function show(CashSession $cashSession)
    {
        return view('admin.cash-sessions.show', compact('cashSession'));
    }
}
