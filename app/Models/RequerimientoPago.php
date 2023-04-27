<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequerimientoPago extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'requerimiento_pago';
    protected $primaryKey = 'id_requerimiento_pago';
    public $timestamps = false;
    protected $fillable = [
        'id_requerimiento_pago',
        'numero_requerimiento_pago',
        'mes_requerimiento_pago',
        'monto_requerimiento_pago',
        'anio_requerimiento_pago',
        'fecha_requerimiento_pago',
        'fecha_reg_requerimiento_pago',
        'fecha_mod_requerimiento_pago',
        'usuario_requerimiento_pago',
        'ip_requerimiento_pago',
    ];


    public function Quedan()
    {
        return $this->hasMany(Quedan::class, "id_requerimiento_pago", "id_requerimiento_pago");
    }
}