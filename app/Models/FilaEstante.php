<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilaEstante extends Model
{
    protected $table = 'fila_estante';
    protected $primaryKey = 'id_fila_estante';
    protected $fillable = [
    	'nombre',
        'id_estante',
        'estado'
    ];

    public function estante(){
      return $this->belongsTo(Estante::class, 'id_estante');
    }
}
