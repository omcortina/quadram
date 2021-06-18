<!DOCTYPE html>
<html>
<head>
	<title>Formato de conteo</title>
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
			<td rowspan="3" width="65%"><center><h2> INVENTARIO GENERAL </h2></center></td>
			<td><b>Contador: </b>{{ $conteo_detalle->usuario->nombre_completo() }}</td>
		</tr>
		<tr><td><b>Almacen: </b>{{ $conteo_detalle->_conteo->auditoria->inventario->almacen->nombre }}</td></tr>
		<tr><td><b>Auditoria: #</b>{{ $conteo_detalle->_conteo->id_auditoria }}</td></tr>
		<tr><td colspan="2"><center><b>ESTANTE {{ $conteo_detalle->estante->nombre }}</b></center></td></tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" class="table" border="1">
		<tr>
			<th colspan="7"><h2><b><center>Conteo #{{ $conteo_detalle->conteo }}</center></b></h3></th>
		</tr>
		<tr>
			<th style="width: 8%;"><b><center>Estante</center></b></th>
			<th style="width: 8%;"><b><center>Fila</center></b></th>
			<th><b><center>Producto</center></b></th>
			<th style="width: 85px;"><b><center>Lab</center></b></th>
			<th style="width: 10%;"><b><center>Cantidad</center></b></th>
			<th style="width: 10%;"><b><center>Lote</center></b></th>
			<th style="width: 10%;"><b><center>F. Vencimiento</center></b></th>
		</tr>
		@foreach ($seguimientos_auditoria as $item)
			<tr>
				<td><center>{{ $item->auditoria_detalle->estante->nombre }}</center></td>
				<td><center>{{ $item->fila->nombre }}</center></td>
				<td><center>{{ ucfirst(strtolower($item->producto->nombre)) }} - {{ $item->producto->codigo }}</center></td>
				<td><center>{{ strtoupper($item->producto->marca) }}</center></td>
				<td><center></center></td>
				<td><center></center></td>
				<td><center></center></td>
			</tr>
		@endforeach
	</table>
</body>
</html>