<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Yajra\DataTables\Html\Editor\Fields\BelongsTo;

class Quedan extends Model
{
    use HasFactory;
    protected $table = 'quedan';
    protected $primaryKey = 'id_quedan';
    public $timestamps = false;
    protected $fillable = [
        'id_estado_quedan',
        'id_prioridad_pago',
        'id_requerimiento_pago',
        'id_empleado_tesoreria',
        'id_proy_financiado',
        'id_acuerdo_compra',
        'id_serie_retencion_iva',
        'id_tipo_doc_adquisicion',
        'id_proveedor',
        'numero_quedan',
        'numero_retencion_iva_quedan',
        'numero_compromiso_ppto_quedan',
        'fecha_emision_quedan',
        'fecha_retencion_iva_quedan',
        'fecha_pago_quedan',
        'monto_total_quedan',
        'monto_liquido_quedan',
        'monto_iva_quedan',
        'monto_isr_quedan',
        'descripcion_quedan',
        'estado_quedan',
        'fecha_reg_quedan',
        'fecha_mod_quedan',
        'usuario_quedan',
        'ip_quedan',
    ];


    public function detalle_quedan()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->hasMany(DetalleQuedan::class, "id_quedan", "id_quedan");
    }
    public function proveedor()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->belongsTo(Proveedor::class, "id_proveedor", "id_proveedor");
    }
    public function tipo_documento_adquisicion()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->belongsTo(TipoDocumentoAdquisicion::class, "id_tipo_doc_adquisicion", "id_tipo_doc_adquisicion");
    }
    public function tesorero()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->belongsTo(EmpleadoTesoreria::class, "id_empleado_tesoreria", "id_empleado_tesoreria");
    }

    public function requerimiento_pago()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->belongsTo(RequerimientoPago::class, "id_requerimiento_pago", "id_requerimiento_pago");
    }
    public function acuerdo_compra()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->belongsTo(AcuerdoCompra::class, "id_acuerdo_compra", "id_acuerdo_compra");
    }
    public function proyecto_financiado()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->belongsTo(ProyectoFinanciado::class, "id_proy_financiado", "id_proy_financiado");
    }
    /**
     * Get all of the liquidacion_quedan for the Quedan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function liquidacion_quedan():HasMany
    {
        return $this->hasMany(LiquidacionQuedan::class, 'id_quedan', 'id_quedan');
    }

    public function prioridad_pago()
    {
        return $this->belongsTo(PrioridadPago::class, 'id_prioridad_pago', 'id_prioridad_pago');
    }

    /**
     * Get the serie_retencion_iva that owns the Quedan
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function serie_retencion_iva(): hasOne
    {
        return $this->hasOne(SerieRetencionIva::class, 'id_serie_retencion_iva', 'id_serie_retencion_iva');
    }

    
}
