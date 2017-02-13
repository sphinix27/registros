<?php

namespace App\Http\Controllers;

use Validator;
use App\Delito;
use Illuminate\Http\Request;

class DelitosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Delito::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->only(['articulo', 'inciso','nombre']));

        if($validator->fails())
        {
            return response()->json([
                'created' => false,
                'errors' => $validator->errors(),
                'delito' => $request->all()
            ])->setStatusCode(422);
        }

        $delito = Delito::create($request->only(['articulo', 'inciso', 'nombre']));
        return [
            'created' => true,
            'delito' => $delito
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
        $delito = Delito::findOrFail($id);
        $validator = $this->validator($request->only(['articulo', 'inciso','nombre']));

        if($validator->fails())
        {
            return response()->json([
                'updated' => false,
                'errors' => $validator->errors(),
                'delito' => $delito
            ])->setStatusCode(422);
        }

        $delito->update($request->only(['articulo', 'inciso','nombre']));
        return [
            'updated' => true,
            'delito' => $delito
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
        $delito = Delito::findOrFail($id);
        $delito->delete();
        return ['deleted' => true];
    }

    public function validator($data)
    {
        return Validator::make($data, [
            'articulo' => ['required', 'numeric', 'min:1', 'max:999'],
            'inciso' => ['present'],
            'nombre' => ['required', 'min:3', 'max:200']             
        ]);
    }
}
