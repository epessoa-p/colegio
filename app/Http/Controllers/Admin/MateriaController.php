<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MateriaController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $query = Materia::with('company')->orderBy('name');

        if (!$authUser->is_super_admin) {
            $query->where('company_id', $authUser->getCurrentCompany()?->id);
        }

        $materias = $query->paginate(15);

        return view('admin.materias.index', compact('materias'));
    }

    public function create()
    {
        $authUser = auth()->user();

        return view('admin.materias.create', [
            'companies' => $authUser->is_super_admin
                ? Company::orderBy('name')->get()
                : collect([$authUser->getCurrentCompany()])->filter(),
        ]);
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin
            ? (int) $request->input('company_id')
            : (int) $authUser->getCurrentCompany()?->id;

        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id'],
                'name' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('materias', 'name')->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'code' => ['nullable', 'string', 'max:50'],
                'active' => ['nullable', 'boolean'],
            ]);

            if (!$companyId) {
                return back()->withInput()->withErrors(['company_id' => 'Debes seleccionar una empresa.']);
            }

            Materia::create([
                'company_id' => $companyId,
                'name' => trim($validated['name']),
                'code' => $validated['code'] ?? null,
                'active' => $request->boolean('active', true),
            ]);

            return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al crear materia', ['message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible crear la materia.']);
        }
    }

    public function edit(Materia $materia)
    {
        $this->authorizeMateria($materia);
        $authUser = auth()->user();

        return view('admin.materias.edit', [
            'materia' => $materia,
            'companies' => $authUser->is_super_admin
                ? Company::orderBy('name')->get()
                : collect([$authUser->getCurrentCompany()])->filter(),
        ]);
    }

    public function update(Request $request, Materia $materia)
    {
        $this->authorizeMateria($materia);

        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin
            ? (int) $request->input('company_id', $materia->company_id)
            : (int) $materia->company_id;

        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id'],
                'name' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('materias', 'name')
                        ->ignore($materia->id)
                        ->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'code' => ['nullable', 'string', 'max:50'],
                'active' => ['nullable', 'boolean'],
            ]);

            $materia->update([
                'company_id' => $companyId,
                'name' => trim($validated['name']),
                'code' => $validated['code'] ?? null,
                'active' => $request->boolean('active', false),
            ]);

            return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al actualizar materia', ['materia_id' => $materia->id, 'message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible actualizar la materia.']);
        }
    }

    public function destroy(Materia $materia)
    {
        $this->authorizeMateria($materia);

        $materia->delete();

        return redirect()->route('materias.index')->with('success', 'Materia eliminada exitosamente.');
    }

    protected function authorizeMateria(Materia $materia): void
    {
        $authUser = auth()->user();

        if (!$authUser->is_super_admin && $materia->company_id !== $authUser->getCurrentCompany()?->id) {
            abort(403);
        }
    }
}
