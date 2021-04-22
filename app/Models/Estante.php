<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estante extends Model
{
    protected $table = 'estante';
    protected $primaryKey = 'id_estante';
    protected $fillable = [
    	'nombre',
        'id_locacion',
        'estado'
    ];

    public function locacion(){
      return $this->belongsTo(Locacion::class, 'id_locacion');
    }

    public function filas(){
        $filas = FilaEstante::all()->where("estado", 1);
        return $filas;
    }
}
