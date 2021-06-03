<!DOCTYPE html>
<html>
<head>
	<title>Informe de conteo - Conteo #{{ $num_conteo }}</title>
	<style type="text/css">
		.table{
			width: 100%;
			font-family : "Calibri, sans-serif";
			font-size: 10px;
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
			<td rowspan="3" width="65%"><center><h2>INFORME DE CONTEO #{{ $num_conteo }}</h2></center></td>
			<td><b>Supervisor: </b>{{ $conteo->auditoria->usuario->nombre_completo() }}</td>
		</tr>
		<tr><td><b>Almacen: </b>{{ $conteo->auditoria->inventario->almacen->nombre }}</td></tr>
		<tr><td><b>Auditoria: #</b>{{ $conteo->id_auditoria }}</td></tr>
		<tr><td colspan="2"><center>Desde <b>{{ date('d/m/Y H:i', strtotime($conteo->fecha_inicio)) }}</b> hasta <b>{{ date('d/m/Y H:i', strtotime($conteo->fecha_fin)) }}</b></center></td></tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" class="table" border="1">
		<tr>
			<th><b><center>Contador</center></b></th>
			<th><b><center>Locación</center></b></th>
			<th><b><center>Estante</center></b></th>
			<th><b><center>Fila</center></b></th>
			<th><b><center>Producto</center></b></th>
			<th><b><center>Cantidad</center></b></th>
			<th><b><center>Lote</center></b></th>
			<th><b><center>Vencimiento</center></b></th>
			<th><b><center>Realización</center></b></th>
		</tr>
		@foreach ($seguimientos as $item)
			<tr>
				<td><center>{{ $item->conteo_detalle->usuario->nombre_completo() }} - {{ $item->conteo_detalle->usuario->documento }}</center></td>
				<td><center>{{ $item->conteo_detalle->auditoria_detalle->estante->locacion->nombre }}</center></td>
				<td><center>{{ $item->conteo_detalle->auditoria_detalle->estante->nombre }}</center></td>
				<td><center>{{ $item->fila->nombre }}</center></td>
				<td><center>{{ $item->producto->codigo }} - {{ ucfirst(strtolower($item->producto->nombre)) }}</center></td>
				<td><center>{{ $item->cantidad }}</center></td>
				<td><center>{{ $item->lote }}</center></td>
				<td><center>{{ date('d/m/Y', strtotime($item->fecha_vencimiento)) }}</center></td>
				<td><center>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</center></td>
			</tr>
		@endforeach
	</table>
</body>
</html>