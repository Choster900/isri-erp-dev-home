<template>
    <div class="py-2 mb-2">
        <!-- Document header -->
        <div class="flex w-full min-h-[80px]">
            <!-- Columna 1 -->
            <div class="w-[23%] flex justify-center items-center border border-black p-1">
                <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-full my-auto">
            </div>
            <!-- Columna 2 -->
            <div
                class="w-[77%] justify-center items-center border-y border-r border-black pb-[10px] flex flex-col min-h-full">
                <div class="flex flex-col justify-center items-center h-full">
                    <p class="font-[Bembo] text-center text-[12px] font-bold">ALMACEN CENTRAL</p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">
                        RECEPCION DE BIENES
                        {{
                            recToPrint.det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion.nombre_tipo_doc_adquisicion
                        }}
                        {{ '"' + recToPrint.det_doc_adquisicion.documento_adquisicion.numero_doc_adquisicion + '"' }}
                    </p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">
                        {{ recToPrint.det_doc_adquisicion.nombre_det_doc_adquisicion }}
                    </p>
                </div>
            </div>



        </div>

        <!-- Fecha referencia -->
        <div class="flex w-full border-x border-black pb-1">
            <div class="w-[40%] flex justify-start items-center">
                <p class="ml-2 font-[MuseoSans] text-gray-800 text-[11px]">Fecha y hora de recepción:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ moment(recToPrint.fecha_reg_recepcion_pedido).format('DD/MM/YYYY, HH:mm:ss') }}
                </p>
            </div>
            <div class="w-[35%] flex justify-start items-center">
                <p class="font-[MuseoSans] text-gray-800 text-[11px]">Financiamiento:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ recToPrint.det_doc_adquisicion.fuente_financiamiento.nombre_proy_financiado }}
                </p>
            </div>
            <div class="w-[25%] flex justify-start items-center">
                <p class="font-[MuseoSans] text-gray-800 text-[11px]">Compromiso:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ recToPrint.det_doc_adquisicion.compromiso_ppto_det_doc_adquisicion }}
                </p>
            </div>
        </div>

        <!-- Proveedor and NIT -->
        <div class="flex w-full border-x border-black pb-1">
            <div class="w-[75%] flex justify-start items-center">
                <p class="ml-2 font-[MuseoSans] text-[11px] text-gray-800">Proveedor:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ recToPrint.det_doc_adquisicion.documento_adquisicion.proveedor.razon_social_proveedor }}
                </p>
            </div>
            <div class="w-[25%] flex justify-start items-center">
                <p class="font-[MuseoSans] text-gray-800 text-[11px]">{{
                    recToPrint.det_doc_adquisicion.documento_adquisicion.proveedor.dui_proveedor ? 'DUI:' : 'NIT:' }}
                </p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ recToPrint.det_doc_adquisicion.documento_adquisicion.proveedor.dui_proveedor ??
                        recToPrint.det_doc_adquisicion.documento_adquisicion.proveedor.nit_proveedor }}
                </p>
            </div>
        </div>

        <!-- Date and time, financing source and commitment number -->
        <div class="flex w-full border-x border-black pb-3">
            <div class="w-[50%] flex justify-start items-center">
                <p class="ml-2 font-[MuseoSans] text-gray-800 text-[11px]">Fecha referencia documento de compra:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans]">
                    {{ moment(
                        recToPrint.det_doc_adquisicion.documento_adquisicion.fecha_adjudicacion_doc_adquisicion).format('DD/MM/YYYY')
                    }}
                </p>
            </div>

        </div>



        <!-- Third row -->
        <div class="flex bg-black  justify-center items-center font-[MuseoSans] pb-[12px]">
            <p class="font-[MuseoSans] font-bold text-white text-[11px]">ACTA DE RECEPCION DE BIENES
                {{ recToPrint.acta_recepcion_pedido }}</p>
        </div>

        <!-- Table header -->
        <div class="flex w-full border-x border-b border-black " style="page-break-inside: avoid;">
            <div class="w-[35%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">PRODUCTO</p>
            </div>
            <div class="w-[12%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">VCTO.</p>
            </div>
            <div class="w-[10%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">CENTRO</p>
            </div>
            <div class="w-[12%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">CANT.</p>
            </div>
            <div class="w-[16%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">COSTO</p>
            </div>
            <div class="w-[15%] flex justify-center items-center py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">TOTAL</p>
            </div>
        </div>

        <!-- Table body -->
        <template v-for="(lts, indexLt) in recToPrint.detalles_agrupados" :key="indexLt">
            <div class="flex  border-x border-b border-black justify-center items-center font-[MuseoSans] pb-[12px]">
                <p class="font-[MuseoSans] font-bold text-[11px]">{{ lts.codigo_up_lt }} - ${{ lts.total }}</p>
            </div>
            <template v-for="(prod, index) in lts.productos" :key="index">
                <div class="flex w-full border-x border-b border-black" style="page-break-inside: avoid;">
                    <!-- Producto -->
                    <div class="w-[35%] flex justify-center items-center border-r border-black">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] px-0.5">
                            {{ prod.producto.codigo_producto + " — " +
                                prod.producto.nombre_completo_producto + " — " +
                                prod.producto.unidad_medida.nombre_unidad_medida + " — " +
                                prod.producto_adquisicion.descripcion_prod_adquisicion }}
                            {{ " — " + (prod.marca.nombre_marca ? "Marca: " +
                                prod.marca.nombre_marca : +"N/A") }}
                        </p>
                    </div>
                    <!-- Vencimiento -->
                    <div class="w-[12%] flex justify-center items-center border-r border-black">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                            {{ prod.fecha_vcto_det_recepcion_pedido ? moment(prod.fecha_vcto_det_recepcion_pedido,
                                'YYYY/MM/DD').format('DD/MM/YYYY') : 'N/A' }}
                        </p>
                    </div>
                    <!-- Centro -->
                    <div class="w-[10%] flex justify-center items-center border-r border-black">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                            {{ prod.centro_atencion.codigo_centro_atencion }}
                        </p>
                    </div>
                    <!-- Cantidad -->
                    <div class="w-[12%] flex justify-center items-center border-r border-black">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                            {{ prod.producto.fraccionado_producto == 0 ? floatToInt(prod.cant_det_recepcion_pedido) : prod.cant_det_recepcion_pedido }}
                        </p>
                    </div>
                    <!-- Costo -->
                    <div class="w-[16%] flex justify-center items-center border-r border-black">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                            ${{ recToPrint.det_doc_adquisicion.documento_adquisicion.id_proceso_compra == 5 ? 
                            prod.costo_det_recepcion_pedido : 
                            parseFloat(prod.costo_det_recepcion_pedido).toFixed(2) }}
                        </p>
                    </div>
                    <!-- Total -->
                    <div class="w-[15%] flex justify-center items-center">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                            ${{ (prod.cant_det_recepcion_pedido * prod.costo_det_recepcion_pedido).toFixed(2) }}
                        </p>
                    </div>
                </div>
            </template>
        </template>


        <!-- Table footer -->
        <div class="flex w-full border-x border-b border-black" style="page-break-inside: avoid;">
            <div class="w-[85%] flex justify-end items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] mr-2 font-[MuseoSans] text-[11px] font-bold">TOTAL ACTA</p>
            </div>
            <div class="w-[15%] flex justify-center items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold"> ${{
                    recToPrint.monto_recepcion_pedido }}</p>
            </div>
        </div>
        <div class="flex w-full border-x border-b border-black" style="page-break-inside: avoid;">
            <div class="w-full flex justify-start items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[11px] font-bold ml-2">
                    SON: {{ recToPrint.monto_letras }}</p>
            </div>
        </div>
        <div> <!-- Aditional information -->
            <!-- Observacion -->
            <div class="flex w-full mt-4">
                <p class="font-[MuseoSans] text-[12px] ">Observación guardaalmacen:</p>
                <p class="font-[MuseoSans] text-[12px] font-bold ml-1">
                    {{ recToPrint.observacion_recepcion_pedido ??
                        "Sin observación" }}</p>
            </div>
            <!-- Incumplimiento -->
            <div class="flex w-full mt-2">
                <p class="font-[MuseoSans] text-[12px] ">Incumplimiento:</p>
                <p class="font-[MuseoSans] text-[12px] font-bold ml-1">{{ recToPrint.incumple_acuerdo_recepcion_pedido
                    == 1 ? recToPrint.incumplimiento_recepcion_pedido : "Sin incumplimiento." }}</p>
            </div>
        </div>
        <div class="" style="page-break-inside: avoid;"> <!-- Signatures -->
            <p class="text-center font-[MuseoSans] text-[12px] pt-[20px] mt-[-5px] font-bold">Firmas acta de recepcion
                de bienes {{ recToPrint.acta_recepcion_pedido }}</p>
            <div class="mb-[50px] pt-[70px]"> <!-- First segment -->
                <div class="flex w-full mb-1">
                    <div class="w-[50%] flex justify-center ">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px] font-bold mr-1">F.</p>
                        <div class="w-[70%] border-b border-black">{{ }}</div>
                    </div>
                    <div class="w-[50%] flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px] font-bold mr-1">F.</p>
                        <div class="w-[70%] border-b border-black">{{ }}</div>
                    </div>
                </div>
                <div class="flex w-full mb-0.5">
                    <div class="w-[50%] flex justify-center ">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] font-bold mt-[-5px] mr-1">
                            {{ recToPrint.representante_prov_recepcion_pedido }}
                        </p>
                    </div>
                    <div class="w-[50%] flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] font-bold mt-[-5px] mr-1">
                            {{ recToPrint.administrador_contrato.persona.nombre_apellido }}
                        </p>
                    </div>
                </div>
                <div class="flex w-full">
                    <div class="w-[50%] flex justify-center ">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px]">
                            Representante proveedor
                        </p>
                    </div>
                    <div class="w-[50%] flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px]">
                            Administrador de contrato
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col"> <!-- Second segment -->
                <div class="flex w-full mb-1">
                    <div class="w-full flex justify-center">
                        <p class="mb-[6px] text-[12px] font-[MuseoSans] mt-[-5px] font-bold mr-1">F.</p>
                        <div class="w-[35%] border-b border-black">{{ }}</div>
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
    methods: {
        floatToInt(value) {
            // Primero, convertimos el valor a un número flotante
            const floatValue = parseFloat(value);
            // Luego, lo redondeamos al entero más cercano
            const roundedValue = Math.round(floatValue);
            // Devolvemos el resultado como un número entero
            return roundedValue;
        },
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