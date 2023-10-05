use GuzzleHttp\Promise\Promise;
<script setup>
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import DocumentBlanck from '@/Components-ISRI/RRHH/DocumentoEvaluacionBlank.vue';
import momentAlias from "moment";
import { v4 as uuid, v4 } from "uuid";
</script>
<template>
    <div class=" flex  justify-center  content-between">
        <!-- <div class="px-2">
            <GeneralButton :color="['bg-orange-700 hover:bg-orange-800']" text="Enviar evaluación" icon="update"
                :disabled="contenidoEvaluacionRendimiento != '' && registroEvaluacionRendimientoPersonal != '' ? false : true"
                styleDisabled="bg-orange-900/80 hover:bg-orange-900/80 cursor-not-allowed"
                @click="contenidoEvaluacionRendimiento != '' && registroEvaluacionRendimientoPersonal != '' ? submitResponseRequest() : ''" />
        </div>
        <div class="px-2">
            <GeneralButton :color="['bg-red-700 hover:bg-red-800']" text="Imprimir" icon="pdf"
                :disabled="contenidoEvaluacionRendimiento != '' && registroEvaluacionRendimientoPersonal != '' && contenidoEvaluacionRendimiento.categorias_rendimiento.length == responsesWithScores.length ? false : true"
                styleDisabled="bg-red-900/80 hover:bg-red-900/80 cursor-not-allowed"
                @click="contenidoEvaluacionRendimiento != '' && registroEvaluacionRendimientoPersonal != '' ? printPdf() : ''" />
        </div> -->


    </div>

    <!-- <div class="mx-4 overflow-y-auto max-h-[calc(100vh-200px)] p-3 mb-4"
        v-if="contenidoEvaluacionRendimiento != '' && registroEvaluacionRendimientoPersonal != ''"
        :class="{ 'animate-slide-out-right': showErrorWhenValuesChanges, 'animate-slide-in-left': !showErrorWhenValuesChanges }"> -->
    <div :class="showMe == 'DocumentoAnalisisDesempeñoVue' ? 'hidden' : ''"
        class="mx-4 overflow-y-auto max-h-[calc(100vh-200px)] p-3 mb-4">
        <table class="w-full">
            <tbody>
                <tr>
                    <td class="border border-black" rowspan="4">
                        <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                    </td>
                    <td class="border border-black text-center font-medium uppercase" rowspan="2">Seguimiento de Desempeño
                    </td>
                    <td class="border border-black text-[8pt] font-semibold pr-3 px-1.5">FECHA : {{ momentAlias(
                        registroEvaluacionRendimientoPersonal.fecha_evaluacion_personal).format('L') }}</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold   px-1.5">ID 00000-{{
                        registroEvaluacionRendimientoPersonal.id_evaluacion_personal }}</td>
                </tr>
                <tr>
                    <td class="border border-black text-center font-medium px-8" rowspan="2">DESEMPEÑO DE PERSONAL
                        ADMINISTRATIVO</td>
                    <td class="border border-black text-[8pt] font-semibold py-0.5 px-1.5">DEPENDENCIA:ADMON</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold  py-0.5 px-1.5">CODIGO: VERSION 1.0</td>
                </tr>
                <tr>
                    <td class="border border-black bg-black h-5 text-white text-center text-[10pt] " colspan="3">
                        ESPECIFICACIONES</td>
                </tr>
            </tbody>
        </table>
        <div class="flex items-center justify-center pt-4 gap-28">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">EMPLEADO: </label>
                <input type="text" :value="generateFullName(infoEmployee.persona)"
                    class="text-left text-[9pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO: </label>
                <input type="text" :value="infoEmployee.plazas_asignadas && infoEmployee.plazas_asignadas.filter((plaza) => plaza.estado_plaza_asignada == 1).map((plaza, index) => {
                    return `${plaza.detalle_plaza.plaza.nombre_plaza}`
                }).join(',') || ''"
                    class="text-left text-[9pt] w-52 text-sm font-medium capitalize h-5 border-x-0 border-t-0">
            </div>
        </div>
        <div class="flex items-center justify-center pt-2 gap-10 pb-7">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">JEFE INME: </label>
                <input type="text" value="MIGUEL JOSUE TOBIAS RIVAS"
                    class=" text-left text-[9pt]  text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO : </label>
                <input type="text" value="COORDINADOR DEL AREA DE SISTEMAS DE INFORMACION"
                    class=" text-left text-[9pt] w-56 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUNTAJE TOTAL:</label>
                <input type="text"
                    :value="responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0)"
                    class=" text-left text-[9pt] w-20 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
        </div>
        <table class="pb-5" v-for="(data, i) in contenidoEvaluacionRendimiento.categorias_rendimiento" :key="i">
            <tbody>
                <tr>
                    <td class="border border-black bg-black h-8
                     text-[10pt] text-white text-center" colspan="2">
                        <div class="flex justify-between items-center">
                            <span class="mx-auto">{{ data.nombre_cat_rendimiento }}</span>
                            <span class="text-end pr-8">
                                PUNTOS: {{
                                    (responsesWithScores.find(obj => obj.nombre_cat_rendimiento === data.nombre_cat_rendimiento)
                                        || { puntaje_rubrica_rendimiento: 0 }).puntaje_rubrica_rendimiento
                                }}

                            </span>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black bg-slate-300/60" rowspan="2">
                        <div class="w-40 p-1.5 text-[9pt] text-justify text-[#4f4f4f] font-medium">
                            {{ data.descripcion_cat_rendimiento }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black">

                        <table class="" id="rubrica_rendimiento">
                            <tr v-for="(rubrica, j) in data.rubricas_rendimiento" :key="j">
                                <td :class="data.rubricas_rendimiento.length != j + 1 ? 'border-b' : ''"
                                    class="text-[9pt] border-l-0 border-r border-t-0 border-black text-justify px-2 font-medium">
                                    {{ rubrica.descripcion_rubrica_rendimiento }}
                                </td>
                                <td :class="data.rubricas_rendimiento.length != j + 1 ? 'border-b' : ''"
                                    class="border-x-0  border-t-0  border-black justify-center text-center px-10 py-4">
                                    <div class="container">
                                        <label>
                                            <input type="radio" @click="saveEvaluationResponses({
                                                nombre_cat_rendimiento: data.nombre_cat_rendimiento,
                                                id_rubrica_rendimiento: rubrica.id_rubrica_rendimiento,
                                                id_cat_rendimiento: data.id_cat_rendimiento,
                                                opcion_rubrica_rendimiento: rubrica.opcion_rubrica_rendimiento,
                                                puntaje_rubrica_rendimiento: rubrica.puntaje_rubrica_rendimiento,
                                            }, true)"
                                                :checked="registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal &&
                                                    registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal.length > i &&
                                                    registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal[i].id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento"
                                                :name="data.nombre_cat_rendimiento">
                                            <span class="text-xs justify-center text-center"
                                                :title="`${rubrica.puntaje_rubrica_rendimiento} Puntos`">{{
                                                    rubrica.opcion_rubrica_rendimiento }}</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="pb-5" v-for="i in 2" :key="i" v-if="registroEvaluacionRendimientoPersonal == ''">
            <tbody>
                <tr>
                    <td class="border border-black bg-black h-10 text-[10pt] text-white text-center" colspan="2">
                        <div class="flex justify-between items-center">
                            <span class="mx-auto">NOMBRE CATEGORIA</span>
                            <span class="text-end pr-8">PUNTOS</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black bg-slate-300/60" rowspan="2">
                        <div class="w-40 p-1.5 text-[9pt] text-justify text-[#4f4f4f] font-medium"></div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black">

                        <table class="" id="rubrica_rendimiento">
                            <tr v-for="j in 2" :key="j">
                                <td :class="2 != j ? 'border-b' : ''"
                                    class=" border-l-0 border-r border-t-0 border-black  w-[490px]">

                                </td>
                                <td :class="2 != j ? 'border-b' : ''"
                                    class="border-x-0  border-t-0 border-black justify-center text-center px-10 py-4">
                                    <div class="container">
                                        <label>
                                            <input type="radio" disabled>
                                            <span class="text-xs justify-center text-center"> </span>
                                        </label>
                                    </div>
                                </td>
                            </tr>


                        </table>
                    </td>

                </tr>

            </tbody>
        </table>

        <div class="flex flex-col justify-between md:flex-row gap-10">

            <!-- Primera tabla -->
            <div class="w-full md:w-1/2 overflow-x-auto">
                <table class="w-full">
                    <tr class="text-center">
                        <th class="py-2" colspan="5">
                            <h1 class="text-sm font-bold ">TABLA DE VALORACIÓN</h1>
                        </th>
                    </tr>
                    <tr class="text-xs text-center bg-gray-200">
                        <th class="border border-black">FACTOR</th>
                        <th class="border border-black">A</th>
                        <th class="border border-black">B</th>
                        <th class="border border-black">C</th>
                        <th class="border border-black">D</th>
                    </tr>
                    <tr class="text-center text-[8pt]"
                        v-for="(data, i) in contenidoEvaluacionRendimiento.categorias_rendimiento" :key="i">
                        <td class="border border-black text-start px-2">
                            {{ i + 1 }} - {{ data.nombre_cat_rendimiento }}
                        </td>
                        <td v-for="(rubrica, j) in data.rubricas_rendimiento" :key="j"
                            class="border border-black w-7 text-[8pt]"
                            :class="registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal &&
                                registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal.length > i &&
                                registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal[i].id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento ? 'bg-red-600/90 text-slate-300' : ''">
                            {{ rubrica.puntaje_rubrica_rendimiento }}
                        </td>
                    </tr>

                    <tr class="text-center text-[8pt]" v-for="i in 6" :key="i"
                        v-if="!contenidoEvaluacionRendimiento.categorias_rendimiento">
                        <td class="border border-black text-start px-2">-</td>
                        <td class="border border-black w-7 text-[8pt]">0</td>
                        <td class="border border-black w-7 text-[8pt]">0</td>
                        <td class="border border-black w-7 text-[8pt]">0</td>
                        <td class="border border-black w-7 text-[8pt]">0</td>
                    </tr>

                </table>
            </div>

            <!-- Segunda tabla -->
            <div class="w-full md:w-1/3 mt-6 md:mt-0 overflow-x-auto">
                <table class="w-full">
                    <tr class="text-start">
                        <th class="py-2" colspan="2">
                            <h1 class="text-sm font-bold">CALIFICACIÓN POR PUNTOS Y POR RANGOS</h1>
                        </th>
                    </tr>
                    <tr class="text-start text-[8pt]">
                        <td class="border border-black"
                            :class="responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0) >= 73 ? 'bg-slate-400' : ''">
                            Excelente
                        </td>
                        <td class="border border-black">
                            De 73 a 84 puntos
                        </td>
                    </tr>
                    <tr class="text-start text-[8pt]">
                        <td class="border border-black"
                            :class="responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0) > 56 && responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0) < 56 ? 'bg-slate-400' : ''">

                            Muy Bueno
                        </td>
                        <td class="border border-black">
                            De 56 a 72 puntos
                        </td>
                    </tr>
                    <tr class="text-start text-[8pt]">
                        <td class="border border-black"
                            :class="responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0) > 28 && responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0) < 55 ? 'bg-slate-400' : ''">

                            Bueno
                        </td>
                        <td class="border border-black">
                            De 28 a 55 puntos
                        </td>
                    </tr>
                    <tr class="text-start text-[8pt]">
                        <td class="border border-black"
                            :class="responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0) <= 27 ? 'bg-slate-400' : ''">

                            Insatisfactorio
                        </td>
                        <td class="border border-black">
                            De 0 a 27 puntos
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Seccion de analicis de desempeño -->
    <div :class="showMe == 'DocumentoEvalacionVue' ? 'hidden' : ''"
        class="mx-4 overflow-y-auto max-h-[calc(100vh-100px)] p-3 mb-4">
        <table class="w-full">
            <tbody>
                <tr>
                    <td class="border border-black" rowspan="4">
                        <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                    </td>
                    <td class="border border-black text-center font-medium uppercase" rowspan="2">Seguimiento de Desempeño
                    </td>
                    <td class="border border-black text-[8pt] font-semibold pr-3 px-1.5">FECHA : HOY</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold   px-1.5">ID 00000-1</td>
                </tr>
                <tr>
                    <td class="border border-black text-center font-medium px-8" rowspan="2">DESEMPEÑO DE PERSONAL
                        ADMINISTRATIVO</td>
                    <td class="border border-black text-[8pt] font-semibold py-0.5 px-1.5">DEPENDENCIA:ADMON</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold  py-0.5 px-1.5">CODIGO: VERSION 1.0</td>
                </tr>
                <tr>
                    <td class="border border-black bg-black h-5 text-white text-center text-[10pt] " colspan="3">
                        ESPECIFICACIONES</td>
                </tr>
            </tbody>
        </table>
        <div class="flex items-center justify-center pt-4 gap-40">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">EMPLEADO: </label>
                <input type="text"
                    class="text-left text-[9pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO: </label>
                <input type="text"
                    class="text-left text-[9pt] w-52 text-sm font-medium capitalize h-5 border-x-0 border-t-0">
            </div>
        </div>
        <div class="flex items-center justify-center pt-2 gap-20 pb-7">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">JEFE INME: </label>
                <input type="text" value="MIGUEL JOSUE TOBIAS RIVAS"
                    class=" text-left text-[9pt]  text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO : </label>
                <input type="text" value="COORDINADOR DEL AREA DE SISTEMAS DE INFORMACION"
                    class=" text-left text-[9pt] w-56 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
        </div>

        <div class="max-h-72 overflow-y-auto">
            <table class="w-full border border-black border-collapse">
                <thead>
                    <tr>
                        <td class="bg-black text-white text-center py-2 text-[10pt]" colspan="5">
                            REGISTRO DE INCIDENTES CRITICOS DEN DESEMPEÑO PARA PERSONAL ADMINISTRATIVO
                        </td>
                    </tr>
                    <tr class="bg-gray-200 text-gray-700 text-xs">
                        <th class="border border-black font-semibold text-center py-1" rowspan="2" style="min-width: 20px;">
                            #
                        </th>
                        <th class="border border-black font-semibold text-center" rowspan="2" style="min-width: 80px;">
                            Fecha
                        </th>
                        <th class="border border-black font-semibold text-center" rowspan="2" style="min-width: 80px;">
                            N° Factor
                        </th>
                        <th class="border border-black font-semibold text-center py-1" style="min-width: 40px;">
                            F / D
                        </th>
                        <th class="border border-black font-semibold text-center" rowspan="2" style="min-width: 200px;">
                            Descripción del evento
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(data, i) in incidentesEvaluaciones" :key="i">
                        <tr v-if="!data.isDelete">
                            <td class="border border-black text-center px-1 text-xs">
                                <svg class="h-7 w-7 cursor-pointer" viewBox="0 0 1024.00 1024.00" fill="#ff0000"
                                    stroke="#ff0000" @click="delteIncident(data.id)" stroke-width="23.552">
                                    <path
                                        d="M512 897.6c-108 0-209.6-42.4-285.6-118.4-76-76-118.4-177.6-118.4-285.6 0-108 42.4-209.6 118.4-285.6 76-76 177.6-118.4 285.6-118.4 108 0 209.6 42.4 285.6 118.4 157.6 157.6 157.6 413.6 0 571.2-76 76-177.6 118.4-285.6 118.4z m0-760c-95.2 0-184.8 36.8-252 104-67.2 67.2-104 156.8-104 252s36.8 184.8 104 252c67.2 67.2 156.8 104 252 104 95.2 0 184.8-36.8 252-104 139.2-139.2 139.2-364.8 0-504-67.2-67.2-156.8-104-252-104z"
                                        fill=""></path>
                                    <path d="M707.872 329.392L348.096 689.16l-31.68-31.68 359.776-359.768z" fill=""></path>
                                    <path d="M328 340.8l32-31.2 348 348-32 32z" fill=""></path>
                                </svg>
                            </td>
                            <td class="border border-black text-center text-xs px-4">{{ momentAlias().format('L') }}</td>
                            <td class="border border-black text-center w-52">
                                <div class="relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="seleccione"
                                        v-model="incidentesEvaluaciones[i].id_cat_rendimiento" :classes="{
                                            placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-black rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ',
                                            noOptions: 'py-2 px-3 text-gray-600 bg-white text-left text-[8pt]',
                                            optionsContainer: 'custom-options-container absolute z-50'// Añade una clase personalizada para el contenedor de opciones
                                        }" :options="optionsFactor" :searchable="true" noOptionsText="sin opciones"
                                        noResultsText="sin resultado" />
                                </div>
                            </td>
                            <td class="border border-black text-center " style="outline: none;">
                                <select v-model="incidentesEvaluaciones[i].resultado_incidente_evaluacion"
                                    class="text-[9pt] py-0.5">
                                    <option value="-" selected> - </option>
                                    <option value="0">F</option>
                                    <option value="1">D</option>
                                </select>
                            </td>
                            <td class="border border-black text-center w-96 max-w-96 text-xs px-4"
                                @input="incidentesEvaluaciones[i].comentario_incidente_evaluacion = $event.target.innerText"
                                contenteditable="true" style="outline: none;">{{
                                    incidentesEvaluaciones[i].comentario_incidente_evaluacion }}
                            </td>
                        </tr>
                    </template>
                    <template v-for="i in 4" :key="i" v-if="incidentesEvaluaciones == ''">
                        <tr>
                            <td class="border border-black text-center px-1 text-xs">
                                <svg class="h-7 w-7 cursor-pointer" viewBox="0 0 1024.00 1024.00" fill="#ff0000"
                                    stroke="#ff0000" stroke-width="23.552">
                                    <path
                                        d="M512 897.6c-108 0-209.6-42.4-285.6-118.4-76-76-118.4-177.6-118.4-285.6 0-108 42.4-209.6 118.4-285.6 76-76 177.6-118.4 285.6-118.4 108 0 209.6 42.4 285.6 118.4 157.6 157.6 157.6 413.6 0 571.2-76 76-177.6 118.4-285.6 118.4z m0-760c-95.2 0-184.8 36.8-252 104-67.2 67.2-104 156.8-104 252s36.8 184.8 104 252c67.2 67.2 156.8 104 252 104 95.2 0 184.8-36.8 252-104 139.2-139.2 139.2-364.8 0-504-67.2-67.2-156.8-104-252-104z"
                                        fill=""></path>
                                    <path d="M707.872 329.392L348.096 689.16l-31.68-31.68 359.776-359.768z" fill=""></path>
                                    <path d="M328 340.8l32-31.2 348 348-32 32z" fill=""></path>
                                </svg>
                            </td>
                            <td class="border border-black text-center text-xs px-4"></td>
                            <td class="border border-black text-center w-52">
                                <div class="relative flex h-8 w-full flex-row-reverse ">
                                   
                                </div>
                            </td>
                            <td class="border border-black text-center " style="outline: none;">
                               
                            </td>
                            <td class="border border-black text-center w-96 max-w-96 text-xs px-4">
                                    
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class=" flex  justify-start  content-between my-3">
            <div>
                <GeneralButton :color="['bg-slate-700 hover:bg-slate-800']" text="Agregar incidente" icon="add"
                    @click="pushToIncidentes" />
            </div>
            <div class="px-2">
                <GeneralButton :color="['bg-orange-700 hover:bg-orange-800']" text="Enviar documento" icon="update"
                    @click="submitResponseRequest()" />
            </div>

        </div>

    </div>
</template>

<script>
import { createApp, h } from 'vue'
import EvaluacionPdfpVue from '@/pdf/RRHH/EvaluacionPdf.vue';
export default {
    props: {
        contenidoEvaluacionRendimiento: {
            type: Object,
            default: () => ({}) // Valor por defecto: un objeto vacío
        },
        registroEvaluacionRendimientoPersonal: {
            type: Object,
            default: () => ({}) // Valor por defecto: un objeto vacío
        },
        infoEmployee: {
            type: Object,
            default: () => ({}) // Valor por defecto: un objeto vacío
        },
        showMe: {
            type: Boolean,
            default: false // Valor por defecto: false
        }
    },
    data() {
        return {
            evaluacionData: [],
            responsesWithScores: [], // Contabiliza el puntaje de la evaluacion        
            responsesWithScoresDataSend: [], // Almacena la data que se enviara al backend        
            showErrorWhenValuesChanges: true,
            registroEvaluacion: [],
            contenidoEvaluacion: [],
            incidentesEvaluaciones: [],
            optionsFactor: [
                { value: '1', label: '1. Calidad de trabajo' },
                { value: '2', label: '2. Productividad' },
                { value: '3', label: '3. Responsabilidad' },
                { value: '4', label: '4. Iniciativa y Creatividad' },
                { value: '5', label: '5. Cumplimiento de normas e instruccciones' },
                { value: '6', label: '6. Relaciones Laborales' },
                { value: '7', label: '7. Discrecion' },
            ],
        }
    },
    methods: {
        pushToIncidentes() {
            this.incidentesEvaluaciones.push({
                id: v4(),
                id_incidente_evaluacion: '',
                id_cat_rendimiento: '',
                resultado_incidente_evaluacion: '',
                comentario_incidente_evaluacion: '',
                isDelete: false,
            })
        },
        delteIncident(row) {
            const index = this.incidentesEvaluaciones.findIndex(i => i.id == row)
            this.incidentesEvaluaciones[index].isDelete = true
        },
        printPdf(dataQuedan) {
            // Opciones de configuración para generar el PDF
            // let fecha = moment().format('DD-MM-YYYY');
            let name = 'EVALUACION'// Nombre del pdf
            // Propiedades del pdf
            const opt = {
                //margin: 0.5, DEJANDO EL MARGEN POR DEFAUL
                //      margin: 0.1,
                filename: name,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                // pagebreak: { mode: 'avoid-all', before: '#page2el' },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },

            };

            // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
            const app = createApp(EvaluacionPdfpVue, {
                contenidoEvaluacionRendimiento: this.contenidoEvaluacionRendimiento,
                registroEvaluacionRendimientoPersonal: this.registroEvaluacionRendimientoPersonal,
                promedio: this.responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0),
                empleado: this.generateFullName(this.infoEmployee.persona),
                puesto: this.infoEmployee.plazas_asignadas && this.infoEmployee.plazas_asignadas.filter((plaza) => plaza.estado_plaza_asignada == 1).map((plaza, index) => {
                    return `${plaza.detalle_plaza.plaza.nombre_plaza}`
                }).join(',') || '',
            });// El pdf en cuestion

            // Crear un elemento div y montar la instancia de la aplicación en él
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            // Generar y guardar el PDF utilizando html2pdf
            html2pdf().set(opt).from(html).save();
        },
        generateFullName(persona) {
            if (persona) {

                const nombres = [
                    persona.pnombre_persona,
                    persona.snombre_persona,
                    persona.tnombre_persona
                ].filter(Boolean).join(' ');

                const apellidos = [
                    persona.papellido_persona,
                    persona.sapellido_persona,
                    persona.tapellido_persona
                ].filter(Boolean).join(' ');

                return `${nombres} ${apellidos}`;
            }
        },

        /**
         * Guarda las respuestas de una evaluación junto con sus puntajes.
         * @param {Object} dataResponse - Objeto que contiene la respuesta y el puntaje.
         */
        saveEvaluationResponses(dataResponse, isModifingResp = false) {
            // Define una función para encontrar el índice de la categoría de rendimiento en un arreglo dado
            const findCategoryIndex = (arr, categoryName) => arr.findIndex(obj => obj.nombre_cat_rendimiento === categoryName);

            // Busca el índice en responsesWithScores
            const catRendimientoIndex = findCategoryIndex(this.responsesWithScores, dataResponse.nombre_cat_rendimiento);

            // Si la categoría de rendimiento ya existe en el arreglo, la reemplaza.
            if (catRendimientoIndex !== -1) {
                this.responsesWithScores.splice(catRendimientoIndex, 1);
            }

            // Agrega la nueva respuesta con su puntaje a responsesWithScores
            this.responsesWithScores.push(dataResponse);

            // Si isModifingResp es true, también agrega la respuesta a responsesWithScoresDataSend
            if (isModifingResp) {
                // Busca el índice en responsesWithScoresDataSend
                const catRendimientoIndexToSend = findCategoryIndex(this.responsesWithScoresDataSend, dataResponse.nombre_cat_rendimiento);

                if (catRendimientoIndexToSend !== -1) {
                    this.responsesWithScoresDataSend.splice(catRendimientoIndexToSend, 1);
                }

                this.responsesWithScoresDataSend.push(dataResponse);
            }
        },



        /**
         * Envia la data al backend
         */
        async submitResponseRequest() {
            const confirmed = await this.$swal.fire({
                title: '<p class="text-[20pt] text-center">¿Esta seguro de enviar la evaluacion?</p>',
                icon: 'question',
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: 'Si, Editar',
                confirmButtonColor: '#001b47',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                // Verificar si no hay posiciones duplicadas de números de acta
                this.executeRequest(
                    this.sendEvaluationRequest(),
                    '¡Los datos se han ingresado correctamente!'
                )
            }
        },


        sendEvaluationRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/save-response', {
                        dataIncidenteEvaluacion: this.incidentesEvaluaciones,
                        data: this.responsesWithScoresDataSend,
                        id_evaluacion_personal: this.registroEvaluacion.id_evaluacion_personal,
                        puntaje_evaluacion_personal: this.responsesWithScores.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0),
                    });
                    console.log(resp.data);
                    this.$emit("actualizar-table-data");
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa
                } catch (error) {
                    console.log('Error en el envio:', error)
                    reject(error); // Rechazamos la promesa en caso de excepción
                }
            });
        },
        /**
         * Funciona que mapea la informacion de todas las respuestas para ponerlas en un solo objeto
         * Esto nos posibilita la obtencion y manejo de datos 
         */
        mapperResponse() {
            const personalEvaluationDetails = this.registroEvaluacion.detalle_evaluaciones_personal;
            const performanceCategories = this.contenidoEvaluacionRendimiento.categorias_rendimiento;


            if (!personalEvaluationDetails || !performanceCategories) {
                // Maneja el caso en el que alguna de las variables sea undefined
                return;
            }
            console.log(personalEvaluationDetails);
            personalEvaluationDetails.forEach(obj => {
                const categoria = performanceCategories.find(cat => cat.id_cat_rendimiento === obj.id_cat_rendimiento);
                if (!categoria) return; // No se encontró la categoría

                const rubrica = categoria.rubricas_rendimiento.find(rub => rub.id_rubrica_rendimiento === obj.id_rubrica_rendimiento);
                if (!rubrica) return; // No se encontró la rubrica

                const data = {
                    id_rubrica_rendimiento: obj.id_rubrica_rendimiento,
                    nombre_cat_rendimiento: categoria.nombre_cat_rendimiento,
                    id_cat_rendimiento: obj.id_cat_rendimiento,
                    opcion_rubrica_rendimiento: rubrica.opcion_rubrica_rendimiento,
                    puntaje_rubrica_rendimiento: rubrica.puntaje_rubrica_rendimiento,

                };
                //   console.log(data);
                console.log(data);
                this.saveEvaluationResponses(data);
            });
        },
        mapperIncidents() {
            const incidentesEvaluation = this.registroEvaluacion.incidentes_evaluacion;

            if (!incidentesEvaluation) {
                // Maneja el caso en el que alguna de las variables sea undefined
                return;
            }
            this.incidentesEvaluaciones = []
            incidentesEvaluation.forEach(element => {
                this.incidentesEvaluaciones.push({
                    id: v4(),
                    id_incidente_evaluacion: element.id_incidente_evaluacion,
                    id_cat_rendimiento: element.id_cat_rendimiento,
                    resultado_incidente_evaluacion: element.resultado_incidente_evaluacion,
                    comentario_incidente_evaluacion: element.comentario_incidente_evaluacion,
                    isDelete: false,
                })
            });

        },
    },
    watch: {
        /*         registroEvaluacionRendimientoPersonal() {
        
                    //   alert("bruh")
                }, */
        dataEvaluacion() {
            // this.evaluacionData = this.dataEvaluacion
            //  console.log(this.evaluacionData.categorias_rendimiento);
            //  this.responsesWithScores = []

        },
        /**
         * Ejecuta accion cuando este detecta un cambio
         * @param {Object} newVal -- Nuevo valor de contenido evaluacion
         */
        contenidoEvaluacionRendimiento(newVal) {
            this.responsesWithScores = []
            this.incidentesEvaluaciones = []

            // Activa la animación estableciendo showErrorWhenValuesChanges en true
            this.showErrorWhenValuesChanges = true;
            // Establece un temporizador para desactivar la animación después de un breve período de tiempo (por ejemplo, 1 segundo)
            setTimeout(() => {
                this.showErrorWhenValuesChanges = false;
            }, 700); // 1000 milisegundos = 1 segundo (ajústalo según la duración de tu animación)

            this.registroEvaluacion = this.registroEvaluacionRendimientoPersonal
            //this.contenidoEvaluacion = this.contenidoEvaluacionRendimiento
            // console.log(this.contenidoEvaluacionRendimiento);

            this.mapperResponse()
            this.mapperIncidents()



        }

    }
}
</script>

<style scoped>
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

/* Define las clases de animación */
@keyframes slideOutRight {
    0% {
        opacity: 1;
        transform: translateX(0);
    }

    100% {
        opacity: 0;
        transform: translateX(100%);
    }
}

@keyframes slideInLeft {
    0% {
        opacity: 0;
        transform: translateX(-100%);
    }

    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Agrega estas clases a tu elemento */
.animate-slide-out-right {
    animation: slideOutRight 0.5s ease both;
}

.animate-slide-in-left {
    animation: slideInLeft 0.5s ease both;
}
</style>