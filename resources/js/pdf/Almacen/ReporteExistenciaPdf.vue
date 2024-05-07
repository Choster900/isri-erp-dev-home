<template enter-class="ml-1">
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class=" text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class=" text-3xl uppercase font-bold" style="font-family: Bell MT, Helvetica, sans-serif">
            INVENTARIO PARA EL LEVANTAMIENTO FISICO
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left flex justify-between">
        <span class=" text-[8pt] uppercase " style="font-family: Arial, Helvetica, sans-serif">
            almacen general
        </span>
        <span class=" text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Del 12 ABRIL 2024
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2  pb-2 text-[8pt] text-left flex justify-between">
        <span class=" text-lg  uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Productos
        </span>
        <span class=" text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Al 54 ABRIL 2024<!--  {{ moment(fecha.fechaHasta).format('DD, MMMM, YYYY') }} -->
        </span>
    </div>
    <div style="margin-top: -12px;"
        class="pl-2  pb-2 text-[8pt] text-left flex justify-end border-b-4 border-b-slate-400">
        <span class=" text-base uppercase font-medium" style="font-family: Arial, Helvetica, sans-serif">
            PROYECTO FINANCIADO
        </span>
    </div>


    <table class=" w-full pl-2 mt-1 pb-3  ">
        <thead>
            <tr class="text-[7pt] uppercase *:border-b">
                <th class="w-14">
                    <div class="py-2 text-center" style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        PROYECTO FINANCIADO
                    </div>
                </th>
                <th class="max-w-[350px]">
                    <div class="text-left w-[350px] "
                        style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        PRODUCTOS
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        ESPECIFICO
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        MARCA
                    </div>
                </th>
                <th class="">
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        UNIDAD DE MEDIDA
                    </div>
                </th>
                <th class="">
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        CENTRO ATENCION
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        LINEA DE TRABAJO
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        CANTIDAD EXISTENCIA
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        COSTO
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        FECHA VENCIMIENTO
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                        SUB ALMACEN
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="*:text-[8pt] *:uppercase *:border-b *:pb-2" v-for="(item, index) in dataReporteExistencia" :key="index">
                <td class="text-center w-14">{{ item.codigo_proy_rpt_existencia }}</td>
                <td class="text-left max-w-[300px]">{{ item.nombre_prod_rpt_existencia }}</td>
                <td class="text-center  w-[50px] max-w-[50px]">{{ item.id_ccta_presupuesto_rpt_existencia }}</td>
                <td class="text-center">{{ item.marca_rpt_existencia }}</td>
                <td class="text-center  w-[75px] max-w-[75px]">{{ item.nombre_umedida_rpt_existencia }}</td>
                <td class="text-center  w-[75px] max-w-[75px]">{{ item.codigo_centro_rpt_existencia }}</td>
                <td class="text-center  w-[60px] max-w-[60px]">{{ item.codigo_lt_rpt_existencia }}</td>
                <td class="text-center  w-[75px] max-w-[75px] ">{{ item.cant_rpt_existencia }}</td>
                <td class="text-center  w-[75px] max-w-[75px]">{{ item.costo_rpt_existencia }}</td>
                <td class="text-center  w-[75px] max-w-[75px]">{{ item.fecha_vcto_rpt_existencia }}</td>
                <td class="text-center  w-[75px] max-w-[75px]">{{ item.id_prod_rpt_existencia }}</td>
            </tr>
        </tbody>
    </table>

</template>
<script>
export default {
    props: ["dataReporteExistencia"],
    setup() {


        return {}
    }
}
</script>

<style lang="scss" scoped></style>
