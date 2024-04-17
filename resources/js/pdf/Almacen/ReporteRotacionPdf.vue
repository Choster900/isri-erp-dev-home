<template>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class=" text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class=" text-3xl uppercase font-bold" style="font-family: Bell MT, Helvetica, sans-serif">
            INFORME ANUAL BIENES DE POCAS ROTACION
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left flex justify-end">
        <span class=" text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Del 01, NOVIEMBRE, 2022
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2  pb-2 text-[8pt] text-left flex justify-end">
        <span class=" text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Al 16, ABRIL, 2024
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <!-- Table header -->
            <thead class="font-semibold uppercase text-slate-700 border-t border-b border-slate-400 *:text-[7pt]">
                <tr>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Dependencia</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">cod</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-left w-96"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Concepto</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Existencia Al Inicio
                            del Periodo</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Rotacion Ideal (0.80
                            Exist.Ini.)</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Salida del periodo
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Diferencia</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Existencia al final
                            del periodo</div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Costo Unita. Aproxima
                        </div>
                    </th>
                    <th class="px-1  py-2">
                        <div class="font-semibold text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">Monto Aproxima</div>
                    </th>

                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataReporteInfo" :key="index" v-show="item.id_tipo_reg_rpt_rotacion == 1">
                    <td colspan="10" class="px-4 font-semibold border-b">

                        <div class=" font-semibold text-left py-1.5"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.id_ccta_presupuesto_rpt_rotacion }} - {{ item.nombre_prod_rpt_rotacion }}
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr v-for="(item, index) in dataReporteInfo" :key="index"
                    class="border-b *:text-[8pt] *:text-center *:hover:bg-slate-200"
                    v-show="item.id_tipo_reg_rpt_rotacion == 2">

                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">

                            {{ item.sigla_centro_rpt_rotacion }}
                        </div>

                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.id_prod_rpt_rotacion }}
                        </div>
                    </td>
                    <td class="text-left" style="text-align: left;">
                        <div class="text-left py-1"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.nombre_prod_rpt_rotacion }}
                        </div>
                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.existencia_inicial_rtp_rotacion }}
                        </div>
                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.rotacion_ideal_rtp_rotacion }}
                        </div>
                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.rotacion_real_rtp_rotacion }}
                        </div>
                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.diferencia_rtp_rotacion || '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.existencia_final_rtp_rotacion }}
                        </div>
                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            {{ item.costo_aprox_rtp_rotacion || '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="text-center py-2"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            ${{ item.saldo_aprox_rtp_rotacion }}
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr v-for="(item, index) in dataReporteInfo" :key="index" v-show="item.id_tipo_reg_rpt_rotacion == 3">
                    <td colspan="10" class="px-4 font-bold">
                        54101-PRODUCTOS ALIMENTICIOS PARA PERSONAS
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "ReporteRotacionPdf",
    props: ["dataReporteInfo"],
    setup() {

        return {};
    },
};
</script>

<style lang="scss" scoped></style>
