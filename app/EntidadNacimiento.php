<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntidadNacimiento extends Model
{
    protected $table = 'entidad_nacimiento';
    protected $fillable = ['nombre'];
}
