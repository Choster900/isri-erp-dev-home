<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoAnexo extends Model
{
    use HasFactory;

    protected $table = 'archivo_anexo';
    protected $primaryKey = 'id_archivo_anexo';
    public $timestamps = false;
    protected $fillable = [
        'id_tipo_archivo_anexo',
        'id_persona',
        'id_tipo_mime',
        'nombre_archivo_anexo',
        'url_archivo_anexo',
        'descripcion_archivo_anexo',
        'fecha_reg_archivo_anexo',
        'fecha_mod_archivo_anexo',
        'usuario_archivo_anexo',
        'ip_archivo_anexo',
    ];
    /**
     * Get the tipo_archivo_anexo that owns the ArchivoAnexo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_archivo_anexo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_tipo_archivo_anexo', 'id_tipo_archivo_anexo');
    }
    /**
     * Get the tipo_mine that owns the ArchivoAnexo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_mine(): BelongsTo
    {
        return $this->belongsTo(TipoMine::class, 'id_tipo_mime', 'id_tipo_mime');
    }
    /**
     * Get the persona that owns the ArchivoAnexo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}
