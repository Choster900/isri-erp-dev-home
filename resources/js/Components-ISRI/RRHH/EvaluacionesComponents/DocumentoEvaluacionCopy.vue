
<template>
    <div class="mx-4 overflow-y-auto h-[550px] py-3 px-2 mb-4 ">
        <div v-if="rubricaAndCategoriaByEvaluacion && !isLoadingObtenerCategoriaYRubrica && evaluacionPersonalProp">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="border border-black w-40" rowspan="4">
                            <div class="w-40 px-1.5">
                                <img src="../../../../img/isri-gob.png" alt="logotipo-isri" draggable="false">
                            </div>
                        </td>
                        <td class="border text-sm border-black text-center font-medium uppercase" rowspan="2">
                            FORMULARIO DE EVALUACIÓN DEL DESEMPEÑO
                        </td>
                        <td class="border border-black text-[7pt] font-semibold pr-3 px-1.5">FECHA : {{
                            evaluacionPersonalProp.data.fecha_evaluacion_personal }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black text-[7pt] font-semibold   px-1.5">EMPLEADO: {{
                            evaluacionPersonalProp.allData.codigo_empleado }}</td>
                    </tr>
                    <tr>
                        <td class="border text-sm border-black text-center font-medium px-8" rowspan="2">
                            <!--  {{ evaluacionPersonalProp }} -->
                            PARA {{
                                separarTexto(evaluacionPersonalProp.data.evaluacion_rendimiento.nombre_evaluacion_rendimiento)
                            }}
                        </td>
                        <td class="border border-black text-[7pt] font-semibold py-0.5 px-1.5">
                            CENTRO:ADMON
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black text-[7pt] font-semibold  py-0.5 px-1.5">
                            CODIGO: {{ evaluacionPersonalProp.data.evaluacion_rendimiento.codigo_evaluacion_rendimiento }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black bg-black h-5 text-white text-center text-[9pt] " colspan="3">
                            ESPECIFICACIONES
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="especificaciones">
                <div class="flex items-center justify-between pt-4 gap-2">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">EMPLEADO:</label>
                        <input type="text"
                            :value="`${evaluacionPersonalProp.allData.persona.pnombre_persona || ''} ${evaluacionPersonalProp.allData.persona.snombre_persona || ''} ${evaluacionPersonalProp.allData.persona.tnombre_persona || ''} ${evaluacionPersonalProp.allData.persona.papellido_persona || ''} ${evaluacionPersonalProp.allData.persona.sapellido_persona || ''} ${evaluacionPersonalProp.allData.persona.tapellido_persona || ''}`"
                            class="w-56 text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUESTO:</label>

                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                            .map((plaza, index) => {
                                return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                            }).join('-') || ''"
                            class="w-64 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                                    }).join('-') || ''"
                                    class="w-64 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class="text-sm font-medium text-white">Información de Plazas Asignadas
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                {{ data.plaza_asignada.detalle_plaza.plaza.nombre_plaza }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">SUELDO:</label>

                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                            .map((plaza, index) => {
                                return plaza.plaza_asignada.detalle_plaza.plaza.salario_base_plaza;
                            }).join('-') || ''"
                            class="w-16 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.detalle_plaza.plaza.salario_base_plaza;
                                    }).join('-') || ''"
                                    class="w-16 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class="text-sm font-medium text-white">Información de salario
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                $ {{
                                                                    data.plaza_asignada.detalle_plaza.plaza.salario_base_plaza
                                                                }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 gap-1">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">UNIDAD ADM:
                            (Direccion,Division,Dpto.,Unidad,Seccion,Area)</label>

                        <input type="text" :value="`${evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.dependencia.nombre_dependencia;
                                }).join('-') || ''} - ${evaluacionPersonalProp.data.plaza_evaluada &&
                                evaluacionPersonalProp.data.plaza_evaluada
                                    .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.centro_atencion.nombre_centro_atencion;
                                    }).join('-') || ''}`"
                            class="w-32 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="`${evaluacionPersonalProp.data.plaza_evaluada &&
                                    evaluacionPersonalProp.data.plaza_evaluada
                                        .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                        .map((plaza, index) => {
                                            return plaza.plaza_asignada.dependencia.nombre_dependencia;
                                        }).join('-') || ''} - ${evaluacionPersonalProp.data.plaza_evaluada &&
                                        evaluacionPersonalProp.data.plaza_evaluada
                                            .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                            .map((plaza, index) => {
                                                return plaza.plaza_asignada.centro_atencion.nombre_centro_atencion;
                                            }).join('-') || ''}`"
                                    class="w-32 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class="text-sm font-medium text-white">Información de UNIDAD
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                {{
                                                                    data.plaza_asignada.centro_atencion.nombre_centro_atencion
                                                                }}
                                                                -
                                                                {{ data.plaza_asignada.dependencia.nombre_dependencia }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PERIODO COMPRENDIDO:</label>
                        <input type="text"
                            :value="evaluacionPersonalProp.data.periodo_evaluacion.id_periodo_evaluacion == 1 ? `ENERO A JUNIO ${moment(evaluacionPersonalProp.data.fecha_evaluacion_personal).format('YYYY')}` : `JULIO A DICIEMBRE ${moment(evaluacionPersonalProp.data.fecha_evaluacion_personal).format('YYYY')}`"
                            class="w-36 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 gap-2 pb-4">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">JEFE INME:</label>

                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return `${plaza.plaza_asignada.dependencia.jefatura?.pnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.snombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.papellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.sapellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tapellido_persona || ''}`;
                                }).join('-') || ''"
                            class="w-40 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="right" :key="weekIndex" v-else>
                            <template v-slot:contenido>


                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                                    evaluacionPersonalProp.data.plaza_evaluada
                                        .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                        .map((plaza, index) => {
                                            return `${plaza.plaza_asignada.dependencia.jefatura?.pnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.snombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.papellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.sapellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tapellido_persona || ''}`;
                                        }).join('-') || ''"
                                    class="w-40 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class="text-sm font-medium text-white">Información de JEFATURA
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                {{
                                                                    `
                                                                ${data.plaza_asignada.dependencia.jefatura?.pnombre_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.snombre_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.tnombre_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.papellido_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.sapellido_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.tapellido_persona
                                                                        || ''}
                                                                `
                                                                }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>


                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUESTO:</label>
                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.dependencia.jefatura?.empleado.plazas_asignadas.map((plaza, index) => {
                                        return plaza.detalle_plaza.plaza.nombre_plaza
                                    })
                                }).join('-') || ''"
                            class="w-72 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                                    evaluacionPersonalProp.data.plaza_evaluada
                                        .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                        .map((plaza, index) => {
                                            return plaza.plaza_asignada.dependencia.jefatura?.empleado.plazas_asignadas.map((plaza, index) => {
                                                return plaza.detalle_plaza.plaza.nombre_plaza
                                            })
                                        }).join('-') || ''"
                                    class="w-72 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class="text-sm font-medium text-white">Información de PUESTO DE JEFATURA
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div v-for="(data, i) in evaluacionPersonalProp.data.plaza_evaluada"
                                                    :key="i">
                                                    <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                        <ul class="ml-10 list-circle py-2">
                                                            <li v-for="(plaza, key) in data.plaza_asignada.dependencia.jefatura?.empleado.plazas_asignadas"
                                                                :key="key"
                                                                class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                <p v-if="plaza.estado_plaza_asignada === 1"
                                                                    class="text-white">
                                                                    {{ plaza.detalle_plaza.plaza.nombre_plaza }}
                                                                </p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>

                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUNTAJE TOTAL:</label>
                        <input type="text"
                            :value="optionsSelected.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0)"
                            class="w-12 text-left text-[9pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
            </div>
            <table border="0" cellpadding="0" cellspacing="0">
                <tbody v-for="(evaluacion, i) in rubricaAndCategoriaByEvaluacion.categorias_rendimiento" :key="i">
                    <tr class="">
                        <td class="border border-black bg-black text-[9pt] text-white text-center pl-8 py-1" colspan="2">{{
                            evaluacion.nombre_cat_rendimiento }}
                        </td>
                        <td class="border border-black bg-black text-[9pt] text-white text-start pl-1">PUNTOS: {{
                            (optionsSelected.find(obj => obj.id_cat_rendimiento === evaluacion.id_cat_rendimiento) || {
                                puntaje_rubrica_rendimiento: 0
                            }).puntaje_rubrica_rendimiento
                        }}</td>
                    </tr>
                    <tr class="">
                        <td class="border border-black bg-slate-300/60"
                            :rowspan="evaluacion.rubricas_rendimiento.length + 1">
                            <div class="w-48 px-3 text-[8pt] text-justify text-[#4f4f4f] font-medium">{{
                                evaluacion.descripcion_cat_rendimiento }}
                            </div>
                        </td>

                    </tr>
                    <tr class="" v-for="(rubrica, j ) in evaluacion.rubricas_rendimiento" :key="j">
                        <td class="text-[8pt] border-l-0 border-r border-t-0 border-black w-full border-b px-2 py-2">
                            {{ rubrica.descripcion_rubrica_rendimiento }}
                        </td>
                        <td
                            class="border-x-0 border-t-0 border-black justify-center text-center  px-4 py-2 border-b border-r">
                            <div class="container mt-1">
                                <label>
                                    <input type="radio" :name="evaluacion.nombre_cat_rendimiento"
                                        @click="saveResponseWhenIsClickedCheckbox(evaluacion.id_cat_rendimiento, rubrica.id_rubrica_rendimiento, rubrica.puntaje_rubrica_rendimiento)"
                                        :checked="optionsSelected.find(cat => cat.id_cat_rendimiento === evaluacion.id_cat_rendimiento)?.id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento">
                                    <span class="text-xs justify-center text-center "> {{
                                        rubrica.opcion_rubrica_rendimiento }}</span>
                                </label>
                            </div>
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
                            v-for="(data, i) in rubricaAndCategoriaByEvaluacion.categorias_rendimiento" :key="i">
                            <td class="border border-black text-start px-2 w-4">
                                {{ i + 1 }} - {{ data.nombre_cat_rendimiento }}
                            </td>
                            <td v-for="(rubrica, j) in data.rubricas_rendimiento" :key="j"
                                class="border border-black  text-[8pt] w-4"
                                :class="optionsSelected.find(cat => cat.id_cat_rendimiento === data.id_cat_rendimiento)?.id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento ? 'bg-slate-400 text-black' : ''">
                                {{ rubrica.puntaje_rubrica_rendimiento }}
                            </td>
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
                        <tr v-for="range in ranges" :key="range.label" class="text-start text-[8pt]">
                            <td class="border border-black"
                                :class="{ 'bg-slate-400': isScoreInRange(range.min, range.max) }">
                                {{ range.label }}
                            </td>
                            <td class="border border-black">
                                {{ range.min }} a {{ range.max }} puntos
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <button @click="guardarYEnviarEvaluacion"
                class="bg-indigo-900 rounded-sm shadow text-center text-white text-sm font-light w-full py-1 mt-5">
                TERMINAR EVALUACIÓN (Los datos se guardaran entonces)</button>
        </div>
    </div>
</template>

<script>
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
import { ref, toRefs, watch } from 'vue';
import Tooltip from '@/Components-ISRI/Tooltip.vue';
import moment from 'moment';
import Swal from "sweetalert2";
import { toast } from 'vue3-toastify'
import { executeRequest } from "@/plugins/requestHelpers.js";
import 'vue3-toastify/dist/index.css';
export default {
    components: { Tooltip },
    props: {
        evaluacionPersonalProp: { // Objeto la tabla evaluacion personal
            type: Object,
            default: () => { },
        },
        rubricaAndCategoriaByEvaluacion: { // Objeto que trae las categorias y rubricas de la evaluacion seleccionada
            type: Object,
            default: () => { },
        },
        isLoadingObtenerCategoriaYRubrica: { // Prop que controla el evento de carga al request de las cateogrias y rubricass de la evaluacion
            type: Object,
            default: false,
        },
    },
    setup(props) {
        const { evaluacionPersonalProp, rubricaAndCategoriaByEvaluacion } = toRefs(props)
        const { separarTexto, evaluacionPersonal, saveResponseWhenIsClickedCheckbox, optionsSelected, sendResponsesEvaluation, ranges, isScoreInRange } = useDocumentoEvaluacion();
        watch(evaluacionPersonalProp, (newValue, oldValue) => {
            if (newValue) {
                optionsSelected.value = []
                evaluacionPersonal.value = newValue.data
                console.log(evaluacionPersonal.value);
                evaluacionPersonal.value.detalle_evaluaciones_personal.forEach(element => {
                    optionsSelected.value.push({
                        id_cat_rendimiento: element.categoria_rendimiento.id_cat_rendimiento,
                        id_rubrica_rendimiento: element.rubrica_rendimiento.id_rubrica_rendimiento,
                        puntaje_rubrica_rendimiento: element.rubrica_rendimiento.puntaje_rubrica_rendimiento
                    })
                });
            }
        })

        const guardarYEnviarEvaluacion = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[16pt] text-center">¿Esta seguro de enviar la evaluacion?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Agregar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {

                if (rubricaAndCategoriaByEvaluacion.value.categorias_rendimiento.length == optionsSelected.value.length) {
                    let res = null;
                    res = await executeRequest(
                        sendResponsesEvaluation(),
                        "La evaluacion se ha enviado"
                    );
                    console.log("HACEMOS ALGO DESPUES");
                } else {
                    alert("alerta ");

                }
                console.log(rubricaAndCategoriaByEvaluacion.value.categorias_rendimiento.length);
                console.log(optionsSelected.value.length);
            }
        };



        return {
            separarTexto,
            optionsSelected,
            moment,
            optionsSelected,
            guardarYEnviarEvaluacion,
            isScoreInRange,
            ranges,
            saveResponseWhenIsClickedCheckbox,
            evaluacionPersonal,
            sendResponsesEvaluation,
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

/* Estilos para la barra de desplazamiento en Webkit (Chrome, Safari) */
::-webkit-scrollbar {
    width: 10px;
    /* Ancho de la barra de desplazamiento */
}

::-webkit-scrollbar-thumb {
    background-color: #c1c2c5;
    /* Color de la manija de desplazamiento */
    border-radius: 5px;
    /* Radio de la manija de desplazamiento */
}

::-webkit-scrollbar-track {
    background-color: #f3f4f6;
    /* Color del fondo de la barra de desplazamiento */
}
</style>