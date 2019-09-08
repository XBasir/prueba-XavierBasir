<?php

use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombres = [
            'FPV',
            'FOCAV',
            'OTRA'
        ];

        foreach ($nombres as $nombre) {
           $Empresa = \App\Empresa::create(['nombre' => $nombre]);
        }
    }
}
