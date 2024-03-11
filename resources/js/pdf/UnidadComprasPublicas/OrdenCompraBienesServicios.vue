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
                    <div style="margin-top: -9px">NIT</div>
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
                    class="w-96 text-[7pt] font-medium uppercase  h-5 border-x-0 border-t-0 border-b border-black text-center">
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
                    class="w-40 text-[7pt] font-medium uppercase  h-5 border-x-0 border-t-0 border-b border-black text-center">
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
            <tr class="*:text-[8pt] *:bg-black *:text-white *:px-2 *:py-0.5 *:font-normal *:border--white">
                <th class="border border-black border-r-white border-b-white h-9 w-[100px]">
                    <div style="margin-top: -9px">
                        PRODUCTO
                    </div>
                </th>
                <th class="border border-black border-r-white border-b-white w-[80px]">
                    <div style="margin-top: -9px">
                        MARCA
                    </div>
                </th>
                <th class="border border-black border-r-white w-32 border-b-white" colspan="2">
                    <div style="margin-top: -9px">
                        DESCRIPCION
                    </div>
                </th>
                <th class="border border-black border-r-white border-b-white  w-10" colspan="2">
                    <div style="margin-top: -9px">
                        U/MEDIDAS
                    </div>

                </th>
                <th class="border border-black border-r-white w-10 border-b-white">
                    <div style="margin-top: -9px">
                        ESPECIFICO
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
            </tr>
        </thead>
        <tbody v-for="(docAdq, i) in arrayProductoAdquisicion" :key="i">
            <tr class="*:border-black cursor-pointer">
                <td colspan="2"
                    class="uppercase border bg-black text-[8pt] text-white border-black border-t-white text-center text-selection-disable">
                    <div style="margin-top: -9px">
                        Linea de trabajo:
                    </div>
                </td>
                <td colspan="4" class="border border-t-black ">
                    <div style="margin-top: -4px" class="text-[7pt] pb-2 px-0.5">
                        <!--  {{ idLt }} -->
                        ATENCIONES DE REHABILTACION INTEGRAL A NIÑOS (AS) CON ALGUNA CONDICION DE DISCAPACIDAD
                        <!-- {{ arrayLineaTrabajo }} -->
                    </div>
                </td>
                <td colspan="2"
                    class="uppercase border bg-black text-[8pt] text-white border-black text-center text-selection-disable">
                    <div style="margin-top: -9px">
                        Doc. Adquisición:
                    </div>
                </td>
                <td colspan="4" class="border border-black">
                    <div style="margin-top: -4px" class="text-[7pt] pb-2 px-1   ">
                        <!-- {{ idDetDocAdquisicion }} -->
                        {{ arrayDocAdquisicion.find(d => d.value === idDetDocAdquisicion)?.label ||
                            '' }}
                        <!-- SUMINISTRO DE INSUMOS PARA LA PREPARACION DE ALIMENTOS PARA PERSONAS DE LAS DIFERENTES DEPENDENCIAS DEL ISRI -->
                    </div>

                </td>
            </tr>
            <tr class="*:text-[8pt]  *:px-2 *:py-0.5 *:font-normal *:border *:border-black  cursor-pointer"
                v-for="(detalle, j) in docAdq.detalleDoc" :key="j">
                <td class="relative h-20 w-[100px]" style="padding-left: 0 !important; padding-right: 0 !important; ">

                    <div style="margin-top: -42px" class="text-[7pt] pb-2 text-center">
                        {{ detalle.detalleProducto }}
                    </div>
                </td>
                <td class="relative h-20 w-[100px]" style="padding-left: 0 !important; padding-right: 0 !important; ">

                    <div style="margin-top: -42px" class="text-[7pt] pb-2 text-center">
                        {{ arrayMarca.find(d => d.value === detalle.idMarca).label }}
                    </div>
                </td>

            </tr>
        </tbody>
    </table>
</template>

<script>
import moment from "moment";

export default {
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

    },
    setup() {
        return {
            moment,
        };
    },
};
</script>

<style></style>
