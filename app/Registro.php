<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $guarded = [];

    public function delitos()
    {
        return $this->belongsToMany(Delito::class)
        			->select('id', 'nombre');
    }
}
