<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collaborator;

class CollaboratorsTableSeeder extends Seeder
{
    public function run()
    {
        Collaborator::create(['user_id' => 2, 'bio' => 'Cabeleireira especialista em cortes modernos', 'specialities' => 'Cortes femininos, penteados', 'status' => 1]);
        Collaborator::create(['user_id' => 1, 'bio' => 'Manicure experiente', 'specialities' => 'Manicure, pedicure', 'status' => 1]);
        Collaborator::create(['user_id' => 2, 'bio' => 'Especialista em penteados', 'specialities' => 'Eventos, noivas', 'status' => 1]);
    }
}
