
<table>
    <thead>
    <tr>
        <th colspan="9" style="border-bottom-color: #FFFFFF"></th>
        <th colspan="4" style="width: 30px;"><b>Fecha de inventario: </b> &nbsp;{{ date('Y-m-d', strtotime($inventario->fecha_inicio)) }} hasta {{ date('Y-m-d', strtotime($inventario->fecha_fin)) }}</th>
    </tr>
    <tr>
        <th colspan="9" style="border-top-color: #FFFFFF border-bottom-color: #FFFFFF; text-align: center;">
            <b>INFORME GENERAL DE INVENTARIO</b>
        </th>
        <th colspan="4" style="width: 30px;"><b>Supervisor: </b>&nbsp; {{ $inventario->usuario->nombre_completo() }}</th>
    </tr>
    
    <tr>
        <th colspan="9" style="border-bottom-color: #FFFFFF"></th>
        <th colspan="4" style="width: 30px;"><b>Almacen: </b> &nbsp;{{ $inventario->almacen->nombre }}</th>
    </tr>
    <tr style="background-color: #5E72E4; color: #FFFFFF">
        <th style="background-color: #5E72E4; width: 50px; text-align: center; color: #FFFFFF">
            <b>Producto</b>
        </th>
        <th style="background-color: #5E72E4; width: 10px; text-align: center; color: #FFFFFF">
            <b>Codigo</b>
        </th>
        <th style="background-color: #5E72E4; width: 20px; text-align: center; color: #FFFFFF">
            <b>Marca</b>
        </th>
        <th style="background-color: #5E72E4; width: 15px; text-align: center; color: #FFFFFF">
            <b>Presentacion</b>
        </th>
        <th style="background-color: #5E72E4; width: 10px; text-align: center; color: #FFFFFF">
            <b>Locacion</b>
        </th>
        <th style="background-color: #5E72E4; width: 10px; text-align: center; color: #FFFFFF">
            <b>Estante</b>
        </th>
        <th style="background-color: #5E72E4; width: 10px; text-align: center; color: #FFFFFF">
            <b>Fila</b>
        </th>
        <th style="background-color: #5E72E4; width: 10px; text-align: center; color: #FFFFFF">
            <b>Lote</b>
        </th>
        <th style="background-color: #5E72E4; width: 20px; text-align: center; color: #FFFFFF">
            <b>Fecha de vencimiento</b>
        </th>
        <th style="background-color: #5E72E4; width: 15px; text-align: center; color: #FFFFFF">
            <b>Primer Conteo</b>
        </th>
        <th style="background-color: #5E72E4; width: 15px; text-align: center; color: #FFFFFF">
            <b>Segundo Conteo</b>
        </th>
        <th style="background-color: #5E72E4; width: 15px; text-align: center; color: #FFFFFF">
            <b>Tercer Conteo</b>
        </th>
        <th  style="background-color: #5E72E4; width: 15px; text-align: center; color: #FFFFFF">
           <b>Cantidad Final</b>
        </th>
    </tr>
    </thead>
    <tbody>
        @foreach ($seguimientos as $seguimiento)
            @php 
                $seguimiento = (object) $seguimiento; 
            @endphp
            <tr>
                <td>{{ $seguimiento->producto }}</td>
                <td>{{ $seguimiento->codigo }}</td>
                <td>{{ $seguimiento->marca }}</td>
                <td>{{ $seguimiento->presentacion }}</td>
                <td>{{ $seguimiento->locacion }}</td>
                <td>{{ $seguimiento->estante }}</td>
                <td>{{ $seguimiento->fila }}</td>
                <td>{{ $seguimiento->lote }}</td>
                <td>{{ $seguimiento->fecha_vencimiento }}</td>
                <td>{{ $seguimiento->total_conteo_1 }}</td>
                <td>{{ $seguimiento->total_conteo_2 }}</td>
                <td>{{ $seguimiento->total_conteo_3 }}</td>
                @php
                    $color = "transparent";
                    $total_final = $seguimiento->total_conteo_1;
                    $total_final = $seguimiento->total_conteo_2 != null ? $seguimiento->total_conteo_2 : $total_final;
                    if ($seguimiento->total_conteo_1 != $seguimiento->total_conteo_2) {
                        if ($seguimiento->total_conteo_3 != null) $total_final = $seguimiento->total_conteo_3;
                    }
                    if ($total_final > 0) $color = "#00B050"; 
                    if ($total_final <= 0) $color = "#C00000"; 
                @endphp
                <td style="background-color: {{ $color }}; ">
                    {{ $total_final }}
                </td>
            </tr>
        @endforeach
        
    </tbody>
</table>

