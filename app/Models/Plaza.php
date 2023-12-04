<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plaza extends Model
{
    use HasFactory;

    protected $table = 'plaza';
    protected $primaryKey = 'id_plaza';
    public $timestamps = false;

    protected $fillable = [
        'id_proy_financiado',
        'nombre_plaza',
        'codigo_plaza',
        'salario_base_plaza',
        'id_tipo_plaza',
        'salario_tope_plaza',
        'fecha_reg_plaza',
        'fecha_mod_plaza',
        'usuario_plaza',
        'ip_plaza',
    ];

    public function detalles_plaza()
    {
        return $this->hasMany(DetallePlaza::class, 'id_plaza', 'id_plaza');
    }
    /**
     * Get the tipo_plaza that owns the Plaza
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_plaza(): BelongsTo
    {
        return $this->belongsTo(TipoPlaza::class, 'id_tipo_plaza', 'id_tipo_plaza');
    }
}
