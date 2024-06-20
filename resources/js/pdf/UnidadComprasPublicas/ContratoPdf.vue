<template>
    <table class="w-full sheet0" border="0" cellpadding="0" cellspacing="0">
        <col class="col0" />
        <col class="col1" />
        <col class="col2" />
        <col class="col3" />
        <col class="col4" />
        <col class="col5" />
        <col class="col6" />
        <col class="col7" />
        <tbody>
            <tr>
                <td class="border border-black w-40" rowspan="2">
                    <div class="w-40 p-1.5">
                        <img src="../../../img/isri-gob.png" alt="logotipo-isri" draggable="false" />
                    </div>
                </td>
                <td class="border text-sm border-black text-center font-medium uppercase" colspan="3">
                    <div class="relative text-center text-sm">
                        UCP DEL INSITUTO SALVADOREÑO DE REHABILITACION INTEGRAL
                    </div>
                </td>
            </tr>
            <tr>
                <td class="border text-sm border-black text-center font-medium px-8" colspan="3">
                    <div class="flex items-center justify-center gap-1">
                        <div class="relative text-center text-sm">
                            ORDEN DE COMPRA DE BIENES Y SERVICIOS
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="border border-black bg-black h-5 border-r-white text-white text-center text-[9pt]"
                    colspan="3">
                    <div style="margin-top: -9px">RAZON SOCIAL DEL SUMINISTRANTE</div>
                </td>
                <td class="border border-black bg-black h-5 text-white text-center text-[9pt]">
                    <div style="margin-top: -9px">

                        {{
                            arrayDocAdquisicion != '' && idDetDocAdquisicion != null &&
                                arrayDocAdquisicion.find(index => index.value == idDetDocAdquisicion) &&
                                arrayDocAdquisicion.find(index => index.value == idDetDocAdquisicion).dataDoc &&
                                (
                                    arrayDocAdquisicion.find(index => index.value ==
                                        idDetDocAdquisicion).dataDoc.documento_adquisicion.proveedor.nit_proveedor !== null ||
                                    arrayDocAdquisicion.find(index => index.value ==
                                        idDetDocAdquisicion).dataDoc.documento_adquisicion.proveedor.dui_proveedor !== null
                                )
                                ? (
                                    arrayDocAdquisicion.find(index => index.value ==
                                        idDetDocAdquisicion).dataDoc.documento_adquisicion.proveedor.nit_proveedor
                                        ? "NIT"
                                        : "DUI"
                                )
                                : ''
                        }}

                    </div>
                </td>
            </tr>
            <tr>
                <td class="border border-black h-5" colspan="3">
                    <h1 class="text-xs font-medium text-center" style="margin-top: -9px">
                        {{
                                arrayDocAdquisicion != "" &&
                                    idDetDocAdquisicion != null &&
                                    arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion) &&
                                    arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                        .dataDoc &&
                                    arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                        .dataDoc.documento_adquisicion.proveedor.razon_social_proveedor
                                    ? arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                        .dataDoc.documento_adquisicion.proveedor.razon_social_proveedor
                                    : ""
                            }}
                    </h1>
                </td>
                <td class="border border-black h-5 text-center text-[9pt]">
                    <h1 class="text-xs font-medium text-center" style="margin-top: -9px">
                        {{
                                arrayDocAdquisicion != "" &&
                                    idDetDocAdquisicion != null &&
                                    arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion) &&
                                    arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                        .dataDoc &&
                                    (arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                        .dataDoc.documento_adquisicion.proveedor.nit_proveedor !== null ||
                                        arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                            .dataDoc.documento_adquisicion.proveedor.dui_proveedor !== null)
                                    ? arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                        .dataDoc.documento_adquisicion.proveedor.nit_proveedor ||
                                    arrayDocAdquisicion.find((index) => index.value == idDetDocAdquisicion)
                                        .dataDoc.documento_adquisicion.proveedor.dui_proveedor
                                    : ""
                            }}
                    </h1>
                </td>
            </tr>
        </tbody>
    </table>
    <div id="especificaciones" class="mb-4">
        <div class="flex items-center justify-between pt-4">
            <div class="flex items-center">
                <label for="" class="text-[7pt] font-semibold mr-2">LUGAR Y FECHA: </label>

                <div
                    class="w-96 text-[7pt] font-medium uppercase  h-5 border-x-0 border-t-0 border-b border-black text-start">
                    <div class="" style="margin-top: -2px">
                        {{ `SAN SALVADOR
                        ${moment(objectGetFromProp.fecha_reg_det_doc_adquisicion)
                                .format('D [de] MMMM [de] YYYY')}`
                        }}
                    </div>
                </div>
            </div>
            <div class="flex items-center">
                <label for="" class="text-[7pt] font-semibold mr-2">N°.ORDEN:</label>

                <div
                    class="w-40 text-[7pt] font-medium uppercase  h-5 border-x-0 border-t-0 border-b border-black text-start">
                    <div class="" style="margin-top: -2px">
                        {{ ((arrayDocAdquisicion.find(index => index.value == idDetDocAdquisicion) || {}).dataDoc ||
                            {}).documento_adquisicion?.numero_doc_adquisicion || '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="w-full" border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr class="*:text-[7pt] *:bg-black *:text-white *:px-2 *:py-0.5 *:font-normal *:border--white">
                <th class="border border-black border-r-white border-b-white h-8 w-[150px]">
                    <div style="margin-top: -9px">
                        PRODUCTO
                    </div>
                </th>
                <th class="border border-black border-r-white border-b-white w-[80px]">
                    <div style="margin-top: -9px">
                        MARCA
                    </div>
                </th>
                <th class="border border-black border-r-white w-[150px] border-b-white" colspan="2">
                    <div style="margin-top: -9px">
                        DESCRIPCION
                    </div>
                </th>
                <th class="border border-black border-r-white w-10 border-b-white">
                    <div style="margin-top: -9px">
                        DEPENDENCIA
                    </div>
                </th>
                <th class="border border-black border-r-white w-10 border-b-white">
                    <div style="margin-top: -9px">
                        CANTIDAD
                    </div>
                </th>
                <th class="border border-black border-r-white w-10 border-b-white">
                    <div style="margin-top: -9px">
                        UNITARIO
                    </div>
                </th>
                <th class="border border-black border-b-white ">
                    <div style="margin-top: -9px">
                        VALOR TOTAL
                    </div>
                </th>
                <th class="border border-black border-b-white border-l-white w-[300px]">
                    <div style="margin-top: -9px">
                        DISTRIBUCION MENSUAL DEL PRODUCTO <br> ( SOLO NUMEROS )

                    </div>
                </th>
            </tr>
        </thead>
        <tbody v-for="(docAdq, i) in arrayProductoAdquisicion" :key="i">
            <tr class="" style="page-break-inside: avoid;">
                <td colspan="4"
                    class="uppercase border py-1 bg-black text-[8pt] text-white border-black border-r-white  text-center ">
                    <div style="margin-top: -10px">
                        Linea de trabajo:
                    </div>
                </td>

                <td colspan="5" class="uppercase border py-1 bg-black text-[8pt] text-white border-black text-center ">
                    <div style="margin-top: -10px">
                        Documento de adquisicion:
                    </div>
                </td>

            </tr>
            <tr class="" style="page-break-inside: avoid;">

                <td colspan="4" class="border border-y-black border-l-black border-r-black">
                    <div style="margin-top: -4px" class="text-[6pt] pb-2 px-1">
                        {{ arrayLineaTrabajo.find(d => d.value === docAdq.idLt)?.label }}
                    </div>
                </td>

                <td colspan="5" class="border border-y-black border-l-0 border-r-black">
                    <div style="margin-top: -4px" class="text-[6pt] pb-2 px-1   ">
                        {{ arrayDocAdquisicion.find(d => d.value === idDetDocAdquisicion)?.label ||
                            '' }}
                    </div>

                </td>
            </tr>
            <tr style="page-break-inside: avoid;"
                class="*:px-2 *:pb-2 *:font-normal *:border *:border-black *:border-l-0" :class="{
                            '*:border-b-1': j + 1 != docAdq.detalleDoc.length,
                            '*:border-t-0': j == 0
                        }" v-for="(detalle, j) in docAdq.detalleDoc" :key="j">
                <td class="relative  align-top" style="border-left: 1px solid black !important;">
                    <div class="text-[6pt]  text-center">
                        {{ detalle.detalleProducto }}
                    </div>
                </td>
                <td class="relative w-[80px] align-top">
                    <div class="text-[6pt]  text-center">
                        {{ arrayMarca.find(d => d.value === detalle.idMarca).label }}
                    </div>
                </td>
                <td class="relative  uppercase align-top" colspan="2">
                    <div class="text-[6pt] pb-2 text-center">
                        {{ detalle.descripcionProdAdquisicion }}
                    </div>
                </td>
                <td class="relative w-12  align-top">

                    <div class="text-[6pt]  text-center">
                        {{ arrayCentroAtencion.
                            find(d => d.value === detalle.idCentroAtencion)
                            .label }}
                    </div>
                </td>

                <td class=" w-12 relative align-top">
                    <div class="text-[6pt]  text-center">
                        {{ detalle.cantProdAdquisicion }}
                    </div>

                </td>

                <td class=" relative text-center align-top">
                    <div class="text-[6pt]  text-center">
                        ${{ detalle.costoProdAdquisicion }}
                    </div>
                </td>
                <td class="relative text-center w-20 align-top ">
                    <div class="text-[6pt]  text-center">
                        ${{ detalle.valorTotalProduct !== undefined ?
                            detalle.valorTotalProduct.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : '00.00' }}
                    </div>
                </td>
                <td class="relative p-0 bg-white" style="padding-left: 0 !important; padding-right: 0 !important; ">
                    <div class="h-28     w-full">

                        <div class="flex *:border-slate-300 *:border hover:bg-slate-200 *:text-[6pt]">
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px" class="pb-1">
                                    Enero
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px">
                                    Marzo
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px">
                                    Mayo
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px">
                                    Julio
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px">
                                    Septmbre
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px">
                                    Nomvbre
                                </div>
                            </div>
                        </div>
                        <div class="flex *:border-slate-300 *:border *:h-[46.5px] hover:bg-slate-200 *:text-[6pt]">
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.January }}
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.March }}
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.May }}

                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.July }}

                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.September }}

                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.November }}

                                </div>
                            </div>
                        </div>
                        <div class="flex *:border-slate-300 *:border hover:bg-slate-200 *:text-[6pt]">
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px" class="pb-1">
                                    Febrero
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px" class="pb-1">
                                    Abril
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px" class="pb-1">
                                    Junio
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px" class="pb-1">
                                    Agosto
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px" class="pb-1">
                                    Octubre
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-top: -6px" class="pb-1">
                                    Dicmbre
                                </div>
                            </div>
                        </div>
                        <div class="flex *:border-slate-300 *:border *:h-[46.5px] hover:bg-slate-200 *:text-[6pt]">
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.February }}
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.April }}
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.June }}
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.August }}
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.October }}
                                </div>
                            </div>
                            <div class="w-1/6 text-center">
                                <div style="margin-bottom: -10px h-full">

                                    <br>
                                    {{ detalle.amountsPerMonthList.December }}
                                </div>
                            </div>
                        </div>
                    </div>




                </td>
            </tr>
        </tbody>
        <tbody style="page-break-inside: avoid;">
            <tr class="" style="page-break-inside: avoid;">
                <td colspan="7"
                    class="px-1 uppercase border py-1  text-[8pt]  border-l-black border-t-black border-r-0 border-b-0   text-start ">
                    <div style="margin-top: -10px">
                        total
                    </div>
                </td>

                <td colspan="2"
                    class="uppercase border py-1 text-[8pt]  border-b-0 border-l-0 border-t-black border-r-black  text-center ">
                    <div style="margin-top: -10px">
                        $ {{ totProductos }}
                    </div>
                </td>
                <!-- <td colspan="1"
                    class="uppercase border py-1 text-[8pt]  border-b-0 border-l-0 border-t-black border-r-black  text-center ">
                    <div style="margin-top: -10px">
                        -
                    </div>
                </td> -->
            </tr>
            <tr class="" style="page-break-inside: avoid;">
                <td colspan="9"
                    class="uppercase border py-1  text-[8pt] font-bold border-x-black border-t-black border-r-black border-b-black text-center ">
                    <div style="margin-top: -10px">
                        SON: {{ letterNumber }}
                    </div>
                </td>
            </tr>
            <!-- !NUEVO -->
            <tr class="" style="page-break-inside: avoid;">
                <td colspan="9"
                    class="uppercase relative  align-top border py-1 h-20 text-[7pt] px-1  border-x-black border-t-black border-t-0 border-r-black border-b-black text-start ">
                    <div style="margin-top: -10px">
                        {{ observacionDetDocAdquisicion }}
                    </div>
                </td>
            </tr>
            <tr class="" style="page-break-inside: avoid;">
                <td colspan="9"
                    class="uppercase border py-1  relative h-8 align-top text-[7pt] px-1 border-x-black border-t-black border-t-0 border-r-black border-b-black text-start ">
                    <div style="margin-top: -10px">
                        {{ recepcionDetDocAdquisicion }}
                    </div>
                </td>
            </tr>
            <tr class="" style="page-break-inside: avoid;">
                <td colspan="9"
                    class="uppercase border py-1 h-14 relative  align-top text-[7pt] px-1 border-x-black border-t-black border-t-0 border-r-black border-b-black text-start ">
                    <div style="margin-top: -10px">
                        {{ notificacionDetDocAdquisicion }}
                    </div>
                </td>
            </tr>

        </tbody>
    </table>
    <!-- ! FIRMA  -->
    <table class="w-full">
        <tr class="" style="page-break-inside: avoid;">
            <td colspan="5"
                class="uppercase border py-1 h-24 relative  align-bottom text-[7pt] px-1 border-x-black border-t-black border-t-0 border-r-black border-b-black text-center ">


                <div class="flex flex-col items-center justify-center" style="margin-top: -20px">
                    <hr class="w-[50%] border-1 border-black">
                    <span class="block">titular o designado</span>
                </div>
            </td>
            <td colspan="5"
                class="uppercase border py-1 h-24 relative  align-top text-[7pt] px-1 border-x-black border-t-black border-t-0 border-r-black border-b-black text-start ">
                <div style="margin-top: -10px" class="text-white">
                    titular o designado <!-- no se mostrara en el pdf pero es para que no se modifique el estilo -->
                </div>
            </td>
        </tr>
        <tr class="" style="page-break-inside: avoid;">
            <td colspan="5"
                class="uppercase border py-1 h-24 relative  align-bottom text-[7pt] px-1 border-x-black border-t-black border-r-black border-b-black text-start ">
                <div class="flex flex-col items-center justify-center" style="margin-top: -20px">
                    <hr class="w-[50%] border-1 border-black">
                    <!--  <span class="block">titular o designado</span> -->
                </div>
            </td>
            <td colspan="5"
                class="uppercase border py-1 h-24 relative  align-bottom text-[7pt] px-1 border-x-black border-t-black  border-r-black border-b-black text-start ">
                <div class="flex flex-col items-center justify-center" style="margin-top: -20px">
                    <hr class="w-[50%] border-1 border-black">
                    <span class="block">Suministrante</span>
                </div>
            </td>
        </tr>
    </table>
</template>

<script>
import CalendarAcuerdoComponent from "@/Components-ISRI/UCP/BienesServicios/CalendarAcuerdoComponent.vue";
import moment from "moment";

export default {
    components: { CalendarAcuerdoComponent },
    props: {
        arrayDocAdquisicion: {
            type: Object,
            required: true,
        },
        idDetDocAdquisicion: {
            type: Object,
            default: () => { },
            required: true,
        },
        objectGetFromProp: {
            type: Object,
            default: () => { },
            required: true,
        },
        arrayLineaTrabajo: {
            type: Object,
            default: () => { },
            required: true,
        },
        arrayProductoAdquisicion: {
            type: Object,
            default: () => { },
            required: true,
        },
        arrayMarca: {
            type: Object,
            default: () => { },
            required: true,
        },
        arrayUnidadMedida: {
            type: Object,
            default: () => { },
            required: true,
        },
        arrayCentroAtencion: {
            type: Object,
            default: () => { },
            required: true,
        },
        notificacionDetDocAdquisicion: {
            type: Object,
            default: () => { },
            required: true,
        },
        observacionDetDocAdquisicion: {
            type: Object,
            default: () => { },
            required: true,
        },
        recepcionDetDocAdquisicion: {
            type: Object,
            default: () => { },
            required: true,
        },
        letterNumber: {
            type: Object,
            default: () => { },
            required: true,
        },
        totProductos: {
            type: Object,
            default: () => { },
            required: true,
        },
    },
    setup() {
        return {
            moment,
        };
    },
};
</script>

<style></style>
