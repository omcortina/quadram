<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';

    public function inventario(){
      	return $this->belongsTo(Inventario::class, 'id_inventario');
    }

    public function usuario(){
      	return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function detalles(){
        return $this->hasMany(AuditoriaDetalle::class, 'id_auditoria');
    }
    
    public function estado(){
      	return $this->belongsTo(Dominio::class, 'id_dominio_estado', 'id_dominio');
    }

    public function conteos(){
        return $this->hasMany(Conteo::class, 'id_auditoria');
    }

    public function conteo()
    {
      return count($this->conteos) > 0 ? $this->conteos[0] : null;
    }

    public function tiene_seguimientos()
    {
        $respuesta = false;
        foreach ($this->detalles as $detalle) {
            if (count($detalle->seguimientos) > 0) $respuesta = true;
        }
        return $respuesta;
    }

    public function estado_actual()
    {
        $fecha_actual = date('Y-m-d H:i:s');
        $estado = "No creada";
        if ($fecha_actual >= $this->fecha_inicio and $fecha_actual <= $this->fecha_fin) $estado = "Iniciado";
        
        if ($this->tiene_seguimientos()) $estado = "En progreso";
        
        if ($fecha_actual < $this->fecha_inicio) $estado = "Sin Iniciar";
        
        if ($fecha_actual > $this->fecha_fin) $estado = "Finalizada";

        return $estado;
    }

}
