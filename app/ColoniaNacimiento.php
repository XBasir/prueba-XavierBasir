<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColoniaNacimiento extends Model
{
    protected $table = 'colonia_nacimiento';
    protected $fillable = ['nombre'];
}
