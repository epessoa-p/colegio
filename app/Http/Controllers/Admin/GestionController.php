<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GestionController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $query = Gestion::with('company')->orderByDesc('year');

        if (!$authUser->is_super_admin) {
            $query->where('company_id', $authUser->getCurrentCompany()?->id);
        }

        $gestiones = $query->paginate(15);

        return view('admin.gestiones.index', compact('gestiones'));
    }

    public function create()
    {
        $authUser = auth()->user();

        return view('admin.gestiones.create', [
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
                'year' => [
                    'required',
                    'integer',
                    'min:2000',
                    'max:2100',
                    Rule::unique('gestiones', 'year')->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'status' => ['required', 'in:abierta,cerrada'],
                'active' => ['nullable', 'boolean'],
            ]);

            if (!$companyId) {
                return back()->withInput()->withErrors(['company_id' => 'Debes seleccionar una empresa.']);
            }

            Gestion::create([
                'company_id' => $companyId,
                'year' => $validated['year'],
                'status' => $validated['status'],
                'active' => $request->boolean('active', true),
            ]);

            return redirect()->route('gestiones.index')->with('success', 'Gestión creada exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al crear gestión', ['message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible crear la gestión.']);
        }
    }

    public function edit(Gestion $gestion)
    {
        $this->authorizeGestion($gestion);
        $authUser = auth()->user();

        return view('admin.gestiones.edit', [
            'gestion' => $gestion,
            'companies' => $authUser->is_super_admin
                ? Company::orderBy('name')->get()
                : collect([$authUser->getCurrentCompany()])->filter(),
        ]);
    }

    public function update(Request $request, Gestion $gestion)
    {
        $this->authorizeGestion($gestion);

        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin
            ? (int) $request->input('company_id', $gestion->company_id)
            : (int) $gestion->company_id;

        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id'],
                'year' => [
                    'required',
                    'integer',
                    'min:2000',
                    'max:2100',
                    Rule::unique('gestiones', 'year')
                        ->ignore($gestion->id)
                        ->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'status' => ['required', 'in:abierta,cerrada'],
                'active' => ['nullable', 'boolean'],
            ]);

            $gestion->update([
                'company_id' => $companyId,
                'year' => $validated['year'],
                'status' => $validated['status'],
                'active' => $request->boolean('active', false),
            ]);

            return redirect()->route('gestiones.index')->with('success', 'Gestión actualizada exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al actualizar gestión', ['gestion_id' => $gestion->id, 'message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible actualizar la gestión.']);
        }
    }

    public function destroy(Gestion $gestion)
    {
        $this->authorizeGestion($gestion);

        if ($gestion->periodos()->exists()) {
            return back()->withErrors(['error' => 'No puedes eliminar una gestión con periodos asociados.']);
        }

        $gestion->delete();

        return redirect()->route('gestiones.index')->with('success', 'Gestión eliminada exitosamente.');
    }

    protected function authorizeGestion(Gestion $gestion): void
    {
        $authUser = auth()->user();

        if (!$authUser->is_super_admin && $gestion->company_id !== $authUser->getCurrentCompany()?->id) {
            abort(403);
        }
    }
}
