<template>
    <div>
        <p class="text-[10pt] mt-[145px] pl-[90px]"><span class="text-black">{{
            formatDate(dataQuedan.fecha_retencion_iva_quedan) }}</span></p>
        <div class="text-center mt-[24px] pl-[65px]">

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
                                <div style="margin-top: -12px;" class="pl-2 pb-3 text-[8pt] text-white">
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
                                <div style="margin-top: -12px;"
                                    class="py-1 text-[9.5pt] font-extrabold leading-tight text-white">
                                    VENTAS<br class="hidden md:inline text-white">AFECTAS
                                </div>

                            </td>
                        </tr>

                        <tr v-for="i in 7" :key="i">
                            <td v-if="dataQuedan.detalle_quedan[i - 1]"
                                class=" border-l border-r border-white border-b text-left ">
                                <div style="margin-top: -12px; margin-left: -150px;"
                                    class="py-[9px] text-[8pt] text-center font-bold">
                                    {{ formatDate(dataQuedan.detalle_quedan[i - 1].fecha_factura_det_quedan) }}
                                </div>
                            </td>
                            <td v-else class=" border-l border-r border-white border-b">&nbsp;</td>
                            <td v-if="dataQuedan.detalle_quedan[i - 1]" class=" border-r border-white border-b text-left"
                                colspan="4">
                                <div style="margin-top: -12px; margin-left: -12px;"
                                    class="py-[9px] text-[8pt] font-bold uppercase">
                                    {{ truncateText(dataQuedan.detalle_quedan[i - 1].descripcion_det_quedan, 100) }}

                                </div>
                            </td>
                            <td v-else class=" border-r border-white border-b" colspan="4">&nbsp;</td>
                            <td v-if="dataQuedan.detalle_quedan[i - 1]" class=" border-r border-white border-b ">
                                <div style="margin-top: -12px;" class="pr-2 py-[9px] text-[8pt] flex justify-between">
                                    <span class="text-left text-white">
                                        $
                                    </span>
                                    <span class="text-right font-bold">
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
                                    SON: <span class="font-extrabold text-black">{{ dataQuedan.monto_iva_quedan_letter
                                    }}</span>
                                </div>
                            </td>
                            <td class="border-r border-white border-b text-left" colspan="2">
                                <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] font-extrabold text-white">
                                    SUMAS
                                </div>
                            </td>
                            <td class=" border-r border-white border-b text-left">
                                <div style="margin-top: -12px;" class="pr-2 py-[9px] text-[8pt] flex justify-between">
                                    <span class="text-left text-white">
                                        US $
                                    </span>
                                    <span class="text-right text-black font-bold">
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
                                <div style="margin-top: -12px;" class="pr-2 py-[9px] text-[8pt] flex justify-between">
                                    <span class="text-left text-white">
                                        US $
                                    </span>
                                    <span class="text-right font-extrabold">
                                        {{ iva }}
                                    </span>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-l border-r border-white border-b text-left" colspan="2">
                                <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-white">
                                    <span class="font-extrabold ml-[15px] mt-[7px] text-black text-[8px]">{{
                                        dataQuedan.tesorero.nombre_empleado_tesoreria }}</span>
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
                                <div style="margin-top: -12px; margin-left: -15px;"
                                    class="pl-1 py-[9px] text-[8pt] text-white">
                                    D.U.I. <span class="font-extrabold text-black">{{
                                        dataQuedan.tesorero.dui_empleado_tesoreria
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
        },
        truncateText(text, maxLength) {
            if (!text || text.length === 0) {
                return "";
            }

            if (text.length <= maxLength) {
                return text;
            } else {
                return text.substring(0, maxLength) + '...';
            }
        },
    }
}
</script>
  
<style scoped>
* {
    font-family: Arial, Helvetica, sans-serif;
}
</style>
  