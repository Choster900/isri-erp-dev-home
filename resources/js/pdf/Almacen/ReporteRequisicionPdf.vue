<template enter-class="ml-1">
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class="text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class="text-3xl uppercase font-bold" style="font-family: Bell MT, Helvetica, sans-serif">
            REQUISICIONES
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-left flex justify-end">

        <span class="text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
           <!--  DE LA REQUISICION: 0171-2024 -->
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pb-2 text-[8pt] text-left flex justify-between">
        <span class="text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            almacen general
        </span>
        <span class="text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
          <!--   A LA REQUISICION: 0174-2024 -->
        </span>
    </div>
    <div style="margin-top: -12px"
        class="pl-2 pb-2 text-[8pt] text-left flex justify-end border-b-4 border-b-slate-400"></div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <!-- Table header -->
            <thead class="font-semibold uppercase text-slate-700 border-t border-b border-slate-400 *:text-[7pt]">
                <tr>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">codigo</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left w-80"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">producto</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left "
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">marca</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Unidad de medida</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Fecha de vencimiento
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Cantidad
                        </div>
                    </th>
                    <th class="px-1  py-2 w-14" >
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Costo</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">monto</div>
                    </th>
                </tr>
            </thead>
            <tbody v-for="(item, index) in dataRequisicion" :key="index">
                <tr v-show="item.id_tipo_reg_rpt_requisicion == 1">
                    <td colspan="10" class="px-4 font-semibold border-b">

                        <div class=" font-semibold text-left py-1.5 text-sm"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif; ">

                            {{ item.marca_rpt_requisicion }} , {{ item.producto_rpt_requisicion }} ,
                            {{ item.umedida_rpt_requisicion }}
                        </div>
                    </td>
                </tr>
                <tr class="border-b *:text-[8pt] *:text-center *:hover:bg-slate-200"
                    v-show="item.id_tipo_reg_rpt_requisicion == 2">

                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.id_prod_rpt_requisicion }}
                        </div>

                    </td>
                    <td>
                        <div class="text-left py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.producto_rpt_requisicion }}
                        </div>
                    </td>
                    <td class="text-left" style="text-align: left;">
                        <div class="text-left py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.marca_rpt_requisicion }}
                        </div>
                    </td>
                    <td class="text-left" style="text-align: left;">
                        <div class="text-left py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.umedida_rpt_requisicion }}
                        </div>
                    </td>
                    <td class="text-left" style="text-align: left;">
                        <div class="text-left py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.fecha_vcto_rpt_requisicion }}
                        </div>
                    </td>
                    <td class="text-left" style="text-align: left;">
                        <div class="text-left py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.cant_rpt_requisicion }}
                        </div>
                    </td>
                    <td class="text-left" style="text-align: left;">
                        <div class="text-left py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.costo_rpt_requisicion }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: left;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.monto_rpt_requisicion }}
                        </div>
                    </td>
                </tr>
                <tr
                    class="border-b *:text-[8pt] *:text-center *:hover:bg-slate-200"
                    v-show="item.id_tipo_reg_rpt_requisicion == 3">

                    <td colspan="7">
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.producto_rpt_requisicion }}
                        </div>

                    </td>
                    <td>
                        <div class="text-end py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.monto_rpt_requisicion }}
                        </div>
                    </td>

                </tr>
            </tbody>
            <!--  <tbody>
                <tr v-for="(item, index) in dataRequisicion" :key="index" class="border-b *:text-[8pt] *:text-center *:hover:bg-slate-200" v-show="item.id_tipo_reg_rpt_requisicion == 2">

                    <td>
                        <div class="text-center py-2" style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.id_prod_rpt_requisicion }}
                        </div>

                    </td>
                    <td>
                        <div class="text-center py-2" style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.producto_rpt_requisicion }}
                        </div>
                    </td>
                    <td class="text-left" style="text-align: left;">
                        <div class="text-left py-1" style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.marca_rpt_requisicion }}
                        </div>
                    </td>
                </tr>
            </tbody> -->
        </table>
    </div>

</template>

<script>
import moment from "moment";

export default {
    props: ["dataRequisicion", "fecha"],
    setup() {
        return { moment };
    },
};
</script>

<style lang="scss" scoped></style>
