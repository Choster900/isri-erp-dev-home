<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
