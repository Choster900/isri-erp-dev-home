

<template>
    <div class="w-full chart-container">
        <LineChart :chart-data="data" :options="options" class="h-44" />


        <div class="flex flex-col md:flex-row  md:space-y-0">

            <div class="md:py-8 w-4/5">

                <article class="bg-white shadow-md rounded border border-slate-200 px-3 py-1 mb-2"
                    v-for="(evaluation, i) in newFilteredData" :key="i">
                    <div class="flex flex-start space-x-4 cursor-pointer" @click="obtenerCategoriaYRubricaRendimiento(evaluation.evaluacion_rendimiento.id_evaluacion_rendimiento);
                    evaluacionPersonalProp = { data: evaluation, allData: userData };
                    updateStateAsView(evaluation)">
                        <div class="shrink-0 mt-1.5">
                            <div class="relative">
                                <lord-icon src="https://cdn.lordicon.com/depeqmsz.json" trigger="hover"
                                    style="width:40px;height:40px">
                                </lord-icon>

                                <lord-icon src="https://cdn.lordicon.com/jnzhohhs.json" trigger="loop" delay="1000"
                                    v-show="evaluation.id_estado_evaluacion_personal == 2" colors="primary:#c71f16"
                                    title="Nueva evaluacion pendiente de revisar"
                                    style="width:18px;height:18px; position: absolute; top: -8px; right: -4px;">
                                </lord-icon>
                            </div>
                        </div>
                        <div class="grow">
                            <h2 class="font-semibold text-slate-800 mt-1">
                                <span class="text-sm">
                                    {{ evaluation.plaza_evaluada &&
                                        evaluation.plaza_evaluada.filter(plaza =>
                                            plaza.plaza_asignada.estado_plaza_asignada === 1)
                                            .map((plaza, index) => {
                                                return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                                            }).join('-') || '' }}
                                </span>
                            </h2>
                            <footer class="flex flex-wrap text-sm">
                                <div
                                    class="flex items-center after:block after:content-['·'] last:after:content-[''] after:text-sm after:text-slate-400 after:px-2">
                                    <span class="font-medium text-indigo-500 hover:text-indigo-600">
                                    <span class="font-medium text-indigo-500 hover:text-indigo-600">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="w-5 h-5 mr-1">
                                                <path
                                                    d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                                                <path fill-rule="evenodd"
                                                    d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                            <span class="text-xs">
                                                {{ evaluation.periodo_evaluacion.id_periodo_evaluacion == 1 ?
                                                    'ENERO A JUNIO' : 'JULIO A DICIEMBRE' }}
                                                {{ moment(evaluation.fecha_inicio_evaluacion_personal).year() }}
                                            </span>
                                        </div>
                                    </span>
                                    </span>
                                </div>
                                <div
                                    class="flex items-center after:block after:content-['·'] last:after:content-[''] after:text-sm after:text-slate-400 after:px-2">
                                    <span class="text-slate-500">{{
                                        moment(evaluation.fecha_inicio_evaluacion_personal).fromNow() }}</span>
                                </div>
                                <div
                                    class="flex items-center after:block after:content-['·'] last:after:content-[''] after:text-sm after:text-slate-400 after:px-2">
                                    <span class="text-slate-500">{{
                                        evaluation.tipo_evaluacion_personal.nombre_tipo_evaluacion_personal }}</span>
                                </div>
                            </footer>
                        </div><!-- Upvote button -->
                        <div class="shrink-0">
                            <button :class="{
                                'fill-red-500 border-red-400': evaluation.puntaje_evaluacion_personal >= 0 && evaluation.puntaje_evaluacion_personal <= 27,
                                'fill-orange-500 border-orange-400': evaluation.puntaje_evaluacion_personal >= 28 && evaluation.puntaje_evaluacion_personal <= 55,
                                'fill-green-500 border-green-400': evaluation.puntaje_evaluacion_personal >= 56 && evaluation.puntaje_evaluacion_personal <= 72,
                                'fill-indigo-500 border-indigo-400': evaluation.puntaje_evaluacion_personal >= 73 && evaluation.puntaje_evaluacion_personal <= 84,
                            }"
                                class="text-xs font-semibold text-center size-12 border  rounded-sm flex flex-col justify-center items-center outline outline-2 outline-indigo-100">
                                <svg class="inline-flex  mt-1.5 mb-1.5" width="12" height="6"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="m0 6 6-6 6 6z"></path>
                                </svg>
                                <div>{{ evaluation.puntaje_evaluacion_personal }} pts</div>
                            </button>
                        </div>
                    </div>
                </article>

            </div>

            <div class=" py-2 rounded-md overflow-x-auto pt-8 w-1/6">
                <div class="flex items-center justify-center pb-2" v-for="index in yearsArray" :key="index">
                    <button @click="year = index; newFilteredDataSet(index)"
                        :class="year == index ? 'bg-blue-900' : 'bg-gray-500 hover:bg-slate-600'"
                        class="relative  text-white p-3 w-20 h-8 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
                        <span class="flex items-center justify-center h-full">{{ index }}</span>
                    </button>
                </div>
            </div>
        </div>


        <ModalSeeEvaluation @cerrar-modal="showModal = false; rubricaAndCategoriaByEvaluacion = []"
            :evaluacionPersonalProp="evaluacionPersonalProp"
            :rubricaAndCategoriaByEvaluacion="rubricaAndCategoriaByEvaluacion" :showModal="showModal"
            :isLoadingObtenerCategoriaYRubrica="isLoadingObtenerCategoriaYRubrica" />

        <ModalSeeEvaluation @cerrar-modal="showModal = false; rubricaAndCategoriaByEvaluacion = []"
            :evaluacionPersonalProp="evaluacionPersonalProp"
            :rubricaAndCategoriaByEvaluacion="rubricaAndCategoriaByEvaluacion" :showModal="showModal"
            :isLoadingObtenerCategoriaYRubrica="isLoadingObtenerCategoriaYRubrica" />
    </div>
</template>

<script>
import { ref, onMounted, watch, computed, toRefs, getCurrentInstance } from 'vue';
import moment from 'moment'; // Asegúrate de importar moment aquí si no lo has hecho
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { usePage } from '@inertiajs/vue3'
import { LineChart } from "vue-chart-3"
import { Chart, LineController, ArcElement, CategoryScale, LinearScale, PointElement, LineElement } from "chart.js"
import axios from 'axios';
import ModalSeeEvaluation from './ModalSeeEvaluation.vue';
Chart.register(LineController, ArcElement, CategoryScale, LinearScale, PointElement, LineElement)

export default {
    props: ["userData"],
    components: { LineChart, ModalSeeEvaluation },
    setup(props, context) {
        const arraySemester = ref([]);
        const data = ref({
            labels: [],
            datasets: [{
                label: 'Calificaciones',
                data: [],
                borderWidth: 2,
                pointRadius: 2,
                fill: true,
                borderColor: '#001c48',
                tension: 0.3
            }]
        });

        const options = ref({
            responsive: true,
            type: 'line',
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: 10,
                        },
                    }
                },
                y: {
                    ticks: {
                        font: {
                            size: 10,
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    display: false,
                },
                title: {
                    display: false,
                    text: ""
                }
            }
        });

        const rubricaAndCategoriaByEvaluacion = ref([])
        const evaluacionPersonalProp = ref([])
        const showModal = ref(false);
        const yearsArray = ref([]);
        const year = ref(null);
        const newFilteredData = ref([]);
        const isLoadingObtenerCategoriaYRubrica = ref(false);

        const { userData } = toRefs(props)
        const filterAllYearsInDeals = () => {
            const uniqueYearsSet = new Set();

            if (
                userData.value &&
                userData.value.evaluaciones_personal &&
                userData.value.evaluaciones_personal.length > 0
            ) {
                userData.value.evaluaciones_personal.forEach((obj, index) => {
                    if (obj.fecha_inicio_evaluacion_personal) {
                        const year = moment(obj.fecha_inicio_evaluacion_personal).year();
                        uniqueYearsSet.add(year);
                    }
                });

            }


            if (
                userData.value &&
                userData.value.evaluaciones_personal &&
                userData.value.evaluaciones_personal.length > 0
            ) {
                userData.value.evaluaciones_personal.forEach((obj, index) => {
                    if (obj.fecha_inicio_evaluacion_personal) {
                        const year = moment(obj.fecha_inicio_evaluacion_personal).year();
                        uniqueYearsSet.add(year);
                    }
                });

            }

            const uniqueYearsArray = Array.from(uniqueYearsSet).sort();
            yearsArray.value = uniqueYearsArray;
            year.value = yearsArray.value[yearsArray.value.length - 1];
            newFilteredDataSet(year.value);
        };

        const newFilteredDataSet = (selectedYear) => {

            if (
                userData.value &&
                userData.value.evaluaciones_personal &&
                userData.value.evaluaciones_personal.length > 0
            ) {

                newFilteredData.value = userData.value.evaluaciones_personal.filter(obj => moment(obj.fecha_inicio_evaluacion_personal).year() === selectedYear);

            } else {
                newFilteredData.value = []
            }


        };

        onMounted(() => {
            filterAllYearsInDeals();
        });

        watch(userData, () => {
    filterAllYearsInDeals();
    data.value.labels = [];
    data.value.datasets[0].data = [];

    if (userData.value && userData.value.length > 0) { // Verificar si userData está definido y tiene elementos
        userData.value.evaluaciones_personal.forEach(element => {
            data.value.labels.push(`${element.periodo_evaluacion.nombre_periodo_evaluacion} - ${moment(element.fecha_inicio_evaluacion_personal).year()}`);
            data.value.datasets[0].data.push(element.puntaje_evaluacion_personal);
        });
    }
});

        // Aquí puedes definir otras funciones computadas si es necesario

        /**
         * Obtiene la información de categoría y rubrica de rendimiento por evaluación.
         * @param {number} idEvaluacionRendimiento - Identificador de la evaluación de rendimiento.
         */
        const obtenerCategoriaYRubricaRendimiento = async (idEvaluacionRendimiento) => {
            try {
                showModal.value = true;
                isLoadingObtenerCategoriaYRubrica.value = true;
                // Realiza la solicitud al servidor.
                const response = await axios.post('/get-evaluacion', { idEvaluacionRendimiento: idEvaluacionRendimiento });
                // Almacena la respuesta en la referencia.
                rubricaAndCategoriaByEvaluacion.value = response.data;
                isLoadingObtenerCategoriaYRubrica.value = false;
            } catch (error) {
                // Maneja los errores imprimiéndolos en la consola.
                console.error('Error en la búsqueda:', error);
            } finally {
                isLoadingObtenerCategoriaYRubrica.value = false;
                // Detiene el indicador de carga, independientemente de si hubo éxito o error.
            }
        };


        /**
         * Actualiza el estado de la evaluación de rendimiento a "Visto" (estado 3).
         * @param {Object} objectValidation - Objeto con datos necesarios para la validación y actualización.
         */
        const updateStateAsView = async (objectValidation) => {
            try {
                // Extraer datos del objeto de validación
                const { id_evaluacion_personal, id_empleado, estado_evaluacion_personal } = objectValidation;
                // Obtener el ID de persona del usuario autenticado
                const authUserId = usePage().props.auth.user.id_persona;
                // Validar si la evaluación está en estado pendiente (2) y si el usuario autenticado es el mismo que el empleado asociado
                if (estado_evaluacion_personal.id_estado_evaluacion_personal === 2 && authUserId === id_empleado) {
                    // Enviar una solicitud para cambiar el estado de la evaluación a "Visto" (estado 3)
                    const response = await axios.post('/changeStateEvaluation', { idEvaluation: id_evaluacion_personal, stateToChange: 3 });
                    // Imprimir la respuesta del servidor para propósitos de depuración
                    // console.log(response);
                }
            } catch (error) {
                // Manejar los errores imprimiéndolos en la consola.
                console.error('Error en el cambio: ', error);
            }
        };


        return {
            updateStateAsView,
            obtenerCategoriaYRubricaRendimiento,
            arraySemester,
            data,
            showModal,
            rubricaAndCategoriaByEvaluacion,
            options,
            moment,
            yearsArray,
            isLoadingObtenerCategoriaYRubrica,
            year,
            newFilteredData,
            filterAllYearsInDeals,
            newFilteredDataSet,
            evaluacionPersonalProp,
            // Otras variables y funciones computadas si es necesario
        };
    }
}

</script>

<style></style>
