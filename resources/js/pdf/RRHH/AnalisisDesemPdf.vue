
<template>
    <div>
        <table class="w-full ">
            <tbody>
                <tr>
                    <td class="border border-y border-l border-r-0 border-black w-40" rowspan="4">
                        <div class="w-40 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                    </td>
                    <td class="border border-b-0  border-black text-center  uppercase" rowspan="2">
                        <div class="mt-[-12px] text-sm"> FORMULARIO DE EVALUACIÓN DEL DESEMPEÑO</div>

                    </td>
                    <td class="border w-40 border-l-0 border-b-0 border-black text-[7pt] font-normal pr-3 px-1.5 pb-2">
                        FECHA: {{ evaluacionPersonalProp.data.fecha_evaluacion_personal }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-l-0 border-b-0 border-black text-[7pt] font-normal px-1.5 pb-2">
                        <div class="" style="margin-top: -5px;">EMPLEADO: {{
                            evaluacionPersonalProp.allData.codigo_empleado }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-x border-b  border-black text-center  px-8" rowspan="2">
                        <div style="margin-top: -12px;" class="text-sm">
                            PARA {{
                                separarTexto(evaluacionPersonalProp.data.evaluacion_rendimiento.nombre_evaluacion_rendimiento)
                            }}
                        </div>
                    </td>
                    <td class="border border-l-0 border-b-0 border-black text-[7pt] font-normal px-1.5 pb-2">
                        <div class="" style="margin-top: -5px;">DEPENDENCIA: ADMON</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-l-0 border-black text-[7pt] font-normal  px-1.5 pb-2">
                        <div class="" style="margin-top: -5px;">CODIGO: {{
                            evaluacionPersonalProp.data.evaluacion_rendimiento.codigo_evaluacion_rendimiento }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-l-0 border-black bg-black h-5 text-white text-center text-[9pt] pb-2"
                        colspan="3">
                        <div class="" style="margin-top: -5px;">ESPECIFICACIONES</div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="especificaciones" class="px-1">

            <div class="flex items-center justify-between pt-2 gap-2 pb-0">
                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">EMPLEADO:</div>
                    </label>
                    <div
                        class="text-left text-[7pt] w-56  font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                        <div class="" style="margin-top: -2px;">{{ evaluacionPersonalProp.allData.persona.nombre_completo }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">PUESTO :</div>
                    </label>
                    <div
                        class="text-left text-[7pt] w-80 font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black overflow-hidden">
                        <div class="overflow-ellipsis" style="margin-top: -2px;">{{
                            evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada.filter(plaza =>
                                plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                                }).join('-') || '' }}.</div>
                    </div>
                </div>

                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">SUELDO:</div>
                    </label>
                    <div
                        class=" text-[7pt] w-12  text-start font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                        <div class="" style="margin-top: -2px;">${{ evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada.filter(plaza =>
                                plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.detalle_plaza.plaza.salario_base_plaza;
                                }).join('-') || '' }}</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 gap-2 pb-0">
                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">UNIDAD ADM: (Direccion,Division,Dpto.,Unidad,Seccion,Area):
                        </div>
                    </label>
                    <div
                        class="text-left text-[7pt] w-64  font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black overflow-hidden">
                        <div class="overflow-ellipsis" style="margin-top: -2px;">{{
                            `${evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.dependencia.nombre_dependencia;
                                }).join('-') || ''} - ${evaluacionPersonalProp.data.plaza_evaluada &&
                                evaluacionPersonalProp.data.plaza_evaluada
                                    .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.centro_atencion.nombre_centro_atencion;
                                    }).join('-') || ''}` }}</div>
                    </div>
                </div>
                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">PERIODO COMPRENDIDO:</div>
                    </label>
                    <div
                        class="text-left text-[7pt] w-28 font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black overflow-hidden">
                        <div class="overflow-ellipsis" style="margin-top: -2px;">{{
                            evaluacionPersonalProp.data.periodo_evaluacion.id_periodo_evaluacion == 1 ? `ENERO A JUNIO
                            ${moment(evaluacionPersonalProp.data.fecha_evaluacion_personal).format('YYYY')}` : `JULIO A
                            DICIEMBRE ${moment(evaluacionPersonalProp.data.fecha_evaluacion_personal).format('YYYY')}` }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-start pt-2 gap-2 pb-4">
                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">JEFE INME:</div>
                    </label>
                    <div
                        class="text-left text-[7pt] w-56  font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black overflow-hidden">
                        <div class="overflow-ellipsis" style="margin-top: -2px;">{{
                            evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return `${plaza.plaza_asignada.dependencia.jefatura?.pnombre_persona || ''}
                                                    ${plaza.plaza_asignada.dependencia.jefatura?.snombre_persona || ''}
                                                    ${plaza.plaza_asignada.dependencia.jefatura?.tnombre_persona || ''}
                                                    ${plaza.plaza_asignada.dependencia.jefatura?.papellido_persona || ''}
                                                    ${plaza.plaza_asignada.dependencia.jefatura?.sapellido_persona || ''}
                                                    ${plaza.plaza_asignada.dependencia.jefatura?.tapellido_persona || ''}`;
                                }).join('-') || '' }} </div>
                    </div>
                </div>
                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">PUESTO:</div>
                    </label>
                    <div
                        class="text-left text-[7pt] w-80 font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black overflow-hidden">
                        <div class="overflow-ellipsis" style="margin-top: -2px;">{{
                            evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.dependencia.jefatura?.empleado.plazas_asignadas
                                        .map((plaza, index) => {
                                            return plaza.detalle_plaza.plaza.nombre_plaza
                                        })
                                }).join('-') || '' }}</div>
                    </div>
                </div>


                <!-- {{ dataIndiceEvaluacion }} -->
            </div>
        </div>


        <table class="w-full">
            <thead>
                <tr class="*:border *:text-xs *:text-center *:align-middle *:border-black *:h-7 *:text-white *:bg-black ">
                    <td class="w-24">#</td>
                    <td class="w-32">FECHA</td>
                    <td class="w-40">CATEGORIA</td>
                    <td class="w-28">TIPO INCIDENTE</td>
                    <td class="w-52">COMENTARIO</td>
                </tr>
            </thead>
            <tbody v-for="(incidente, i) in dataIndiceEvaluacion" :key="i">
                <tr v-if="!incidente.isDeleted"
                    class="*:border *:text-xs *:text-center *:align-middle *:border-black *:h-24 *:text-slate-600 ">
                    <td class="w-24">
                        <div class="mt-[-12px] w-full"> {{ i + 1 }}</div>
                    </td>
                    <td class="w-32">
                        <div class="mt-[-12px] w-full">{{  moment(incidente.fechaRegIncidenteEvaluacion).format('L')  }} </div>
                    </td>
                    <td class="w-40">
                        <div class="relative flex  w-full ">

                            <div class="mt-[-12px] w-full">
                                {{ opcionesCategoriaRendimiento.find(index => index.value ===
                                    incidente.idCategoriaRendimiento).label }}
                            </div>
                        </div>
                    </td>
                    <td class="w-28">
                        <div class="relative flex w-full ">

                            <div class="mt-[-12px] w-full">
                                {{ incidente.resultadoIncidenteEvaluacion === 1 ? 'FAVORABLE' : 'DESFAVORABLE' }}
                            </div>
                        </div>

                    </td>
                    <td class="w-52 p-2" contenteditable="true">
                        <div class="mt-[-12px] w-full">{{ incidente.comentarioIncidenteEvaluacion }}</div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
import { toRef, toRefs } from 'vue';
import moment from 'moment';
export default {
    props: ["rubricaAndCategoriaByEvaluacion", "dataIndiceEvaluacion", "opcionesCategoriaRendimiento", "evaluacionPersonalProp"],
    setup(props) {
        const { ranges, separarTexto } = useDocumentoEvaluacion()

        return {
            separarTexto,
            moment,
            ranges,
        }
    }
}
</script>

<style>
.container form {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
}

.container label {
    display: flex;
    cursor: pointer;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    margin-bottom: 0.375em;
}

.container label input {
    position: absolute;
    left: -9999px;
}

.container label input:checked+span {
    background-color: #ffffff;
    color: rgb(0, 0, 0);
}

.container label input:checked+span:before {
    box-shadow: inset 0 0 0 0.4375em #000000;
}

.container label span {
    display: flex;
    align-items: center;
    padding: 0.375em 0.75em 0.375em 0.375em;
    /*  border-radius: 99em; */
    transition: 0.25s ease;
    color: #000000;
}

.container label span:hover {
    background-color: #d6d6e5;
}

.container label span:before {
    display: flex;
    flex-shrink: 0;
    content: "";
    background-color: #fff;
    width: 1.5em;
    height: 1.5em;
    /* border-radius: 50%; */
    margin-right: 0.375em;
    transition: 0.25s ease;
    box-shadow: inset 0 0 0 0.125em #000000;
}
</style>

