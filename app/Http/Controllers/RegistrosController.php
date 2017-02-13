<?php

namespace App\Http\Controllers;

use App\Delito;
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
        $registros = Registro::with('delitos')->get();
        $delitos = Delito::all();
        $delitosOption = $delitos->map(function ($delito) {
            return [
                'label' => $delito->nombre,
                'value' => $delito->id
            ];
        });
        return [
            'registros' => $registros,
            'delitos' => Delito::select('id', 'nombre')->get()
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
        $validator = $this->validator($request->only(['caso', 'fecha', 'denunciante', 'denunciado', 'estado', 'situacion_procesal', 'observaciones', 'delitos']));

        if($validator->fails())
        {
            return response()->json([
                'created' => false,
                'errors' => $validator->errors(),
                'registro' => $request->all()
            ])->setStatusCode(422);
        }
        $fecha = Carbon::parse($request->fecha); 
        $request['fecha'] = $fecha->toDateString();
        $registro = Registro::create($request->only(['caso', 'fecha', 'denunciante', 'denunciado', 'estado', 'situacion_procesal', 'observaciones']));

        $registro->delitos()->attach(collect($request->delitos)->pluck('id'));

        return [
            'created' => true,
            'registro' => $registro->load('delitos')
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

        $validator = $this->validator($request->only(['caso', 'fecha', 'denunciante', 'denunciado', 'estado', 'situacion_procesal', 'observaciones', 'delitos']));

        if($validator->fails())
        {
            return response()->json([
                'updated' => false,
                'errors' => $validator->errors(),
                'registro' => $registro->load('delitos')
            ])->setStatusCode(422);
        }

        $registro->update($request->intersect(['caso', 'fecha', 'denunciante', 'denunciado', 'estado', 'situacion_procesal', 'observaciones']));
        $registro->delitos()->detach();
        $registro->delitos()->attach(collect($request->delitos)->pluck('id'));

        return [
            'updated' => true,
            'registro' => $registro->load('delitos')
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
        $registro->delete();
        return ['deleted' => true];
    }


    public function validator($data)
    {
        return Validator::make($data, [
            'caso' => ['required', 'min:7', 'max:20'],
            'fecha' => ['required', 'date'],
            'denunciante' => ['required', 'min:3', 'max:50'],
            'denunciado' => ['required', 'min:3', 'max:50'],
            'estado' => ['required', Rule::in(['CAUTELAR', 'IMPUTADO', 'INICIADO', 'OBSERVADO', 'DESESTIMADO'])],
            'situacion_procesal' => ['required', Rule::in(['APRENDIDO PARA CAULTER', 'DISPOSICION DEL JUEZ', 'LIBRE'])],
            'observaciones' => ['present', 'max:256'],
            'delitos' => ['required']
        ]);
    }
}
