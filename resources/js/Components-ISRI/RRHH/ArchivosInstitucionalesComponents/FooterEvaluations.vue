<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import ListEvaluations from './ListEvaluations.vue'
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import { computed, ref } from "vue"
import { LineChart } from "vue-chart-3"
import { Chart, LineController, ArcElement, CategoryScale, LinearScale, PointElement, LineElement } from "chart.js"
Chart.register(LineController, ArcElement, CategoryScale, LinearScale, PointElement, LineElement)

</script>
<template>
    <div class="w-full ">

        <LineChart :chart-data="data" :options="options" css-classes="chart-container" class="-ml- h-72 " />

        <div class="flex flex-col md:flex-row  md:space-y-0">

            <div class="md:py-8 w-4/5">



                <article class="bg-white shadow-md rounded border border-slate-200 px-3 py-1 mb-2"
                    v-for="(evaluation, i) in newFilteredData" :key="i">
                    <div class="flex flex-start space-x-4">
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
                                    <a class="font-medium text-indigo-500 hover:text-indigo-600" href="#0">
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
                                    </a>
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



    </div>
</template>

<script>
export default {
    props: ["userData"],
    data() {
        const arraySemester = []
        return {
            data:
            {
                labels: [],
                datasets: [{
                    label: 'Calificaciones',
                    data: [],
                    borderWidth: 2, // Añade el ancho de la línea
                    pointRadius: 2, // Añade el radio de los puntos
                    fill: true,
                    borderColor: '#001c48',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,

                type: 'line',
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 9, // Ajusta el tamaño de la fuente para el eje x
                            },
                            /*   maxRotation: 60,
                              minRotation: 60 */
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                size: 10, // Ajusta el tamaño de la fuente para el eje y
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
                        text: "Gatos del requerimiento"
                    }
                }
            },
            moment,
            yearsArray: [],
            year: null,
            newFilteredData: [],
        }
    },
    methods: {
        filterAllYearsInDeals() {
            const uniqueYearsSet = new Set();
            // Iteramos sobre el arreglo dataDeals y agregamos los años al conjunto
            this.userData.forEach((obj, index) => {
                // Extraemos el año de la fecha_inicio_evaluacion_personal y lo agregamos al conjunto
                if (obj.fecha_inicio_evaluacion_personal) {
                    const year = moment(obj.fecha_inicio_evaluacion_personal).year();
                    uniqueYearsSet.add(year);
                }
            });
            // Convertimos el conjunto a un nuevo arreglo y lo ordenamos
            const uniqueYearsArray = Array.from(uniqueYearsSet).sort();
            this.yearsArray = uniqueYearsArray
            this.year = this.yearsArray[this.yearsArray.length - 1]
            this.newFilteredDataSet(this.year)
        },
        newFilteredDataSet(year) {
            this.newFilteredData = this.userData.filter(obj => moment(obj.fecha_inicio_evaluacion_personal).year() == year)
            console.log(this.userData.filter(obj => moment(obj.fecha_inicio_evaluacion_personal).year() == year));
        },

    },
    mounted() {
        this.filterAllYearsInDeals()
    },
    computed: {
        spliceEachSemester() {

            if (this.userData != '') {
                this.userData.forEach(element => {
                    console.log(element);
                });
            }
        }
    },
    watch: {
        userData() {
            this.filterAllYearsInDeals()
            this.data.labels = []
            this.data.datasets[0].data = []
            if (this.userData != '') {
                this.userData.forEach(element => {
                    this.data.labels.push(`${element.periodo_evaluacion.nombre_periodo_evaluacion} - ${moment(element.fecha_inicio_evaluacion_personal).year()}`)
                    this.data.datasets[0].data.push(element.puntaje_evaluacion_personal)
                    //console.log(element.periodo_evaluacion.nombre_periodo_evaluacion);
                });
            }
            console.log(this.data.labels);

        }
    }
}
</script>

<style></style>
