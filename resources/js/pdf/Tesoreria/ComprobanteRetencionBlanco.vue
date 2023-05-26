<template>
    <div>
        <div class="text-center">
            <div class="border border-white ml-9 mr-7 my-[25px]" id="main-container" ref="contenedorPdfQuedan">
                <div class="flex justify-between px-4 pt-[15px] pb-[12px] text-white">
                    <div class="w-[70%] text-left" style="margin-top: -5px;">
                        <p class="text-[8pt]">NOMBRE DEL INSTITUTO EMISOR</p>
                        <p class="font-bold text-[12pt]">INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL (ISRI)</p>
                        <p class="font-bold text-[8pt]">GIRO: PRESENTACION DE SERVICIOS DE REHABILITACION</p>
                        <p class="text-[10pt]">DIRECCION: Colonia Costa Rica y Avenida Irazú, #181, San Salvador</p>
                        <p class="text-[10pt]">Tel.: 2240-6519, 2240-6421</p>
                        <p class="text-[8pt] ">FECHA: <span class="text-black">{{ formatDate(dataQuedan.fecha_retencion_iva_quedan) }}</span></p>
                    </div>
                    <div class="w-[30%] text-center" style="margin-top: -5px;">
                        <div class="bg-white rounded-lg border-2 border-white h-[110px]">
                            <div style="margin-top: -12px;" class="py-1">
                                <p class="font-extrabold text-[11pt]">COMPROBANTE DE RETENCION</p>
                                <p class="font-bold text-[6.5pt]">SERIE AUTORIZADA DE 21DS000E1 AL 21DS000E30000</p>
                                <p class="text-[19pt]">{{ dataQuedan.numero_retencion_iva_quedan.toString().padStart(7, '0')
                                }}</p>
                                <p class="font-extrabold text-[9pt]">N.I.T.: 0614-170324-001-9</p>
                                <p class="font-extrabold text-[9pt]">RESOLUCION: 15041-RES-IN-26216-2021</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4" style="margin-top: -5px;">
                    <table border="0" cellpadding="0" cellspacing="0" class="  table-auto w-full">
                        <col class="w-[167px]">
                        <col class="w-[185px]">
                        <col class="w-[318px]">
                        <col class="w-[58px]">
                        <col class="w-[200px]">
                        <col class="w-[150px]">
                        <tbody>
                            <tr>
                                <td class="  style4 text-left border-t border-b border-l border-r border-white rounded-t-md"
                                    colspan="6">
                                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                                        NOMBRE DEL PROVEEDOR DEL BIEN O SERVICIO: <span class="font-extrabold text-black">{{
                                            dataQuedan.proveedor.nombre_comercial_proveedor }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-l border-r border-white border-b text-left " colspan="2">
                                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                                        DIRECCION:
                                    </div>
                                </td>
                                <td class=" border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                                        DEPARTAMENTO:
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                                        GIRO:
                                        <!-- <span class="font-extrabold text-[8px]">{{ dataQuedan.proveedor.giro.nombre_giro }}</span> -->
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-l border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                                        N.I.T: <span class="font-extrabold">{{ dataQuedan.proveedor.nit_proveedor }}</span>
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left" colspan="4">
                                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                                        N.R.C.: <span class="font-extrabold">{{ dataQuedan.proveedor.nrc_proveedor }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-l border-r border-white border-b text-center">
                                    <div style="margin-top: -12px;"
                                        class=" py-1.5 text-[9.5pt] font-extrabold leading-tight text-white">
                                        FECHA DE EMISIÓN<br class="hidden md:inline">DE FACTURA:
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-center" colspan="4">
                                    <div style="margin-top: -12px;" class=" py-1 text-[9.5pt] font-extrabold text-white">
                                        D E S C R I P C I O N
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-center">
                                    <div style="margin-top: -12px;" class="py-1 text-[9.5pt] font-extrabold leading-tight text-white">
                                        VENTAS<br class="hidden md:inline text-white">AFECTAS
                                    </div>

                                </td>
                            </tr>

                            <tr v-for="i in 7" :key="i">
                                <td v-if="dataQuedan.detalle_quedan[i - 1]"
                                    class=" border-l border-r border-white border-b text-left ">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-center">
                                        {{ formatDate(dataQuedan.detalle_quedan[i - 1].fecha_factura_det_quedan) }}
                                    </div>
                                </td>
                                <td v-else class=" border-l border-r border-white border-b">&nbsp;</td>
                                <td v-if="dataQuedan.detalle_quedan[i - 1]" class=" border-r border-white border-b"
                                    colspan="4">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt]">
                                        {{ dataQuedan.detalle_quedan[i - 1].descripcion_det_quedan }}

                                    </div>
                                </td>
                                <td v-else class=" border-r border-white border-b" colspan="4">&nbsp;</td>
                                <td v-if="dataQuedan.detalle_quedan[i - 1]" class=" border-r border-white border-b ">
                                    <div style="margin-top: -12px;" class="px-1 py-[9px] text-[8pt] flex justify-between">
                                        <span class="text-left text-white">
                                            $
                                        </span>
                                        <span class="text-right">
                                            {{
                                                (
                                                    ((!isNaN(parseFloat(dataQuedan.detalle_quedan[i -
                                                        1].producto_factura_det_quedan)) ?
                                                        parseFloat(dataQuedan.detalle_quedan[i - 1].producto_factura_det_quedan) : 0)
                                                        +
                                                        (!isNaN(parseFloat(dataQuedan.detalle_quedan[i -
                                                            1].servicio_factura_det_quedan)) ?
                                                            parseFloat(dataQuedan.detalle_quedan[i - 1].servicio_factura_det_quedan) : 0)) /
                                                    1.13
                                                ).toFixed(2) }}
                                        </span>

                                    </div>
                                </td>
                                <td v-else class=" border-r border-white border-b">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-left text-white">
                                        $
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class=" border-l border-r border-white border-b text-left" colspan="3">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        SON: <span class="font-extrabold text-black">{{ dataQuedan.monto_iva_quedan_letter }}</span>
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] font-extrabold text-white">
                                        SUMAS
                                    </div>
                                </td>
                                <td class=" border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="px-1 py-[9px] text-[8pt] flex justify-between">
                                        <span class="text-left text-white">
                                            US $
                                        </span>
                                        <span class="text-right text-black">
                                            {{
                                                (this.dataQuedan.detalle_quedan.reduce((total, element) => {
                                                    const producto = isNaN(parseFloat(element.producto_factura_det_quedan)) ? 0 :
                                                        parseFloat(element.producto_factura_det_quedan);
                                                    const servicio = isNaN(parseFloat(element.servicio_factura_det_quedan)) ? 0 :
                                                        parseFloat(element.servicio_factura_det_quedan);
                                                    return total + producto + servicio;
                                                }, 0) / 1.13).toFixed(2)
                                            }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-l border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        ENTREGADO POR:
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        RECIBIDO POR:
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white font-extrabold">
                                        IVA RETENIDO
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="px-1 py-[9px] text-[8pt] flex justify-between">
                                        <span class="text-left text-white">
                                            US $
                                        </span>
                                        <span class="text-right ">
                                            {{ iva }}
                                        </span>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-l border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        NOMBRE <span class="font-extrabold text-black">{{ dataQuedan.tesorero.nombre_empleado_tesoreria
                                        }}</span>
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        NOMBRE:
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] font-extrabold">
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="pl-0.5 py-[9px] text-[8pt]">

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-l border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        D.U.I. <span class="font-extrabold text-black">{{ dataQuedan.tesorero.dui_empleado_tesoreria
                                        }}</span>
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        D.U.I.
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] font-extrabold">

                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="pl-0.5 py-[9px] text-[8pt]">

                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="border-l border-r border-white border-b text-left rounded-bl-md" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        FIRMA
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                        FIRMA
                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left" colspan="2">
                                    <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] font-extrabold">

                                    </div>
                                </td>
                                <td class="border-r border-white border-b text-left rounded-br-md">
                                    <div style="margin-top: -12px;" class="pl-0.5 py-[9px] text-[8pt]">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between px-4 py-4">
                    <div class="w-[40%] text-left text-white">
                        <div class="bg-white rounded-lg border-2 border-dashed  border-white">
                            <div style="margin-top: -12px;" class="py-4 pl-1 font-bold">
                                <p class=" text-[6.5pt] mb-1">NOMBRE, DENOMINACION RAZON SOCIAL:</p>
                                <p class=" text-[6.5pt] mb-1">CHAVEZ IMPRESORES: Tom Alberto Hernández Chávez</p>
                                <p class="text-[6.5pt] mb-1">NIT: 0614-291264-010-8
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NRC:
                                    130243-0</p>
                                <p class="text-[6.5pt] mb-1">DIRECCION: 31 Calle Oriente # 434 Colonia La Rábida S. S.</p>
                                <p class=" text-[6.5pt] mb-1">AUTORIZACION DE IMPRENTA No. 1031 D.G.I.I. FECHA DE
                                    AUTORIZACION 03/06/2008
                                </p>
                                <p class=" text-[6.5pt]">RESOLUCIÓN: 15041-RES-IN-26216-2021</p>
                                <p class=" text-[6.5pt]">FECHA DE AUTORIZACIÓN: 29/04/2021</p>
                                <p class=" text-[6.5pt] mb-1">FECHA DE IMPRESION: 07/05/2021</p>
                                <p class=" text-[6.5pt] mb-3">TIRAJE del 21DS000E1 al 21DS000E3000</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-[60%] text-left text-white">
                        <div style="margin-top: -12px;" class="py-16 pl-32 font-extrabold">
                            <p class="text-[8pt]">BLANCO ORIGINAL: Instituto Salvadoreño de Rehabilitación Integral (ISRI)
                            </p>
                            <p class="text-[8pt]">VERDE PRIMERA COPIA: Contribuyente</p>
                            <p class="text-[8pt]">AMARILLO SEGUNDA COPIA: Tesorería (ISRI)</p>
                            <p class="text-[8pt]">ROSADO TERCERA COPIA: Tesorería (ISRI) </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
export default {
    props: {
        dataQuedan: {//prop has total information from "quedan"
            type: Object,
            required: true,
        },
        totalCheque: {//prop has total information from "quedan"
            type: String,
            required: true,
        },
        iva: {//prop has total information from "quedan"
            type: String,
            required: true,
        },
    },
    methods: {
        formatDate(dateString) {
            const date = new Date(dateString);
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear().toString();
            return `${day}/${month}/${year}`;
        }
    }
}
</script>
  
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap');

* {
    font-family: 'Lato', sans-serif;
}
</style>
  