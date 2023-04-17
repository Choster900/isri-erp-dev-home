<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboIngreso extends Model
{
    use HasFactory;

    protected $table = 'recibo_ingreso';
    protected $primaryKey = 'id_recibo_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'id_ctrl_ingreso',
        'id_ccta_presupuestal',
        'fecha_recibo_ingreso',
        'numero_recibo_ingreso',
        'doc_identidad_recibo_ingreso',
        'cliente_recibo_ingreso',
        'monto_recibo_ingreso',
        'descripcion_recibo_ingreso',
        'estado_recibo_ingreso',
        'fecha_reg_recibo_ingreso',
        'fecha_mod_recibo_ingreso',
        'ip_recibo_ingreso',
        'usuario_recibo_ingreso'
    ];
}
