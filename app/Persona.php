<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre'];

    protected $hidden = ['pivot'];

    public static function createPersonasRegistro($request)
    {
        $personas = collect($request);
        $nuevos = $personas->filter(function ($person) {
            return $person['id'] == null;
        });
        $collection = collect();
        foreach($nuevos as $nuevo) {
            if ($nuevo['nombre'] != null)
            {
                $persona = Persona::create($nuevo);
                $nuevo['id'] = $persona->id;
                $collection->push($nuevo);
            }
        }
        $existentes = $personas->diffKeys($nuevos);
        foreach($existentes as $existente) {
            $collection->push($existente);
        }
        return $collection;
    }
}
