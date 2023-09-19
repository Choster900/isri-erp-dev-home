<script setup>
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import DocumentBlanck from '@/Components-ISRI/RRHH/DocumentoEvaluacionBlank.vue';
</script>
<template>
    <div class=" flex  justify-center  content-between">
        <div class="px-2">
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
        </div>
    </div>


    <div class="mx-4 overflow-y-auto max-h-[calc(100vh-100px)] p-3 mb-4"
        v-if="contenidoEvaluacionRendimiento != '' && registroEvaluacionRendimientoPersonal != ''"
        :class="{ 'animate-slide-out-right': showErrorWhenValuesChanges, 'animate-slide-in-left': !showErrorWhenValuesChanges }">

        <table>
            <tbody>
                <tr>
                    <td class="border border-black" rowspan="4">
                        <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                    </td>
                    <td class="border border-black text-center font-medium uppercase" rowspan="2">
                        Seguimiento de Desempeño</td>
                    <td class="border border-black text-[8pt] font-semibold pr-3 px-1.5">FECHA DE CREACIÓN:
                        12/12/2021</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold   px-1.5">ID 00000-1<!-- {{
                        dataPersonalEvaluacion[0].id_evaluacion_personal }} --></td>
                </tr>
                <tr>
                    <td class="border border-black text-center font-medium px-8" rowspan="2">DESEMPEÑO DE
                        PERSONAL ADMINISTRATIVO</td>
                    <td class="border border-black text-[8pt] font-semibold py-0.5 px-1.5">DEPENDENCIA:
                        ADMON</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold  py-0.5 px-1.5">CODIGO: VERSION
                        1.0</td>
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
        <!-- 
        <pre>
            {{ infoEmployee.plazas_asignadas }}

 
        </pre> -->
        <table class="pb-5" v-for="(data, i) in contenidoEvaluacionRendimiento.categorias_rendimiento" :key="i">
            <tbody>
                <tr>
                    <td class="border border-black bg-black h-10 text-[10pt] text-white text-center" colspan="2">
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
                                <td :class="{ ' border-t-0 border-b': j == data.rubricas_rendimiento.lenght }"
                                    class="text-[9pt] border-l-0 border-r border-t-0 border-b border-black text-justify px-2 font-medium">
                                    {{ rubrica.descripcion_rubrica_rendimiento }}
                                </td>
                                <td
                                    class="border-x-0  border-t-0 border-b  border-black justify-center text-center px-10 py-4">
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


    </div>

    <DocumentBlanck v-else /><!-- Este es un documento en blanco -->
</template>

<script>
import { createApp, h } from 'vue'
import EvaluacionPdfpVue from '@/pdf/RRHH/EvaluacionPdf.vue';
import moment from 'moment';

export default {
    props: ["contenidoEvaluacionRendimiento", "registroEvaluacionRendimientoPersonal", "infoEmployee"],

    data() {
        return {
            evaluacionData: [],
            responsesWithScores: [], // Contabiliza el puntaje de la evaluacion        
            responsesWithScoresDataSend: [], // Almacena la data que se enviara al backend        
            showErrorWhenValuesChanges: true,
            registroEvaluacion: [],
            contenidoEvaluacion: [],
        }
    },
    methods: {
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


        }



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