<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $guarded = [];

    public function delitos()
    {
        return $this->belongsToMany(Delito::class)
        			->select('id', 'nombre');
    }

    public function estados()
    {
        return $this->belongsToMany(Estado::class)
        			->select('id', 'nombre');
    }

    public function denunciados()
    {
        return $this->belongsToMany(Persona::class, 'denunciados', 'registro_id', 'persona_id')->withTimestamps()
                    ->select('id', 'nombre');
    }

    public function denunciantes()
    {
        return $this->belongsToMany(Persona::class, 'denunciantes', 'registro_id', 'persona_id')->withTimestamps()
                    ->select('id', 'nombre');
    }

    public function attachEstados($estados)
    {
        $collection = collect($estados);
        $states = $collection->map(function ($item) {
            if(is_array($item))
                return $item['id'];
            return $item;
        });
        return $this->estados()->attach($states);
    }


    public static function formatFecha($request)
    {
        $fecha = Carbon::parse($request['fecha']); 
        $request['fecha'] = $fecha->toDateString();
        return $request;
    }
}
