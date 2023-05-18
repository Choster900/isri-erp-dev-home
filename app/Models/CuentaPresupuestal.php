<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaPresupuestal extends Model
{
    use HasFactory;

    protected $table = 'catalogo_cta_presupuestal';
    protected $primaryKey = 'id_ccta_presupuestal';
    public $timestamps = false;

    protected $fillable = [
        'id_padre_ccta_presupuestal',
        'nombre_ccta_presupuestal',
        'estado_ccta_presupuestal',
        'tesoreria_ccta_presupuestal',
        'descripcion_ccta_presupuestal',
    ];

    public function recibos_ingreso()
    {
        return $this->hasMany('App\Models\ReciboIngreso', 'id_ccta_presupuestal', 'id_ccta_presupuestal');
    }

    public function conceptos_ingreso()
    {
        return $this->hasMany('App\Models\ConceptoIngreso', 'id_ccta_presupuestal', 'id_ccta_presupuestal');
    }

    public function parentBudgetAccount()
    {
        return $this->hasOne('App\Models\CuentaPresupuestal', 'id_ccta_presupuestal', 'id_padre_ccta_presupuestal');
    }

    public function childrenBudgetAccounts()
    {
        return $this->hasMany('App\Models\CuentaPresupuestal', 'id_padre_ccta_presupuestal', 'id_ccta_presupuestal');
    }

}
