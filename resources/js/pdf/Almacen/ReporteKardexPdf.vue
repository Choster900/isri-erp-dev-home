<template enter-class="ml-1">
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class="text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>
    <div style="margin-top: -12px" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class="text-3xl uppercase font-bold" style="font-family: Bell MT, Helvetica, sans-serif">
            REPORTE TARJETA KARDEX
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
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">FECHA</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left w-80"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">DETALLE</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center "
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">TIPO DE INGRESO</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">CENTRO DE ANTECION</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">CANTIDAD DE ENTRADA
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">COSTO DE ENTRADA
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">MONTO DE ENTRADA
                        </div>
                    </th>
                    <th class="px-1  py-2 w-14" >
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">CANTIDAD DE SALIDA</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">COSTO DE SALIDA</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">MONTO DE SALIDA</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">CANTIDAD FINAL</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">COSTO FINAL</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">MONTO FINAL</div>
                    </th>
                </tr>
            </thead>
            <tbody v-for="(item, index) in dataKardex" :key="index">

                <tr class="border-b *:text-[8pt] *:text-center *:hover:bg-slate-200"
                  >

                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.fecha }}
                        </div>

                    </td>
                    <td>
                        <div class="text-left py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.referencia_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.tipo_mov_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.centro_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.cant_entrada_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.costo_entrada_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.monto_entrada_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-center" style="text-align: center;">
                        <div class="text-center py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.cant_salida_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.costo_salida_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.monto_salida_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.cant_final_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.costo_final_rpt_kardex }}
                        </div>
                    </td>
                    <td class="text-end" style="text-align: center;">
                        <div class="text-end py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.monto_final_rpt_kardex }}
                        </div>
                    </td>

                </tr>

            </tbody>

        </table>
    </div>

    <!-- {{ dataKardex }} -->

</template>

<script>
import moment from "moment";

export default {
    props: ["dataKardex", "fecha"],
    setup() {
        return { moment };
    },
};
</script>

<style lang="scss" scoped></style>
