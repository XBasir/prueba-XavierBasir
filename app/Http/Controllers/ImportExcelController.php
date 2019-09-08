<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Excel;
use App\Empleado;
use App\Empresa;
use App\EstadoCivil;
use App\Sexo;


class ImportExcelController extends Controller
{
    function index()
    {
     $data =  Empleado::all();
     $empresa =  Empresa::all();
     $sexo =  Sexo::all();
     $estado_civil =  EstadoCivil::all();
     $rejected_data = DB::table('rejected')->orderBy('id', 'DESC')->get();
     return view('import_excel', compact('data', 'rejected_data', 'empresa', 'sexo', 'estado_civil'));
    }

    function import(Request $request)
    {
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);

     $path = $request->file('select_file')->getRealPath();

     $data = Excel::load($path)->noHeading()->skip(1)->get();
  
     if($data->count() > 0)
     {
        $insert_data = array();
        $rejected_data = array();
        $final_data = array();
        foreach($data->toArray() as $key => $value)
        {
            if($key==0){
                foreach($value as $i => $row) {
                    $insert_data = array_map('strtolower', $value);
                }
            }elseif( empty($value[0]) || empty($value[1]) || empty($value[2]) || empty($value[4]) || empty($value[11]) ){
                $rejected_data[] = [
                    "nombre" => ($key+2)."# FILA ".($key+2)." :Rechazado por falta de datos principales (nombre, apellido paterno, empresa, sexo y/o estado civil. ",
                ];
            }else{
                foreach($insert_data as $i => $insert) {
                   $insert_data[$i] = str_replace(' ', '_', $insert_data[$i]);
                   if($insert_data[$i] == "empresa"){
                        $empresa = Empresa::where('nombre', $value[$i])->first();
                        if( !empty($empresa)){ 
                            $value[$i] = $empresa->id;
                        }
                   }
                   if($insert_data[$i] == "sexo"){
                        $sexo = Sexo::where('nombre', $value[$i])->first();
                        if( !empty($sexo)){ 
                            $value[$i] = $sexo->id;
                        }
                    }
                    if($insert_data[$i] == "estado_civil" && !empty($value[$i]) ){
                        $estado = EstadoCivil::where('nombre', $value[$i])->first();
                        if( !empty($estado)){ 
                            $value[$i] = $estado->id;
                        }
                        if($value[$i] == "UNION  LIBRE"){
                            $value[$i] = 2;
                        }
                    }
                };
            $final_data[] = [
                $insert_data[0] => $value[0],
                $insert_data[1] => $value[1],
                $insert_data[2] => $value[2],
                $insert_data[3] => $value[3],
                $insert_data[4] => $value[4],
                $insert_data[5] => $value[5],
                $insert_data[6] => $value[6],
                $insert_data[7] => $value[7],
                $insert_data[8] => $value[8],
                $insert_data[9] => $value[9],
                $insert_data[10] => $value[10],
                $insert_data[11] => $value[11],
                $insert_data[12] => $value[12],
                $insert_data[13] => $value[13],
                $insert_data[14] => $value[14],
                $insert_data[15] => $value[15],
                $insert_data[16] => $value[16],
                $insert_data[17] => $value[17]
            ];
            }
        }

      if(!empty($final_data))
      {
         DB::table('empleados')->whereNotNull('id')->delete();
         DB::table('empleados')->insert($final_data);
         DB::table('rejected')->whereNotNull('id')->delete();
         DB::table('rejected')->insert($rejected_data);
      }
     }
     return back()->with('success', 'Se han importado los datos correctamente y borrado los registros anteriores');
    }
}


