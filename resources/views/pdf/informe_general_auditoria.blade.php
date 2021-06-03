<!DOCTYPE html>
<html>
<head>
	<title>Informe de auditoria</title>
	<style type="text/css">
		.table{
			width: 100%;
			font-family : "Calibri, sans-serif";
			font-size: 12px;
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
			<td rowspan="3" width="65%"><center><h2>INFORME GENERAL DE AUDITORIA</h2></center></td>
			<td><b>Supervisor: </b>{{ $auditoria->usuario->nombre_completo() }}</td>
		</tr>
		<tr><td><b>Almacen: </b>{{ $auditoria->inventario->almacen->nombre }}</td></tr>
		<tr><td><b>Estado: </b>{{ $auditoria->estado == 1 ? "Activa" : "Inactiva" }}</td></tr>
		<tr><td colspan="2"><center>Desde <b>{{ date('d/m/Y H:i', strtotime($auditoria->fecha_inicio)) }}</b> hasta <b>{{ date('d/m/Y H:i', strtotime($auditoria->fecha_fin)) }}</b></center></td></tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" class="table" border="1">
		<tr>
			<th><b><center>Auditor</center></b></th>
			<th><b><center>Locación</center></b></th>
			<th><b><center>Estante</center></b></th>
			<th><b><center>Fila</center></b></th>
			<th><b><center>Producto</center></b></th>
			<th><b><center>Realización</center></b></th>
		</tr>
		@foreach ($seguimientos as $item)
			<tr>
				<td><center>{{ $item->auditoria_detalle->usuario->nombre_completo() }} - {{ $item->auditoria_detalle->usuario->documento }}</center></td>
				<td><center>{{ $item->auditoria_detalle->estante->locacion->nombre }}</center></td>
				<td><center>{{ $item->auditoria_detalle->estante->nombre }}</center></td>
				<td><center>{{ $item->fila->nombre }}</center></td>
				<td><center>{{ $item->producto->codigo }} - {{ ucfirst(strtolower($item->producto->nombre)) }}</center></td>
				<td><center>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</center></td>
			</tr>
		@endforeach
	</table>
</body>
</html>