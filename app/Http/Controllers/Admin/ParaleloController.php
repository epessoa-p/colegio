<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ParaleloController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $query = Paralelo::with('company')->orderBy('name');

        if (!$authUser->is_super_admin) {
            $query->where('company_id', $authUser->getCurrentCompany()?->id);
        }

        $paralelos = $query->paginate(15);

        return view('admin.paralelos.index', compact('paralelos'));
    }

    public function create()
    {
        $authUser = auth()->user();

        return view('admin.paralelos.create', [
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
                    'max:50',
                    Rule::unique('paralelos', 'name')->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'active' => ['nullable', 'boolean'],
            ]);

            if (!$companyId) {
                return back()->withInput()->withErrors(['company_id' => 'Debes seleccionar una empresa.']);
            }

            Paralelo::create([
                'company_id' => $companyId,
                'name' => trim($validated['name']),
                'active' => $request->boolean('active', true),
            ]);

            return redirect()->route('paralelos.index')->with('success', 'Paralelo creado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al crear paralelo', ['message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible crear el paralelo.']);
        }
    }

    public function edit(Paralelo $paralelo)
    {
        $this->authorizeParalelo($paralelo);
        $authUser = auth()->user();

        return view('admin.paralelos.edit', [
            'paralelo' => $paralelo,
            'companies' => $authUser->is_super_admin
                ? Company::orderBy('name')->get()
                : collect([$authUser->getCurrentCompany()])->filter(),
        ]);
    }

    public function update(Request $request, Paralelo $paralelo)
    {
        $this->authorizeParalelo($paralelo);

        $authUser = auth()->user();
        $companyId = $authUser->is_super_admin
            ? (int) $request->input('company_id', $paralelo->company_id)
            : (int) $paralelo->company_id;

        try {
            $validated = $request->validate([
                'company_id' => ['nullable', 'exists:companies,id'],
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('paralelos', 'name')
                        ->ignore($paralelo->id)
                        ->where(fn ($q) => $q->where('company_id', $companyId)->whereNull('deleted_at')),
                ],
                'active' => ['nullable', 'boolean'],
            ]);

            $paralelo->update([
                'company_id' => $companyId,
                'name' => trim($validated['name']),
                'active' => $request->boolean('active', false),
            ]);

            return redirect()->route('paralelos.index')->with('success', 'Paralelo actualizado exitosamente.');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error al actualizar paralelo', ['paralelo_id' => $paralelo->id, 'message' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'No fue posible actualizar el paralelo.']);
        }
    }

    public function destroy(Paralelo $paralelo)
    {
        $this->authorizeParalelo($paralelo);

        $paralelo->delete();

        return redirect()->route('paralelos.index')->with('success', 'Paralelo eliminado exitosamente.');
    }

    protected function authorizeParalelo(Paralelo $paralelo): void
    {
        $authUser = auth()->user();

        if (!$authUser->is_super_admin && $paralelo->company_id !== $authUser->getCurrentCompany()?->id) {
            abort(403);
        }
    }
}
