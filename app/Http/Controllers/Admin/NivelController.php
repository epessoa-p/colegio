<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class NivelController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $query = Nivel::with('company')->orderBy('order')->orderBy('name');

        if (!$authUser->is_super_admin) {
            $query->where('company_id', $authUser->getCurrentCompany()?->id);
        }

        $niveles = $query->paginate(15);

        return view('admin.niveles.index', compact('niveles'));
    }

    public function create()
    {
        $authUser = auth()->user();

        return view('admin.niveles.create', [
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
                    Rule::unique('niveles', 'name')->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'order' => ['nullable', 'integer', 'min:0'],
                'active' => ['nullable', 'boolean'],
            ]);

            if (!$companyId) {
                return back()->withInput()->withErrors(['company_id' => 'Debes seleccionar una empresa.']);
            }

            Nivel::create([
                'company_id' => $companyId,
                'name' => trim($validated['name']),
                'order' => $validated['order'] ?? null,
                'active' => $request->boolean('active', true),
            ]);

            return redirect()->route('niveles.index')->with('success', 'Nivel creado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al crear nivel', ['message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible crear el nivel.']);
        }
    }

    public function edit(Nivel $nivel)
    {
        $this->authorizeNivel($nivel);
        $authUser = auth()->user();

        return view('admin.niveles.edit', [
            'nivel' => $nivel,
            'companies' => $authUser->is_super_admin
                ? Company::orderBy('name')->get()
                : collect([$authUser->getCurrentCompany()])->filter(),
        ]);
    }

    public function update(Request $request, Nivel $nivel)
    {
        $this->authorizeNivel($nivel);

        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin
            ? (int) $request->input('company_id', $nivel->company_id)
            : (int) $nivel->company_id;

        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id'],
                'name' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('niveles', 'name')
                        ->ignore($nivel->id)
                        ->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'order' => ['nullable', 'integer', 'min:0'],
                'active' => ['nullable', 'boolean'],
            ]);

            $nivel->update([
                'company_id' => $companyId,
                'name' => trim($validated['name']),
                'order' => $validated['order'] ?? null,
                'active' => $request->boolean('active', false),
            ]);

            return redirect()->route('niveles.index')->with('success', 'Nivel actualizado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al actualizar nivel', ['nivel_id' => $nivel->id, 'message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible actualizar el nivel.']);
        }
    }

    public function destroy(Nivel $nivel)
    {
        $this->authorizeNivel($nivel);

        if ($nivel->grados()->exists()) {
            return back()->withErrors(['error' => 'No puedes eliminar un nivel con grados asociados.']);
        }

        $nivel->delete();

        return redirect()->route('niveles.index')->with('success', 'Nivel eliminado exitosamente.');
    }

    protected function authorizeNivel(Nivel $nivel): void
    {
        $authUser = auth()->user();

        if (!$authUser->is_super_admin && $nivel->company_id !== $authUser->getCurrentCompany()?->id) {
            abort(403);
        }
    }
}
