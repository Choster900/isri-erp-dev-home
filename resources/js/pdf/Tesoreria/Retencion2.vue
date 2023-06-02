<template>
    <div>
        <table border="0" cellpadding="0" cellspacing="0" class="w-full mt-[145px]">
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
                            NOMBRE DEL PROVEEDOR DEL BIEN O SERVICIO: <span class="font-extrabold text-black ml-[20px]">{{
                                dataQuedan.proveedor.nombre_comercial_proveedor }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="border-l border-r border-white border-b text-left " colspan="2">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-black">
                            DIRECCION: <span class="font-extrabold text-[8px]">{{
                                dataQuedan.proveedor.direccion_proveedor }}</span>
                        </div>
                    </td>
                    <td class=" border-r border-white border-b text-left" colspan="2">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                            DEPARTAMENTO: 120px
                        </div>
                    </td>
                    <td class="border-r border-white border-b text-left" colspan="2">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 pb-3 text-[8pt] text-white">
                            GIRO:
                            <span class="font-extrabold text-[8px]">{{ dataQuedan.proveedor.giro.nombre_giro }}</span>
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
                    <td class="h-[50px]">

                    </td>
                </tr>

                <tr>
                    <td class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class="mt-[18px] mb-[4px] text-[8pt] text-center">
                            {{ formatDate(dataQuedan.detalle_quedan[0].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class="mt-[18px] mb-[4px] text-[8pt]">
                            {{ dataQuedan.detalle_quedan[0].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[18px] text-[8pt] mb-[4px]">
                                {{ (((!isNaN(parseFloat(dataQuedan.detalle_quedan[0].producto_factura_det_quedan))
                                    ? parseFloat(dataQuedan.detalle_quedan[0].producto_factura_det_quedan) :
                                    0) + (!isNaN(parseFloat(dataQuedan.detalle_quedan[0].servicio_factura_det_quedan))
                                        ? parseFloat(dataQuedan.detalle_quedan[0].servicio_factura_det_quedan) : 0))
                                    / 1.13).toFixed(2) }}
                            </span>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class="mt-[17px]  mb-[4px] text-[8pt] text-center">
                            {{ formatDate(dataQuedan.detalle_quedan[1].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class="mt-[17px]  mb-[4px] text-[8pt]">
                            {{ dataQuedan.detalle_quedan[1].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[17px]  mb-[4px] text-[8pt] ">
                                {{ (((!isNaN(parseFloat(dataQuedan.detalle_quedan[1].producto_factura_det_quedan))
                                    ? parseFloat(dataQuedan.detalle_quedan[1].producto_factura_det_quedan) :
                                    0) + (!isNaN(parseFloat(dataQuedan.detalle_quedan[1].servicio_factura_det_quedan))
                                        ? parseFloat(dataQuedan.detalle_quedan[1].servicio_factura_det_quedan) : 0))
                                    / 1.13).toFixed(2) }}
                            </span>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class=" mt-[18px] mb-[5px] text-[8pt] text-center">
                            {{ formatDate(dataQuedan.detalle_quedan[2].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class=" mt-[18px] mb-[5px] text-[8pt]">
                            {{ dataQuedan.detalle_quedan[2].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[18px] text-[8pt] mb-[5px]">
                                {{ (((!isNaN(parseFloat(dataQuedan.detalle_quedan[2].producto_factura_det_quedan))
                                    ? parseFloat(dataQuedan.detalle_quedan[2].producto_factura_det_quedan) :
                                    0) + (!isNaN(parseFloat(dataQuedan.detalle_quedan[2].servicio_factura_det_quedan))
                                        ? parseFloat(dataQuedan.detalle_quedan[2].servicio_factura_det_quedan) : 0))
                                    / 1.13).toFixed(2) }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class=" mt-[16px]  mb-[4px] text-[8pt]  text-center">
                            {{ formatDate(dataQuedan.detalle_quedan[3].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class=" mt-[16px] text-[8pt] mb-[4px]">
                            {{ dataQuedan.detalle_quedan[3].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[16px] text-[8pt] mb-[4px]">
                                {{ (((!isNaN(parseFloat(dataQuedan.detalle_quedan[3].producto_factura_det_quedan))
                                    ? parseFloat(dataQuedan.detalle_quedan[3].producto_factura_det_quedan) :
                                    0) + (!isNaN(parseFloat(dataQuedan.detalle_quedan[3].servicio_factura_det_quedan))
                                        ? parseFloat(dataQuedan.detalle_quedan[3].servicio_factura_det_quedan) : 0))
                                    / 1.13).toFixed(2) }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class=" mt-[16px] mb-[2px] text-[8pt] text-center">
                            {{ formatDate(dataQuedan.detalle_quedan[4].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class=" mt-[16px] mb-[2px] text-[8pt]">
                            {{ dataQuedan.detalle_quedan[4].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[16px] text-[8pt] mb-[2px]">
                                {{ (((!isNaN(parseFloat(dataQuedan.detalle_quedan[4].producto_factura_det_quedan))
                                    ? parseFloat(dataQuedan.detalle_quedan[4].producto_factura_det_quedan) :
                                    0) + (!isNaN(parseFloat(dataQuedan.detalle_quedan[4].servicio_factura_det_quedan))
                                        ? parseFloat(dataQuedan.detalle_quedan[4].servicio_factura_det_quedan) : 0))
                                    / 1.13).toFixed(2) }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class=" mt-[16px] mb-[1px] text-[8pt] text-center">
                            {{ formatDate(dataQuedan.detalle_quedan[5].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class=" mt-[16px] mb-[1px] text-[8pt]">
                            {{ dataQuedan.detalle_quedan[5].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[16px] text-[8pt] mb-[1px]">
                                {{ (((!isNaN(parseFloat(dataQuedan.detalle_quedan[5].producto_factura_det_quedan))
                                    ? parseFloat(dataQuedan.detalle_quedan[5].producto_factura_det_quedan) :
                                    0) + (!isNaN(parseFloat(dataQuedan.detalle_quedan[5].servicio_factura_det_quedan))
                                        ? parseFloat(dataQuedan.detalle_quedan[5].servicio_factura_det_quedan) : 0))
                                    / 1.13).toFixed(2) }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class=" mt-[16px] text-[8pt] text-center">
                            {{ formatDate(dataQuedan.detalle_quedan[6].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class=" mt-[16px] text-[8pt]">
                            {{ dataQuedan.detalle_quedan[6].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[16px] text-[8pt]">
                                {{ (((!isNaN(parseFloat(dataQuedan.detalle_quedan[6].producto_factura_det_quedan))
                                    ? parseFloat(dataQuedan.detalle_quedan[6].producto_factura_det_quedan) :
                                    0) + (!isNaN(parseFloat(dataQuedan.detalle_quedan[6].servicio_factura_det_quedan))
                                        ? parseFloat(dataQuedan.detalle_quedan[6].servicio_factura_det_quedan) : 0))
                                    / 1.13).toFixed(2) }}
                            </span>
                        </div>
                    </td>
                </tr>


                <!--   <tr v-for="i in 7" :key="i">
                    <td v-if="dataQuedan.detalle_quedan[i - 1]" class=" border-l border-r border-white border-b text-left ">
                        <div style="margin-top: -12px;" class=" mt-[16px] text-[8pt] text-center" :class="p">
                            {{ formatDate(dataQuedan.detalle_quedan[i - 1].fecha_factura_det_quedan) }}
                        </div>
                    </td>
                    <td v-else class=" border-l border-r border-white border-b">&nbsp;</td>
                    <td v-if="dataQuedan.detalle_quedan[i - 1]" class=" border-r border-white border-b" colspan="4">
                        <div style="margin-top: -12px;" class=" mt-[16px] text-[8pt]">
                            {{ dataQuedan.detalle_quedan[i - 1].descripcion_det_quedan }}

                        </div>
                    </td>
                    <td v-else class=" border-r border-white border-b" colspan="4">&nbsp;</td>
                    <td v-if="dataQuedan.detalle_quedan[i - 1]" class=" border-r border-white border-b ">
                        <div style="margin-top: -12px;" class="px-1 flex justify-between">
                            <span class="text-left text-white">$</span>
                            <span class="text-right mt-[16px] text-[8pt]">
                                {{(((!isNaN(parseFloat(dataQuedan.detalle_quedan[i -1].producto_factura_det_quedan)) ?parseFloat(dataQuedan.detalle_quedan[i - 1].producto_factura_det_quedan) : 0)+(!isNaN(parseFloat(dataQuedan.detalle_quedan[i -1].servicio_factura_det_quedan)) ?parseFloat(dataQuedan.detalle_quedan[i - 1].servicio_factura_det_quedan) : 0)) /1.13).toFixed(2) }}
                            </span>

                        </div>
                    </td>
                    <td v-else class=" border-r border-white border-b">
                        <div style="margin-top: -12px;" class="pl-1 py-[9px] text-[8pt] text-left text-white">
                            $
                        </div>
                    </td>
                </tr> -->
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
            </tbody>
        </table>
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
  