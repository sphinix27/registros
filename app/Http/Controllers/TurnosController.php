<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Turno;
use Illuminate\Http\Request;

class TurnosController extends Controller
{
    
    public function index()
    {
    	$limit = request()->limit;
        if(auth()->user()->isAdmin())
            $turnos = Turno::paginate($limit);
        else
            $turnos = Turno::where('user_id', '=',auth()->user()->id)->paginate($limit);
        return $turnos;
    }


    public function store()
    {
    	$validator = $this->validator(request()->only(['fecha']));

        if($validator->fails())
        {
            return response()->json([
                'created' => false,
                'errors' => $validator->errors(),
                'turno' => request()->all()
            ])->setStatusCode(422);
        }

        $turno = Turno::format(request()->only(['fecha']));
        $turno['user_id'] = Auth::user()->id;
        $turno = Turno::create($turno);
		return [
            'created' => true,
            'turno' => $turno
        ];
    }


    public function update($id)
    {
    	$turno = Turno::findOrFail($id);
        $validator = $this->validator(request()->only(['fecha']));

        if($validator->fails())
        {
            return response()->json([
                'updated' => false,
                'errors' => $validator->errors(),
                'turno' => request()->all()
            ])->setStatusCode(422);
        }

        $request = Turno::format(request()->only(['fecha']));
        $turno->update($request);
		return [
            'updated' => true,
            'turno' => $turno
        ];
    }

    public function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->delete();
        return ['deleted' => true];
    }

    public function validator($data)
    {
        return Validator::make($data, [
            'fecha' => ['required', 'date'],            
        ]);
    }
}
