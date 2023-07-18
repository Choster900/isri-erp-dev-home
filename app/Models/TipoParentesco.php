<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoParentesco extends Model
{
    use HasFactory;
    protected $table = 'tipo_parentesco';
    protected $primaryKey = 'id_tipo_parentesco';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_parentesco',
    ];
    /**
     * Get all of the parentesco for the TipoParentesco
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parentesco(): HasMany
    {
        return $this->hasMany(Parentesco::class, 'id_tipo_parentesco', 'id_tipo_parentesco');
    }
}