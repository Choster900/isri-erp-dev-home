
<template>
    <div class="mx-4 overflow-y-auto h-[550px] py-3 px-2 mb-4 ">
        <div v-if="rubricaAndCategoriaByEvaluacion && !isLoadingObtenerCategoriaYRubrica && evaluacionPersonalProp != ''">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="border border-black w-40" rowspan="4">
                            <div class="w-40 px-1.5">
                                <img src="../../../../img/isri-gob.png" alt="logotipo-isri" draggable="false">
                            </div>
                        </td>
                        <td class="border text-sm border-black text-center font-medium uppercase" rowspan="2">
                            <div class="flex items-center justify-center">
                                <div class="relative text-center">
                                    FORMULARIO DE ANALISIS DE DESEMPEÑO
                                </div>
                                <!-- <svg @click="printEvaluacionDesemp" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-5 h-5 ml-2 stroke-slate-400 hover:stroke-slate-500 cursor-pointer">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                </svg> -->
                            </div>
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
                            class="w-44 text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUESTO:</label>
                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                            .map((plaza, index) => {
                                return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                            }).join('-') || ''"
                            class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                                    }).join('-') || ''"
                                    class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de Plazas Asignadas
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
                            class="w-16 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.detalle_plaza.plaza.salario_base_plaza;
                                    }).join('-') || ''"
                                    class="w-16 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de salario
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
                            class="w-32 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
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
                                    class="w-32 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de UNIDAD
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
                            class="w-36 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
                <div class="flex items-center justify-start pt-2 gap-2 pb-4">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">JEFE INME:</label>

                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return `${plaza.plaza_asignada.dependencia.jefatura?.pnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.snombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.papellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.sapellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tapellido_persona || ''}`;
                                }).join('-') || ''"
                            class="w-40 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="right" :key="weekIndex" v-else>
                            <template v-slot:contenido>


                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                                    evaluacionPersonalProp.data.plaza_evaluada
                                        .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                        .map((plaza, index) => {
                                            return `${plaza.plaza_asignada.dependencia.jefatura?.pnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.snombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.papellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.sapellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tapellido_persona || ''}`;
                                        }).join('-') || ''"
                                    class="w-40 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de JEFATURA
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
                            class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
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
                                    class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de PUESTO DE
                                                    JEFATURA
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

                </div>
            </div>

            <table class="">
                <thead>
                    <tr
                        class="*:border *:text-xs *:text-center *:align-middle *:border-black *:h-7 *:text-white *:bg-black ">
                        <td class="w-24">#</td>
                        <td class="w-32">FECHA</td>
                        <td class="w-40">CATEGORIA</td>
                        <td class="w-28">TIPO INCIDENTE</td>
                        <td class="w-52">COMENTARIO</td>
                    </tr>
                </thead>
                <tbody v-if="dataIndiceEvaluacion.length > 0">
                    <template v-for="(incidente, i) in dataIndiceEvaluacion" :key="i">
                        <tr @contextmenu.prevent="deleteRow(incidente.idIncidenteEvaluacion)" v-if="!incidente.isDeleted"
                            class="*:border *:text-xs *:text-center *:align-middle *:border-black *:h-24 *:text-slate-600">
                            <td class="w-24">{{ i + 1 }}</td>
                            <td class="w-32">{{ moment(incidente.fechaRegIncidenteEvaluacion).format('L') }}</td>
                            <td class="w-40">
                                {{ opcionesCategoriaRendimiento ? opcionesCategoriaRendimiento.find(index => index.value ==
                                    incidente.idCategoriaRendimiento)?.label : 'No disponible' }}
                            </td>

                            <td class="w-28">
                                {{ incidente.resultadoIncidenteEvaluacion == 0 ? 'F' : 'D' }}
                            </td>
                            <td class="w-52 p-2" @input="incidente.comentarioIncidenteEvaluacion = $event.target.innerText">
                                {{ incidente.comentarioIncidenteEvaluacion }}
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="5" class="text-center">No hay incidentes de evaluación disponibles</td>
                    </tr>
                </tbody>

            </table>


        </div>

        <div v-if="isLoadingObtenerCategoriaYRubrica" class="flex items-center justify-center h-full">
            <div aria-label="Loading..." role="status" class="loader">
                <svg class="icon" viewBox="0 0 256 256">
                    <line x1="128" y1="32" x2="128" y2="64" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                    <line x1="195.9" y1="60.1" x2="173.3" y2="82.7" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                    <line x1="224" y1="128" x2="192" y2="128" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                    <line x1="195.9" y1="195.9" x2="173.3" y2="173.3" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                    <line x1="128" y1="224" x2="128" y2="192" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                    <line x1="60.1" y1="195.9" x2="82.7" y2="173.3" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                    <line x1="32" y1="128" x2="64" y2="128" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                    <line x1="60.1" y1="60.1" x2="82.7" y2="82.7" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="24"></line>
                </svg>
                <span class="loading-text">Cargando...</span>
            </div>
        </div>


        <div class="w-full h-[500px] px-1 text-selection-disable"
            v-if="!isLoadingObtenerCategoriaYRubrica && rubricaAndCategoriaByEvaluacion == ''">

            <div class="flex flex-col items-center justify-center h-full">
                <img src="../../../../img/evaluationModal.svg" class="h-96 rounded-full mx-auto" alt="SVG Image"
                    draggable="false">
                <h1 class="font-medium text-center">Todas tus evaluaciones aparecerán aquí</h1>
                <p class="text-[9pt] text-center">Parece que la evaluación aún no está lista. Comunícate con tu jefe
                    inmediato para obtener más información.</p>

            </div>
        </div>

    </div>
</template>

<script>
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
import { ref, toRefs, watch, createApp, h } from 'vue';
import Tooltip from '@/Components-ISRI/Tooltip.vue';
import moment from 'moment';
import Swal from "sweetalert2";
import { toast } from 'vue3-toastify'
import { executeRequest } from "@/plugins/requestHelpers.js";
import 'vue3-toastify/dist/index.css';
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import EvaluacionPdfVue from '@/pdf/RRHH/EvaluacionPdf.vue';
import { useAnalisisDes } from '@/Composables/RRHH/Evaluaciones/useAnalisisDes';
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
    emit: ["actualizar-datatable"],
    setup(props, { emit }) {
        const { evaluacionPersonalProp, rubricaAndCategoriaByEvaluacion } = toRefs(props)
        const { separarTexto, evaluacionPersonal, saveResponseWhenIsClickedCheckbox, optionsSelected, sendResponsesEvaluation, ranges, isScoreInRange } = useDocumentoEvaluacion();
        const { opcionesCategoriaRendimiento, objectCat, dataIndiceEvaluacion, addDataIndiceEvaluacion, deleteRow, saveIncidentesEvaluacionesRequest } = useAnalisisDes(evaluacionPersonalProp)

        watch(rubricaAndCategoriaByEvaluacion, (newValue, oldValue) => {
            if (newValue != '') {
                objectCat.value = newValue.categorias_rendimiento
            } else {
                objectCat.value = null;
            }
        })

        watch(evaluacionPersonalProp, (newValue, oldValue) => {
            if (newValue !== null && typeof newValue === 'object' && 'data' in newValue) {
                const { incidentes_evaluacion } = newValue.data;
                dataIndiceEvaluacion.value = []

                incidentes_evaluacion.forEach(element => {
                    dataIndiceEvaluacion.value.push({
                        idEvaluacionPersonal: evaluacionPersonalProp.value.data.id_evaluacion_personal,
                        idCategoriaRendimiento: element.id_cat_rendimiento,
                        resultadoIncidenteEvaluacion: element.resultado_incidente_evaluacion,
                        comentarioIncidenteEvaluacion: element.comentario_incidente_evaluacion,
                        fechaRegIncidenteEvaluacion: element.fecha_reg_incidente_evaluacion,
                        idIncidenteEvaluacion: element.id_incidente_evaluacion,
                        isDeleted: false,
                    })

                });
            }
        });






        return {
            separarTexto,
            optionsSelected,
            moment,
            dataIndiceEvaluacion,
            optionsSelected,
            opcionesCategoriaRendimiento,
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

.loader {
    display: flex;
    align-items: center;
}

.icon {
    height: 1.5rem;
    width: 1.5rem;
    animation: spin 1s linear infinite;
    stroke: rgba(107, 114, 128, 1);
}

.loading-text {
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 500;
    color: rgba(107, 114, 128, 1);
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
