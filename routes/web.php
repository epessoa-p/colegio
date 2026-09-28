<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CargoController;
use App\Http\Controllers\Admin\PersonalController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\NivelController;
use App\Http\Controllers\Admin\GradoController;
use App\Http\Controllers\Admin\ParaleloController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\PeriodoController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Company selection (multi-empresa)
    Route::get('/select-company', [LoginController::class, 'selectCompany'])->name('select-company');
    Route::post('/set-company/{companyId}', [LoginController::class, 'setCompany'])->name('set-company');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Super Admin - Company Management
    Route::middleware('check-role:super_admin')->prefix('admin/companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/create', [CompanyController::class, 'create'])->name('create');
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
    });

    // User Management
    Route::prefix('admin/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/assign-role/{company}/{role}', [UserController::class, 'assignRole'])->name('assign-role');
    });

    // Role Management
    Route::prefix('admin/roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // Cargos
    Route::prefix('admin/cargos')->name('cargos.')->group(function () {
        Route::get('/', [CargoController::class, 'index'])->name('index');
        Route::get('/create', [CargoController::class, 'create'])->name('create');
        Route::post('/', [CargoController::class, 'store'])->name('store');
        Route::get('/role-permissions/{role}', [CargoController::class, 'rolePermissions'])->name('role-permissions');
        Route::get('/{cargo}/edit', [CargoController::class, 'edit'])->name('edit');
        Route::put('/{cargo}', [CargoController::class, 'update'])->name('update');
        Route::delete('/{cargo}', [CargoController::class, 'destroy'])->name('destroy');
    });

    // Personal
    Route::prefix('admin/personal')->name('personal.')->group(function () {
        Route::get('/', [PersonalController::class, 'index'])->name('index');
        Route::get('/create', [PersonalController::class, 'create'])->name('create');
        Route::post('/', [PersonalController::class, 'store'])->name('store');
        Route::get('/{personal}/edit', [PersonalController::class, 'edit'])->name('edit');
        Route::put('/{personal}', [PersonalController::class, 'update'])->name('update');
        Route::delete('/{personal}', [PersonalController::class, 'destroy'])->name('destroy');
    });

    // Branches (Sucursales)
    Route::prefix('admin/branches')->name('branches.')->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('/create', [BranchController::class, 'create'])->name('create');
        Route::post('/', [BranchController::class, 'store'])->name('store');
        Route::get('/{branch}', [BranchController::class, 'show'])->name('show');
        Route::get('/{branch}/edit', [BranchController::class, 'edit'])->name('edit');
        Route::put('/{branch}', [BranchController::class, 'update'])->name('update');
        Route::delete('/{branch}', [BranchController::class, 'destroy'])->name('destroy');
    });

    // ==================== ACADÉMICO ====================

    // Gestión Escolar
    Route::middleware('check-permission:gestiones.view')->prefix('admin/academico/gestiones')->name('gestiones.')->group(function () {
        Route::get('/', [GestionController::class, 'index'])->name('index');
        Route::get('/create', [GestionController::class, 'create'])->name('create');
        Route::post('/', [GestionController::class, 'store'])->name('store');
        Route::get('/{gestion}/edit', [GestionController::class, 'edit'])->name('edit');
        Route::put('/{gestion}', [GestionController::class, 'update'])->name('update');
        Route::delete('/{gestion}', [GestionController::class, 'destroy'])->name('destroy');
    });

    // Niveles
    Route::middleware('check-permission:niveles.view')->prefix('admin/academico/niveles')->name('niveles.')->group(function () {
        Route::get('/', [NivelController::class, 'index'])->name('index');
        Route::get('/create', [NivelController::class, 'create'])->name('create');
        Route::post('/', [NivelController::class, 'store'])->name('store');
        Route::get('/{nivel}/edit', [NivelController::class, 'edit'])->name('edit');
        Route::put('/{nivel}', [NivelController::class, 'update'])->name('update');
        Route::delete('/{nivel}', [NivelController::class, 'destroy'])->name('destroy');
    });

    // Grados/Cursos
    Route::middleware('check-permission:grados.view')->prefix('admin/academico/grados')->name('grados.')->group(function () {
        Route::get('/', [GradoController::class, 'index'])->name('index');
        Route::get('/create', [GradoController::class, 'create'])->name('create');
        Route::post('/', [GradoController::class, 'store'])->name('store');
        Route::get('/{grado}/edit', [GradoController::class, 'edit'])->name('edit');
        Route::put('/{grado}', [GradoController::class, 'update'])->name('update');
        Route::delete('/{grado}', [GradoController::class, 'destroy'])->name('destroy');
    });

    // Paralelos
    Route::middleware('check-permission:paralelos.view')->prefix('admin/academico/paralelos')->name('paralelos.')->group(function () {
        Route::get('/', [ParaleloController::class, 'index'])->name('index');
        Route::get('/create', [ParaleloController::class, 'create'])->name('create');
        Route::post('/', [ParaleloController::class, 'store'])->name('store');
        Route::get('/{paralelo}/edit', [ParaleloController::class, 'edit'])->name('edit');
        Route::put('/{paralelo}', [ParaleloController::class, 'update'])->name('update');
        Route::delete('/{paralelo}', [ParaleloController::class, 'destroy'])->name('destroy');
    });

    // Materias
    Route::middleware('check-permission:materias.view')->prefix('admin/academico/materias')->name('materias.')->group(function () {
        Route::get('/', [MateriaController::class, 'index'])->name('index');
        Route::get('/create', [MateriaController::class, 'create'])->name('create');
        Route::post('/', [MateriaController::class, 'store'])->name('store');
        Route::get('/{materia}/edit', [MateriaController::class, 'edit'])->name('edit');
        Route::put('/{materia}', [MateriaController::class, 'update'])->name('update');
        Route::delete('/{materia}', [MateriaController::class, 'destroy'])->name('destroy');
    });

    // Periodos
    Route::middleware('check-permission:periodos.view')->prefix('admin/academico/periodos')->name('periodos.')->group(function () {
        Route::get('/', [PeriodoController::class, 'index'])->name('index');
        Route::get('/create', [PeriodoController::class, 'create'])->name('create');
        Route::post('/', [PeriodoController::class, 'store'])->name('store');
        Route::get('/{periodo}/edit', [PeriodoController::class, 'edit'])->name('edit');
        Route::put('/{periodo}', [PeriodoController::class, 'update'])->name('update');
        Route::delete('/{periodo}', [PeriodoController::class, 'destroy'])->name('destroy');
    });
});

// Fallback
Route::redirect('/', '/dashboard');
