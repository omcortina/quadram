<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacen';
    protected $primaryKey = 'id_almacen';

    protected $fillable = [
    	'nombre',
    	'direccion',
    	'telefono',
    	'estado'
    ];

    public function locaciones(){
        return $this->hasMany(Locacion::class, 'id_almacen');
    }
}
