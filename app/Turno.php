<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $fillable = ['fecha', 'user_id'];

    protected $appends = ['total_registros'];

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function format($request)
    {
        $fecha = Carbon::parse($request['fecha']); 
        $request['fecha'] = $fecha->toDateString();
        return $request;
    }


    public function getTotalRegistrosAttribute()
    {
        return $this->registros()->count();
    }
}
