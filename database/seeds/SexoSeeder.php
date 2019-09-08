<?php

use Illuminate\Database\Seeder;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombres = [
            'MASCULINO',
            'FEMENINO',
            'OTRO'
        ];

        foreach ($nombres as $nombre) {
            $Sexo = \App\Sexo::create(['nombre' => $nombre]);
        }
    }
}
