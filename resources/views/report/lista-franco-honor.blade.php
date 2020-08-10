<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Usuarios</title>
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
            width: auto;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }
        .table-bordered {
            border: 1px solid #c2cfd6;
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
        /*.table th, .table td {*/
            /*padding: 0.75rem;*/
            /*vertical-align: top;*/
            /*border-top: 1px solid #c2cfd6;*/
        /*}*/
        /*.table thead th {*/
            /*vertical-align: bottom;*/
            /*border-bottom: 2px solid #c2cfd6;*/
        /*}*/
        /*.table-bordered thead th, .table-bordered thead td {*/
            /*border-bottom-width: 2px;*/
        /*}*/
        /*.table-bordered th, .table-bordered td {*/
            /*border: 1px solid #c2cfd6;*/
        /*}*/
        th, td {
            display: table-cell;
            vertical-align: inherit;
        }
        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
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
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .izquierda{
            float:left;
        }
        .derecha{
            float:right;
        }
    </style>
</head>
<body>
    <div>
        <h3 style="text-align: justify">COLEGIO MILITAR DE AVIACION <br> GRUPO CADETES <br> BOLIVIA <span class="derecha">{{now()}}</span></h3>
    </div>
    <div>
        <h3 style="text-align: center">LISTA DE FRANCO Y ARRESTOS DE LAS DAMAS Y CABALLEROS <br> CADETES DEL COLEGIO MILITAR DE AVIACION</h3>
    </div>
    <div style="width: 50%;margin: 0 auto">
        <h3 style="text-align: left">{{$titular}}
        </h3>
    </div>
    <div>
        @foreach ($groupCadeteList as $key => $cadeteList)
            <div>
                @if($key == 4)
                    <h3><u>CUARTO AÑO MILITAR</u></h3>
                @endif
                @if($key == 3)
                    <h3><u>TERCER AÑO MILITAR</u></h3>
                @endif
                @if($key == 2)
                    <h3><u>SEGUNDO AÑO MILITAR</u></h3>
                @endif
                @if($key == 1)
                    <h3><u>PRIMER AÑO MILITAR</u></h3>
                @endif
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nº <br>GRAL</th>
                        <th>GRADO </th>
                        <th>APELLIDO Y NOMBRE </th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($cadeteList as $key => $cadete)
                            <?php
                                $arrayGrado = ["", "I" , "II", "III", "IV"];
                                $numeroGrado = $arrayGrado[$cadete->year_ingreso];
                                $nombreGrado = "";
                                if ($cadete->grado === "Cdte") {
                                    $nombreGrado = $cadete->grado. " ". $numeroGrado;
                                } else {
                                    $nombreGrado = $cadete->grado;
                                }
                            ?>
                            <tr style="text-align: center">
                                <td>{{$key + 1}} </td>
                                <td>{{$nombreGrado}} </td>
                                <td style="text-align: left">{{$cadete->nombre}} </td>
                            </tr>
                        @endforeach
                </tbody>
                <strong>TOTAL CADETES : {{count($cadeteList)}}</strong>
            </table>
        @endforeach
    </div>
</body>
</html>