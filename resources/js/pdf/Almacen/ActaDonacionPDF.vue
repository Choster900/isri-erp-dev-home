<template>
    <div class="py-2 mb-2">
        <!-- Document header -->
        <div class="flex w-full">
            <!-- Columna 1 -->
            <div class="w-[23%] flex justify-center items-center border border-black p-1">
                <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-full my-auto">
            </div>
            <!-- Columna 2 -->
            <div class="w-[77%] justify-center items-center border-y border-r border-black pb-[10px]">
                <p class="font-[Bembo] text-center text-[12px] font-bold">ALMACEN GENERAL</p>
                <p class="font-[Bembo] text-center text-[12px] font-bold">
                    DONACIONES
                </p>
                <p class="font-[Bembo] text-center text-[12px] font-bold">
                    ACTA DE RECEPCION {{ recToPrint.acta_recepcion_pedido }}
                </p>
            </div>
        </div>

        <!-- Date and time, financing source and commitment number -->
        <div class="flex w-full border-x border-black pb-1">
            <div class="w-[73%] flex justify-start items-center text-[12px]">
                <p class="font-[MuseoSans] text-gray-800 ml-2">Centro:</p>
                <p class="ml-1 font-bold text-[10px] font-[MuseoSans] mt-[3px]">
                    {{ recToPrint.detalle_recepcion[0].centro_atencion.nombre_centro_atencion }}
                    {{ "(" + recToPrint.detalle_recepcion[0].centro_atencion.codigo_centro_atencion + ")" }}
                </p>
            </div>
            <div class="w-[27%] flex justify-start items-center text-[12px] ">
                <p class="font-[MuseoSans] text-gray-800">Fecha y hora:</p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans] mt-[3px]">
                    {{ moment(recToPrint.fecha_reg_recepcion_pedido).format('DD/MM/YYYY, HH:mm:ss') }}
                </p>
            </div>
        </div>
        <!-- Proveedor and NIT -->
        <div class="flex w-full border-x border-black pt-1 pb-3">
            <div class="w-[73%] flex justify-start items-center text-[12px]">
                <p class="ml-2 font-[MuseoSans] text-gray-800">Donante:</p>
                <p class="ml-1 font-bold text-[10px] font-[MuseoSans] mt-[3px]">
                    {{ recToPrint.proveedor.razon_social_proveedor }}
                </p>
            </div>
            <div class="w-[27%] flex justify-start items-center text-[12px]">
                <p class="font-[MuseoSans] text-gray-800">{{ recToPrint.proveedor.dui_proveedor ? 'DUI:' : 'NIT:' }}
                </p>
                <p class="ml-1 font-bold text-[11px] font-[MuseoSans] mt-[3px]">
                    {{ recToPrint.proveedor.dui_proveedor ? recToPrint.proveedor.dui_proveedor :
                        recToPrint.proveedor.nit_proveedor }}
                </p>
            </div>
        </div>
        <!-- Third row -->
        <div class="flex justify-center items-center bg-black border border-black font-[MuseoSans] py-0.5">
            <p class="font-[MuseoSans] font-bold text-[11px] mb-[10px] mt-[-5px] text-white">LISTADO DE PRODUCTOS</p>
        </div>

        <!-- Table header -->
        <div class="flex w-full border-x border-b border-black" style="page-break-inside: avoid;">
            <div class="w-[35%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">PRODUCTO</p>
            </div>
            <div class="w-[12%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">MARCA</p>
            </div>
            <div class="w-[12%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">VCTO.</p>
            </div>
            <div class="w-[12%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">CANT.</p>
            </div>
            <div class="w-[14%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">COSTO</p>
            </div>
            <div class="w-[15%] flex justify-center items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">TOTAL</p>
            </div>
        </div>
        <!-- Table body -->
        <div v-for="(prod, index) in recToPrint.detalle_recepcion" :key="index"
            class="flex w-full border-x border-b border-black" style="page-break-inside: avoid;">
            <!-- Producto -->
            <div class="w-[35%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] px-0.5">
                    {{
                        prod.producto.codigo_producto }} - {{ prod.producto.nombre_completo_producto }} - {{
                        prod.producto.unidad_medida.nombre_unidad_medida
                    }}
                </p>
            </div>
            <!-- Marca -->
            <div class="w-[12%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                    {{ prod.marca.nombre_marca }}
                </p>
            </div>
            <!-- Fecha de vencimiento -->
            <div class="w-[12%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                    {{ prod.fecha_vcto_det_recepcion_pedido ? moment(prod.fecha_vcto_det_recepcion_pedido,
                        'YYYY/MM/DD').format('DD/MM/YYYY') : 'N/A' }}
                </p>
            </div>
            <!-- Cantidad -->
            <div class="w-[12%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                    {{ prod.producto.fraccionado_producto == 0 ? floatToInt(prod.cant_det_recepcion_pedido) : prod.cant_det_recepcion_pedido }}
                </p>
            </div>
            <!-- Costo -->
            <div class="w-[14%] flex justify-center items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                    ${{ parseFloat(prod.costo_det_recepcion_pedido).toFixed(2) }}
                </p>
            </div>
            <!-- Total -->
            <div class="w-[15%] flex justify-center items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                    ${{ round2Decimals(prod.cant_det_recepcion_pedido * prod.costo_det_recepcion_pedido) }}
                </p>
            </div>
        </div>
        <!-- Table footer -->
        <div class="flex w-full border-x border-b border-black" style="page-break-inside: avoid;">
            <div class="w-[85%] flex justify-end items-center border-r border-black">
                <p class="mb-[10px] mt-[-5px] mr-2 font-[MuseoSans] text-[10px] font-bold">TOTAL DONACION</p>
            </div>
            <div class="w-[15%] flex justify-center items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold"> ${{
                    recToPrint.monto_recepcion_pedido }}</p>
            </div>
        </div>
        <div class="flex w-full border-x border-b border-black" style="page-break-inside: avoid;">
            <div class="w-full flex justify-start items-center">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold ml-2">
                    SON: {{ recToPrint.monto_letras }}</p>
            </div>
        </div>
        <div> <!-- Aditional information -->
            <!-- Observacion -->
            <div class="flex w-full mt-4">
                <p class="font-[MuseoSans] text-[12px] ">Observación guardaalmacen:</p>
                <p class="font-[MuseoSans] text-[12px] font-bold ml-1">{{ recToPrint.observacion_recepcion_pedido ??
                    'Sin observación.' }}</p>
            </div>
        </div>
        <div class="pt-[30px]" style="page-break-inside: avoid;"> <!-- Signatures -->
            <p class="text-center font-[MuseoSans] text-[12px] mt-[-5px] font-bold">Firmas acta de recepcion
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
import { useToCalculate } from '@/Composables/General/useToCalculate.js';

export default {
    components: {},
    props: {
        recToPrint: {
            type: Object,
            default: {},
        }
    },
    setup(props) {
        const { round2Decimals } = useToCalculate();

        const floatToInt = (value) => {
            // Primero, convertimos el valor a un número flotante
            const floatValue = parseFloat(value);
            // Luego, lo redondeamos al entero más cercano
            const roundedValue = Math.round(floatValue);
            // Devolvemos el resultado como un número entero
            return roundedValue;
        }

        return {
            moment, floatToInt, round2Decimals
        }
    },
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