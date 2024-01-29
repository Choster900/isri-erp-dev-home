<template>
    <Head title="RRHH - Evaluacion del personal" />
    <AppLayoutVue nameSubModule="RRHH - Evaluacion del personal">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="objectEvaluacionPersonal = []; showModalEvaluacion = !showModalEvaluacion"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Acuerdos" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getEvaluaciones()"
                                @deselect=" tableData.length = 5; getEvaluaciones()"
                                @clear="tableData.length = 5; getEvaluaciones()" :options="perPage" :searchable="true"
                                placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <!--  <h2 class="font-semibold text-slate-800 pt-1">Empleados: <span class="text-slate-400 font-medium">{{
                        evaluaciones.length
                    }}</span></h2> -->
                </div>
            </header>
            <!--     -->
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getEvaluaciones()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="(evaluacion, i) in evaluaciones" :key="i">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">{{ evaluacion.id_empleado }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">{{ evaluacion.codigo_empleado }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">
                                    {{ `${evaluacion.persona.pnombre_persona ? evaluacion.persona.pnombre_persona : ''}
                                                                        ${evaluacion.persona.snombre_persona ? evaluacion.persona.snombre_persona : ''}
                                                                        ${evaluacion.persona.tapellido_persona ? evaluacion.persona.tapellido_persona : ''}` }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">
                                    {{ `${evaluacion.persona.papellido_persona ? evaluacion.persona.papellido_persona : ''}
                                                                        ${evaluacion.persona.sapellido_persona ? evaluacion.persona.sapellido_persona : ''}
                                                                        ${evaluacion.persona.tapellido_persona ? evaluacion.persona.tapellido_persona : ''}` }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div class=" overflow-y-auto scrollbar">
                                        <template v-for="(ren, j) in evaluacion.evaluaciones_personal" :key="j">
                                            <div class=" text-center">
                                                <p class="text-[8.5pt]">
                                                    {{ moment(ren.fecha_reg_evaluacion_personal).format('L') }}
                                                </p>
                                            </div>
                                            <template v-if="j < evaluacion.evaluaciones_personal.length - 1">
                                                <hr class="my-0.5 border-t border-gray-300">
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div class=" overflow-y-auto scrollbar">
                                        <template v-for="(ren, j) in evaluacion.evaluaciones_personal" :key="j">
                                            <div class=" text-center">
                                                <p class="text-[8.5pt]">
                                                    {{ ren.periodo_evaluacion.nombre_periodo_evaluacion }}
                                                </p>
                                            </div>
                                            <template v-if="j < evaluacion.evaluaciones_personal.length - 1">
                                                <hr class="my-0.5 border-t border-gray-300">
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div class=" overflow-y-auto scrollbar">
                                        <template v-for="(ren, j) in evaluacion.evaluaciones_personal" :key="j">
                                            <div class=" text-center">
                                                <p class="text-[8.5pt]">
                                                    {{ ren.tipo_evaluacion_personal.nombre_tipo_evaluacion_personal }}
                                                </p>
                                            </div>
                                            <template v-if="j < evaluacion.evaluaciones_personal.length - 1">
                                                <hr class="my-0.5 border-t border-gray-300">
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div class=" overflow-y-auto scrollbar">
                                        <template v-for="(ren, j) in evaluacion.evaluaciones_personal" :key="j">
                                            <div class=" text-center">
                                                <p class="text-[8.5pt]">
                                                    {{ ren.puntaje_evaluacion_personal }} pts
                                                </p>
                                            </div>
                                            <template v-if="j < evaluacion.evaluaciones_personal.length - 1">
                                                <hr class="my-0.5 border-t border-gray-300">
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div class=" overflow-y-auto scrollbar">
                                        <template v-for="(ren, j) in evaluacion.evaluaciones_personal" :key="j">
                                            <div class=" text-center">
                                                <p class="text-[8.5pt]">
                                                    ID: {{ ren.estado_evaluacion_personal.id_estado_evaluacion_personal }}
                                                </p>
                                            </div>
                                            <template v-if="j < evaluacion.evaluaciones_personal.length - 1">
                                                <hr class="my-0.5 border-t border-gray-300">
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </td>


                            <td class="first:pl-5 last:pr-5">
                                <div class="space-x-1">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="objectEvaluacionPersonal = evaluacion; showModalEvaluacion = !showModalEvaluacion">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">VER</div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="9" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="emptyObject && !isLoadinRequest">
                        <tr>
                            <td colspan="9" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>

        </div>

        <div v-if="!emptyObject" class="px-6 py-4 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="link.label === 'Anterior'"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <a @click="getEvaluaciones(link.url)"
                                        class="btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer text-indigo-500">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                                            </svg>
                                            <span class="hidden sm:inline ml-1">Anterior</span>
                                        </div>
                                    </a>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getEvaluaciones(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer text-indigo-500">

                                            <div class="flex items-center">
                                                <span class="hidden sm:inline">Siguiente&nbsp;</span> <svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                                </svg>
                                            </div>
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer mt-2" v-else @click="getEvaluaciones(link.url)"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalEvalueacionesVue v-if="listDependencias != ''" :evaluacionPersonalProp="objectEvaluacionPersonal"
            @actualizar-datatable="getEvaluaciones()" :showModal="showModalEvaluacion"
            @cerrar-modal="showModalEvaluacion = false; objectEvaluacionPersonal = []; "
            :listDependencias="listDependencias" />
    </AppLayoutVue>
</template>

<script>
import { useEvaluacion } from "@/Composables/RRHH/Evaluaciones/useDatatable"
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalEvalueacionesVue from '@/Components-ISRI/RRHH/EvaluacionesComponents/ModalEvalueacionesCopy.vue';
import moment from "moment";
export default {
    components: { Head, AppLayoutVue, Datatable, ModalEvalueacionesVue },
    setup() {
        const {
            links,
            sortBy,
            columns,
            sortKey,
            permits,
            evaluaciones,
            lastUrl,
            perPage,
            tableData,
            getEvaluaciones,
            sortOrders,
            pagination,
            stateModal,
            objectEvaluacionPersonal,
            handleData,
            emptyObject,
            isLoadinRequest,
            showModalEvaluacion,
            dataBeneficiariosToSendModal,
            listDependencias,
        } = useEvaluacion()
        return {
            links,
            sortBy,
            columns,
            sortKey,
            permits,
            evaluaciones,
            lastUrl,
            perPage,
            tableData,
            getEvaluaciones,
            moment,
            sortOrders,
            objectEvaluacionPersonal,
            pagination,
            stateModal,
            handleData,
            listDependencias,
            emptyObject,
            isLoadinRequest,
            showModalEvaluacion,
            dataBeneficiariosToSendModal,
        }
    }

}
</script>
<style scoped>
#tabla_imprentas td {
    padding: 0 !important;
    margin: 0 !important;
}

#tabla_imprentas tbody tr:hover {
    background-color: rgba(31, 53, 88, 0.1);
}

.less-padding {
    padding: 0;
}
</style>
