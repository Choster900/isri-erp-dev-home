
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

            <div class="flex items-center justify-between pt-2 gap-2 pb-4">
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
                <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
                    <label class="text-[7pt]">
                        <div class="" style="margin-top: -9px;">PUNTAJE TOTAL:</div>
                    </label>
                    <div
                        class="text-left text-[7pt] w-10 font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black overflow-hidden">
                        <div class="overflow-ellipsis" style="margin-top: -2px;">{{ optionsSelected.reduce((score, object) =>
                            score + parseFloat(object.puntaje_rubrica_rendimiento), 0) }}</div>
                    </div>
                </div>


            </div>
        </div>
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody v-for="(evaluacion, i) in rubricaAndCategoriaByEvaluacion" :key="i">
                <tr class="">
                    <td class="border border-black bg-black text-[9pt] text-white text-center pl-8 py-2" colspan="2">
                        <div class="mt-[-12px]">{{ evaluacion.nombre_cat_rendimiento }}</div>
                    </td>
                    <td class="border border-black bg-black text-[9pt] text-white text-center pl-1 px-7">
                        <div class="mt-[-12px] w-full ml-3">PUNTOS:10</div>
                    </td>
                </tr>
                <tr class="">
                    <td class="border-x border-b border-black bg-slate-300/60"
                        :rowspan="evaluacion.rubricas_rendimiento.length + 1">
                        <div class="w-48 px-3 text-[8pt] text-justify text-[#4f4f4f] font-medium mt-[-12px]">{{
                            evaluacion.descripcion_cat_rendimiento }}
                        </div>
                    </td>

                </tr>
                <tr class="" v-for="(rubrica, j ) in evaluacion.rubricas_rendimiento" :key="j">
                    <td class="text-[8pt] border-l-0 border-r border-t-0 border-black w-full border-b px-2 py-2">
                        <div class="mt-[-12px] "> {{ rubrica.descripcion_rubrica_rendimiento }}</div>
                    </td>
                    <td class="border-x-0 border-t-0 border-black justify-center text-center  px-4 py-2 border-b border-r">
                        <div class="container mt-1 ml-4">
                            <label>
                                <div class="border border-black size-4"
                                    :class="optionsSelected.find(cat => cat.id_cat_rendimiento === rubrica.id_cat_rendimiento)?.id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento ? 'bg-black text-black' : ''">
                                </div>
                                <p class="mt-[-12px] pl-2">{{ rubrica.opcion_rubrica_rendimiento }}</p>
                            </label>
                        </div>

                    </td>
                </tr>

            </tbody>
        </table>


        <div class="flex  justify-between flex-row space-x-5">

            <!-- Primera tabla -->
            <div class="w-1/2 overflow-x-auto">
                <table class="w-full">
                    <tr class="text-start">
                        <th class="py-2 text-center border border-white" colspan="5">
                            <h1 class="text-sm font-bold">TABLA DE VALORACIÓN</h1>
                        </th>
                    </tr>
                    <tr class="text-xs text-center bg-gray-200">
                        <th class="border border-black">
                            <div class="mt-[-12px] py-2">FACTOR</div>
                        </th>
                        <th class="border border-black">
                            <div class="mt-[-12px] py-2">A</div>
                        </th>
                        <th class="border border-black">
                            <div class="mt-[-12px] py-2">B</div>
                        </th>
                        <th class="border border-black">
                            <div class="mt-[-12px] py-2">C</div>
                        </th>
                        <th class="border border-black">
                            <div class="mt-[-12px] py-2">D</div>
                        </th>
                    </tr>
                    <tr class="text-center text-[8pt]" v-for="(data, i) in rubricaAndCategoriaByEvaluacion" :key="i">
                        <td class="border border-black text-start px-2 w-4">
                            <div class="mt-[-12px] py-2">
                                {{ i + 1 }} - {{ data.nombre_cat_rendimiento }}
                            </div>
                        </td>
                        <td v-for="(rubrica, j) in data.rubricas_rendimiento" :key="j"
                            class="border border-black  text-[8pt] w-4"
                            :class="optionsSelected.find(cat => cat.id_cat_rendimiento === data.id_cat_rendimiento)?.id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento ? 'bg-slate-400 text-black' : ''">
                            <div class="mt-[-12px] py-2">
                                {{ rubrica.puntaje_rubrica_rendimiento }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- Segunda tabla -->
            <div class="w-1/3 mt-6 md:mt-0 overflow-x-auto">
                <table class="w-full">
                    <tr class="text-start">
                        <th class="py-2 border border-white" colspan="2">
                            <h1 class="text-sm font-bold">CALIFICACIÓN POR PUNTOS Y POR RANGOS</h1>
                        </th>
                    </tr>
                    <tr v-for="range in ranges" :key="range.label" class="text-start text-[8pt]">
                        <td class="border border-black" :class="{ 'bg-slate-400': isScoreInRange(range.min, range.max) }">
                            <div class="mt-[-12px] py-2">{{ range.label }}</div>
                        </td>
                        <td class="border border-black">
                            <div class="mt-[-12px] py-2"> {{ range.min }} a {{ range.max }} puntos</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
import { toRef, toRefs } from 'vue';
import moment from 'moment';
export default {
    props: ["rubricaAndCategoriaByEvaluacion", "optionsSelected", "evaluacionPersonalProp"],
    setup(props) {
        const { ranges, separarTexto } = useDocumentoEvaluacion()
        const { optionsSelected } = toRefs(props)
        const isScoreInRange = (min, max) => {
            const totalScore = optionsSelected.value.reduce(
                (score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento),
                0
            );
            return totalScore >= min && totalScore <= max;
        }

        return {
            separarTexto,
            moment,
            ranges,
            isScoreInRange,
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

