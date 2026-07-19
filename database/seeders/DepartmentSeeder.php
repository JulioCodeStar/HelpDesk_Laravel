<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Soporte Técnico', 'Recursos Humanos', 'Finanzas', 'Ventas',
            'Marketing', 'Desarrollo', 'Infraestructura', 'Atención al Cliente',
            'Logística', 'Administración',
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}
