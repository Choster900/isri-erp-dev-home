<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use Carbon\Carbon;
use App\Http\Requests\Tesoreria\SupplierRequest;


class ProveedorController extends Controller
{
    public function getProveedores(Request $request)
    {
        $v_columns = [
            'id_proveedor',
            'dui_proveedor',
            'nrc_proveedor',
            'nit_proveedor',
            'id_tipo_contribuyente',
            'id_sujeto_retencion',
            'razon_social_proveedor',
            'estado_proveedor',
            'nombre_comercial_proveedor',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');
        $v_query = Proveedor::select('*')
            ->join('tipo_contribuyente', 'proveedor.id_tipo_contribuyente', '=', 'tipo_contribuyente.id_tipo_contribuyente')
            ->with('sujeto_retencion')
            ->select('*')
            ->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {
            $v_query->where('id_proveedor', 'like', '%' . $data["id_proveedor"] . '%')
                ->where('dui_proveedor', 'like', '%' . $data["dui_proveedor"] . '%')
                ->where('razon_social_proveedor', 'like', '%' . $data["razon_social_proveedor"] . '%')
                ->where('nombre_comercial_proveedor', 'like', '%' . $data["nombre_comercial_proveedor"] . '%')
                ->where('estado_proveedor', 'like', '%' . $data["estado_proveedor"] . '%');
        }

        $v_roles = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_roles,
            'draw' => $request->input('draw'),
        ];
    }

    public function getInformationToSelect(Request $request)
    { //Retorna un arreglo de diferentes tablas para llenar los select del modal
        $v_typeContribuyent = DB::table('tipo_contribuyente')
            ->select(
                'id_tipo_contribuyente as value',
                'nombre_tipo_contribuyente as label'
            )->get();
        $v_giro = DB::table('giro')
            ->select(
                'id_giro as value',
                DB::raw("CONCAT(codigo_giro,' - ',nombre_giro) AS label")
            )->get();
        $v_retention = DB::table('sujeto_retencion')
            ->select(
                'id_sujeto_retencion as value',
                'nombre_sujeto_retencion as label'
            )->get();

        $v_location = DB::table("departamento")
            ->select('municipio.id_municipio as value', DB::raw("CONCAT(pais.id_pais,' - ',departamento.nombre_departamento,' - ',municipio.nombre_municipio ) AS label"))
            ->join('municipio', 'departamento.id_departamento', '=', 'municipio.id_departamento')
            ->join('pais', 'departamento.id_pais', '=', 'pais.id_pais')
            ->get();

        return [
            "location"         => $v_location,
            "typeContribuyent" => $v_typeContribuyent,
            "retention"        => $v_retention,
            "giro"             => $v_giro,
        ];
    }

    public function changeValueRetencion($id_sujeto_retencion)
    {
        return DB::table('sujeto_retencion')->where("id_sujeto_retencion", $id_sujeto_retencion)->get();
    }

    public function updateDataSupplier(SupplierRequest $request)
    {
        try {
            DB::beginTransaction();
            $v_supplie = Proveedor::where("id_proveedor", $request->input("id_proveedor"))->update([
                'razon_social_proveedor'     => $request->input("razon_social_proveedor"),
                'nombre_comercial_proveedor' => $request->input("nombre_comercial_proveedor"),
                'nrc_proveedor'              => $request->input("nrc_proveedor"),
                'dui_proveedor'              => $request->input("dui_proveedor"),
                'nit_proveedor'              => $request->input("nit_proveedor"),
                'id_tipo_contribuyente'      => $request->input("id_tipo_contribuyente"),
                'id_sujeto_retencion'        => $request->input("id_sujeto_retencion"),
                'telefono1_proveedor'        => $request->input("telefono1_proveedor"),
                'telefono2_proveedor'        => $request->input("telefono2_proveedor"),
                'contacto_proveedor'         => $request->input("contacto_proveedor"),
                'id_municipio'               => $request->input("id_municipio"),
                'direccion_proveedor'        => $request->input("direccion_proveedor"),
                'id_giro'                    => $request->input("id_giro"),
                'email_proveedor'            => $request->input("email"),
            ]);
            DB::commit();
            return $v_supplie;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function addDataSupplier(SupplierRequest $request)
    {
        try {
            DB::beginTransaction();
            $v_supplie = Proveedor::create([
                'razon_social_proveedor'     => $request->input("razon_social_proveedor"),
                'nombre_comercial_proveedor' => $request->input("nombre_comercial_proveedor"),
                'nrc_proveedor'              => $request->input("nrc_proveedor"),
                'dui_proveedor'              => $request->input("dui_proveedor"),
                'nit_proveedor'              => $request->input("nit_proveedor"),
                'id_tipo_contribuyente'      => $request->input("id_tipo_contribuyente"),
                'id_sujeto_retencion'        => $request->input("id_sujeto_retencion"),
                'telefono1_proveedor'        => $request->input("telefono1_proveedor"),
                'telefono2_proveedor'        => $request->input("telefono2_proveedor"),
                'contacto_proveedor'         => $request->input("contacto_proveedor"),
                'id_municipio'               => $request->input("id_municipio"),
                'direccion_proveedor'        => $request->input("direccion_proveedor"),
                'estado_proveedor'           => 1,
                'fecha_reg_proveedor'        => Carbon::now(),
                'id_giro'                    => $request->input("id_giro"),
                'email_proveedor'            => $request->input("email"),
            ]);
            DB::commit();
            return $v_supplie;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
    public function changeStateSupplier(Request $request)
    {
        $v_dataSupplier = Proveedor::find($request->id_proveedor);
        return Proveedor::where("id_proveedor", $request->input("id_proveedor"))->update([
            'estado_proveedor' => $v_dataSupplier["estado_proveedor"] != 1 ? 1 : 0, //si es diferente de 1 ingresamos 1 => Activo de lo contrario 0 =>inactivo
        ]);

    }
}