<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoAdquisicion extends Model
{
    use HasFactory;

    protected $table = 'documento_adquisicion';
    protected $primaryKey = 'id_doc_adquisicion';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_gestion_compra',
        'id_tipo_doc_adquisicion',
        'id_proveedor',
        'monto_doc_adquisicion',
        'numero_doc_adquisicion',
        'numero_gestion_doc_adquisicion',
        'numero_adjudicacion_doc_adquisicion',
        'fecha_adjudicacion_doc_adquisicion',
        'estado_doc_adquisicion',
        'fecha_reg_doc_adquisicion',
        'fecha_mod_doc_adquisicion',
        'usuario_doc_adquisicion',
        'ip_doc_adquisicion',
    ];

    public function detalles()
    {
        return $this->hasMany('App\Models\DetDocumentoAdquisicion', 'id_doc_adquisicion', 'id_doc_adquisicion')->where('estado_det_doc_adquisicion', 1);
    }
    public function tipo_documento_adquisicion()
    { 
        return $this->belongsTo(TipoDocumentoAdquisicion::class, "id_tipo_doc_adquisicion", "id_tipo_doc_adquisicion");
    }
    public function Quedan()
    {
        return $this->hasManyThrough(DocumentoAdquisicion::class, TipoDocumentoAdquisicion::class);
    }
}
