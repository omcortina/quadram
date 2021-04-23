<!DOCTYPE html>
<html>
<head>
	<title>Informe general de conteo</title>
	<style type="text/css">
		.table{
			width: 100%;
			font-family : "Calibri, sans-serif";
			font-size: 9px;
			border: 1px solid #000;
		}
		.table td, th{
			padding: 10px;
		}
		.table th{
			background-color: #5e72e4;
			color: white;
		}
		.table-head{
			width: 100%;
			font-family : "Calibri, sans-serif";
			font-size: 12px;
			border: 1px solid #000;
		}
		.table-head td, th{
			padding: 7px;
		}
	</style>
</head>
<body>
	<table cellpadding="0" cellspacing="0" class="table-head" border="1">
		<tr>
			<td rowspan="3" width="65%"><center><h2>INFORME GENERAL DE CONTEO</h2></center></td>
			<td><b>Supervisor: </b>{{ $conteo->auditoria->usuario->nombre_completo() }}</td>
		</tr>
		<tr><td><b>Almacen: </b>{{ $conteo->auditoria->inventario->almacen->nombre }}</td></tr>
		<tr><td><b>Auditoria: #</b>{{ $conteo->id_auditoria }}</td></tr>
		<tr><td colspan="2"><center>Desde <b>{{ date('d/m/Y', strtotime($conteo->fecha_inicio)) }}</b> hasta <b>{{ date('d/m/Y', strtotime($conteo->fecha_fin)) }}</b></center></td></tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" class="table" border="1">
		<tr>
			<th><b><center>Locación</center></b></th>
			<th><b><center>Estante</center></b></th>
			<th><b><center>Fila</center></b></th>
			<th><b><center>Producto</center></b></th>
			<th><b><center>Primer conteo</center></b></th>
			<th><b><center>Segundo conteo</center></b></th>
			<th><b><center>Tercer conteo</center></b></th>
			<th><b><center>Cantidad final</center></b></th>
		</tr>
		@foreach ($seguimientos as $item)
			<tr>
				<td><center>{{ $item->auditoria_detalle->estante->locacion->nombre }}</center></td>
				<td><center>{{ $item->auditoria_detalle->estante->nombre }}</center></td>
				<td><center>{{ $item->fila->nombre }}</center></td>
				<td><center>{{ $item->producto->codigo }} - {{ ucfirst(strtolower($item->producto->nombre)) }}</center></td>
				@for ($_conteo = 1; $_conteo <= 3; $_conteo++)
					<td>
						<center>
							@php

								$cantidad_ultimo_conteo = "No definida";
								$cantidad = "No definida";
								$lote = "No definido";
								$vencimiento = "No definido";
								
								//BUSCAMOS SEGUIMIENTOS DE CONTEO RELACIONADOS A LOS DETALLES
								$conteo_detalle = \App\Models\ConteoDetalle::where('estado', 1)
																		   ->where('id_auditoria_detalle', $item->id_auditoria_detalle)
																		   ->where('conteo', $_conteo)
																		   ->first();
								if($conteo_detalle){
									$seguimiento_conteo = \App\Models\SeguimientoConteo::where('estado', 1)
																				   	   ->where('id_conteo_detalle', $conteo_detalle->id_conteo_detalle)
																				   	   ->where('id_fila_estante', $item->id_fila_estante)
																				   	   ->where('id_producto', $item->id_producto)
																				   	   ->first();
									if($seguimiento_conteo){
										$cantidad = $seguimiento_conteo->cantidad;
										$lote = $seguimiento_conteo->lote;
										$vencimiento = $seguimiento_conteo->fecha_vencimiento;
									}
								}
							@endphp

							<b>Cantidad</b><br>
							{{ $cantidad }}<br>
							<b>Lote</b><br>
							{{ $lote }}<br>
							<b>Vencimiento</b><br>
							{{ $vencimiento }}<br>
						</center>
					</td>
				@endfor
				<td><center>{{ $cantidad_ultimo_conteo }}</center></td>
			</tr>
		@endforeach
	</table>
</body>
</html>