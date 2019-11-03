<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    public $timestamps = false;

    public function paciente(){
        return $this->belongsTo(Paciente::class);
    }
}