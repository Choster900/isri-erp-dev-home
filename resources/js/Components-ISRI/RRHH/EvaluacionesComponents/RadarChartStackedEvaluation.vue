<template>
    <div class="mx-4 overflow-y-auto h-[550px]  mb-4 ">
        <RadarChart :chart-data="data" :options="options" css-classes="chart-container" />
    </div>
</template>

<script>
import { RadarChart } from "vue-chart-3"
import { computed, ref, toRefs, watch } from 'vue'
Chart.register(RadarController, CategoryScale, LinearScale, RadialLinearScale, PointElement, LineElement)
import { Chart, RadarController, CategoryScale, LinearScale, RadialLinearScale, PointElement, LineElement } from "chart.js"

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
        // Desestructura las propiedades recibidas mediante props
        const { rubricaAndCategoriaByEvaluacion, evaluacionPersonalProp } = toRefs(props);

        // Variables reactivas para almacenar datos de categorías, puntuaciones y datos del gráfico
        const categories = ref([]);
        const score = ref([]);
        const data = ref([]);

        // Utiliza watch para reaccionar a cambios en evaluacionPersonalProp
        watch(evaluacionPersonalProp, () => {
            // Calcula las categorías a partir de rubricaAndCategoriaByEvaluacion
            categories.value = computed(() => {
                const data = rubricaAndCategoriaByEvaluacion.value;

                if (data && data.categorias_rendimiento) {
                    return data.categorias_rendimiento.map(index => index.nombre_cat_rendimiento);
                } else {
                    return [];
                }
            });

            // Calcula las puntuaciones a partir de evaluacionPersonalProp
            score.value = computed(() => {
                const data = evaluacionPersonalProp.value;

                if (data && data.data) {
                    return data.data.detalle_evaluaciones_personal.map(index => index.rubrica_rendimiento.puntaje_rubrica_rendimiento);
                } else {
                    return [];
                }
            });

            // Actualiza los datos del gráfico
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
            };
        });

        // Configuración de opciones para el gráfico
        const options = ref({
            responsive: true,
            layout: {
                padding: 1
            },
            type: 'line',
            scales: {
                x: {
                    display: false,
                    ticks: {
                        font: {
                            size: 9,
                        },
                    }
                },
                y: {
                    display: false,
                    ticks: {
                        font: {
                            size: 10,
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
        });

        // Retorna las variables reactivas para ser utilizadas en el componente
        return {
            categories,
            score,
            data,
            options,
        };
    }

}
</script>

<style></style>
