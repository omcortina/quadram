<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';

     protected $fillable = [
    	'estado',
        'codigo',
        'codigo_barras',
        'cantidad_mano',
        'nombre',
        'descripcion',
        'precio_venta',
        'unidad_medida',
        'codigo_invima',
        'fecha_vencimiento_invima',
        'codigo_atc',
        'codigo_cum',
        'presentacion',
        'marca'
    ];
}
