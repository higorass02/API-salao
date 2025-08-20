<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        Service::create(['name' => 'Corte Feminino', 'description' => 'Corte de cabelo feminino', 'category' => 'Cabelo', 'duration' => 60, 'price' => 80, 'status' => 1]);
        Service::create(['name' => 'Manicure', 'description' => 'Manicure completa', 'category' => 'Unhas', 'duration' => 45, 'price' => 50, 'status' => 1]);
        Service::create(['name' => 'Penteado', 'description' => 'Penteado para eventos', 'category' => 'Cabelo', 'duration' => 90, 'price' => 120, 'status' => 1]);
    }
}
