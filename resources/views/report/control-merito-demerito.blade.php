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
            <h3 style="text-align: left">COLEGIO MILITAR DE AVIACION <br> GRUPO CADETES <br> BOLIVIA
            </h3>
        </div>

        <div class="column" style="text-align: right">
            <h3>CURSO: {{$cadete->year_ingreso}} Año <br> GESTION: {{now()->year}} <br> FECHA DE EMISION: {{now()->format('Y-m-d H:i')}}</h3>
        </div>
    </div>

    <div>
        <h3 style="text-align: center"> HOJA DE CONTROL DE MERITOS Y DEMERITOS</h3>
    </div>
    <div>
        <h4>GRADO, APELLIDOS Y NOMBRES: <span style="font-weight: normal;">{{ $cadete->persona->nombre}}</span> </h4>
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
            @foreach ($meritoDemeritoData as $key => $meritoDemerito)
                <tr style="background-color: #D8D8D8">
                    <td colspan="2">{{$meritoDemerito['titulo']}}</td>
                    <td colspan="3">{{$meritoDemerito['fecha']}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($meritoDemerito['results'] as $keyB => $item)
                    <?php

                        if (is_null($item->merito)) {
                            $demeritoSubTotal += $item->demerito;
                        }

                        if (is_null($item->num_orden)
                            && is_null($item->merito) && (is_null($item->cant_dia) || $item->cant_dia == 0)) {
                            $demeritoTotal += $item->demerito;
                        } else if (!is_null($item->num_orden)
                            && is_null($item->merito) && (is_null($item->cant_dia) || $item->cant_dia == 0)) {
                            $sancionDisciplinariaTotal += $item->demerito;
                        } else if (is_null($item->num_orden)
                            && is_null($item->merito) && (!is_null($item->cant_dia) && $item->cant_dia > 0)) {
                            $reposoTotal += $item->demerito;
                        }

                        if (is_null($item->demerito)) {
                            $meritoSubTotal += $item->merito;
                        }

                        if (is_null($item->num_orden)
                            && is_null($item->demerito) && (is_null($item->cant_dia) || $item->cant_dia == 0)) {
                            $francoDeHonorTotal += $item->merito;
                        } else if (!is_null($item->num_orden)
                            && is_null($item->demerito) && (is_null($item->cant_dia) || $item->cant_dia == 0)) {
                            $felicitacionTotal += $item->merito;
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
        <?php
            $total = $francoDeHonorTotal + $felicitacionTotal - $reposoTotal - $sancionDisciplinariaTotal - $demeritoTotal;
        ?>
        <div>
            <table class="table-total">
                <tbody>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" colspan="1" scope="row">DEMERITOS</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$demeritoTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" colspan="1" scope="row">SANCIONES DISCIPLINARIAS</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$sancionDisciplinariaTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" scope="row">REPOSOS</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$reposoTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" scope="row">FELICITACIONES</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$felicitacionTotal}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;padding: 0 10px 0 0;" scope="row">FRANCOS DE HONOR</th>
                        <td style="text-align: center;padding: 0 10px 0 10px;">{{$francoDeHonorTotal}}</td>
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
    </div>
</body>
</html>