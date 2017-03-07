<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Informe Causas Turno</title>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: TimesNewRoman,Times New Roman,Times,Baskerville,Georgia,serif !important; 
    }
    .page-break {
        page-break-after: always;
        page-break-inside: avoid !important;
    }
    table {
        border:1px solid;
        width:100%;
    }
    tr {
        border:1px solid;
    }
    th.rotate {
        font-size:16px;
        font-weight:bold;
        text-align:center;
        height: 150px;
        width:30px;
        vertical-align:bottom;
        text-decoration:underline;
        border:1px solid;
        padding-bottom: 10px;
    }

    th.rotate > div {
        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);
        white-space:nowrap;
        text-align:center;
        width: 30px;

    }
    th.header {
        font-size:16px;
        font-weight:bold;
        text-align:center;
        vertical-align:middle;
        text-decoration:underline;
        border:1px solid;
    }
    td {
        font-size: 12px;
        text-align:center;
        vertical-align:middle;
        border:1px solid;
    }

    img.centered {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 77px;
    }
    </style>
</head>
<body>
    <div class="content">

        <div class="col-xs-12">
            <div class="col-xs-12">
                <h1 align="center" style="font-size:16px; font-weight:bold; margin-bottom:0px;">INFORME DE CAUSAS DE TURNO EN PLATAFORMA DEL {{ mb_strtoupper(\Carbon\Carbon::parse($registros->first()->turno->fecha)->formatLocalized('%A %d DE %B De %Y')) }}</h1>                
                <h1 align="center" style="font-size:16px; font-weight:bold; margin:0px;">DE HORAS 08:00 a.m. a 12:00 p.m. y de 14:30 p.m. a 18:30 p.m.</h1>                
                <h1 align="center" style="font-size:16px; font-weight:bold; text-decoration:underline; margin-top:0px;">{{ $user->name }}</h1>                
            </div>
        </div>
        <br>

        <div class="col-xs-12">
            <table>
                <tr>
                    <th rowspan="2" class="header" >N°</th>
                    <th rowspan="2" class="header" >N° DE CASO FIS</th>
                    <th rowspan="2" class="header" >FECHA</th>
                    <th rowspan="2" class="header" >DENUNCIANTE/S O QUERELLANTE/S</th>
                    <th rowspan="2" class="header" >DENUNCIADO/S O QUERELLADO/S</th>
                    <th rowspan="2" class="header" >DELITO/S</th>
                    <th colspan="5" class="header" >ESTADO DEL CASO</th>
                    <th colspan="3" class="header" style="font-size:12px;">SITUACION PROCESAL DE AUTOR PRESUNTO</th>
                    <th rowspan="2" class="header" ><div>OBSERVACIONES</div></th>
                </tr>
                <tr style="height:150px; border:1px solid;">
                    <th class="rotate"><div style="font-size:13px;">DESESTIMADO</div></th>
                    <th class="rotate"><div style="font-size:13px;">OBSERVADO</div></th>
                    <th class="rotate"><div style="font-size:13px;">INICIADO</div></th>
                    <th class="rotate"><div style="font-size:13px;">IMPUTADO</div></th>
                    <th class="rotate"><div style="font-size:13px;">CAUTELAR</div></th>
                    <th class="rotate"><div style="font-size:13px;">LIBRE</div></th>
                    <th class="rotate"><div style="font-size:9px;">A DISPOSICION DE JUEZ</div></th>
                    <th class="rotate"><div style="font-size:9px;">APREHENDIDO PARA CAUTELAR</div></th>
                </tr>
                @foreach($registros as $registro)
                    <tr class="page-break">
                        <td>{{ $loop->index + 1}}</td>
                        <td>{{ $registro->caso }}</td>
                        <td>{{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}</td>
                        <td>
                            @foreach($registro->denunciantes as $denunciante)
                                {{ mb_strtoupper($denunciante->nombre) }} <br>
                            @endforeach
                        </td>
                        <td>
                            @foreach($registro->denunciados as $denunciado)
                                {{ mb_strtoupper($denunciado->nombre) }} <br>
                            @endforeach
                        </td>
                        <td>
                            @foreach($registro->delitos as $delito)
                                {{ mb_strtoupper($delito->nombre) }} <br>
                            @endforeach
                        </td>
                            <td>
                                @if($registro->hasEstado("DESESTIMADO"))
                                    &#10008; 
                                @endif
                            </td>
                            <td>
                                @if($registro->hasEstado("OBSERVADO"))
                                    &#10008; 
                                @endif
                            </td>
                            <td>
                                @if($registro->hasEstado("INICIADO"))
                                    &#10008; 
                                @endif
                            </td>
                            <td>
                                @if($registro->hasEstado("IMPUTADO"))
                                    &#10008; 
                                @endif
                            </td>
                            <td>
                                @if($registro->hasEstado("CAUTELAR"))
                                    &#10008; 
                                @endif
                            </td>
                        <td>
                            @if($registro->situacion_procesal == "LIB")
                               &#10008; 
                            @endif
                        </td>
                        <td>
                            @if($registro->situacion_procesal == "DIS")
                               &#10008; 
                            @endif
                        </td>
                        <td>
                            @if($registro->situacion_procesal == "APR")
                               &#10008; 
                            @endif
                        </td>
                        <td>{{ mb_strtoupper($registro->observaciones) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        
    </div>
</body>
</html>