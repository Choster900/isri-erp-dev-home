<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMine extends Model
{
    use HasFactory;
    protected $table = 'tipo_mine';
    protected $primaryKey = 'id_tipo_mime';
    public $timestamps = false;
    protected $fillable = [
        'extension_tipo_mime',
        'img_tipo_mime',
    ];
    /**
     * Get all of the archivos_anexos for the TipoMine
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos_anexos(): HasMany
    {
        return $this->hasMany(ArchivoAnexo::class, 'id_tipo_mime', 'id_tipo_mime');
    }
}
