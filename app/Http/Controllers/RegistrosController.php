<?php

namespace App\Http\Controllers;

use App\Delito;
use App\Estado;
use App\Persona;
use App\Registro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class RegistrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registros = Registro::with(['delitos', 'estados', 'denunciantes', 'denunciados'])->get();
        $delitos = Delito::select('id', 'nombre')->get();
        $estados = Estado::select('id', 'nombre')->get();
        $personas = Persona::select('id', 'nombre')->get();
        return [
            'registros' => $registros,
            'delitos' => $delitos,
            'estados' => $estados,
            'personas' => $personas
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->only(['caso', 'fecha', 'situacion_procesal', 'observaciones', 'delitos', 'estados', 'denunciantes', 'denunciados']));

        if($validator->fails())
        {
            return response()->json([
                'created' => false,
                'errors' => $validator->errors(),
                'registro' => $request->all()
            ])->setStatusCode(422);
        }
        $registro = Registro::formatFecha($request->only(['caso', 'fecha', 'situacion_procesal', 'observaciones']));
        $registro = Registro::create($registro);
        $registro->delitos()->attach(collect($request->delitos)->pluck('id'));
        $registro->attachEstados($request->estados);
        $denunciantes = Persona::createPersonasRegistro($request->denunciantes);
        $denunciados = Persona::createPersonasRegistro($request->denunciados);
        $registro->denunciantes()->attach($denunciantes->pluck('id'));
        $registro->denunciados()->attach($denunciados->pluck('id'));

        return [
            'created' => true,
            'registro' => $registro->load(['delitos', 'estados', 'denunciantes', 'denunciados'])
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $registro = Registro::findOrFail($id);

        $validator = $this->validator($request->only(['caso', 'fecha', 'situacion_procesal', 'observaciones', 'delitos', 'estados', 'denunciantes', 'denunciados']));

        if($validator->fails())
        {
            return response()->json([
                'updated' => false,
                'errors' => $validator->errors(),
                'registro' => $registro->load(['delitos', 'estados', 'denunciantes', 'denunciados'])
            ])->setStatusCode(422);
        }

        $record = Registro::formatFecha($request->only(['caso', 'fecha', 'situacion_procesal', 'observaciones']));
        $registro->update($record);
        $registro->delitos()->detach();
        $registro->delitos()->attach(collect($request->delitos)->pluck('id'));
        $registro->estados()->detach();
        $registro->attachEstados($request->estados);  
        $registro->denunciantes()->detach();      
        $denunciantes = Persona::createPersonasRegistro($request->denunciantes);
        $registro->denunciantes()->attach($denunciantes->pluck('id'));
        $registro->denunciados()->detach();
        $denunciados = Persona::createPersonasRegistro($request->denunciados);
        $registro->denunciados()->attach($denunciados->pluck('id'));

        return [
            'updated' => true,
            'registro' => $registro->load(['delitos', 'estados', 'denunciantes', 'denunciados'])
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registro = Registro::findOrFail($id);
        $registro->delitos()->detach();
        $registro->estados()->detach();
        $registro->denunciantes()->detach();
        $registro->denunciados()->detach();
        $registro->delete();
        return ['deleted' => true];
    }


    public function validator($data)
    {
        return Validator::make($data, [
            'caso' => ['required', 'min:7', 'max:20'],
            'fecha' => ['required', 'date'],
            'situacion_procesal' => ['required', Rule::in(['APR', 'DIS', 'LIB'])],
            'observaciones' => ['present', 'max:256'],
            'delitos' => ['required'],
            'estados' => ['required'],
            'denunciantes' => ['required', 'array'],
            'denunciados' => ['required', 'array'],
        ]);
    }
}
