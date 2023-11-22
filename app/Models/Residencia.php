<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Residencia extends Model
{
    use HasFactory;

    protected $table = 'residencia';
    protected $primaryKey = 'id_residencia';
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'id_municipio',
        'direccion_residencia',
        'punto_ref_residencia',
        'estado_residencia',
        'fecha_reg_residencia',
        'fecha_mod_residencia',
        'usuario_residencia',
        'ip_residencia',
    ];

    public function persona()
    {
        return $this->belongsTo('App\Models\Persona','id_persona','id_persona');
    }
    /**
     * Get the municipios that owns the Residencia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }
}
