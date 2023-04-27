<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Yajra\DataTables\Html\Editor\Fields\BelongsTo;

class LiquidacionQuedan extends Model
{
    use HasFactory;
    protected $table = 'liquidacion_quedan';
    protected $primaryKey = 'id_liquidacion_quedan';
    public $timestamps = false;

    protected $fillable = [
        'id_quedan',
        'fecha_liquidacion_quedan',
        'monto_liquidacion_quedan',
        'estado_liquidacion_quedan',
        'notifica_liquidacion_quedan',
        'fecha_reg_liquidacion_quedan',
        'fecha_mod_liquidacion_quedan',
        'usuario_liquidacion_quedan',
        'ip_liquidacion_quedan',
    ];
    /**
     * Get the quedan that owns the LiquidacionQuedan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quedan():HasOne
    {
        return $this->hasOne(Quedan::class, 'id_quedan', 'id_quedan');
    }

    


}