<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoRecepcionPedido extends Model
{
    use HasFactory;

    protected $table = 'estado_recepcion_pedido';
    protected $primaryKey = 'id_estado_recepcion_pedido';
    public $timestamps = false;

    protected $fillable = [
        'estado_recepcion_pedido',
    ];
}
