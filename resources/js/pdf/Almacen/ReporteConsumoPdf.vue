<template enter-class="ml-1">
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class=" text-[7pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            sistema de almacen para el control de bienes en existencia - isri
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left">
        <span class=" text-3xl uppercase font-bold" style="font-family: Bell MT, Helvetica, sans-serif">
            consumo por programa
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2 pt-2 pb-2 text-[8pt] text-left flex justify-between">
        <span class=" text-[8pt] uppercase " style="font-family: Arial, Helvetica, sans-serif">
            almacen general
        </span>
        <span class=" text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Del {{ moment(fecha.fechaDesde).format('DD, MMMM, YYYY') }}
        </span>
    </div>
    <div style="margin-top: -12px;" class="pl-2  pb-2 text-[8pt] text-left flex justify-between">
        <span class=" text-lg  uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Productos
        </span>
        <span class=" text-[8pt] uppercase" style="font-family: Arial, Helvetica, sans-serif">
            Al {{ moment(fecha.fechaHasta).format('DD, MMMM, YYYY') }}
        </span>
    </div>
    <div style="margin-top: -12px;"
        class="pl-2  pb-2 text-[8pt] text-left flex justify-end border-b-4 border-b-slate-400">
        <span class=" text-base uppercase font-medium" style="font-family: Arial, Helvetica, sans-serif">
            fondo general
        </span>
    </div>

    <template v-for="(item, index) in dataConsumo" :key="index">

        <div style="margin-top: -12px;" class="pl-2 pt-2  pb-2 text-[8pt] text-start"
            v-show="item.id_tipo_reg_rpt_consumo == 0">
            <span class=" text-base uppercase font-bold" style="font-family: Arial, Helvetica, sans-serif">
                PROGRAMA: ADMON - {{ item.nombre_prod_rpt_consumo  }}
            </span>
        </div>
        <div style="margin-top: -12px;" class="pl-2 pt-2  pb-2 text-[8pt] text-start"
            v-show="item.id_tipo_reg_rpt_consumo == 1">
            <span class=" text-sm uppercase " style="font-family: Arial, Helvetica, sans-serif">
                {{  item.id_ccta_presupuesto_rpt_consumo }} - {{ item.nombre_prod_rpt_consumo  }}
            </span>
        </div>

        <table class=" w-full pl-2 mt-1 pb-3 text-[8pt] text-start  border-2 border-slate-300"
            v-show="item.id_tipo_reg_rpt_consumo == 1">
            <thead>
                <tr class="text-xs uppercase">
                    <th class="w-14">
                        <div class="py-2 text-center"
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            CODIGO
                        </div>
                    </th>
                    <th class="max-w-[350px]">
                        <div class="text-left w-[350px] "
                            style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            DESCRIPCIÓN
                        </div>
                    </th>
                    <th>
                        <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            MARCA
                        </div>
                    </th>
                    <th>
                        <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            UNIDAD
                        </div>
                    </th>
                    <th class="">
                        <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            NUMERO
                        </div>
                    </th>
                    <th class="">
                        <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            FECHA
                        </div>
                    </th>
                    <th>
                        <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            CANTIDAD
                        </div>
                    </th>
                    <th>
                        <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            COSTO
                        </div>
                    </th>
                    <th>
                        <div style="margin-top: -12px; font-family: Arial, Helvetica, sans-serif;">
                            MONTOS
                        </div>
                    </th>
                </tr>
            </thead>
        </table>

        <table class=" w-full pl-2  mb-2 pb-3 text-[8pt] text-start " v-show="item.id_tipo_reg_rpt_consumo == 2">
            <tbody>
                <tr class="*:text-[8pt] *:uppercase ">
                    <td class="text-center w-14">{{ item.codigo_uplt_rpt_consumo }}</td>
                    <td class="text-left max-w-[300px]">{{ item.nombre_prod_rpt_consumo }}</td>
                    <td class="text-left  w-[50px] max-w-[50px]">{{ item.marca_rpt_consumo }}</td>
                    <td class="text-center">{{ item.nombre_umedida_rpt_consumo }}</td>
                    <td class="text-left   w-[75px] max-w-[75px]">{{ item.numero_mov_rpt_consumo }}</td>
                    <td class="text-left  w-[75px] max-w-[75px]">{{ item.fecha }}</td>
                    <td class="text-center  w-[60px] max-w-[60px]">{{ item.cant_rpt_consumo }}</td>
                    <td class="text-end  w-[75px] max-w-[75px] ">{{ item.costo_rpt_consumo }}</td>
                    <td class="text-end pr-4  w-[75px] max-w-[75px]">{{ item.monto_rpt_consumo }}</td>
                </tr>
            </tbody>
        </table>
        <table class=" w-full pl-2  mb-2 pb-3 text-[8pt] text-start " v-show="item.id_tipo_reg_rpt_consumo == 3">
            <tbody>
                <tr class="*:text-[8pt] *:uppercase ">
                    <td class="text-right w-[1200px] font-bold  px-5">{{ item.nombre_prod_rpt_consumo }}</td>
                    <td class="text-right  font-bold ">${{ item.monto_rpt_consumo }}</td>

                </tr>
            </tbody>
        </table>
    </template>


</template>

<script>
import moment from 'moment';

export default {
    props: ["dataConsumo","fecha"],
    setup() {


        return {moment}
    }
}
</script>

<style lang="scss" scoped></style>
