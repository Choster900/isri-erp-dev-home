<template enter-class="ml-1">
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class="text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class="text-3xl uppercase font-bold" style="font-family: Bell MT, Helvetica, sans-serif">
            REPORTE DE GASTOS O CONSUMO PERC
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
            <thead v-for="(item, index) in dataPerc" :key="index" class="font-semibold uppercase text-slate-700 border-t border-b border-slate-400 *:text-[6pt]">
                <tr v-if="item.id_tipo_reg_rpt_perc === 0">
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{item.codigo_centro_prod_rpt_perc}}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left w-20"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{item.nombre_centro_prod_rpt_perc}}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center "
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.tipo_rpt_perc }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{  item.total_rpt_perc }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_1_rpt_perc }}
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_2_rpt_perc }}
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{  item.col_3_rpt_perc }}
                        </div>
                    </th>
                    <th class="px-1  py-2 w-14" >
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_4_rpt_perc }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_5_rpt_perc }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_6_rpt_perc }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{item.col_7_rpt_perc}}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_8_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_9_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_10_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_11_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_12_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_13_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_14_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_15_rpt_perc  }}</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center  w-10 break-all"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">{{ item.col_16_rpt_perc  }}</div>
                    </th>
                </tr>
            </thead>
            <tbody v-for="(item, index) in dataPerc" :key="index">

                <tr class="border-b *:text-[7pt] *:text-center *:hover:bg-slate-200 "
                v-if="item.id_tipo_reg_rpt_perc === 1">

                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.codigo_centro_prod_rpt_perc }}
                        </div>

                    </td>
                    <td>
                        <div class="text-left py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.nombre_centro_prod_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.tipo_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.total_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.col_1_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.col_2_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.col_3_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_4_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_5_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_6_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_7_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_8_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_9_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_10_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_11_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_12_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_13_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_14_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_15_rpt_perc }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.col_16_rpt_perc }}
                        </div>
                    </td>
                </tr>

            </tbody>

        </table>
    </div>

    <!-- {{ dataPerc }} -->

</template>

<script>
import moment from "moment";

export default {
    props: ["dataPerc", "fecha"],
    setup() {
        return { moment };
    },
};
</script>

<style lang="scss" scoped></style>
