<table>
    <thead>
        <tr>
            <th style="width: 50px;"><b>Producto</b></th>
            <th style="width: 30px;"><b>Invima</b></th>
            <th style="width: 30px;"><b>Ubicación</b></th>
            <th style="width: 30px;"><b>Lote/Nº de Serie</b></th>
            <th style="width: 30px;"><b>Fecha Vencimiento</b></th>
            <th style="width: 30px;"><b>Cantidad contada</b></th>
            <th style="width: 30px;"><b>Unidad de Medida del Producto</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($seguimientos as $seguimiento)
            @php 
                $seguimiento = (object) $seguimiento; 
                $total_final = $seguimiento->total_conteo_1;
                $total_final = $seguimiento->total_conteo_2 != null ? $seguimiento->total_conteo_2 : $total_final;
                if ($seguimiento->total_conteo_1 != $seguimiento->total_conteo_2) {
                    if ($seguimiento->total_conteo_3 != null) $total_final = $seguimiento->total_conteo_3;
                }
            @endphp
            <tr>
                <td>{{ $seguimiento->producto }}</td>
                <td>{{ $seguimiento->codigo_invima }}</td>
                <td>{{ $seguimiento->locacion }}/{{ $seguimiento->estante }}</td>
                <td>{{ $seguimiento->lote }}</td>
                <td>{{ $seguimiento->fecha_vencimiento }}</td>
                <td>{{ $total_final }}</td>
                <td>{{ $seguimiento->unidad_medida }}</td>
            </tr>
        @endforeach
        
    </tbody>
</table>

