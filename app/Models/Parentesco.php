<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parentesco extends Model
{
    use HasFactory;
    protected $table = 'parentesco';
    protected $primaryKey = 'id_parentesco';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_parentesco',
        'nombre_parentesco',
        'unico_parentesco',
        'grado_parentesco',
        'estado_parentesco',
    ];
    /**
     * Get all of the comments for the Parentesco
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function familiar(): HasMany
    {
        return $this->hasMany(Familiar::class, 'id_parentesco', 'id_parentesco');
    }
    /**
     * Get the user that owns the Parentesco
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_parentesco(): BelongsTo
    {
        return $this->belongsTo(TipoParentesco::class, 'id_tipo_parentesco', 'id_tipo_parentesco');
    }
}