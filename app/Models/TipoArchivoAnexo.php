<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoArchivoAnexo extends Model
{
    use HasFactory;
    protected $table = 'tipo_archivo_anexo';
    protected $primaryKey = 'id_tipo_archivo_anexo';
    public $timestamps = false;
    protected $fillable = [
        'nombre_tipo_archivo_anexo',
        'img_tipo_archivo_anexo',
        'laboral_tipo_archivo_anexo',
        'estado_tipo_archivo_anexo',
    ];
   /*  public $appends = ['countAnexos']; //ESTO ES PARTE DEL WITH EN EL MODELO*/

    /**
     * Get all of the archivos_anexos for the TipoArchivoAnexo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos_anexos(): HasMany
    {
        return $this->hasMany(ArchivoAnexo::class, 'id_tipo_archivo_anexo', 'id_tipo_archivo_anexo');
    }

   /*  public function getCountAnexosAttribute()
    {//ESTO ES COMO IN WITH PERO EN EL MODELO
        # code...
        return $this->archivos_anexos->count();
    } */
}
