<?php

namespace App\Http\Controllers\Admin\CashRegisters;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CashRegisters\CashRegister;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    public function index()
    {
        $company = auth()->user()->getCurrentCompany();
        $cashRegisters = CashRegister::where('company_id', $company?->id)
            ->with(['branch', 'createdBy', 'activeSessions'])
            ->latest()
            ->get();

        return view('admin.cash-registers.index', compact('cashRegisters'));
    }

    public function create()
    {
        $company = auth()->user()->getCurrentCompany();
        $branches = Branch::where('company_id', $company?->id)
            ->where('active', true)
            ->get();

        return view('admin.cash-registers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'active' => 'boolean',
        ]);

        $company = auth()->user()->getCurrentCompany();
        $validated['company_id'] = $company->id;
        $validated['created_by'] = auth()->id();
        $validated['active'] = $request->boolean('active', true);

        CashRegister::create($validated);

        return redirect()->route('cash-registers.index')
            ->with('success', 'Caja creada exitosamente.');
    }

    public function edit(CashRegister $cashRegister)
    {
        $company = auth()->user()->getCurrentCompany();
        $branches = Branch::where('company_id', $company?->id)
            ->where('active', true)
            ->get();

        return view('admin.cash-registers.edit', compact('cashRegister', 'branches'));
    }

    public function update(Request $request, CashRegister $cashRegister)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);

        $cashRegister->update($validated);

        return redirect()->route('cash-registers.index')
            ->with('success', 'Caja actualizada exitosamente.');
    }

    public function destroy(CashRegister $cashRegister)
    {
        $cashRegister->delete();

        return redirect()->route('cash-registers.index')
            ->with('success', 'Caja eliminada exitosamente.');
    }
}
