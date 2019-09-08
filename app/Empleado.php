<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = [
        'nombres',
        'apellido_paterno_',
        'apellido_materno',
        'rfc',
        'clade_del_ife',
        'clave_de_elector',
        'telefono',
        'curp',
        'afiliacion_a_imss',
        'fecha_de_contrato',
        'fecha_de_nacimiento',
        'empresa',
        'sexo',
        'estado_civil',
        'entidad_de_nacimiento',
        'municipio_de_nacimiento',
        'colonia_de_nacimiento_',
        'modo_de_nacionalidad'
       ];
   
       public function empresa(){
           return $this->belongsTo(Empresa::class);
       }
       public function sexo(){
        return $this->belongsTo(Sexo::class);
       }
       public function estado_civil(){
        return $this->belongsTo(EstadoCivil::class);
        }   
}
