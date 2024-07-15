<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Http\Requests\UCP\BienesServiciosRequest;
use App\Models\CentroAtencion;
use App\Models\DetDocumentoAdquisicion;
use App\Models\DocumentoAdquisicion;
use App\Models\LineaTrabajo;
use App\Models\Marca;
use App\Models\ProductoAdquisicion;
use App\Models\Proveedor;
use App\Models\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BienesServiciosController extends Controller
{

    public function getProductoAdquisicionByDocumentoAdquisicion(Request $request)
    {
        $v_columns = [
            'id_doc_adquisicion',
            'id_prod_adquisicion',
            'id_producto',
            'id_det_doc_adquisicion',
            'id_marca',
            'id_lt',
            'id_centro_atencion',
            'cant_prod_adquisicion',
            'costo_prod_adquisicion',
            'descripcion_prod_adquisicion',
            'estado_prod_adquisicion',
            'fecha_reg_prod_adquisicion',
            'fecha_mod_prod_adquisicion',
            'usuario_prod_adquisicion',
            'ip_prod_adquisicion',

        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');
        $docType = $request->input('docType');

        $v_query = DetDocumentoAdquisicion::with([
            "estado_documento_adquisicion",
            "productos_adquisiciones" => function ($query) {
                return $query->where("estado_prod_adquisicion", 1);
            },
            "productos_adquisiciones.producto.unidad_medida",
            "productos_adquisiciones.linea_trabajo",
            "productos_adquisiciones.centro_atencion",
            "productos_adquisiciones.marca",
            "documento_adquisicion.proveedor",
            "documento_adquisicion.tipo_documento_adquisicion",
            "documento_adquisicion.tipo_gestion_compra",
        ])
            ->whereHas('documento_adquisicion', function ($query) use ($docType) {

                if ($docType == "contrato") {
                    # code...
                    $query->where('id_tipo_doc_adquisicion', 1);
                } else if ($docType == "orden de compra") {
                    # code...
                    $query->where('id_tipo_doc_adquisicion', 2);
                }
            })
            ->has('productos_adquisiciones')->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {

            $v_query->whereHas('documento_adquisicion.proveedor', function ($query) use ($data) {
                $query->where('razon_social_proveedor', 'like', '%' . $data["razon_social_proveedor"] . '%');
            });
            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('id_tipo_doc_adquisicion', 'like', '%' . $data["id_tipo_doc_adquisicion"] . '%');
            });
            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('id_tipo_gestion_compra', 'like', '%' . $data["id_tipo_gestion_compra"] . '%');
            });

            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('numero_doc_adquisicion', 'like', '%' . $data["id_tipo_gestion_compra"] . '%');
            });
            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('numero_doc_adquisicion', 'like', '%' . $data["id_tipo_gestion_compra"] . '%');
            });

            $v_query->where('monto_det_doc_adquisicion', 'like', '%' . $data["monto_det_doc_adquisicion"] . '%');

            $v_query->whereHas('estado_documento_adquisicion', function ($query) use ($data) {
                $query->where('id_estado_doc_adquisicion', 'like', '%' . $data["id_estado_doc_adquisicion"] . '%');
            });
        }

        $data = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $data,
            'draw' => $request->input('draw'),
        ];
    }

    public function saveProductoAdquisicion(BienesServiciosRequest $request): object
    {

        /*  return $request; */
        try {
            DB::beginTransaction();
            $detalles = $request->productAdq;
            $idDetDocAdquisicion = $request->idDetDocAdquisicion;
            $notificacionDetDocAdquisicion = $request->notificacionDetDocAdquisicion;
            $recepcionDetDocAdquisicion = $request->recepcionDetDocAdquisicion;
            $observacionDetDocAdquisicion = $request->observacionDetDocAdquisicion;
            $usuario = $request->user()->nick_usuario;
            $ip = $request->ip();
            $fechaActual = now();
            $totCostoProdAdquisicion = 0; // Variable para almacenar la el total

            foreach ( $detalles as $detalle ) {
                if ($detalle["estadoLt"] != 0) {
                    foreach ( $detalle["detalleDoc"] as $detalleProducto ) {
                        if ($detalleProducto["estadoProdAdquisicion"] == 1) {
                            $nuevoDetalle = [
                                'id_producto'                  => $detalleProducto["idProducto"],
                                'id_det_doc_adquisicion'       => $idDetDocAdquisicion,
                                'id_marca'                     => $detalleProducto["idMarca"],
                                'id_lt'                        => $detalle["idLt"],
                                'id_centro_atencion'           => $detalleProducto["idCentroAtencion"],
                                'cant_prod_adquisicion'        => $detalleProducto["cantProdAdquisicion"],
                                'costo_prod_adquisicion'       => $detalleProducto["costoProdAdquisicion"],
                                'descripcion_prod_adquisicion' => $detalleProducto["descripcionProdAdquisicion"],
                                'estado_prod_adquisicion'      => 1,
                                'fecha_reg_prod_adquisicion'   => $fechaActual,
                                'usuario_prod_adquisicion'     => $usuario,
                                'ip_prod_adquisicion'          => $ip,


                                'cant_ene_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["January"]) ? $detalleProducto["amountsPerMonthList"]["January"] : 0,
                                'cant_feb_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["February"]) ? $detalleProducto["amountsPerMonthList"]["February"] : 0,
                                'cant_mar_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["March"]) ? $detalleProducto["amountsPerMonthList"]["March"] : 0,
                                'cant_abr_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["April"]) ? $detalleProducto["amountsPerMonthList"]["April"] : 0,
                                'cant_may_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["May"]) ? $detalleProducto["amountsPerMonthList"]["May"] : 0,
                                'cant_jun_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["June"]) ? $detalleProducto["amountsPerMonthList"]["June"] : 0,
                                'cant_jul_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["July"]) ? $detalleProducto["amountsPerMonthList"]["July"] : 0,
                                'cant_ago_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["August"]) ? $detalleProducto["amountsPerMonthList"]["August"] : 0,
                                'cant_sept_prod_adquisicion'   => isset($detalleProducto["amountsPerMonthList"]["September"]) ? $detalleProducto["amountsPerMonthList"]["September"] : 0,
                                'cant_oct_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["October"]) ? $detalleProducto["amountsPerMonthList"]["October"] : 0,
                                'cant_nov_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["November"]) ? $detalleProducto["amountsPerMonthList"]["November"] : 0,
                                'cant_dic_prod_adquisicion'    => isset($detalleProducto["amountsPerMonthList"]["December"]) ? $detalleProducto["amountsPerMonthList"]["December"] : 0,
                            ];
                            // Sumando el total
                            $totCostoProdAdquisicion += $detalleProducto["valorTotalProduct"];

                            // Usar DB::insert para insertar directamente y mejorar el rendimiento
                            DB::table('producto_adquisicion')->insert($nuevoDetalle);
                        }
                    }
                }
            }
            DetDocumentoAdquisicion::where("id_det_doc_adquisicion", $idDetDocAdquisicion)->update([
                "observacion_det_doc_adquisicion"  => $observacionDetDocAdquisicion,
                "recepcion_det_doc_adquisicion"    => $recepcionDetDocAdquisicion,
                "notificacion_det_doc_adquisicion" => $notificacionDetDocAdquisicion,
            ]);

            DB::commit();
            return response()->json($request); // O cualquier otra respuesta que desees enviar
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function updateProductoAdquisicion(BienesServiciosRequest $request)
    {
        try {
            $detalles = $request->productAdq;
            $idDetDocAdquisicion = $request->idDetDocAdquisicion;
            $notificacionDetDocAdquisicion = $request->notificacionDetDocAdquisicion;
            $recepcionDetDocAdquisicion = $request->recepcionDetDocAdquisicion;
            $observacionDetDocAdquisicion = $request->observacionDetDocAdquisicion;
            $usuario = $request->user()->nick_usuario;
            $ip = $request->ip();
            $fechaActual = now();
            $totCostoProdAdquisicion = 0; // Variable para almacenar la el total

            foreach ( $detalles as $detalle ) {
                // Verificar si la linea de trabajo del producto adquisicion no ha sido eliminada en el front
                if ($detalle["estadoLt"] != 0) {
                    foreach ( $detalle["detalleDoc"] as $detalleProducto ) {
                        switch ($detalleProducto["estadoProdAdquisicion"]) {
                            // Actualizar producto existente
                            case 2:
                                ProductoAdquisicion::where([
                                    'id_prod_adquisicion' => $detalleProducto["idProdAdquisicion"],
                                    'id_lt'               => $detalle["idLt"],
                                ])->update([
                                            'id_producto'                  => $detalleProducto["idProducto"],
                                            'id_det_doc_adquisicion'       => $idDetDocAdquisicion,
                                            'id_marca'                     => $detalleProducto["idMarca"],
                                            'id_lt'                        => $detalle["idLt"],
                                            'id_centro_atencion'           => $detalleProducto["idCentroAtencion"],
                                            'cant_prod_adquisicion'        => $detalleProducto["cantProdAdquisicion"],
                                            'costo_prod_adquisicion'       => $detalleProducto["costoProdAdquisicion"],
                                            'descripcion_prod_adquisicion' => $detalleProducto["descripcionProdAdquisicion"],
                                            'estado_prod_adquisicion'      => 1,
                                            'fecha_reg_prod_adquisicion'   => $fechaActual,
                                            'usuario_prod_adquisicion'     => $usuario,
                                            'ip_prod_adquisicion'          => $ip,
                                            'cant_ene_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["January"] ?? 0,
                                            'cant_feb_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["February"] ?? 0,
                                            'cant_mar_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["March"] ?? 0,
                                            'cant_abr_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["April"] ?? 0,
                                            'cant_may_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["May"] ?? 0,
                                            'cant_jun_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["June"] ?? 0,
                                            'cant_jul_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["July"] ?? 0,
                                            'cant_ago_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["August"] ?? 0,
                                            'cant_sept_prod_adquisicion'   => $detalleProducto["amountsPerMonthList"]["September"] ?? 0,
                                            'cant_oct_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["October"] ?? 0,
                                            'cant_nov_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["November"] ?? 0,
                                            'cant_dic_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["December"] ?? 0,
                                        ]);

                                // Sumando el total
                                $totCostoProdAdquisicion += $detalleProducto["valorTotalProduct"];
                                break;

                            // Insertar nuevo producto
                            case 1:
                                $nuevoDetalle = [
                                    'id_producto'                  => $detalleProducto["idProducto"],
                                    'id_det_doc_adquisicion'       => $idDetDocAdquisicion,
                                    'id_marca'                     => $detalleProducto["idMarca"],
                                    'id_lt'                        => $detalle["idLt"],
                                    'id_centro_atencion'           => $detalleProducto["idCentroAtencion"],
                                    'cant_prod_adquisicion'        => $detalleProducto["cantProdAdquisicion"],
                                    'costo_prod_adquisicion'       => $detalleProducto["costoProdAdquisicion"],
                                    'descripcion_prod_adquisicion' => $detalleProducto["descripcionProdAdquisicion"],
                                    'estado_prod_adquisicion'      => 1,
                                    'fecha_reg_prod_adquisicion'   => $fechaActual,
                                    'usuario_prod_adquisicion'     => $usuario,
                                    'ip_prod_adquisicion'          => $ip,
                                    'cant_ene_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["January"] ?? 0,
                                    'cant_feb_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["February"] ?? 0,
                                    'cant_mar_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["March"] ?? 0,
                                    'cant_abr_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["April"] ?? 0,
                                    'cant_may_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["May"] ?? 0,
                                    'cant_jun_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["June"] ?? 0,
                                    'cant_jul_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["July"] ?? 0,
                                    'cant_ago_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["August"] ?? 0,
                                    'cant_sept_prod_adquisicion'   => $detalleProducto["amountsPerMonthList"]["September"] ?? 0,
                                    'cant_oct_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["October"] ?? 0,
                                    'cant_nov_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["November"] ?? 0,
                                    'cant_dic_prod_adquisicion'    => $detalleProducto["amountsPerMonthList"]["December"] ?? 0,
                                ];

                                // Sumando el total
                                $totCostoProdAdquisicion += $detalleProducto["valorTotalProduct"];
                                // Usar DB::insert para insertar directamente y mejorar el rendimiento
                                DB::table('producto_adquisicion')->insert($nuevoDetalle);
                                break;

                            // Desactivar producto
                            default:
                                ProductoAdquisicion::where([
                                    'id_prod_adquisicion' => $detalleProducto["idProdAdquisicion"],
                                    'id_lt'               => $detalle["idLt"],
                                ])->update([
                                            'estado_prod_adquisicion' => 0,
                                        ]);
                                break;
                        }
                    }
                }


                // Desactivar los productos adquisiciones por linea de trabajo
                else {
                    foreach ( $detalle["detalleDoc"] as $detalleProducto ) {
                        ProductoAdquisicion::where([
                            'id_prod_adquisicion' => $detalleProducto["idProdAdquisicion"],
                            'id_lt'               => $detalle["idLt"],
                        ])->update([
                                    'estado_prod_adquisicion' => 0,
                                ]);
                    }
                }
            }
            DetDocumentoAdquisicion::where("id_det_doc_adquisicion", $idDetDocAdquisicion)->update([
                "observacion_det_doc_adquisicion"  => $observacionDetDocAdquisicion,
                "recepcion_det_doc_adquisicion"    => $recepcionDetDocAdquisicion,
                "notificacion_det_doc_adquisicion" => $notificacionDetDocAdquisicion,
            ]);

            return response()->json(["message" => "Actualización exitosa"]);
        } catch (\Exception $e) {
            // Manejar excepciones y retornar un mensaje de error
            return response()->json(["error" => "Error en la actualización: " . $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene arreglo de dinstintos objetos para el uso en multiselect.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Request
     */
    function getArrayObjectoForMultiSelect(Request $request): array
    {
        // Obtener detalles de documentos de adquisición con información adicional

        $query = DetDocumentoAdquisicion::with([
            "documento_adquisicion.proveedor",
            "documento_adquisicion.proceso_compra"
        ])
            ->where("estado_det_doc_adquisicion", 1)
            ->where("visible_ucp_det_doc_adquisicion", 1)
            ->whereDoesntHave("productos_adquisiciones");

        // Si el tipo de documento es "contrato", añadir una condición adicional a la consulta
        if ($request->tipoDoc == "contrato") {
            $query->whereHas('documento_adquisicion', function ($query) {
                $query->where('id_tipo_doc_adquisicion', 1);
            });
        } else if ($request->tipoDoc == "orden de compra") {
            $query->whereHas('documento_adquisicion', function ($query) {
                $query->where('id_tipo_doc_adquisicion', 2);
            });
        }

        $detalleDocumentoAdquisicion = $query->get();

        // Obtener todas las unidades de medida
        $unidadesMedida = UnidadMedida::all();

        // Obtener todas las líneas de trabajo
        $lineaTrabajo = LineaTrabajo::all();

        // Obtener todos los centros de atención
        $centrosAtencion = CentroAtencion::all();

        // Obtener marca
        $marca = Marca::all();

        // Devolver un arreglo asociativo con los resultados
        return [
            "detalleDocAdquisicion" => $detalleDocumentoAdquisicion,
            "lineaTrabajo"          => $lineaTrabajo,
            "unidadesMedida"        => $unidadesMedida,
            "centrosAtencion"       => $centrosAtencion,
            "marca"                 => $marca,
        ];
    }

    function changeStateProductoAdquisicion(Request $request)
    {
        // Calcular el total de los productos
        $productoAdquisicionTotal = ProductoAdquisicion::where("id_det_doc_adquisicion", $request->id)
            ->select(DB::raw('SUM(cant_prod_adquisicion * costo_prod_adquisicion) as total'))
            ->value('total');

        // Obtener el detalle del documento de adquisición
        $detalleDoc = DetDocumentoAdquisicion::find($request->id);

        // Comparar los totales
        if ($productoAdquisicionTotal > $detalleDoc->monto_det_doc_adquisicion) {
            // Devolver error 422 si el total de los productos es mayor que el monto del detalle
            return response()->json([
                'error' => 'El total de los productos es mayor que el monto del detalle del documento de adquisición.'
            ], 422);
        }

        // Si la validación pasa, actualizar el estado del documento
        DetDocumentoAdquisicion::where('id_det_doc_adquisicion', $request->id)->update([
            'id_estado_doc_adquisicion' => $request->idState,
        ]);

        // Devolver una respuesta exitosa
        return response()->json([
            'message' => 'Estado del documento de adquisición actualizado correctamente.',
        ]);
    }

    function exportDocumentToExcel(Request $request)
    {
        // Crear una instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezado principal
        $sheet->mergeCells('A1:U1');
        $sheet->setCellValue('A1', 'ADENDA CONTRATO 30176');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setName('Cambria')->setSize(10)->getColor()->setARGB(Color::COLOR_WHITE);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1')->getFill()->getStartColor()->setARGB('244062'); // Azul, énfasis 1, oscuro 50%

        $sheet->mergeCells('A2:U2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setName('Cambria')->setSize(10)->getColor()->setARGB(Color::COLOR_WHITE);
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A2')->getFill()->getStartColor()->setARGB('244062'); // Azul, énfasis 1, oscuro 50%


        // Obtener el número de documento de adquisición
        $docAdquisicion = collect($request->arrayDocAdquisicion)->firstWhere('value', $request->idDetDocAdquisicion);
        $numeroDocAdquisicion = $docAdquisicion['dataDoc']['documento_adquisicion']['numero_doc_adquisicion'] ?? '-';

        $sheet->setCellValue('C4', ':' . $numeroDocAdquisicion);
        $sheet->getStyle('C4')->getFont()->setName('Cambria')->setSize(10);
        $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Obtener el valor correspondiente a idDetDocAdquisicion
        $docAdquisicion = collect($request->arrayDocAdquisicion)->firstWhere('value', $request->idDetDocAdquisicion);
        $docAdquisicionLabel = $docAdquisicion ? $docAdquisicion['label'] : '';

        $sheet->mergeCells('C5:U5');
        $sheet->setCellValue('C5', ':' . $docAdquisicionLabel);
        $sheet->getStyle('C5')->getFont()->setName('Cambria')->setSize(10);
        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->mergeCells('C6:U6');
        $sheet->setCellValue('C6', ':INSTITUTO SALVADOREÑO DE REHABILITACIÓN INTEGRAL');
        $sheet->getStyle('C6')->getFont()->setName('Cambria')->setSize(10);
        $sheet->getStyle('C6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Encabezados
        $encabezados = [
            'PRODUCTO',
            'MARCA',
            'DESCRIPCIÓN',
            'CENTRO',
            'CANTIDAD',
            'PRECIO UNITARIO',
            'VALOR TOTAL',
            'DISTRIBUCIÓN MENSUAL',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];

        $sheet->fromArray([$encabezados], null, 'A8');

        // Sub-encabezados para los meses
        $meses = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            'ENERO',
            'FEBRERO',
            'MARZO',
            'ABRIL',
            'MAYO',
            'JUNIO',
            'JULIO',
            'AGOSTO',
            'SEPTIEMBRE',
            'OCTUBRE',
            'NOVIEMBRE',
            'DICIEMBRE'
        ];

        $sheet->fromArray([$meses], null, 'A9');

        // Combinar celdas para "DISTRIBUCIÓN MENSUAL"
        $sheet->mergeCells('H8:S8');
        $sheet->getStyle('H8:S8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H8:S8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Aplicar estilo a los encabezados
        $sheet->getStyle('A8:G9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A8:G9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A8:G8')->getFont()->setBold(true);
        $sheet->getStyle('H8:S8')->getFont()->setBold(true);
        $sheet->getStyle('H9:S9')->getFont()->setBold(true);

        $sheet->getColumnDimension('A')->setWidth(31);
        $sheet->getColumnDimension('B')->setWidth(11);
        $sheet->getColumnDimension('C')->setWidth(60);



        // Establecer estilo para encabezados
        $styleHeader = ['font' => ['bold' => true, 'size' => 9]];
        $sheet->getStyle('A8:J8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        foreach ( range('A', 'J') as $column ) {
            $sheet->getStyle($column . '8')->applyFromArray($styleHeader);
            $sheet->getStyle($column . '8')->getAlignment()->setWrapText(true);
        }


        // Combinar celdas de la columna A a la G para las filas 8 y 9
        foreach ( range('A', 'G') as $column ) {
            $sheet->mergeCells($column . '8:' . $column . '9');
        }

        // Aplicar fuente "Calibri Light" y tamaño 10 a las filas 8 y 9
        foreach ( range('A', 'S') as $column ) {
            $sheet->getStyle($column . '8:' . $column . '9')->getFont()->setName('Calibri Light')->setSize(10);
            $sheet->getStyle($column . '8')->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle($column . '8')->getFill()->getStartColor()->setARGB('D9E1F2'); // Azul, énfasis 1, oscuro 50%

            $sheet->getStyle($column . '9')->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle($column . '9')->getFill()->getStartColor()->setARGB('D9E1F2'); // Azul, énfasis 1, oscuro 50%
        }

        $row = 10; // Comenzar desde la fila 10 para los datos

        foreach ( $request->arrayProductoAdquisicion as $data ) {
            foreach ( $data["detalleDoc"] as $value ) {
                $sheet->setCellValue('A' . $row, $value["detalleProducto"]);

                // Encontrar la marca correspondiente
                $marca = collect($request->arrayMarca)->firstWhere('value', $value["idMarca"]);
                $marcaLabel = $marca ? $marca['label'] : '';
                $sheet->setCellValue('B' . $row, $marcaLabel);

                $sheet->setCellValue('C' . $row, $value["descripcionProdAdquisicion"]);

                // Encontrar el centro correspondiente
                $centro = collect($request->arrayCentroAtencion)->firstWhere('value', $value["idCentroAtencion"]);
                $centroLabel = $centro ? $centro['label'] : '';
                $sheet->setCellValue('D' . $row, $centroLabel);

                $sheet->setCellValue('E' . $row, $value["cantProdAdquisicion"]);
                $sheet->setCellValue('F' . $row, $value["costoProdAdquisicion"]);
                $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');

                $sheet->setCellValue('G' . $row, $value["valorTotalProduct"]);
                $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');

                // Acceder a los meses
                $sheet->setCellValue('H' . $row, $value["amountsPerMonthList"]["January"]);
                $sheet->setCellValue('I' . $row, $value["amountsPerMonthList"]["February"]);
                $sheet->setCellValue('J' . $row, $value["amountsPerMonthList"]["March"]);
                $sheet->setCellValue('K' . $row, $value["amountsPerMonthList"]["April"]);
                $sheet->setCellValue('L' . $row, $value["amountsPerMonthList"]["May"]);
                $sheet->setCellValue('M' . $row, $value["amountsPerMonthList"]["June"]);
                $sheet->setCellValue('N' . $row, $value["amountsPerMonthList"]["July"]);
                $sheet->setCellValue('O' . $row, $value["amountsPerMonthList"]["August"]);
                $sheet->setCellValue('P' . $row, $value["amountsPerMonthList"]["September"]);
                $sheet->setCellValue('Q' . $row, $value["amountsPerMonthList"]["October"]);
                $sheet->setCellValue('R' . $row, $value["amountsPerMonthList"]["November"]);
                $sheet->setCellValue('S' . $row, $value["amountsPerMonthList"]["December"]);

                foreach ( range('A', 'S') as $column ) {
                    $sheet->getStyle($column . $row)->getAlignment()->setWrapText(true)->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                }

                $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setName('Calibri')->setSize(9);

                $row++;
            }
        }

        // Aplicar bordes a toda la tabla desde los encabezados hasta el contenido
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A8:S' . ($row - 1))->applyFromArray($styleArray);

        // Guardar el archivo como XLSX
        $writer = new Xlsx($spreadsheet);

        // Establecer el nombre del archivo
        $current_date = Carbon::now()->format('d_m_Y');
        $filename = 'texto_excel_' . $current_date . '.xlsx';

        // Establecer las cabeceras para descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Guardar el archivo en la salida PHP
        $writer->save('php://output');
    }
}
