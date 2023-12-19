<template>
    <div id="principal" class="pb-1">
        <div class="text-center relative">
            <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[250px] mx-auto">
            <h2 class="font-[Bembo] font-bold text-[14px] ">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
            <h2 class="font-[Bembo] font-bold text-[14px]">{{ title }}</h2>
            <h2 class="font-[Bembo] font-bold text-[14px]">{{ depInfo }}</h2>
            <h2 class="font-[Bembo] font-bold text-[14px]">{{ date }}</h2>
        </div>
        <div class="mx-2">
            <div class="container mx-auto">
                <div class="bg-white shadow-md rounded mt-2">
                    <div class="flex justify-between bg-teal-800 px-2 py-2 text-white text-[13px]">
                        <div class="w-[8%] text-center">CODIGO</div>
                        <div class="w-[32%] text-center">NOMBRE</div>
                        <div class="w-[10%] text-center">DUI</div>
                        <div class="w-[15%] text-center">PENSIONADO</div>
                        <div class="w-[35%] text-center">PUESTO</div>
                    </div>
                    <div v-for="(employee, index) in queryResult" :key="index" :class="{
                        'bg-gray-400': index % 2 === 0,
                        'bg-gray-300': index % 2 !== 0,
                        'custom-break': index === 0,
                    }" class="flex justify-between border-b border-gray-100 text-[14px] px-2 py-2 hover:bg-gray-300">
                        <div class="w-[8%] text-center break-words overflow-wrap flex items-center justify-center">{{
                            employee.codigo_empleado }}</div>
                        <div class="w-[32%] text-center break-words overflow-wrap flex items-center justify-center">
                            {{ employee.persona.papellido_persona }}
                            {{ employee.persona.sapellido_persona }}
                            {{ employee.persona.tapellido_persona }}
                            ,
                            {{ employee.persona.pnombre_persona }}
                            {{ employee.persona.snombre_persona }}
                            {{ employee.persona.tnombre_persona }}
                        </div>
                        <div class="w-[10%] text-center break-words overflow-wrap flex items-center justify-center">
                            {{ employee.persona.dui_persona }}
                        </div>
                        <div class="w-[15%] text-center break-words overflow-wrap flex items-center justify-center">
                            {{ employee.pensionado_empleado === 1 ? 'SI' : 'NO' }}
                        </div>
                        <div class="w-[35%] text-center break-words overflow-wrap flex flex-col items-center">
                            <template v-for="(plaza, index) in employee.plazas_asignadas" :key="index">
                                <p :class="index != 0 ? 'border-t border-slate-400 w-full pt-2' : ''">
                                    {{
                                        plaza.detalle_plaza.plaza.nombre_plaza
                                    }}
                                </p>
                                <p :class="(index + 1) != employee.plazas_asignadas.length ? 'pb-2' : ''">
                                    {{ plaza.dependencia.centro_atencion.codigo_centro_atencion + ' - (' +
                                        formatDate(plaza.fecha_plaza_asignada) +
                                        (plaza.fecha_renuncia_plaza_asignada ? ' - ' +
                                            formatDate(plaza.fecha_renuncia_plaza_asignada) + ')' :
                                            employee.id_estado_empleado === 1 ? ')' : ' - sin registro)') }}
                                </p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import { useReportesRRHH } from '@/Composables/RRHH/Reporte/useReportesRRHH.js';

export default {
    props: {
        title: {
            type: String,
            default: ""
        },
        depInfo: {
            type: String,
            default: ""
        },
        date: {
            type: String,
            default: ""
        },
        queryResult: {
            type: Object,
            default: {}
        }
    },
    setup(props) {
        const {
            formatDate
        } = useReportesRRHH()

        return {
            formatDate
        }
    }
}
</script>

<style>
.custom-break {
    page-break-after: always;
    /* page-break-inside: avoid; */
}
</style>