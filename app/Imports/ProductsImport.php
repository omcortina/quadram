<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Producto;
class ProductsImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        $product = Producto::where('codigo', $row["referencia_interna"])->firts();
        if ($product) {
            $product->estado = 0;
            $product->save();
        }
        return new Producto([
            'estado' => $row['activo'] == true ? 1 : 0,
            'codigo' => $row["referencia_interna"],
            'codigo_barras' => $row["codigo_barras"],
            'cantidad_mano' => $row["cantidad_a_mano"],
            'nombre' => $row["nombre"],
            'descripcion' => $row["descripcion"] ?? "",
            'precio_venta' => $row["precio_venta"],
            'unidad_medida' => $row["unidad_de_medida"],
            'codigo_invima' => $row["codigo_invima"],
            'fecha_vencimiento_invima' => date('Y-m-d', $row["venc_invima"]),
            'codigo_atc' => $row["codigo_atc"],
            'codigo_ucm' => $row["codigo_ucm"],
            'presentacion' => $row["presentacion"],
            'marca' => $row["marcalaboratorio"],
        ]);
    }
}
