<?php

namespace App\Http\Requests\RRHH;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EvaluacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'idEmpleado'              => 'required',
            'idCentroAtencion'        => 'required',
            'fechaInicioFechafin' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Obtener el valor de idTipoEvaluacion del request
                    $idTipoEvaluacion = $this->input('idTipoEvaluacion');

                    // Dividir el rango de fechas en dos valores individuales
                    [$fecha_inicio, $fecha_fin] = explode(" a ", $value) + [null, null];

                    // Intentar convertir las partes del rango a objetos Carbon
                    $fechaInicioObj = Carbon::parse($fecha_inicio);
                    $fechaFinObj = Carbon::parse($fecha_fin);
                    $anio = $fechaInicioObj->year;

                    // Verificar si la conversión fue exitosa
                    if (!$fechaInicioObj || !$fechaFinObj) {
                        $fail('El formato del rango de fechas no es válido.');
                        return;
                    }

                    // Definir fechas límite según el valor de idTipoEvaluacion
                    if ($idTipoEvaluacion == 1) {
                        // Caso en que idTipoEvaluacion es igual a 1
                        $limitePrimerPeriodo = Carbon::parse("{$anio}-06-30");
                        $limiteSegundoPeriodo = Carbon::parse("{$anio}-12-31");
                    } else {
                        // Caso en que idTipoEvaluacion no es igual a 1
                        $limitePrimerPeriodo = $fechaInicioObj->copy();
                        $limiteSegundoPeriodo = $fechaInicioObj->copy()->addMonths(3);
                    }

                    // Validar el rango de fechas
                    if (
                        !($fechaInicioObj->lte($limitePrimerPeriodo) && $fechaFinObj->lte($limitePrimerPeriodo)) &&
                        !($fechaInicioObj->gte($limitePrimerPeriodo) && $fechaFinObj->lte($limiteSegundoPeriodo))
                    ) {
                        // Mensaje de error personalizado para cada caso
                        if ($idTipoEvaluacion == 1) {
                            $fail('Las evaluaciones de desempeño deben estar dentro del primer periodo (del 1 de enero al 30 de junio) o del segundo periodo (del 1 de julio al 31 de diciembre).');
                        } else {
                            $fail('Las evaluaciones de periodo de prueba deben tener un rango máximo de 3 meses.');
                        }
                    }
                },
            ],


            'idEvaluacionRendimiento' => 'required',
            'idTipoEvaluacion'        => 'required',
            'plazasAsignadas'         => 'required',
        ];
    }

    public function messages()
    {
        return [
            'idEmpleado.required'              => 'Por favor, seleccione el empleado.',
            'idCentroAtencion.required'        => 'Por favor, seleccione un centro de atención.',
            'fechaInicioFechafin.required'     => 'El periodo de evaluación es obligatorio.',
            'idEvaluacionRendimiento.required' => 'Por favor, seleccione una evaluación de rendimiento.',
            'idTipoEvaluacion.required'        => 'El tipo de evaluación es obligatorio.',
            'plazasAsignadas.required'         => 'Por favor, seleccione una o más plazas para enviar la información.',
        ];
    }
}
