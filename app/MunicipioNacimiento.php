<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MunicipioNacimiento extends Model
{
    protected $table = 'municipio_nacimiento';
    protected $fillable = ['nombre'];
}
