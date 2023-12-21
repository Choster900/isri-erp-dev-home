
<template>
    <div class="m-1.5">
        <!-- Componente del modal ProcessModal -->
        <ProcessModal maxWidth="6xl" :show="showModal" @close="$emit('cerrar-modal')">
            <div class="flex flex-col md:flex-row  ">
                <div class="w-full md:w-2/6 bg-slate-200/40 p-3 border">
                    <div class="col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300 ">
                        <header class="px-5 py-2 border-b-4 border-indigo-500">
                            <h2 class="font-semibold text-slate-800">Crear una nueva evaluación +</h2>
                        </header>
                        <div class="p-3">
                            <div class="mx-0.5 mt-2 mb-2">
                                <label class="block text-gray-700 text-xs font-medium mb-1" for="name">
                                    Seleccione el tipo de evaluacion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="flex flex-wrap items-center -m-3">
                                    <div class="m-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="tipoEvaluacion" v-model="idTipoEvaluacion"
                                                class="form-radio" checked="true" value="1"
                                                @change=" getPlazasByEmployeeIdAndCentroAtencionId()">
                                            <span class="text-xs ml-2 text-selection-disable">Desempeño</span>
                                        </label>
                                    </div>
                                    <div class="m-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="tipoEvaluacion" v-model="idTipoEvaluacion"
                                                class="form-radio" value="2"
                                                @change="  getPlazasByEmployeeIdAndCentroAtencionId()">
                                            <span class=" text-xs ml-2 text-selection-disable">Periodo de prueba</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <InputError class="mt-2 px-2"
                                :message="errorsData && errorsData.idTipoEvaluacion ? errorsData.idTipoEvaluacion : ''" />
                            <div class="-mx-3 flex">
                                <div class="mb-1 px-3 w-full">
                                    <div class="mb-1 md:mr-0 md:mb-0 basis-1/2">
                                        <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Fecha [Desde
                                            - Hasta] <span class="text-red-600 font-extrabold">*</span></label>
                                        <div class="relative flex gap-1">
                                            <flat-pickr v-model="fechaInicioFechafin"
                                                class="text-xs cursor-pointer rounded-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                                :config="configSecondInput" :placeholder="'Seleccione Fecha Inicial'" />

                                            <Tooltip bg="dark" position="right" :key="weekIndex" class="mt-1.5">
                                                <template v-slot:contenido>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        @click="clearLock" title="Quitar bloqueos de fecha"
                                                        stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5 cursor-pointer">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                </template>
                                                <template v-slot:message>
                                                    <div class="text-xs text-slate-200 w-28 text-center">
                                                        Limpiar bloqueos
                                                    </div>
                                                </template>
                                            </Tooltip>
                                        </div>
                                        <InputError class="mt-2 px-2"
                                            :message="errorsData && errorsData.fechaInicioFechafin ? errorsData.fechaInicioFechafin : ''" />

                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 flex gap-1">
                                <div class="mb-1 px-3  w-full">
                                    <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Nombre del
                                        empleado <span class="text-red-600 font-extrabold">*</span></label>
                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect v-if="!opcionEmpleado || opcionEmpleado == ''" v-model="idEmpleado"
                                            :disabled="loadingEvaluacionRendimiento"
                                            @select="getPlazasByEmployeeIdAndCentroAtencionId()" :filter-results="false"
                                            :resolve-on-load="false" :delay="1000" :searchable="true"
                                            :clear-on-search="true" :min-chars="5" placeholder="Busqueda de empleado..."
                                            :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>" :options="async function (query) {
                                                return await handleEmployeeSearch(query)
                                            }" />

                                        <Multiselect v-else v-model="selectedEmpleadoValue" :filter-results="false"
                                            :disabled="true" :resolve-on-load="false" :delay="1000" :searchable="true"
                                            :clear-on-search="true" :min-chars="5" placeholder="Busqueda de usuario..."
                                            :classes="{
                                                wrapper: 'relative text-xs cursor-not-allowed mx-auto w-full flex items-center justify-end box-border cursor-pointer outline-none',
                                                containerDisabled: 'cursor-not-allowed bg-gray-200 text-text-slate-400',
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                            :options="opcionEmpleado" />
                                    </div>
                                    <InputError class="mt-2 px-2"
                                        :message="errorsData && errorsData.idEmpleado ? errorsData.idEmpleado : ''" />
                                </div>
                            </div>
                            <div class=" flex gap-1">
                                <div class="mb-1 -ml-3 pl-3 w-7/12">
                                    <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Centro de
                                        atencion <span class="text-red-600 font-extrabold">*</span></label>
                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect :searchable="true" v-model="idCentroAtencion" placeholder="Centros"
                                            :disabled="loadingEvaluacionRendimiento"
                                            @select="getPlazasByEmployeeIdAndCentroAtencionId()" :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin dependencias<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de dependencias <p>"
                                            :options="listDependencias" />
                                    </div>
                                    <InputError class="mt-2 px-2"
                                        :message="errorsData && errorsData.idCentroAtencion ? errorsData.idCentroAtencion : ''" />
                                </div>
                                <div class="mb-1 -mr-3 pr-3 w-1/2">
                                    <div class="flex gap-1">
                                        <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Evaluacion
                                            <span class="text-red-600 font-extrabold">*</span>
                                            <svg v-if="loadingEvaluacionRendimiento" aria-hidden="true" role="status"
                                                class="inline mr-3 w-4 h-4 text-indigo-500 animate-spin"
                                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                    fill="#E5E7EB"></path>
                                                <path
                                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                        </label>
                                        <Tooltip bg="dark" position="right" :key="weekIndex" class="-mt-1"
                                            v-if="!showPlazasModal && evaluationsOptions.length < 2 && !loadingEvaluacionRendimiento">
                                            <template v-slot:contenido>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2.5" stroke="currentColor" class="inline mr-3 w-4 h-">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                                </svg>
                                            </template>
                                            <template v-slot:message>
                                                <div class="text-xs text-slate-200">
                                                    <table class="min-w-full w-40 border border-white"
                                                        v-if="objectPlazas && objectPlazas.length > 0">
                                                        <tr>
                                                            <th class="py-0.5 text-[7.5pt] w-36 px-2 border-b">Nombre de la
                                                                Plaza</th>
                                                        </tr>
                                                        <tr v-for="(plaza, index) in objectPlazas" :key="index">
                                                            <td class="py-0.5 text-[7.5pt] w-36 px-2 border-b">
                                                                {{ plaza.label }} -
                                                                {{ plaza.dependencia["nombre_dependencia"] }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="min-w-full w-40 border border-white" v-else>
                                                        <tr>
                                                            <th class="py-0.5 text-[7.5pt] w-36 px-2 border-b">Sin plazas
                                                            </th>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </template>
                                        </Tooltip>
                                    </div>
                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect :filter-results="false" :resolve-on-load="false" :delay="1000"
                                            v-model="idEvaluacionRendimiento" :disabled="!showPlazasModal"
                                            :searchable="true" :clear-on-search="true" :min-chars="5"
                                            placeholder="Evaluaciones" :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin evaluaciones<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de evaluaciones <p>"
                                            :options="evaluationsOptions" />
                                    </div>
                                    <InputError class="mt-2 px-2"
                                        :message="errorsData && errorsData.idEvaluacionRendimiento ? errorsData.idEvaluacionRendimiento : ''" />
                                </div>
                            </div>
                            <InputError v-if="doesntExistResult" class="mt-1 px-2"
                                message="No hay plazas disponibles para la dependencia seleccionada." />
                            <div class="-mx-3 flex gap-1" v-if="showPlazasModal">
                                <div class="mb-1 px-3  w-full">
                                    <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Todas las plazas
                                        <span class="text-red-600 font-extrabold">*</span></label>
                                    <Multiselect mode="tags" placeholder="Seleccione las plazas a evaluar"
                                        :disabled="idEvaluacionRendimiento == '' || idEvaluacionRendimiento == null"
                                        :close-on-select="false" v-model="objectPlazas" @select="handleTagToSelect"
                                        noOptionsText="<p class='text-xs'>No hay resultado de plaza<p>"
                                        noResultsText="<p class='text-xs'>Vacio <p>" :classes="{
                                            containerDisabled: 'cursor-default bg-gray-100',
                                            wrapper: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer outline-none',
                                            container: 'text-xs relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                            placeholder: 'text-xs flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                        }" :object="true" :options="plazaOptions" />
                                </div>

                            </div>
                            <InputError v-if="showPlazasModal" class="mt-2 px-2 border-b"
                                :message="errorsData && errorsData.plazasAsignadas ? errorsData.plazasAsignadas : ''" />
                            <InputError v-if="existMoreThanOne" class="mt-1 px-2"
                                message="La dependencia tiene múltiples tipos de plazas. Por favor, seleccione una evaluación y las plazas que desea evaluar." />


                            <button @click="createEvaluationPersona"
                                class="bg-indigo-900 rounded-sm shadow text-center text-white text-sm font-light w-full py-1 mt-5">Crear
                                una nueva evaluación</button>
                        </div>
                    </div>
                    <div
                        class="h-[300px] overflow-y-auto col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300">
                        <div class="p-3">
                            <div class="max-h-[300px] ">
                                <article class="pt-4 border-b border-slate-200"
                                    v-for="(año, i) in evaluacionesAgrupadasPorAño" :key="i">

                                    <header class="flex items-start mb-2 cursor-pointer"
                                        @click='activeIndex = activeIndex === i ? null : i'>
                                        <div class="mr-3">
                                            <svg class="w-4 h-4 shrink-0 fill-current" viewBox="0 0 16 16">
                                                <path class="text-indigo-300"
                                                    d="M4 8H0v4.9c0 1 .7 1.9 1.7 2.1 1.2.2 2.3-.8 2.3-2V8z">
                                                </path>
                                                <path class="text-indigo-500"
                                                    d="M15 1H7c-.6 0-1 .4-1 1v11c0 .7-.2 1.4-.6 2H13c1.7 0 3-1.3 3-3V2c0-.6-.4-1-1-1z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm text-selection-disable"
                                            :class="activeIndex === i ? 'text-slate-800 font-medium' : 'text-slate-400 font-medium'">
                                            Evaluaciones del año {{ año.year }}</h3>

                                    </header>
                                    <div class="accordion-content" :class="i === activeIndex ? 'open' : ''">
                                        <div>
                                            <div v-for="(evaluacion, j) in año.evaluaciones" :key="j"
                                                @click="obtenerCategoriaYRubricaRendimiento(evaluacion.evaluacion_rendimiento.id_evaluacion_rendimiento); evaluacionToPassDocumento = { data: evaluacion, allData: año.allContent }"
                                                class="bg-slate-300/40 border-l-[4px] border-y-0 border-r-0 border-l-indigo-500 hover:bg-slate-300 cursor-pointer"
                                                :class="j == 1 ? ' border-b border-b-slate-400' : ''">
                                                <ul class="ml-10 list-circle py-2 ">
                                                    <li
                                                        class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Puntuación</li>
                                                    <p class="font-medium text-xs">{{
                                                        evaluacion.puntaje_evaluacion_personal }}</p>
                                                    <li
                                                        class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Fecha Evaluado</li>
                                                    <p class="font-medium text-xs uppercase">
                                                        {{ moment(evaluacion.fecha_reg_evaluacion_personal).
                                                            format('dddd, MMMM D, YYYY') }} -
                                                        {{ moment(evaluacion.fecha_reg_evaluacion_personal).fromNow() }}
                                                    </p>
                                                    <li
                                                        class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Plazas evaluadas</li>
                                                    <p class="font-medium text-xs">
                                                        {{ evaluacion.plaza_evaluada.map(plaza =>
                                                            plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza).join(' - ')
                                                        }}
                                                    </p>

                                                    <li
                                                        class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Formato aplicado</li>
                                                    <p class="font-medium text-xs">
                                                        {{ evaluacion.evaluacion_rendimiento.codigo_evaluacion_rendimiento
                                                        }}
                                                        -
                                                        {{ evaluacion.evaluacion_rendimiento.nombre_evaluacion_rendimiento
                                                        }}
                                                    </p>
                                                    <li
                                                        class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Periodo</li>
                                                    <p class="font-medium text-xs">
                                                        {{ evaluacion.periodo_evaluacion.nombre_periodo_evaluacion }} </p>
                                                    <li
                                                        class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Tipo evaluacion</li>
                                                    <p class="font-medium text-xs">
                                                        {{
                                                            evaluacion.tipo_evaluacion_personal.nombre_tipo_evaluacion_personal
                                                        }}
                                                    </p>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="justify-center w-full md:w-4/6">
                    <!-- Header evaluacion  -->
                    <div class=" flex  justify-center pt-2 content-between">
                        <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                            @click="$emit('cerrar-modal')">
                            <path fill="currentColor"
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                        </svg>

                    </div>
                    <!--/ Header evaluacion  -->

                    <!-- With Icons -->
                    <div class="mx-6 mt-2">
                        <h2 class="flex gap-2 text-xl text-slate-800 font-medium mb-6">Evaluación de Desempeño para Personal
                            Administrativo</h2>
                        <div class="mb-1 border-b border-slate-200">
                            <ul class="text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 ">
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a class=" whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current mr-2" viewBox=" 0 0 16 16">
                                            <path
                                                d="M12.311 9.527c-1.161-.393-1.85-.825-2.143-1.175A3.991 3.991 0 0012 5V4c0-2.206-1.794-4-4-4S4 1.794 4 4v1c0 1.406.732 2.639 1.832 3.352-.292.35-.981.782-2.142 1.175A3.942 3.942 0 001 13.26V16h14v-2.74c0-1.69-1.081-3.19-2.689-3.733zM6 4c0-1.103.897-2 2-2s2 .897 2 2v1c0 1.103-.897 2-2 2s-2-.897-2-2V4zm7 10H3v-.74c0-.831.534-1.569 1.33-1.838 1.845-.624 3-1.436 3.452-2.422h.436c.452.986 1.607 1.798 3.453 2.422A1.943 1.943 0 0113 13.26V14z" />
                                        </svg>
                                        <span>Medición de Competencias</span>
                                    </a>
                                </li>

                            </ul>
                        </div>

                    </div>
                    <DocumentoEvaluacionCopy :isLoadingObtenerCategoriaYRubrica="isLoadingObtenerCategoriaYRubrica"
                        :evaluacionPersonalProp="evaluacionToPassDocumento"
                        :rubricaAndCategoriaByEvaluacion="rubricaAndCategoriaByEvaluacion" />

                </div>
                <ModalAlert id="danger-modal" :modalOpen="showMessageAlert">
                    <div class="p-5 flex space-x-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-orange-100">
                            <svg class="w-4 h-4 shrink-0 fill-current text-orange-500" viewBox="0 0 16 16">
                                <path
                                    d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="text-lg font-semibold text-slate-800">¿Agregar plaza para evaluar?</div>
                            </div>
                            <div class="text-sm">
                                <div class="space-y">
                                    <p v-html="messageAlert"></p>
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-end space-x-2">
                                <button @click="handleCancel"
                                    class="btn-sm border border-slate-300 hover:border-slate-400 text-slate-600">Cancelar</button>
                                <button @click="handleAccept"
                                    class="btn-sm border border-transparent bg-orange-500 hover:bg-orange-600 text-white">Sí,
                                    evaluar</button>
                            </div>
                        </div>
                    </div>
                </ModalAlert>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import moment from 'moment';
import Swal from "sweetalert2";
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css';
import { Spanish } from "flatpickr/dist/l10n/es.js"
import Tooltip from '@/Components-ISRI/Tooltip.vue';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { executeRequest } from "@/plugins/requestHelpers.js";
import ModalMorenThanOneTipo from './ModalMorenThanOneTipo.vue';
import AccordionBasic from '@/Components-ISRI/AccordionBasic.vue';
import ModalAlert from '@/Components-ISRI/AllModal/ModalAlert.vue';
import DocumentoEvaluacionCopy from './DocumentoEvaluacionCopy.vue';
import { computed, onMounted, ref, toRef, toRefs, watch } from 'vue';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import { useEvaluacion } from '@/Composables/RRHH/Evaluaciones/useEvaluacion';
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
export default {
    components: {
        Modal,
        moment,
        Tooltip,
        ModalAlert,
        ProcessModal,
        AccordionBasic,
        DocumentoEvaluacionCopy,
        ModalMorenThanOneTipo,
        DocumentoEvaluacionCopy
    },
    emit: ["cerrar-modal", "actualizar-datatable"],
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
        listDependencias: {
            type: Object,
            default: () => { },

        },
        evaluacionPersonalProp: {
            type: Object,
            default: () => { },
        },
    },
    setup(props, { emit }) {
        const selectedDates = ref([]);
        const dangerModalOpen = ref(false);
        const evaluacionPersonal = ref(null);
        const { evaluacionPersonalProp, listDependencias, showModal } = toRefs(props);
        const evaluacionToPassDocumento = ref(null);
        const {
            idEmpleado, errorsData,
            activeIndex, objectPlazas,
            messageAlert, handleAccept,
            handleCancel, plazaOptions,
            showPlazasModal, existMoreThanOne,
            showMessageAlert, idTipoEvaluacion,
            idCentroAtencion, doesntExistResult,
            handleTagToSelect, evaluationsOptions,
            objectEvaluaciones, fechaInicioFechafin,
            handleEmployeeSearch, idEvaluacionRendimiento,
            loadingEvaluacionRendimiento, createPersonalEvaluationRequest, getPlazasByEmployeeIdAndCentroAtencionId,
        } = useEvaluacion();

        const { obtenerCategoriaYRubricaRendimiento,
            isLoadingObtenerCategoriaYRubrica,
            rubricaAndCategoriaByEvaluacion, } = useDocumentoEvaluacion()
        const configSecondInput = ref({
            mode: 'range',
            wrap: true,
            altInput: true,
            minDate: '',
            maxDate: '',
            altFormat: 'M j, Y',
            dateFormat: 'Y-m-d',
            weekNumbers: true,
            ordinal: function () {
                return "º";
            },
            disableMobile: 'true',
            locale: {
                rangeSeparator: ' a ',
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                },
                months: {
                    shorthand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                },
            },
            onChange: function (selectedDates, dateStr, instance) {
                if (idTipoEvaluacion.value == 1) {
                    // Verifica en qué rango de fechas se encuentra la primera fecha seleccionada
                    if (selectedDates.length === 1) {
                        /* const firstDate = selectedDates[0];
                        const january1 = new Date(firstDate.getFullYear(), 0, 1);
                        const june30 = new Date(firstDate.getFullYear(), 5, 30);
                        const july1 = new Date(firstDate.getFullYear(), 6, 1);
                        const december31 = new Date(firstDate.getFullYear(), 11, 31);

                        if (firstDate >= january1 && firstDate <= june30) {
                            // Si la fecha está entre enero 1 y junio 30
                            instance.set('minDate', january1);
                            instance.set('maxDate', june30);
                        } else if (firstDate >= july1 && firstDate <= december31) {
                            // Si la fecha está entre julio 1 y diciembre 31
                            instance.set('minDate', july1);
                            instance.set('maxDate', december31);
                        } */
                    } else {
                        getPlazasByEmployeeIdAndCentroAtencionId()
                    }
                } else {
                    /* const firstDate = selectedDates[0];
                    const maxDate = new Date(
                        firstDate.getFullYear(),
                        firstDate.getMonth() + 3,
                        firstDate.getDate()
                    );
                    instance.set('maxDate', maxDate); */
                    getPlazasByEmployeeIdAndCentroAtencionId()
                }
            },

        });

        watch(showModal, (newValue, oldValue) => {
            if (!newValue) {
                idCentroAtencion.value = null;
                idEmpleado.value = null;
                objectPlazas.value = null;
                evaluacionToPassDocumento.value = null;
                rubricaAndCategoriaByEvaluacion.value = [];
            }
        })

        watch(evaluacionPersonalProp, (newValue, oldValue) => {
            console.log({ newValue, oldValue });
            if (newValue != '') {
                evaluacionPersonal.value = newValue

                if (newValue.plazas_asignadas.length === 1) {
                    idCentroAtencion.value = newValue.plazas_asignadas[0].id_centro_atencion
                }
            } else {
                evaluacionPersonal.value = null;
            }
        })

        /**
         * Propiedad computada que genera un objeto con un array para obtener el id y el nombre de la persona seleccionada 
         * Esto se usa cuando estamos editando y queremos setear el id de la persona actual
         */
        const selectedEmpleadoValue = computed(() => {
            if (evaluacionPersonal.value &&
                evaluacionPersonal.value.evaluaciones_personal &&
                evaluacionPersonal.value.evaluaciones_personal.length > 0) {

                idEmpleado.value = evaluacionPersonal.value.persona.id_persona
                return evaluacionPersonal.value.persona.id_persona
            } else {
                idEmpleado.value = null
                return null;
            }
        });

        const opcionEmpleado = computed(() => {
            let objectPersonaOption = [];
            if (
                evaluacionPersonal.value &&
                evaluacionPersonal.value.evaluaciones_personal &&
                evaluacionPersonal.value.evaluaciones_personal.length > 0
            ) {

                objectPersonaOption = evaluacionPersonal.value.persona ? [
                    {
                        value: evaluacionPersonal.value.persona.id_persona,
                        label: `${evaluacionPersonal.value.persona.pnombre_persona || ''} ${evaluacionPersonal.value.persona.snombre_persona || ''} ${evaluacionPersonal.value.persona.tnombre_persona || ''}  ${evaluacionPersonal.value.persona.papellido_persona || ''}  ${evaluacionPersonal.value.persona.sapellido_persona || ''}  ${evaluacionPersonal.value.persona.tapellido_persona || ''}`
                    }] : [];
                return objectPersonaOption; // Corregir aquí: evaluacionPersonal.value.persona en lugar de evaluacionPersonal.persona
            } else {
                return null;
            }
        });



        const evaluacionesAgrupadasPorAño = computed(() => {
            const evaluacionesAgrupadas = {};
            // Verificar si evaluacionPersonal tiene evaluaciones_personal y no está vacío
            if (
                evaluacionPersonal.value &&
                evaluacionPersonal.value.evaluaciones_personal &&
                evaluacionPersonal.value.evaluaciones_personal.length > 0
            ) {
                evaluacionPersonal.value.evaluaciones_personal.forEach(evaluacion => {
                    const year = moment(evaluacion.fecha_inicio_evaluacion_personal).year();
                    // Verificar si ya existe una entrada para ese año
                    if (!evaluacionesAgrupadas[year]) {
                        evaluacionesAgrupadas[year] = {
                            year: year,
                            allContent: evaluacionPersonal.value,
                            evaluaciones: [],
                        };
                    }
                    // Agregar la evaluación al arreglo correspondiente al año
                    evaluacionesAgrupadas[year].evaluaciones.push(evaluacion);
                    activeIndex.value = moment(evaluacionPersonal.value.evaluaciones_personal[0].fecha_inicio_evaluacion_personal).year().toString();
                });
            }
            return evaluacionesAgrupadas;
        });

        const clearLock = () => {
            selectedDates.value = [];
            configSecondInput.value.minDate = null;
            configSecondInput.value.maxDate = null;
            fechaInicioFechafin.value = null;
            toast.info('Reinicio de filtros');
        }

        const createEvaluationPersona = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[16pt] text-center">¿Esta seguro de agregar una nueva evaluacion?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Agregar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                let res = null;
                res = await executeRequest(
                    createPersonalEvaluationRequest(),
                    "La evaluacion se ha creado"
                );
                evaluacionPersonal.value = res.data.response
                emit("actualizar-datatable")
            }
        };
        return {
            moment,
            evaluacionToPassDocumento,
            clearLock,
            errorsData,
            activeIndex,
            activeIndex,
            handleAccept,
            handleCancel,
            objectPlazas,
            messageAlert,
            selectedEmpleadoValue,
            plazaOptions,
            selectedDates,
            dangerModalOpen,
            obtenerCategoriaYRubricaRendimiento,
            showPlazasModal,
            showMessageAlert,
            isLoadingObtenerCategoriaYRubrica,
            rubricaAndCategoriaByEvaluacion,
            listDependencias,
            existMoreThanOne,
            idTipoEvaluacion,
            configSecondInput,
            handleTagToSelect,
            doesntExistResult,
            opcionEmpleado,
            evaluacionPersonal,
            objectEvaluaciones,
            evaluacionPersonal,
            evaluationsOptions,
            fechaInicioFechafin,
            handleEmployeeSearch,
            createEvaluationPersona,
            idEvaluacionRendimiento,
            evaluacionesAgrupadasPorAño,
            evaluacionesAgrupadasPorAño,
            idEmpleado, idCentroAtencion,
            loadingEvaluacionRendimiento,
            createPersonalEvaluationRequest,
            getPlazasByEmployeeIdAndCentroAtencionId,
        }
    }

}
</script>
<style >
.accordion-content {
    overflow: hidden;
    transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
    max-height: 0;
    opacity: 0;
}

.accordion-content.open {
    max-height: 999px;
    /* Un valor suficientemente grande */
    /* Ajusta este valor según sea necesario */
    opacity: 1;
}

.flatpickr-current-month .flatpickr-monthDropdown-months {
    background: #001b47;
    border: none;
    border-radius: 20px;
    box-sizing: border-box;
    color: inherit;
    cursor: pointer;
    appearance: none;
    font-size: 12pt;
    padding: 10px;
    font-family: inherit;
    font-weight: 300;
    height: auto;
    line-height: inherit;
    margin: -1px 0 0 0;
    outline: none;
    padding: 0 0 0 0.5ch;
    position: relative;
    vertical-align: initial;
    -webkit-box-sizing: border-box;

    width: auto;
}


.flatpickr-current-month input.cur-year {
    background: transparent;
    box-sizing: border-box;
    color: inherit;
    cursor: text;
    padding: 0 0 0 0.1ch;
    margin: 0;
    display: inline-block;
    font-size: 12pt;
    font-family: inherit;
    font-weight: 300;
    line-height: inherit;
    height: auto;
    border: 0;
    border-radius: 0;
    vertical-align: initial;
    -webkit-appearance: textfield;
    -moz-appearance: textfield;
    appearance: textfield;
}

:root {
    --ms-tag-bg: #0d2141;
    --ms-tag-font-weight: 300;
    --ms-tag-color: #ffffff;
    --ms-tag-font-size: 0.875rem;

}
</style>