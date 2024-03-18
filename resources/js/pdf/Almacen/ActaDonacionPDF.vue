<template>
    <div class="py-2 mb-2">
        <!-- Document header -->
        <div class="flex w-full">
            <!-- Columna 1 -->
            <div class="w-[23%] flex justify-center items-center border border-gray-500 p-1">
                <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-full my-auto">
            </div>
            <!-- Columna 2 -->
            <div class="w-[77%] justify-center items-center border-y border-r border-gray-500 pb-[10px]">
                <p class="font-[Bembo] text-center text-[12px] font-bold">ALMACEN CENTRAL</p>
                <p class="font-[Bembo] text-center text-[12px] font-bold">
                    RECEPCION DE BIENES&nbsp;Y SERVICIOS
                </p>
                <p class="font-[Bembo] text-center text-[12px] font-bold">
                    DONACIONES
                </p>
            </div>
        </div>

        <!-- Date and time, financing source and commitment number -->
        <div class="flex w-full border-x border-gray-500 pt-2">
            <div class="w-[50%] flex justify-start items-center text-[12px] mt-[-5px] pb-[8px]">
                <p class="ml-2 font-[MuseoSans] text-gray-800">Fecha y hora:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ moment(recToPrint.fecha_reg_recepcion_pedido).format('DD/MM/YYYY, HH:mm:ss') }}
                </p>
            </div>
            <div class="w-[25%] flex justify-start items-center text-[12px] mt-[-5px] pb-[8px]">
                <p class="font-[MuseoSans] text-gray-800">Financiamiento:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ recToPrint.fuente_financiamiento.codigo_proy_financiado }}
                </p>
            </div>
            <div class="w-[25%] flex justify-start items-center  text-[12px] mt-[-5px] pb-[8px]">
                <p class="font-[MuseoSans] text-gray-800">Compromiso:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ }}
                </p>
            </div>
        </div>
        <!-- Proveedor and NIT -->
        <div class="flex w-full border-x border-gray-500 py-2">
            <div class="w-[75%] flex justify-start items-center  text-[12px] mt-[-5px] pb-[8px]">
                <p class="ml-2 font-[MuseoSans] text-gray-800">Donante:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ recToPrint.proveedor.razon_social_proveedor }}
                </p>
            </div>
            <div class="w-[25%] flex justify-start items-center  text-[12px] mt-[-5px] pb-[8px]">
                <p class="font-[MuseoSans] text-gray-800">NIT:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ recToPrint.proveedor.nit_proveedor }}
                </p>
            </div>
        </div>
        <!-- Third row -->
        <div class="flex bg-gray-800  justify-center items-center font-[MuseoSans] pb-[12px]">
            <p class="font-[MuseoSans] font-bold text-white text-[11px]">ACTA DE RECEPCION DE BIENES {{
                        recToPrint.acta_recepcion_pedido }}</p>
        </div>

        <!-- Table header -->
        <div class="flex w-full border-x border-b border-gray-500" style="page-break-inside: avoid;">
            <div class="w-[12%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">CODIGO</p>
            </div>
            <div class="w-[35%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">PRODUCTO</p>
            </div>
            <div class="w-[8%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">UNIDAD</p>
            </div>
            <!-- <div class="w-[10%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">VENC.</p>
            </div> -->
            <div class="w-[10%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">CENTRO</p>
            </div>
            <div class="w-[10%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">CANT.</p>
            </div>
            <div class="w-[12%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">COSTO</p>
            </div>
            <div class="w-[13%] flex justify-center items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold">TOTAL</p>
            </div>
        </div>
        <!-- Table body -->
        <div v-for="(prod, index) in recToPrint.detalle_recepcion" :key="index"
            class="flex w-full border-x border-b border-gray-500" style="page-break-inside: avoid;">
            <!-- Codigo -->
            <div class="w-[12%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px]">{{
                        prod.producto.codigo_producto }}</p>
            </div>
            <!-- Producto -->
            <div class="w-[35%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] px-0.5">
                    {{ prod.producto.nombre_producto }}
                </p>
            </div>
            <!-- Unidad -->
            <div class="w-[8%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px]">
                    {{ prod.producto.unidad_medida.abreviatura_unidad_medida }}
                </p>
            </div>
            <!-- Vencimiento -->
            <!-- <div class="w-[10%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px]">
                    {{ prod.fecha_vencimiento_det_recepcion_pedido ? moment(prod.fecha_vencimiento_det_recepcion_pedido,
                        'YYYY/MM/DD').format('DD/MM/YYYY') : 'N/A' }}
                </p>
            </div> -->
            <!-- Centro -->
            <div class="w-[10%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px]">
                    {{ prod.centro_atencion.codigo_centro_atencion }}
                </p>
            </div>
            <!-- Cantidad -->
            <div class="w-[10%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px]">
                    {{ prod.cant_det_recepcion_pedido }}
                </p>
            </div>
            <!-- Costo -->
            <div class="w-[12%] flex justify-center items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px]">
                    ${{ prod.costo_det_recepcion_pedido }}
                </p>
            </div>
            <!-- Total -->
            <div class="w-[13%] flex justify-center items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px]">
                    ${{ (prod.cant_det_recepcion_pedido * prod.costo_det_recepcion_pedido).toFixed(2) }}
                </p>
            </div>
        </div>
        <!-- Table footer -->
        <div class="flex w-full border-x border-b border-gray-500" style="page-break-inside: avoid;">
            <div class="w-[87%] flex justify-end items-center border-r border-gray-500">
                <p class="mb-[10px] mt-[-5px] mr-2 font-[MuseoSans] text-[11px] font-bold">TOTAL ACTA</p>
            </div>
            <div class="w-[13%] flex justify-center items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold"> ${{
                        recToPrint.monto_recepcion_pedido }}</p>
            </div>
        </div>
        <div class="flex w-full border-x border-b border-gray-500" style="page-break-inside: avoid;">
            <div class="w-full flex justify-start items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold ml-2">
                    SON: {{ recToPrint.monto_letras }}</p>
            </div>
        </div>
        <div> <!-- Aditional information -->
            <!-- Observacion -->
            <div class="flex w-full mt-4">
                <p class="font-[MuseoSans] text-[12px] ">Observación guardaalmacen:</p>
                <p class="font-[MuseoSans] text-[12px] font-bold ml-1">{{ recToPrint.observacion_recepcion_pedido ?? 'Sin observación.' }}</p>
            </div>
        </div>
        <div class="pt-[30px]" style="page-break-inside: avoid;"> <!-- Signatures -->
            <p class="text-center font-[MuseoSans] text-[12px] mt-[-5px] font-bold">Firmas acta de recepcion
                de bienes {{ recToPrint.acta_recepcion_pedido }}</p>
            <div class="mb-[50px] pt-[70px]"> <!-- First segment -->
                <div class="flex w-full mb-1">
                    <div class="w-[50%] flex justify-center ">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px] font-bold mr-1">F.</p>
                        <div class="w-[70%] border-b border-gray-500">{{ }}</div>
                    </div>
                    <div class="w-[50%] flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px] font-bold mr-1">F.</p>
                        <div class="w-[70%] border-b border-gray-500">{{ }}</div>
                    </div>
                </div>
                <div class="flex w-full mb-0.5">
                    <div class="w-[50%] flex justify-center ">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] font-bold mt-[-5px] mr-1">
                            {{ recToPrint.administrador_contrato.persona.nombre_apellido }}
                        </p>
                    </div>
                    <div class="w-[50%] flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] font-bold mt-[-5px] mr-1">
                            {{ recToPrint.representante_prov_recepcion_pedido }}
                        </p>
                    </div>
                </div>
                <div class="flex w-full">
                    <div class="w-[50%] flex justify-center ">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px]">
                            Acepta donación
                        </p>
                    </div>
                    <div class="w-[50%] flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px]">
                            Recibe donación
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col"> <!-- Second segment -->
                <div class="flex w-full mb-1">
                    <div class="w-full flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px] font-bold mr-1">F.</p>
                        <div class="w-[35%] border-b border-gray-500">{{ }}</div>
                    </div>
                </div>
                <div class="flex w-full mb-0.5">
                    <div class="w-full flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] font-bold mt-[-5px] mr-1">
                            {{ recToPrint.guarda_almacen.persona.nombre_apellido }}
                        </p>
                    </div>
                </div>
                <div class="flex w-full">
                    <div class="w-full flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px]">
                            Guardaalmacen
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import moment from 'moment';

export default {
    components: {},
    props: {
        recToPrint: {
            type: Object,
            default: {},
        }
    },
    setup() {
        return {
            moment
        }
    }
};
</script>

<style>
#pagebreak {
    page-break-before: always;
}

.center-vertically {
    align-items: center;
}
</style>