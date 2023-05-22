<template>
    <div class="mb-5 mt-3 md:flex flex-row justify-between">
        <div class=" basis-1/5 pl-6">
            <img src="../../../img/isri-logo2.png" width="100px">
        </div>
        <div class="md:mx-10 md:mb-0 basis-1/2">
            <h3 style class="text-[15pt] font-semibold text-center">
                INSTITUTO SALVADOREÑO DE REHABILITACIÓN INTEGRAL
            </h3>

            <div style class="text-center">
                ORIGINAL - CLIENTE
            </div>
        </div>
        <div class="md:mb-0 basis-1/4 pr-2">
            <div class="justify-end">
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">Cheque $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right">
                                <span class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                    cheque
                                }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IVA $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right">
                                <span class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                    iva }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">
                            &nbsp;&nbsp;&nbsp;&nbsp;Renta $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right"> <span
                                    class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                        renta }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">
                            &nbsp;&nbsp;&nbsp;&nbsp;TOTAL $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right">
                                <span class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                    totalCheque
                                }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="text-center pb-5" style="margin-top: -40px;">
        <span class="text-[15pt] font-bold">QUEDAN N°</span>
        <span class="text-[18pt] pl-2" style="font-family: 'Courier New', Courier, monospace;">
            {{
                dataQuedan.id_quedan.toString().padStart(7, '0')
            }}
        </span>

    </div>
    <div class="px-5 pb-9 border border-dashed border-b-black border-x-transparent border-t-transparent">
        <p class="font-medium text-[12pt]">QUEDAN EN NUESTRO PODER PARA TRAMITE DE PAGO LOS SIGUIENTES DOCUMENTOS:</p>

        <table>
            <tr>
                <td class="text-[11pt] font-bold">RECIBO(S) No.</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[450px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]"></div>
                </td>
                <td class="text-[11pt] font-bold">US $:</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[120px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]"></div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">FACTURA(S) No.</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[390px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span v-for="(detalle, i) in dataQuedan.detalle_quedan" :key="i">
                            <template v-if="i != 0 && i != detalle.length"> , </template> <span
                                style="font-family:  Arial, Helvetica, sans-serif;">{{
                                    detalle.numero_factura_det_quedan }}</span>
                        </span>
                    </div>
                </td>
                <td class="text-[11pt] font-bold">POR: US $</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[128px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            totalCheque }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">A FAVOR DE:</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[625px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.proveedor.nombre_comercial_proveedor }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">QUE AMPARA:</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[610px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.descripcion_quedan }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 1">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }} N°</td>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 2">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }} N°</td>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 3">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }} N°</td>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 4">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }}</td>

                <td class="border border-b-black border-x-transparent border-t-transparent w-[200px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.numero_acuerdo_quedan }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">FINANCIAMIENTO</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[170px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.proyecto_financiado.nombre_proy_financiado }}</span>

                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">N° DE COMPROMISO</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[158px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.numero_compromiso_ppto_quedan }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">SAN SALVADOR</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[100px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            (dataQuedan.fecha_emision_quedan).split("-")[2] }}</span>
                    </div>
                </td>
                <td class="text-[11pt] font-bold">DE</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[100px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            (dataQuedan.fecha_emision_quedan).split("-")[1] }}</span>
                    </div>
                </td>
                <td class="text-[11pt] font-bold">DE</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[100px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            (dataQuedan.fecha_emision_quedan).split("-")[0] }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <div class="flex justify-center pt-8">
            <table class="mx-auto">
                <tr>
                    <td class="text-right text-[11pt] font-bold">F.</td>
                    <td class="border border-b-black border-x-transparent border-t-transparent w-[225px]">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]"></div>
                    </td>
                </tr>
                <tr>
                    <td class="h-5 text-[11pt] font-bold">NOMBRE:</td>
                    <td class="border border-b-black border-x-transparent border-t-transparent w-[225px]">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 text-[7pt]">
                            <i>{{ dataQuedan.tesorero.nombre_empleado_tesoreria }}</i>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="h-5"></td>
                    <td class=" w-[225px] text-center">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt] font-semibold">TESORERO INSTITUCIONAL
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
    <div class="mb-5 mt-3 md:flex flex-row justify-between">
        <div class=" basis-1/5 pl-6">
            <img src="../../../img/isri-logo2.png" width="100px">
        </div>
        <div class="md:mx-10 md:mb-0 basis-1/2">
            <h3 style class="text-[15pt] font-semibold text-center">
                INSTITUTO SALVADOREÑO DE REHABILITACIÓN INTEGRAL
            </h3>
            <div style class="text-center">
                COPIA - CONTABILIDAD
            </div>
        </div>
        <div class="md:mb-0 basis-1/4 pr-2">
            <div class="justify-end">
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">Cheque $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right">
                                <span class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                    cheque
                                }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IVA $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right">
                                <span class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                    iva }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">
                            &nbsp;&nbsp;&nbsp;&nbsp;Renta $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right"> <span
                                    class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                        renta }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="mx-auto">
                    <tr>
                        <td class="text-[10pt] text-right" style="font-family: Arial, Helvetica, sans-serif;">
                            &nbsp;&nbsp;&nbsp;&nbsp;TOTAL $</td>
                        <td class="border border-b-black border-x-transparent border-t-transparent w-[75px]">
                            <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right">
                                <span class="font-semibold text-[10pt]" style="font-family: Arial, Helvetica, sans-serif">{{
                                    totalCheque
                                }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="text-center pb-5" style="margin-top: -40px;">
        <span class="text-[15pt] font-bold">QUEDAN N°</span>
        <span class="text-[18pt] pl-2" style="font-family: 'Courier New', Courier, monospace;">
            {{
                dataQuedan.id_quedan.toString().padStart(7, '0')
            }}
        </span>

    </div>
    <div class="px-5">
        <p class="font-medium text-[12pt]">QUEDAN EN NUESTRO PODER PARA TRAMITE DE PAGO LOS SIGUIENTES DOCUMENTOS:</p>

        <table>
            <tr>
                <td class="text-[11pt] font-bold">RECIBO(S) No.</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[450px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]"></div>
                </td>
                <td class="text-[11pt] font-bold">US $:</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[120px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]"></div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">FACTURA(S) No.</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[390px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span v-for="(detalle, i) in dataQuedan.detalle_quedan" :key="i">
                            <template v-if="i != 0 && i != detalle.length"> , </template> <span
                                style="font-family:  Arial, Helvetica, sans-serif;">{{
                                    detalle.numero_factura_det_quedan }}</span>
                        </span>
                    </div>
                </td>
                <td class="text-[11pt] font-bold">POR: US $</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[128px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            totalCheque }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">A FAVOR DE:</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[625px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.proveedor.nombre_comercial_proveedor }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">QUE AMPARA:</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[610px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.descripcion_quedan }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 1">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }} N°</td>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 2">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }} N°</td>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 3">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }} N°</td>
                <td class="text-[11pt] font-bold" v-if="dataQuedan.acuerdo_compra.id_acuerdo_compra === 4">{{
                    dataQuedan.acuerdo_compra.nombre_acuerdo_compra }}</td>

                <td class="border border-b-black border-x-transparent border-t-transparent w-[200px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.numero_acuerdo_quedan }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">FINANCIAMIENTO</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[170px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.proyecto_financiado.nombre_proy_financiado }}</span>

                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">N° DE COMPROMISO</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[158px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            dataQuedan.numero_compromiso_ppto_quedan }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-[11pt] font-bold">SAN SALVADOIR</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[100px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[9pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            (dataQuedan.fecha_emision_quedan).split("-")[2] }}</span>
                    </div>
                </td>
                <td class="text-[11pt] font-bold">DE</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[100px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            (dataQuedan.fecha_emision_quedan).split("-")[1] }}</span>
                    </div>
                </td>
                <td class="text-[11pt] font-bold">DE</td>
                <td class="border border-b-black border-x-transparent border-t-transparent w-[100px]">
                    <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]">
                        <span style="font-family:  Arial, Helvetica, sans-serif;">{{
                            (dataQuedan.fecha_emision_quedan).split("-")[0] }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <div class="flex justify-center pt-8">
            <table class="mx-auto">
                <tr>
                    <td class="text-right text-[11pt] font-bold">F.</td>
                    <td class="border border-b-black border-x-transparent border-t-transparent w-[225px]">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt]"></div>
                    </td>
                </tr>
                <tr>
                    <td class="h-5 text-[11pt] font-bold">NOMBRE:</td>
                    <td class="border border-b-black border-x-transparent border-t-transparent w-[225px]">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 text-[7pt]">
                            <i>{{ dataQuedan.tesorero.nombre_empleado_tesoreria }}</i>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="h-5"></td>
                    <td class=" w-[225px] text-center">
                        <div style="margin-top: -12px;" class="pl-2 pt-2 text-[8pt] font-semibold">TESORERO INSTITUCIONAL
                        </div>
                    </td>
                </tr>
            </table>
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
        renta: {//prop has total information from "quedan"
            type: String,
            required: true,
        },
        iva: {//prop has total information from "quedan"
            type: String,
            required: true,
        },
        cheque: {//prop has total information from "quedan"
            type: String,
            required: true,
        },
    }
}
</script>

<style scoped>
/* Estilos del componente */
* {
    font-family: Georgia, "Times New Roman", Times, serif;
}

.error {
    background-color: rgb(203, 56, 56) !important;
}

.error2 {
    background-color: rgb(191, 141, 15) !important;
}

.checkbox {
    display: flex;
    align-items: center;
}

.checkbox-icon {
    display: inline-block;
    width: 0.75rem;
    height: 0.75rem;
    margin-right: 0.5rem;
    border-color: black;
}

.checkbox-label {
    font-size: 0.75rem;
}
</style>
