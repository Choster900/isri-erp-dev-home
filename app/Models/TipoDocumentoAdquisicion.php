<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoDocumentoAdquisicion extends Model
{
    use HasFactory;

    protected $table = 'tipo_documento_adquisicion';
    protected $primaryKey = 'id_tipo_doc_adquisicion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_doc_adquisicion',
        'estado_tipo_doc_adquisicion',

    ];

    /**
     * Get all of the Quedan for the TipoDocumentoAdquisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quedan(): HasMany
    {
        return $this->hasMany(Quedan::class, 'id_tipo_doc_adquisicion', 'id_tipo_doc_adquisicion');
    }
    public function documento_adquisicion(): belongsTo
    {
        return $this->belongsTo(DocumentoAdquisicion::class, 'id_tipo_doc_adquisicion', 'id_tipo_doc_adquisicion');
    }
}