<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hoja de Control Meritos y Demeritos</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;           
        }
        .table {
            display: table;
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }
        .table-column {
            display: table;
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
            /*page-break-inside: avoid;*/
        }
        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        th, td {
            display: table-cell;
            vertical-align: inherit;
        }
        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: center;
        }
        td, th {
            border: 1px solid #999;
            /*padding: 0.5rem;*/
            text-align: center;
            font-size: 10px;
        }
        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        .column {
            float: left;
            width: 50%;
        }
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .table-total {
            display: table;
            width: auto;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }

    </style>
</head>
<body>
    <?php
        $meritoSubTotal = 0;
        $demeritoSubTotal = 0;
        $demeritoTotal = 0;
        $sancionDisciplinariaTotal = 0;
        $reposoTotal = 0;
        $felicitacionTotal = 0;
        $francoDeHonorTotal = 0;
        $total = 0;
    ?>
    <div class="row">
        <div class="column">
            <h4 style="text-align: left">COLEGIO MILITAR DE AVIACION <br> GRUPO CADETES <br> BOLIVIA
            </h4>
        </div>

        <div class="column" style="text-align: right">
            <h4>CURSO: {{$cadete['year_ingreso']}} Año <br> GESTION: {{now()->year}} <br> FECHA DE EMISION: {{now()->format('Y-m-d H:i')}}</h4>
        </div>
    </div>

    <div>
        <h4 style="text-align: center"> HOJA DE CONTROL DE MERITOS Y DEMERITOS</h4>
    </div>
    <div>
        <h4>GRADO, APELLIDOS Y NOMBRES: <span style="font-weight: normal;">{{ $cadete['nombre']}}</span> </h4>
    </div>
    <div>
        <table class="table">
            <thead>
                <tr style="background-color: #BFBFBF">
                    <th>Nº DOC</th>
                    <th>FECHA</th>
                    <th>GRUPO</th>
                    <th>INCISO</th>
                    <th>ARTC.</th>
                    <th>MERITOS</th>
                    <th>DEMERITOS</th>
                    <th>NRO.</th>
                    <th>DETALLE</th>
                    <th>SANCIONADOR</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $puntajeFrancoDeHonor = 3 ;
            $demeritoSubTotalPorSemana = 0;
            ?>
            @foreach ($meritoDemeritoData as $key => $meritoDemerito)
                <tr style="background-color: #D8D8D8">
                    <td colspan="2">{{$meritoDemerito['titulo']}}</td>
                    <td colspan="3">{{$meritoDemerito['fecha']}}</td>
                    <td>{{$puntajeFrancoDeHonor}}</td>
                    <td>{{$demeritoSubTotalPorSemana}}</td>
                    <td></td>
                    <td>{{($puntajeFrancoDeHonor == 3) ? 'FRANCO DE HONOR' : ''}}</td>
                    <td></td>
                </tr>
                <?php
                    $demeritoSubTotalPorSemana = 0;
                ?>
                @foreach ($meritoDemerito['results'] as $keyB => $item)
                    <?php

                        if (is_null($item->merito)) {
//                            $demeritoSubTotal += $item->demerito;
                            $demeritoSubTotalPorSemana += $item->demerito;
                        }

                    ?>
                    <tr>
                        <td>{{$keyB + 1}} </td>
                        <td>{{$item->created_at->format('Y-m-d')}} </td>
                        <td>{{$item->grupo}} </td>
                        <td>{{$item->inciso}} </td>
                        <td>{{$item->articulo}} </td>
                        <td>{{$item->merito}} </td>
                        <td>{{$item->demerito}} </td>
                        <td>{{$item->num_orden}} </td>
                        <td>{{$item->detalle}} </td>
                        <td>{{strtoupper($item->sancionador)}} </td>
                    </tr>
                @endforeach
                <?php
                $demeritoSubTotal += $demeritoSubTotalPorSemana;
                $meritoSubTotal += $puntajeFrancoDeHonor;

                    if ($demeritoSubTotalPorSemana == 0) {
                        $puntajeFrancoDeHonor = 3 ;
                    } else if ($demeritoSubTotalPorSemana > 0) {
                        $puntajeFrancoDeHonor = 0 ;
                    }
                ?>
            @endforeach
            <tr>
                <td style="border: none;" colspan="4"></td>
                <td style="background-color: #D8D8D8"><strong>SUBTOTAL</strong></td>
                <td style="background-color: #D8D8D8">{{$meritoSubTotal}}</td>
                <td style="background-color: #D8D8D8">{{$demeritoSubTotal}}</td>
                <td style="border: none;" colspan="3"></td>
            </tr>
            </tbody>
        </table>
    </div>
        <?php
        $reposoSubTotal = 0;
        $felicitacionSubTotal = 0;
        $ordenSancionSubTotal = 0;
        ?>
        <div style="page-break-inside: avoid;">
            <table class="table-column" style="width: 50%">
                <thead>
                    <tr style="background-color: #BFBFBF">
                        <th colspan="3">DIAS DE REPOSO</th>
                    </tr>
                    <tr style="background-color: #D8D8D8">
                        <th width="40%">FECHA</th>
                        <th width="40%">CANTIDAD DE DIAS</th>
                        <th width="20%">DEMERITOS</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($porReposoList as $key => $porReposo)
                    <?php
                        if (is_null($porReposo->merito) && !is_null($porReposo->cant_dia)) {
                            $reposoSubTotal += $porReposo->demerito;
                        }
                    ?>
                    <tr>
                        <td>{{$porReposo->created_at->format('Y-m-d')}}</td>
                        <td>{{$porReposo->cant_dia}}</td>
                        <td>{{$porReposo->demerito}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="border: none;" colspan="1"></td>
                    <td style="background-color: #D8D8D8"><strong>SUBTOTAL</strong></td>
                    <td style="background-color: #D8D8D8">{{$reposoSubTotal}}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div style="page-break-inside: avoid;">
            <table class="table-column" style="width: 70%">
                <thead>
                    <tr style="background-color: #BFBFBF">
                        <th colspan="5">FELICITACIONES Y SANCIONES POR ORDEN DEL DÍA</th>
                    </tr>
                    <tr style="background-color: #D8D8D8">
                        <th width="35%">ORDEN DEL DIA</th>
                        <th width="15%">FECHA</th>
                        <th width="20%">NRO.</th>
                        <th width="15%">MERITOS</th>
                        <th width="15%">DEMERITOS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($porNumOrdenList as $key => $porNumOrden)
                        <?php

                            if (!is_null($porNumOrden->num_orden) && is_null($porNumOrden->demerito)) {
                                $felicitacionSubTotal += $porNumOrden->merito;
                            }

                            if (!is_null($porNumOrden->num_orden) && is_null($porNumOrden->merito)) {
                                $ordenSancionSubTotal += $porNumOrden->demerito;
                            }
                        ?>
                        <tr>
                            <td>{{$porNumOrden->detalle}}</td>
                            <td>{{$porNumOrden->created_at->format('Y-m-d')}}</td>
                            <td>{{$porNumOrden->num_orden}}</td>
                            <td>{{$porNumOrden->merito}}</td>
                            <td>{{$porNumOrden->demerito}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="border: none;" colspan="2"></td>
                        <td style="background-color: #D8D8D8"><strong>SUBTOTAL</strong></td>
                        <td style="background-color: #D8D8D8">{{$felicitacionSubTotal}}</td>
                        <td style="background-color: #D8D8D8">{{$ordenSancionSubTotal}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
            $total = $meritoSubTotal + $felicitacionSubTotal - $reposoSubTotal - $ordenSancionSubTotal - $demeritoSubTotal;
        ?>
        <div style="page-break-inside: avoid;">
            <table class="table-total">
                <tbody>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" colspan="1" scope="row">DEMERITOS</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$demeritoSubTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" colspan="1" scope="row">SANCIONES DISCIPLINARIAS</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$ordenSancionSubTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" scope="row">REPOSOS</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$reposoSubTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" scope="row">FELICITACIONES</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$felicitacionSubTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" scope="row">FRANCOS DE HONOR</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$meritoSubTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;background-color: #D8D8D8" scope="row">TOTAL</th>
                        <td style="text-align: center;padding: 0 10px 0 10px; background-color: #D8D8D8">
                            <strong>
                                {{$total}}
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <br>
    <div class="row">
        <div class="column">
            <h4 style="text-align: center;font-size: 10px;"><span style="font-weight: normal;">{{ $cadete['nombre']}}</span> <br> COMUNICADO
            </h4>
        </div>

        <div class="column">
            <h4 style="text-align: center;font-size: 10px;"><span style="font-weight: normal;">{{ $jefeDeSeccion['nombre']}}</span> <br> JEFE DE SECCION DE MERITOS Y DEMERITOS
            </h4>
        </div>
    </div>

    <br>
    <div class="row">
        <h4 style="text-align: center;font-size: 10px;"><span style="font-weight: normal;">{{ $comandanteEscuadron['nombre']}}</span> <br> COMANDANTE ESCUADRON CONDUCTA Y DISCIPLINA
        </h4>
    </div>
</body>
</html>