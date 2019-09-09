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

     $start_row = $request->get('start_row');
    

     $path = $request->file('select_file')->getRealPath();

     //El skip(1) es para saltarse la primera fila para tomar como heading la segunda
     $data = Excel::load($path)->noHeading()->skip($start_row-1)->get();

     $schemas = DB::getSchemaBuilder()->getColumnListing('empleados');
     array_shift($schemas);

     $data = $data->toArray();

     if(count($data) > 0)
     {
        $insert_data = array();
        $rejected_data = array();
        $final_data = array();
        $empleado_data = array();
        $values = array();
        $specific_value = array();
        $values_position = array();
        

        $empresa_position = null;
        $nombres_position = null;
        $apellido_paterno_position = null;
        $sexo_position = null;
        $estado_civil_position = null;
        
        foreach($data as $key => $base_data){
            //primera fila para definir las cabeceras
            if($key==0){
                foreach($base_data as $i => $row) {
                    
                    foreach($schemas as $id => $column){
                        $row = str_replace(' ', '_', $row);
                        $row = strtolower($row);
                        if($row == $column){ 
                            $insert_data[] = $row;
                            //encuentra las posiciones de las columnas de los datos principales en el excel
                            $values_position[] = $i;
                        }
                    }
                }

                foreach($insert_data as $i => $column){
                    if($column == "empresa") $empresa_position = $i;
                    if($column == "nombres") $nombres_position = $i;
                    if($column == "apellido_paterno_") $apellido_paterno_position = $i;
                    if($column == "sexo") $sexo_position = $i;
                    if($column == "estado_civil") $estado_civil_position = $i;
                }

                foreach($data as $key => $filter) {
                    foreach($values_position as $i => $position) {
                        $specific_value[] = ($filter[$position]);
                    }
                    $values[] = $specific_value;
                    $specific_value = [];
                }
                unset($values[0]);
            
            //Si las filas principales empresa && nombres && apellido_paterto && sexo $$ estado civil estan vacias
            }elseif($key== 1){
                foreach($values as $row => $value){
                        if( empty($value[$empresa_position]) && empty($value[$nombres_position]) 
                            && empty($value[$apellido_paterno_position]) && empty($value[$sexo_position]) 
                            && empty($value[$estado_civil_position]) ){
                       /* $rejected_data[] = [
                            "nombre" => ($row+$start_row)."# FILA ".($row+$start_row)." :Rechazado por falta de datos principales",
                       ];*/
                       
                    }elseif(empty($value[$nombres_position]) ){
                        $rejected_data[] = [
                            "nombre" => ($row+$start_row)." - FILA ".($row+$start_row)." :Rechazado por falta de dato principal 'nombre'",
                        ];
                        
                    }elseif(empty($value[$apellido_paterno_position])){
                        $rejected_data[] = [
                            "nombre" => ($row+$start_row)." - FILA ".($row+$start_row)." :Rechazado por falta de dato principal 'apellido paterno'",
                        ];
                        
                    }elseif(empty($value[$sexo_position])){
                        $rejected_data[] = [
                            "nombre" => ($row+$start_row)." - FILA ".($row+$start_row)." :Rechazado por falta de dato principal 'sexo'",
                        ];
                       
                    }elseif(empty($value[$estado_civil_position])){
                        $rejected_data[] = [
                            "nombre" => ($row+$start_row)." - FILA ".($row+$start_row)." :Rechazado por falta de dato principal 'estado civil'",
                        ];
                        
                    }elseif(empty($value[$empresa_position])){
                        $rejected_data[] = [
                            "nombre" => ($row+$start_row)." - FILA ".($row+$start_row)." :Rechazado por falta de dato principal 'empresa'",
                        ];

                    }else{
                        foreach($insert_data as $i => $insert) {
                        $rejected_by_relation = false;
                        if($insert_data[$i] == "empresa"){
                                $empresa = Empresa::where('nombre', $value[$i])->first();
                                if( !empty($empresa)){ 
                                    $value[$i] = $empresa->id;
                                }else{
                                    $rejected_data[] = [
                                        "nombre" => ($row+$start_row)." # FILA ".($row+$start_row)." :Rechazado por incongruencia de datos. La empresa '".$value[$i]."' no está registrada" ,
                                    ];
                                    $rejected_by_relation = true;
                                    break;
                                }
                        }
                        if($insert_data[$i] == "sexo"){
                                $sexo = Sexo::where('nombre', $value[$i])->first();
                                if( !empty($sexo)){ 
                                    $value[$i] = $sexo->id;
                                }else{
                                    $rejected_data[] = [
                                        "nombre" => ($row+$start_row)." # FILA ".($row+$start_row)." :Rechazado por incongruencia de datos. El sexo '".$value[$i]."' no está registrado" ,
                                    ];
                                    $rejected_by_relation = true;
                                    break;
                                }
                            }
                            if($insert_data[$i] == "estado_civil"){
                                $estado = EstadoCivil::where('nombre', $value[$i])->first();
                                if( !empty($estado)){ 
                                    $value[$i] = $estado->id;
                                }else{
                                    $rejected_data[] = [
                                        "nombre" => ($row+$start_row)." # FILA ".($row+$start_row)." :Rechazado por incongruencia de datos. El estado civil '".$value[$i]."' no está registrado" ,
                                    ];
                                    $rejected_by_relation = true;
                                    break;
                                }
                                
                            }
                        }
                        if($rejected_by_relation == false){
                            foreach($insert_data as $i => $insert) {
                                $empleado_data[$insert_data[$i]] = $value[$i];
                            }
                            $final_data[] = $empleado_data;
                            $empleado_data = [];
                        }
                    }
                }
            }else{
                break;
            }
        }
        if(!empty($final_data))
        {
           DB::table('empleados')->whereNotNull('id')->delete();
           DB::table('rejected')->whereNotNull('id')->delete();
           DB::table('empleados')->insert($final_data);
           DB::table('rejected')->insert($rejected_data);
           return back()->with('success', 'Se han importado los datos correctamente y borrado los registros anteriores');
        }
       }
       DB::table('empleados')->whereNotNull('id')->delete();
       DB::table('rejected')->whereNotNull('id')->delete();
       DB::table('rejected')->insert($rejected_data);
       return back()->with('danger', 'Hubo un problema, no se importaron datos');
      }
  }
  
