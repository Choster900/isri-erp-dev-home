<template>
    <div class="mx-4 overflow-y-auto h-[550px]  mb-4 ">
        <RadarChart :chart-data="data" :options="options" css-classes="chart-container"/>
    </div>
</template>

<script>
import { RadarChart } from "vue-chart-3"
import { Chart, RadarController, CategoryScale, LinearScale, RadialLinearScale, PointElement, LineElement } from "chart.js"
import { computed, ref, toRefs, watch } from 'vue'
Chart.register(RadarController, CategoryScale, LinearScale, RadialLinearScale, PointElement, LineElement)

export default {
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
    components: { RadarChart },
    setup(props) {
        const { rubricaAndCategoriaByEvaluacion, evaluacionPersonalProp } = toRefs(props)

        const categories = ref([])
        const score = ref([])
        const data = ref([])

        watch(evaluacionPersonalProp, () => {

            categories.value = computed(() => {
                const data = rubricaAndCategoriaByEvaluacion.value;

                if (data && data.categorias_rendimiento) {
                    return data.categorias_rendimiento.map(index => index.nombre_cat_rendimiento);
                } else {
                    return [];
                }
            });

            score.value = computed(() => {
                const data = evaluacionPersonalProp.value;

                if (data && data) {
                    return data.data.detalle_evaluaciones_personal.map(index => index.rubrica_rendimiento.puntaje_rubrica_rendimiento);
                } else {
                    return [];
                }
            });

            data.value = {
                labels: categories.value,
                datasets: [
                    {
                        label: 'Puntuacion por categorias',
                        data: score.value,
                        fill: true,
                        backgroundColor: '#900C3F',
                        borderColor: '#001c48',
                        pointBackgroundColor: '#900C3F',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#f3f4f6',
                        pointHoverBorderColor: '#001c48'
                    }
                ]
            }

        })

        const options = ref({
            responsive: true,
            layout: {
                padding: 1
            },
            type: 'line',
            scales: {
                x: {
                    display: false, // Oculta el eje x

                    ticks: {
                        font: {
                            size: 9, // Ajusta el tamaño de la fuente para el eje x
                        },
                    }
                },
                y: {
                    display: false, // Oculta el eje x

                    ticks: {
                        font: {
                            size: 10, // Ajusta el tamaño de la fuente para el eje y
                        }
                    }
                }
            },

            plugins: {
                colors: {
                    enabled: true
                },
                legend: {
                    position: 'top',
                    display: false,

                },
                title: {
                    display: false,
                    text: "Radar Chart"
                }
            }
        })

        return {
            categories,
            score,
            data,
            options,
        }
    }
}
</script>

<style></style>
