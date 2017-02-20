<?php

namespace App\Http\Controllers;

use App\Delito;
use Excel;
use Illuminate\Http\Request;
use Validator;

class ImportarDelitosController extends Controller
{

	public function store()
	{
        $this->authorize('admin', auth()->user());
	    $delitos = request()->all();
    	$validator = $this->validator($delitos);
    	if($validator['fails'])
    	{
    		return response()->json([
    			'success' => false,
    			'errors' => $validator['errors']
    		])->setStatusCode(422);
    	}
    	Delito::saveAll($delitos);
		return [ 
			'success' => true,
			'delitos' => $delitos
		];
	}
    
    public function show()
    {
        $this->authorize('admin', auth()->user());
        $validator = $this->validatorPreview(request()->only(['file']));

    	if($validator->fails())
    	{
    		return response()->json([
    			'success' => false,
    			'errors' => $validator->errors()
    		])->setStatusCode(422);
    	}
		$data = '';
    	if(request()->hasFile('file')){
            $filePath = request()->file('file')->getRealPath();
            $data = Excel::load($filePath, function($reader){
            }, 'UTF-8')->get();
        } 
        $filteredData = $data->filter(function ($delito, $key) {
        	return $delito->articulo != null || $delito->nombre != null;
        });
        return [
        	'succes' => true,
        	'delitos' => $filteredData->map(function ($data) {
	        		return [
	        			'articulo' => $data->articulo,
	        			'inciso' => $data->inciso,
	        			'nombre' => $data->nombre,
	        		];
        	})
        ];
    }

    public function validatorPreview($data)
    {
        return Validator::make($data, [
        	'file' => ['required', 'file', 'mimes:xls,xlsx,csv']
        ]);
    }

    public function validator($delitos)
    {
    	$errors = [];
    	foreach($delitos as $key => $delito){
	        $validator = Validator::make($delito, [
	        	'articulo' => ['required', 'numeric', 'min:1', 'max:999'],
	        	'inciso' => ['present'],
	        	'nombre' => ['required', 'min:5', 'max:200']
	        ]);  
	        if ($validator->fails()) {
	        	$errors[$key] = $validator->messages()->toArray();
	        }
    	}
    	return [
    		'fails' => (!empty($errors)),
    		'errors' => $errors
    	];
    }
}
