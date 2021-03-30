<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacion extends Model
{
    protected $table = 'locacion';
    protected $primaryKey = 'id_locacion';

    public function almacen(){
      return $this->belongsTo(Almacen::class, 'id_almacen');
    }
}
