<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';
    protected $primaryKey = 'id_inventario';

    public function usuario(){
      	return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function almacen(){
      	return $this->belongsTo(Almacen::class, 'id_almacen');
    }
}
