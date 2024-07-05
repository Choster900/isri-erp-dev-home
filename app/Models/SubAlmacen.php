<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubAlmacen extends Model
{
    use HasFactory;

    protected $table = 'sub_almacen';
    protected $primaryKey = 'id_sub_almacen';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'nombre_sub_almacen',
        'estado_sub_almacen',
        'fecha_reg_sub_almacen',
        'fecha_mod_sub_almacen',
        'usuario_sub_almacen',
        'ip_sub_almacen',
    ];

    /**
     * Get the empleado that owns the SubAlmacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }
}
