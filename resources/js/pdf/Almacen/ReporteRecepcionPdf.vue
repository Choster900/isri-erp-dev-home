<template enter-class="ml-1">
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class="text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class="text-3xl uppercase font-bold" style="font-family: Bell MT, Helvetica, sans-serif">
            ACTA DE RECEPCIÓN
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-center flex justify-end">

        <span class="text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
           <!--  DE LA REQUISICION: 0171-2024 -->
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pb-2 text-[8pt] text-center flex justify-between">
        <span class="text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            <!-- almacen general -->
        </span>
        <span class="text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
          <!--   A LA REQUISICION: 0174-2024 -->
        </span>
    </div>
    <div
        class="mt-2 pl-2 pb-2 text-[8pt] text-center flex justify-end border-b-4 border-b-slate-400"></div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <!-- Table header -->
            <thead class="font-semibold uppercase text-slate-700 border-t border-b border-slate-400 *:text-[7pt]">
                <tr>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Codigo</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left w-80"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Producto</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center "
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Marca</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Unidad medida</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Centro
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Codigo Uplt
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Cantidad compra
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
            <tbody v-for="(item, index) in dataRecepcion" :key="index">
                <tr v-show="item.id_tipo_reg_rpt_compra == 1">
                    <td colspan="9" class="px-1 font-semibold border-y border-black">

                        <div class=" font-semibold text-left py-1.5 text-sm"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif; ">

                            {{ item.producto_rpt_compra }} -
                                    {{ item.marca_rpt_compra }} -
                                    {{ item.umedida_rpt_compra }} -
                                    {{ item.fecha_vcto_rpt_compra }}
                        </div>
                    </td>
                </tr>
                <tr class="border-b *:text-[8pt] *:text-center *:hover:bg-slate-200"
                    v-show="item.id_tipo_reg_rpt_compra == 2">

                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.id_prod_rpt_compra }}
                        </div>

                    </td>
                    <td>
                        <div class="text-left py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.producto_rpt_compra }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.marca_rpt_compra }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.umedida_rpt_compra }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.codigo_centro_rpt_compra }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.codigo_uplt_rpt_compra }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.cant_rpt_compra }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.costo_rpt_compra }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.monto_rpt_compra }}
                        </div>
                    </td>
                </tr>
                <tr
                    class="border-b *:text-[8pt] *:text-center *:hover:bg-slate-200"
                    v-show="item.id_tipo_reg_rpt_compra == 3">

                    <td colspan="8">
                        <div class="text-left py-2 px-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.producto_rpt_compra }}
                        </div>

                    </td>
                    <td>
                        <div class="text-end py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.monto_rpt_compra }}
                        </div>
                    </td>

                </tr>
            </tbody>

        </table>
    </div>

    <!-- {{ dataRecepcion }} -->

</template>

<script>
import moment from "moment";

export default {
    props: ["dataRecepcion", "fecha"],
    setup() {
        return { moment };
    },
};
</script>

<style lang="scss" scoped></style>
