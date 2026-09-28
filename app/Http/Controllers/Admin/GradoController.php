<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GradoController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $query = Grado::with(['company', 'nivel'])->orderBy('order')->orderBy('name');

        if (!$authUser->is_super_admin) {
            $query->where('company_id', $authUser->getCurrentCompany()?->id);
        }

        $grados = $query->paginate(15);

        return view('admin.grados.index', compact('grados'));
    }

    public function create()
    {
        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin ? request()->integer('company_id') : $authUser->getCurrentCompany()?->id;

        return view('admin.grados.create', [
            'niveles' => $this->nivelesFor($companyId),
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
                'nivel_id' => ['required', 'exists:niveles,id'],
                'name' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('grados', 'name')->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'order' => ['nullable', 'integer', 'min:0'],
                'active' => ['nullable', 'boolean'],
            ]);

            if (!$companyId) {
                return back()->withInput()->withErrors(['company_id' => 'Debes seleccionar una empresa.']);
            }

            $nivel = Nivel::findOrFail($validated['nivel_id']);
            if ($nivel->company_id !== $companyId) {
                return back()->withInput()->withErrors(['nivel_id' => 'El nivel seleccionado no pertenece a la empresa actual.']);
            }

            Grado::create([
                'company_id' => $companyId,
                'nivel_id' => $nivel->id,
                'name' => trim($validated['name']),
                'order' => $validated['order'] ?? null,
                'active' => $request->boolean('active', true),
            ]);

            return redirect()->route('grados.index')->with('success', 'Grado creado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al crear grado', ['message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible crear el grado.']);
        }
    }

    public function edit(Grado $grado)
    {
        $this->authorizeGrado($grado);
        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin ? $grado->company_id : $authUser->getCurrentCompany()?->id;

        return view('admin.grados.edit', [
            'grado' => $grado,
            'niveles' => $this->nivelesFor($companyId),
            'companies' => $authUser->is_super_admin
                ? Company::orderBy('name')->get()
                : collect([$authUser->getCurrentCompany()])->filter(),
        ]);
    }

    public function update(Request $request, Grado $grado)
    {
        $this->authorizeGrado($grado);

        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin
            ? (int) $request->input('company_id', $grado->company_id)
            : (int) $grado->company_id;

        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id'],
                'nivel_id' => ['required', 'exists:niveles,id'],
                'name' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('grados', 'name')
                        ->ignore($grado->id)
                        ->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'order' => ['nullable', 'integer', 'min:0'],
                'active' => ['nullable', 'boolean'],
            ]);

            $nivel = Nivel::findOrFail($validated['nivel_id']);
            if ($nivel->company_id !== $companyId) {
                return back()->withInput()->withErrors(['nivel_id' => 'El nivel seleccionado no pertenece a la empresa actual.']);
            }

            $grado->update([
                'company_id' => $companyId,
                'nivel_id' => $nivel->id,
                'name' => trim($validated['name']),
                'order' => $validated['order'] ?? null,
                'active' => $request->boolean('active', false),
            ]);

            return redirect()->route('grados.index')->with('success', 'Grado actualizado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al actualizar grado', ['grado_id' => $grado->id, 'message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible actualizar el grado.']);
        }
    }

    public function destroy(Grado $grado)
    {
        $this->authorizeGrado($grado);

        $grado->delete();

        return redirect()->route('grados.index')->with('success', 'Grado eliminado exitosamente.');
    }

    protected function nivelesFor(?int $companyId)
    {
        return Nivel::when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->where('active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }

    protected function authorizeGrado(Grado $grado): void
    {
        $authUser = auth()->user();

        if (!$authUser->is_super_admin && $grado->company_id !== $authUser->getCurrentCompany()?->id) {
            abort(403);
        }
    }
}
