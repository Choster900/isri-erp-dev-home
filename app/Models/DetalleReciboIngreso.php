<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReciboIngreso extends Model
{
    use HasFactory;

    protected $table = 'detalle_recibo_ingreso';
    protected $primaryKey = 'id_det_recibo_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'id_recibo_ingreso',
        'id_concepto_ingreso',
        'monto_det_recibo_ingreso',
        'estado_det_recibo_ingreso',
        'fecha_reg_det_recibo_ingreso',
        'fecha_mod_det_recibo_ingreso',
        'ip_det_recibo_ingreso',
        'usuario_det_recibo_ingreso'
    ];

    public function recibo_ingreso()
    {
        return $this->belongsTo('App\Models\ReciboIngreso','id_recibo_ingreso','id_recibo_ingreso');
    }
}
