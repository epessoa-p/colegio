<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Branch;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        $company = auth()->user()->getCurrentCompany();
        $cajas = Caja::where('company_id', $company?->id)->with('branch')->latest()->get();

        return view('admin.cajas.index', compact('cajas'));
    }

    public function create()
    {
        $company = auth()->user()->getCurrentCompany();
        $branches = Branch::where('company_id', $company?->id)->where('active', true)->get();

        return view('admin.cajas.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50',
            'branch_id'   => 'nullable|exists:branches,id',
            'description' => 'nullable|string',
            'active'      => 'boolean',
        ]);

        $company = auth()->user()->getCurrentCompany();
        $validated['company_id'] = $company->id;
        $validated['active'] = $request->boolean('active', true);

        Caja::create($validated);

        return redirect()->route('cajas.index')->with('success', 'Caja creada exitosamente.');
    }

    public function edit(Caja $caja)
    {
        $company = auth()->user()->getCurrentCompany();
        $branches = Branch::where('company_id', $company?->id)->where('active', true)->get();

        return view('admin.cajas.edit', compact('caja', 'branches'));
    }

    public function update(Request $request, Caja $caja)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50',
            'branch_id'   => 'nullable|exists:branches,id',
            'description' => 'nullable|string',
            'active'      => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);

        $caja->update($validated);

        return redirect()->route('cajas.index')->with('success', 'Caja actualizada exitosamente.');
    }

    public function destroy(Caja $caja)
    {
        $caja->delete();

        return redirect()->route('cajas.index')->with('success', 'Caja eliminada exitosamente.');
    }
}
