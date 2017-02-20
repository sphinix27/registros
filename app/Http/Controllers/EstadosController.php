<?php

namespace App\Http\Controllers;

use Validator;
use App\Estado;
use Illuminate\Http\Request;

class EstadosController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin', auth()->user());
        return Estado::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin', auth()->user());
        $validator = $this->validator($request->only(['nombre']));

        if($validator->fails())
        {
            return response()->json([
                'created' => false,
                'errors' => $validator->errors(),
                'estado' => $request->all()
            ])->setStatusCode(422);
        }

        $estado = Estado::create($request->only(['nombre']));
        return [
            'created' => true,
            'estado' => $estado
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
        $this->authorize('admin', auth()->user());
        $estado = Estado::findOrFail($id);
        $validator = $this->validator($request->only(['nombre']));

        if($validator->fails())
        {
            return response()->json([
                'updated' => false,
                'errors' => $validator->errors(),
                'estado' => $estado
            ])->setStatusCode(422);
        }

        $estado->update($request->only(['nombre']));
        return [
            'updated' => true,
            'estado' => $estado
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
        $this->authorize('admin', auth()->user());
        $estado = Estado::findOrFail($id);
        $estado->delete();
        return ['deleted' => true];
    }

    public function validator($data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'min:3', 'max:200']             
        ]);
    }
}
