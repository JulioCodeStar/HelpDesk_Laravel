<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Hardware',        'description' => 'Problemas con equipos físicos'],
            ['name' => 'Software',        'description' => 'Errores y fallos de aplicaciones'],
            ['name' => 'Redes',           'description' => 'Conectividad e internet'],
            ['name' => 'Correo',          'description' => 'Incidencias con el correo electrónico'],
            ['name' => 'Accesos',         'description' => 'Usuarios, contraseñas y permisos'],
            ['name' => 'Impresoras',      'description' => 'Impresión y escaneo'],
            ['name' => 'Base de datos',   'description' => 'Consultas y rendimiento de BD'],
            ['name' => 'Seguridad',       'description' => 'Antivirus y amenazas'],
            ['name' => 'Telefonía',       'description' => 'Equipos y líneas telefónicas'],
            ['name' => 'Otros',           'description' => 'Solicitudes generales'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
