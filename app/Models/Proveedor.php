<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedor'; // Nombre de la tabla
    protected $primaryKey = 'id_proveedor'; // Llave primari de la tabla
    public $timestamps = false; // Deshabilitando campos generados por laravel

    protected $fillable = [
        'id_tipo_contribuyente',
        'id_municipio',
        'id_sujeto_retencion',
        'id_giro',
        'razon_social_proveedor',
        'nombre_comercial_proveedor',
        'nrc_proveedor',
        'dui_proveedor',
        'nit_proveedor',
        'contacto_proveedor',
        'telefono1_proveedor',
        'telefono2_proveedor',
        'direccion_proveedor',
        'email_proveedor',
        'estado_proveedor',
        'fecha_reg_proveedor',
        'fecha_mod_proveedor',
        'usuario_proveedor',
        'ip_proveedor',
    ];

    public function sujeto_retencion()
    {
        return $this->belongsTo(SujetoRetencion::class, "id_sujeto_retencion", "id_sujeto_retencion");
    }
    /**
     * Get the giro that owns the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function giro(): BelongsTo
    {
        return $this->belongsTo(Giro::class, 'id_giro', 'id_giro');
    }
    public function scopeWithSujetoRetencion($query)
    {
        return $query->with("sujeto_retencion");
    }
    public function quedan()
    {
        return $this->hasMany(Quedan::class, "id_proveedor", "id_proveedor");
    }
    /**
     * Get all of the documentos_adquisiciones for the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentos_adquisiciones(): HasMany
    {
        return $this->hasMany(DocumentoAdquisicion::class, 'id_proveedor', 'id_proveedor');
    }
    public function recepciones()
    {
        return $this->hasMany('App\Models\Recepcion', 'id_proveedor', 'id_proveedor');
    }
}
