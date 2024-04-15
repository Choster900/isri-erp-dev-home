<template>

    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class=" text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">

            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>

    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class="font-semibold text-[11pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">

            REPORTE MENSUAL FINANCIERO DEL ALMACEN DE BIENES EN EXISTENCIA
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-center">
        <span class=" text-[10pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">

            ALMACEN GENERAL
        </span>
    </div>

    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-right">
        <span class=" text-[9pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            DEL 13, April, 2024
        </span>
    </div>


    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] flex justify-between">
        <span class=" text-[9pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            REPORTE GENERAL
        </span>
        <span class=" text-[9pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            AL 13, April, 2024
        </span>
    </div>


    <table class="px-auto">
        <thead>
            <tr class="*:border-y *:font-medium *:text-[9px] *:text-center">
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt]  ">ESPECIFICO
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt]   w-72">DESCRIPCION
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt]  ">SALDO
                        ANTERIOR</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt]  ">CUENTA
                        CONTABLE</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt]  ">COMPRAS</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">TRANS
                        INGRESO</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">AJUSTE
                        INGRESO</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">CUENTA
                        CONTABLE</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">CONSUMO</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">TRANS
                     EGRESO</div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">AJUSTE SALIDA
                    </div>
                </th>
                <th>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">NUEVO SALDO
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="*:border-y  *:text-xs *:text-center" v-for="(existencia, i) in dataFinanciamiento"
                :key="i">
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">{{
                existencia.codigo_cta_presupuesto_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left">{{
                existencia.nombre_cta_presupuesto_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] "> $
                        {{
                existencia.saldo_inicial_rpt_finaciero
                    ? existencia.saldo_inicial_rpt_finaciero.toLocaleString(
                        "en-US",
                        {
                            style: "currency",
                            currency: "USD",
                        }
                    )
                    : ""
            }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">{{
                    existencia.codigo_cta_gasto_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">${{
                existencia.compra_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] "> ${{
                existencia.transf_ingreso_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] "> ${{
                existencia.ajuste_ingreso_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] "> {{
                existencia.codigo_cta_inversion_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] "> ${{
                existencia.consumo_rpt_finaciero }}</div>
                </td>
                <td>
                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] "> ${{
                existencia.transf_egreso_rpt_finaciero }}</div>
                </td>
                <td>

                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">${{
                        existencia.ajuste_egreso_rpt_finaciero }}</div>
                </td>
                <td>

                    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] ">${{
                        existencia.saldo_actual_rpt_finaciero }}</div>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- {{ dataFinanciamiento }} -->
</template>

<script>
export default {
    props: ["dataFinanciamiento"],
    setup() {


        return {}
    }
}
</script>

<style lang="scss" scoped></style>
