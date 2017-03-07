<?php

namespace App\Http\Controllers;

use App;
use App\Registro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use View;

class RegistroInformesController extends Controller
{
    
    public function show($id)
    {
	    setlocale(LC_TIME, 'es_ES.utf8');
		$header = View::make('pdf.HeaderCausasTurno')->render();
		$user = auth()->user();
		$registros = Registro::with('denunciantes', 'denunciados', 'delitos', 'turno')->where('turno_id', $id)->get();
	    $view = View::make('pdf.InformeCausasTurno', compact('registros', 'user'))->render();
	    $pdf = App::make('snappy.pdf.wrapper');
	    $pdf->loadHTML($view)->setOption('header-html', $header)->setOption('header-spacing', 28);
	    return $pdf->download('Informe '.Carbon::parse($registros->first()->turno->fecha)->format('d-m-Y').'.pdf');
    }
}
