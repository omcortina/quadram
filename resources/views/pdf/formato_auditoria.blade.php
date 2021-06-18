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
			padding: 3px;
		}
		.table th{
            border: 1px solid #000;
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
			padding: 4px;
		}
	</style>
</head>
<body>
	<table cellpadding="0" cellspacing="0" class="table-head" border="1">
		<tr>
			<td rowspan="3" width="65%"><center><h2>FORMATO DE AUDITORIA</h2></center></td>
			<td><b>Supervisor: </b>{{ $auditoria->usuario->nombre_completo() }}</td>
		</tr>
		<tr><td><b>Almacen: </b>{{ $auditoria->inventario->almacen->nombre }}</td></tr>
		<tr><td><b>Estado: </b>{{ $auditoria->estado == 1 ? "Activa" : "Inactiva" }}</td></tr>
		<tr><td colspan="2"><center>Desde <b>{{ date('d/m/Y H:i', strtotime($auditoria->fecha_inicio)) }}</b> hasta <b>{{ date('d/m/Y H:i', strtotime($auditoria->fecha_fin)) }}</b></center></td></tr>
	</table>
	<br>
	<table cellpadding="0" cellspacing="0" class="table" border="1">
		<tr>
            <th style="width: 50px;"><b><center>Estante</center></b></th>
            <th style="width: 370px;"><b><center>Producto</center></b></th>
			<th style="width: 100px;"><b><center>Fila</center></b></th>
		</tr>
        @for ($i = 0; $i < 38; $i++)
            <tr>
                <td><center>{{ $estante->nombre }}</center></td>
                <td></td>
                <td></td>
            </tr>
        @endfor
	</table>
</body>
</html>
