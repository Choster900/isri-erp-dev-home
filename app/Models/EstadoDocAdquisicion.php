<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoDocAdquisicion extends Model
{
    use HasFactory;
    protected $table = 'estado_doc_adquisicion';
    protected $primaryKey = 'id_estado_doc_adquisicion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_estado_doc_adquisicion',
    ];
    /**
     * Get all of the detalle_documentos_adquisiciones for the EstadoDocAdquisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalle_documentos_adquisiciones(): HasMany
    {
        return $this->hasMany(DetDocumentoAdquisicion::class, 'id_estado_doc_adquisicion', 'id_estado_doc_adquisicion');
    }
}
