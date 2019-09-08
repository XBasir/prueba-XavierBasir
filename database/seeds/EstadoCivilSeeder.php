<?php

use Illuminate\Database\Seeder;

class EstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombres = [
            'CASADO',
            'UNION LIBRE',
            'SOLTERO',
            'VIUDO',
            'DIVORCIADO',
            'OTRO'
        ];

        foreach ($nombres as $nombre) {
            $EstadoCivil = \App\EstadoCivil::create(['nombre' => $nombre]);
        }
    }
}
