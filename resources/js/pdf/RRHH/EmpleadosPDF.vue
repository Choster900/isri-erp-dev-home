<template>
    <div id="principal" class="py-2 mb-2">

        <div class="flex w-full min-h-[80px]">
            <!-- Columna 1 -->
            <div class="w-[23%] flex justify-center items-center border border-black p-1">
                <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-full my-auto">
            </div>
            <!-- Columna 2 -->
            <div
                class="w-[77%] justify-center items-center border-y border-r border-black pb-[10px] flex flex-col min-h-full">
                <div class="flex flex-col justify-center items-center h-full">
                    <p class="font-[Bembo] text-center text-[12px] font-bold">DEPARTAMENTO DE RECURSOS HUMANOS</p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">{{ title }}</p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">{{ depInfo }}</p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">{{ date }}</p>
                </div>
            </div>
        </div>

        <!-- Table header -->
        <div class="flex w-full border-x border-black bg-gray-200" style="page-break-inside: avoid;">
            <div class="w-[8%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">CODIGO</p>
            </div>
            <div class="w-[32%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">NOMBRE</p>
            </div>
            <div class="w-[10%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">DUI</p>
            </div>
            <div class="w-[15%] flex justify-center items-center border-r border-black py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">PENSIONADO</p>
            </div>
            <div class="w-[35%] flex justify-center items-center py-1">
                <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">PUESTO</p>
            </div>
        </div>

        <template v-for="(employee, index) in queryResult" :key="index">
            <div class="flex w-full border-x border-y border-black" style="page-break-inside: avoid;">
                <!-- Code -->
                <div class="w-[8%] flex justify-center items-center border-r border-black">
                    <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] px-0.5">
                        {{ employee.codigo_empleado }}
                    </p>
                </div>
                <!-- Name -->
                <div class="w-[32%] flex justify-center items-center border-r border-black">
                    <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                        {{ employee.persona.papellido_persona }}
                        {{ employee.persona.sapellido_persona }}
                        {{ employee.persona.tapellido_persona }}
                        ,
                        {{ employee.persona.pnombre_persona }}
                        {{ employee.persona.snombre_persona }}
                        {{ employee.persona.tnombre_persona }}
                    </p>
                </div>
                <!-- ID -->
                <div class="w-[10%] flex justify-center items-center border-r border-black">
                    <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                        {{ employee.persona.dui_persona }}
                    </p>
                </div>
                <!-- Retirement -->
                <div class="w-[15%] flex justify-center items-center border-r border-black">
                    <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                        {{ employee.pensionado_empleado === 1 ? 'SI' : 'NO' }}
                    </p>
                </div>
                <!-- Job positions -->
                <div class="w-[35%] min-h-[45px]">
                    <div 
                        class="w-full h-full flex flex-col justify-center items-center pb-1">
                        <template v-for="(plaza, index) in employee.plazas_asignadas" :key="index">
                            <p class="font-[MuseoSans] text-[10px]"
                                :class="index > 0 ? 'border-t border-slate-400 w-full text-center' : ''">
                                {{
                                    plaza.detalle_plaza.plaza.nombre_plaza
                                }}
                            </p>
                            <p class="font-[MuseoSans] text-[10px] pb-2"
                                >
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
        </template>
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