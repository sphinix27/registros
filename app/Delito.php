<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delito extends Model
{
    protected $fillable = ['articulo', 'inciso', 'nombre'];

    protected $hidden = ['pivot'];

    public function registros()
    {
        return $this->belongsToMany(Registro::class);
    }    
}
