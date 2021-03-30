<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estante extends Model
{
    protected $table = 'estante';
    protected $primaryKey = 'id_estante';

    public function almacen(){
		return $this->belongsTo(Locacion::class, 'id_locacion');
	}
}
