<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Empresas
            ['name' => 'Ver Empresas', 'slug' => 'companies.view', 'module' => 'companies'],
            ['name' => 'Crear Empresas', 'slug' => 'companies.create', 'module' => 'companies'],
            ['name' => 'Editar Empresas', 'slug' => 'companies.edit', 'module' => 'companies'],
            ['name' => 'Eliminar Empresas', 'slug' => 'companies.delete', 'module' => 'companies'],

            // Usuarios
            ['name' => 'Ver Usuarios', 'slug' => 'users.view', 'module' => 'users'],
            ['name' => 'Crear Usuarios', 'slug' => 'users.create', 'module' => 'users'],
            ['name' => 'Editar Usuarios', 'slug' => 'users.edit', 'module' => 'users'],
            ['name' => 'Eliminar Usuarios', 'slug' => 'users.delete', 'module' => 'users'],

            // Roles
            ['name' => 'Ver Roles', 'slug' => 'roles.view', 'module' => 'roles'],
            ['name' => 'Crear Roles', 'slug' => 'roles.create', 'module' => 'roles'],
            ['name' => 'Editar Roles', 'slug' => 'roles.edit', 'module' => 'roles'],
            ['name' => 'Eliminar Roles', 'slug' => 'roles.delete', 'module' => 'roles'],

            // Cargos
            ['name' => 'Ver Cargos', 'slug' => 'cargos.view', 'module' => 'cargos'],
            ['name' => 'Crear Cargos', 'slug' => 'cargos.create', 'module' => 'cargos'],
            ['name' => 'Editar Cargos', 'slug' => 'cargos.edit', 'module' => 'cargos'],
            ['name' => 'Eliminar Cargos', 'slug' => 'cargos.delete', 'module' => 'cargos'],

            // Personal
            ['name' => 'Ver Personal', 'slug' => 'personal.view', 'module' => 'personal'],
            ['name' => 'Crear Personal', 'slug' => 'personal.create', 'module' => 'personal'],
            ['name' => 'Editar Personal', 'slug' => 'personal.edit', 'module' => 'personal'],
            ['name' => 'Eliminar Personal', 'slug' => 'personal.delete', 'module' => 'personal'],

            // Sucursales
            ['name' => 'Ver Sucursales', 'slug' => 'branches.view', 'module' => 'branches'],
            ['name' => 'Crear Sucursales', 'slug' => 'branches.create', 'module' => 'branches'],
            ['name' => 'Editar Sucursales', 'slug' => 'branches.edit', 'module' => 'branches'],
            ['name' => 'Eliminar Sucursales', 'slug' => 'branches.delete', 'module' => 'branches'],

            // Gestiones (Académico)
            ['name' => 'Ver Gestiones', 'slug' => 'gestiones.view', 'module' => 'gestiones'],
            ['name' => 'Crear Gestiones', 'slug' => 'gestiones.create', 'module' => 'gestiones'],
            ['name' => 'Editar Gestiones', 'slug' => 'gestiones.edit', 'module' => 'gestiones'],
            ['name' => 'Eliminar Gestiones', 'slug' => 'gestiones.delete', 'module' => 'gestiones'],

            // Niveles (Académico)
            ['name' => 'Ver Niveles', 'slug' => 'niveles.view', 'module' => 'niveles'],
            ['name' => 'Crear Niveles', 'slug' => 'niveles.create', 'module' => 'niveles'],
            ['name' => 'Editar Niveles', 'slug' => 'niveles.edit', 'module' => 'niveles'],
            ['name' => 'Eliminar Niveles', 'slug' => 'niveles.delete', 'module' => 'niveles'],

            // Grados (Académico)
            ['name' => 'Ver Grados', 'slug' => 'grados.view', 'module' => 'grados'],
            ['name' => 'Crear Grados', 'slug' => 'grados.create', 'module' => 'grados'],
            ['name' => 'Editar Grados', 'slug' => 'grados.edit', 'module' => 'grados'],
            ['name' => 'Eliminar Grados', 'slug' => 'grados.delete', 'module' => 'grados'],

            // Paralelos (Académico)
            ['name' => 'Ver Paralelos', 'slug' => 'paralelos.view', 'module' => 'paralelos'],
            ['name' => 'Crear Paralelos', 'slug' => 'paralelos.create', 'module' => 'paralelos'],
            ['name' => 'Editar Paralelos', 'slug' => 'paralelos.edit', 'module' => 'paralelos'],
            ['name' => 'Eliminar Paralelos', 'slug' => 'paralelos.delete', 'module' => 'paralelos'],

            // Materias (Académico)
            ['name' => 'Ver Materias', 'slug' => 'materias.view', 'module' => 'materias'],
            ['name' => 'Crear Materias', 'slug' => 'materias.create', 'module' => 'materias'],
            ['name' => 'Editar Materias', 'slug' => 'materias.edit', 'module' => 'materias'],
            ['name' => 'Eliminar Materias', 'slug' => 'materias.delete', 'module' => 'materias'],

            // Periodos (Académico)
            ['name' => 'Ver Periodos', 'slug' => 'periodos.view', 'module' => 'periodos'],
            ['name' => 'Crear Periodos', 'slug' => 'periodos.create', 'module' => 'periodos'],
            ['name' => 'Editar Periodos', 'slug' => 'periodos.edit', 'module' => 'periodos'],
            ['name' => 'Eliminar Periodos', 'slug' => 'periodos.delete', 'module' => 'periodos'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
