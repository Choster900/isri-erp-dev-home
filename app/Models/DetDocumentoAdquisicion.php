<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetDocumentoAdquisicion extends Model
{
    use HasFactory;

    protected $table = 'detalle_documento_adquisicion';
    protected $primaryKey = 'id_det_doc_adquisicion';
    public $timestamps = false;

    protected $fillable = [
        'id_doc_adquisicion',
        'id_proy_financiado',
        'nombre_det_doc_adquisicion',
        'monto_det_doc_adquisicion',
        'compromiso_ppto_det_doc_adquisicion',
        'admon_det_doc_adquisicion',
        'estado_det_doc_adquisicion',
        'fecha_reg_det_doc_adquisicion',
        'fecha_mod_det_doc_adquisicion',
        'usuario_det_doc_adquisicion',
        'ip_det_doc_adquisicion',
    ];

    public function documento_adquisicion()
    {
        return $this->belongsTo(DocumentoAdquisicion::class,'id_doc_adquisicion','id_doc_adquisicion');
    }
    public function fuente_financiamiento()
    {
        return $this->belongsTo('App\Models\ProyectoFinanciado','id_proy_financiado','id_proy_financiado');
    }
    public function quedan()
    {
        return $this->hasMany(Quedan::class,'id_det_doc_adquisicion','id_det_doc_adquisicion');
    }
}
