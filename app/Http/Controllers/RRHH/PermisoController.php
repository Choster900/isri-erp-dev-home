<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Permiso;
use App\Models\PlazaAsignada;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermisoController extends Controller
{
    public function getJobPermissions(Request $request)
    {
        $columns = [
            'id_permiso',
            'id_empleado',
            'id_motivo_permiso',
            'id_tipo_permiso',
            'fecha_inicio_permiso',
            'estado_permiso'
        ];

        $user = User::find($request->user()->id_usuario);
        $id_empleado = $user->persona->empleado->id_empleado;

        $arrayIdDependencias = [];
        foreach ($user->persona->empleado->plazas_asignadas as $plaza) {
            if ($plaza->estado_plaza_asignada == 1) {
                $arrayIdDependencias[] = $plaza->id_dependencia;
            }
        }

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        //Agregar el if para permits.ejecutar != 0
        $query = Permiso::select('*')
            ->join('plaza_asignada', 'plaza_asignada.id_plaza_asignada', '=', 'permiso.id_plaza_asignada')
            ->whereIn('plaza_asignada.id_dependencia', $arrayIdDependencias)
            ->orderBy($columns[$column], $dir);

        // if ($search_value) {
        //     $query->where('codigo_det_plaza', 'like', '%' . $search_value['codigo_det_plaza'] . '%')
        //         ->where('estado_det_plaza', 'like', '%' . $search_value['estado_det_plaza'] . '%')
        //         ->where('id_estado_plaza', 'like', '%' . $search_value["id_estado_plaza"] . '%')
        //         ->where(function ($query) use ($search_value) {
        //             $query->whereHas('plaza', function ($query) use ($search_value) {
        //                 $query->where('nombre_plaza', 'like', '%' . $search_value["nombre_plaza"] . '%');
        //             });
        //             if ($search_value["codigo_dependencia"]) {
        //                 $query->whereHas('plazas_asignadas.dependencia', function ($query) use ($search_value) {
        //                     $query->where('codigo_dependencia', 'like', '%' . $search_value["codigo_dependencia"] . '%');
        //                 });
        //             }
        //             if ($search_value["nombre_empleado"]) {
        //                 $query->whereHas(
        //                     'plaza_asignada_activa.empleado.persona',
        //                     function ($query) use ($search_value) {
        //                         $query->where('pnombre_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
        //                             ->orWhere('snombre_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
        //                             ->orWhere('tnombre_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
        //                             ->orWhere('papellido_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
        //                             ->orWhere('sapellido_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
        //                             ->orWhere('tapellido_persona', 'like', '%' . $search_value["nombre_empleado"] . '%');
        //                     }
        //                 );
        //             }
        //         });
        // }

        $permissions = $query->paginate($length)->onEachSide(1);
        return ['data' => $permissions, 'draw' => $request->input('draw')];
    }

    public function getDataPermissionModal(Request $request)
    {
        $user = User::find($request->user()->id_usuario);
        $executePermit = $request->ejecutar;
        $id_empleado = $user->persona->empleado->id_empleado;

        $arrayIdDependencias = [];
        foreach ($user->persona->empleado->plazas_asignadas as $plaza) {
            if ($plaza->estado_plaza_asignada == 1) {
                $arrayIdDependencias[] = $plaza->id_dependencia;
            }
        }
        if ($executePermit != 0) {
            $empleados = DB::table('empleado as e')
                ->selectRaw('CONCAT(e.codigo_empleado, "-",CONCAT_WS(" ", p.pnombre_persona, p.snombre_persona, p.tnombre_persona,
                p.papellido_persona, p.sapellido_persona, p.tapellido_persona)) as label,
                 e.id_empleado as value,
                 GROUP_CONCAT(pa.id_dependencia) as dependencias')
                ->join('persona as p', 'e.id_persona', '=', 'p.id_persona')
                ->join('plaza_asignada as pa', 'e.id_empleado', '=', 'pa.id_empleado')
                ->where('pa.estado_plaza_asignada', 1)
                ->whereIn('pa.id_dependencia', $arrayIdDependencias)
                ->groupBy(
                    'e.id_empleado',
                    'e.codigo_empleado',
                    'p.pnombre_persona',
                    'p.snombre_persona',
                    'p.tnombre_persona',
                    'p.papellido_persona',
                    'p.sapellido_persona',
                    'p.tapellido_persona'
                )
                ->get();
        } else {
            $empleados = DB::table('empleado as e')
                ->selectRaw('CONCAT(e.codigo_empleado, "-",CONCAT_WS(" ", p.pnombre_persona, p.snombre_persona, p.tnombre_persona,
                p.papellido_persona, p.sapellido_persona, p.tapellido_persona)) as label,
                 e.id_empleado as value')
                ->join('persona as p', 'e.id_persona', '=', 'p.id_persona')
                ->join('plaza_asignada as pa', 'e.id_empleado', '=', 'pa.id_empleado')
                ->where('pa.estado_plaza_asignada', 1)
                ->where('e.id_empleado', $id_empleado)
                ->get();
            $permissionData = $this->getPermissionData($id_empleado);
        }

        return response()->json([
            'typesOfPermissions'          => $executePermit == 0 ? $permissionData['typesOfPermissions'] : [],
            'jobPositions'                => $executePermit == 0 ? $permissionData['jobPositions'] : [],
            'employees'                   => $empleados ?? []
        ]);
    }

    public function getPermissionData($id_empleado)
    {
        $typesOfPermissions = DB::table('tipo_permiso as tpe')
            ->leftJoin(
                DB::raw('
                    (
                        SELECT
                            tp.id_tipo_permiso,
                            tp.nombre_tipo_permiso,
                            (tp.tiempo_max_tipo_permiso * 60 * 60) as limite_permiso,
                            SUM(
                                CASE
                                    WHEN p.fecha_inicio_permiso IS NOT NULL AND p.fecha_fin_permiso IS NOT NULL THEN 
                                        (DATEDIFF(p.fecha_fin_permiso, p.fecha_inicio_permiso) + 1) * 8 * 60 * 60
                                    ELSE
                                        TIME_TO_SEC(TIMEDIFF(p.hora_salida_permiso, p.hora_entrada_permiso))
                                END
                            ) AS total_permiso_acumulado
                        FROM permiso p
                        INNER JOIN tipo_permiso tp ON p.id_tipo_permiso = tp.id_tipo_permiso
                        WHERE p.id_empleado = ?
                        AND YEAR(p.fecha_reg_permiso) = YEAR(CURDATE())
                        GROUP BY tp.id_tipo_permiso, tp.nombre_tipo_permiso, tp.tiempo_max_tipo_permiso
                    ) AS t'),
                'tpe.id_tipo_permiso',
                '=',
                't.id_tipo_permiso'
            )
            ->select(
                'tpe.id_tipo_permiso as value',
                'tpe.nombre_tipo_permiso as label',
                DB::raw('SEC_TO_TIME(tpe.tiempo_max_tipo_permiso * 60 * 60) as limite_permiso'),
                DB::raw('SEC_TO_TIME(t.total_permiso_acumulado) as total_acumulado'),
                DB::raw('SEC_TO_TIME((tpe.tiempo_max_tipo_permiso * 60 * 60) - IFNULL(t.total_permiso_acumulado,0)) as restante')
            )
            ->addBinding($id_empleado)
            ->get();


        $jobPositions = PlazaAsignada::selectRaw('plaza_asignada.id_plaza_asignada as value, plaza.nombre_plaza as label')
            ->join('detalle_plaza', 'plaza_asignada.id_det_plaza', '=', 'detalle_plaza.id_det_plaza')
            ->join('plaza', 'detalle_plaza.id_plaza', '=', 'plaza.id_plaza')
            ->where('plaza_asignada.id_empleado', $id_empleado)
            ->get();

        return [
            'typesOfPermissions' => $typesOfPermissions ?? [],
            'jobPositions' => $jobPositions ?? [],
        ];
    }
}
