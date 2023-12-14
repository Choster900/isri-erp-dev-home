
<template>
    <div class="mx-4 overflow-y-auto h-[550px] py-3 px-2 mb-4 ">


        <div v-if="rubricaAndCategoriaByEvaluacion">
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
                            CODIGO: F21 V1.0
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
                        <input type="text" class="w-56 text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>

                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUESTO:</label>
                        <input type="text"
                            class="w-64 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>

                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">SUELDO:</label>
                        <input type="text"
                            class="w-16 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 gap-1">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">UNIDAD ADM:
                            (Direccion,Division,Dpto.,Unidad,Seccion,Area)</label>
                        <input type="text" class="w-32 text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0"
                            value="ADMINISTRACION SUPERIOR - UNIDAD DE ADQUISICIONES Y CONTRATACIONES INSTITUCIONAL"
                            readonly
                            title="ADMINISTRACION SUPERIOR - UNIDAD DE ADQUISICIONES Y CONTRATACIONES INSTITUCIONAL">
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PERIODO COMPRENDIDO:</label>
                        <input type="text" value="JULIO A DICIEMBRE 2023"
                            class="w-36 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 gap-2 pb-7">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">JEFE INME:</label>
                        <input type="text" value="MIGUEL JOSUE TOBIAS RIVAS"
                            class="w-40 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUESTO:</label>
                        <input type="text" value="COORDINADOR DEL AREA DE SISTEMAS DE INFORMACION"
                            class="w-72 text-left text-[7pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>

                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUNTAJE TOTAL:</label>
                        <input type="text"
                            class="w-12 text-left text-[9pt] text-sm font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
            </div>
            <table border="0" cellpadding="0" cellspacing="0" >
                <tbody v-for="(evaluacion, i) in rubricaAndCategoriaByEvaluacion.categorias_rendimiento" :key="i">
                    <tr class="">
                        <td class="border border-black bg-black text-[9pt] text-white text-center pl-8 py-1" colspan="2">{{
                            evaluacion.nombre_cat_rendimiento }}
                        </td>
                        <td class="border border-black bg-black text-[9pt] text-white text-center">PUNTOS: 10</td>
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
                                    <input type="radio" :name="evaluacion.nombre_cat_rendimiento">
                                    <span class="text-xs justify-center text-center "> {{ rubrica.opcion_rubrica_rendimiento}}</span>
                                </label>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
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
    setup() {
        const { separarTexto } = useDocumentoEvaluacion();
        return {
            separarTexto,
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
}</style>