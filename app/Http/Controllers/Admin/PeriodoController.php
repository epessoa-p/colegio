<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Gestion;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeriodoController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $query = Periodo::with(['company', 'gestion'])->orderBy('order')->orderBy('name');

        if (!$authUser->is_super_admin) {
            $query->where('company_id', $authUser->getCurrentCompany()?->id);
        }

        $periodos = $query->paginate(15);

        return view('admin.periodos.index', compact('periodos'));
    }

    public function create()
    {
        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin ? request()->integer('company_id') : $authUser->getCurrentCompany()?->id;

        return view('admin.periodos.create', [
            'gestiones' => $this->gestionesFor($companyId),
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
                'gestion_id' => ['required', 'exists:gestiones,id'],
                'name' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('periodos', 'name')->where(fn ($q) => $q->where('gestion_id', $request->input('gestion_id'))->whereNull('deleted_at')),
                ],
                'order' => ['nullable', 'integer', 'min:0'],
                'active' => ['nullable', 'boolean'],
            ]);

            if (!$companyId) {
                return back()->withInput()->withErrors(['company_id' => 'Debes seleccionar una empresa.']);
            }

            $gestion = Gestion::findOrFail($validated['gestion_id']);
            if ($gestion->company_id !== $companyId) {
                return back()->withInput()->withErrors(['gestion_id' => 'La gestión seleccionada no pertenece a la empresa actual.']);
            }

            Periodo::create([
                'company_id' => $companyId,
                'gestion_id' => $gestion->id,
                'name' => trim($validated['name']),
                'order' => $validated['order'] ?? null,
                'active' => $request->boolean('active', true),
            ]);

            return redirect()->route('periodos.index')->with('success', 'Periodo creado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al crear periodo', ['message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible crear el periodo.']);
        }
    }

    public function edit(Periodo $periodo)
    {
        $this->authorizePeriodo($periodo);
        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin ? $periodo->company_id : $authUser->getCurrentCompany()?->id;

        return view('admin.periodos.edit', [
            'periodo' => $periodo,
            'gestiones' => $this->gestionesFor($companyId),
            'companies' => $authUser->is_super_admin
                ? Company::orderBy('name')->get()
                : collect([$authUser->getCurrentCompany()])->filter(),
        ]);
    }

    public function update(Request $request, Periodo $periodo)
    {
        $this->authorizePeriodo($periodo);

        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin
            ? (int) $request->input('company_id', $periodo->company_id)
            : (int) $periodo->company_id;

        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id'],
                'gestion_id' => ['required', 'exists:gestiones,id'],
                'name' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('periodos', 'name')
                        ->ignore($periodo->id)
                        ->where(fn ($q) => $q->where('gestion_id', $request->input('gestion_id'))->whereNull('deleted_at')),
                ],
                'order' => ['nullable', 'integer', 'min:0'],
                'active' => ['nullable', 'boolean'],
            ]);

            $gestion = Gestion::findOrFail($validated['gestion_id']);
            if ($gestion->company_id !== $companyId) {
                return back()->withInput()->withErrors(['gestion_id' => 'La gestión seleccionada no pertenece a la empresa actual.']);
            }

            $periodo->update([
                'company_id' => $companyId,
                'gestion_id' => $gestion->id,
                'name' => trim($validated['name']),
                'order' => $validated['order'] ?? null,
                'active' => $request->boolean('active', false),
            ]);

            return redirect()->route('periodos.index')->with('success', 'Periodo actualizado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al actualizar periodo', ['periodo_id' => $periodo->id, 'message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible actualizar el periodo.']);
        }
    }

    public function destroy(Periodo $periodo)
    {
        $this->authorizePeriodo($periodo);

        $periodo->delete();

        return redirect()->route('periodos.index')->with('success', 'Periodo eliminado exitosamente.');
    }

    protected function gestionesFor(?int $companyId)
    {
        return Gestion::when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderByDesc('year')
            ->get();
    }

    protected function authorizePeriodo(Periodo $periodo): void
    {
        $authUser = auth()->user();

        if (!$authUser->is_super_admin && $periodo->company_id !== $authUser->getCurrentCompany()?->id) {
            abort(403);
        }
    }
}
