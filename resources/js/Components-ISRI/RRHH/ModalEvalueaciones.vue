<script setup>
import { toast } from 'vue3-toastify'; // Importar el módulo toast de vue3-toastify
import 'vue3-toastify/dist/index.css'; // Importar los estilos de vue3-toastify
import TooltipVue from '../Tooltip.vue';
import moment from 'moment';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import AccordionBasicVue from '../AccordionBasic.vue';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import DocumentoEvaluacionVue from './DocumentoEvaluacion.vue';
import DocumentoAnalisisDesempeñoVue from './DocumentoAnalisisDesempeño.vue';

</script>

<template>
    <div class="m-1.5">
        <!-- Componente del modal ProcessModal -->
        <ProcessModal maxWidth="7xl" :show="showModal" @close="$emit('cerrar-modal')">

            <div class="flex flex-col md:flex-row md:space-y-0 max-h-screen ">
                <div class="w-full md:w-2/6 bg-slate-200/40 p-4 border">
                    <div class="col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300 ">
                        <header class="px-5 py-4 border-b-4 border-indigo-500">
                            <h2 class="font-semibold text-slate-800">Crear una nueva evaluación +</h2>
                        </header>
                        <div class="p-3">
                            <div class="">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre del
                                        empleado</label>
                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect v-model="id_empleado"
                                            :disabled="evaluacionEmpleadoDBData != '' ? true : false"
                                            @search-change="handleEmployeeSearchChange($event)" :clearOnSearch="true"
                                            placeholder="Puedes filtrar por apellidos" :filter-results="false"
                                            :min-chars="10" :resolve-on-load="true" :searchable="true"
                                            :options="employeOptions" noOptionsText="Sin opciones" :classes="{
                                                optionSelectedDisabled: 'text-green-100 bg-green-500 bg-opacity-50 cursor-not-allowed',
                                                noOptions: 'py-2 px-3 text-gray-600 bg-white text-left text-sm ',
                                                containerDisabled: 'cursor-default bg-gray-100 cursor-not-allowed',
                                                tagDisabled: 'pr-2 opacity-50 rtl:pl-2  cursor-not-allowed',
                                                placeholder: 'text-sm flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-slate-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ',
                                            }" />
                                    </div>
                                    <span class="text-xs text-red-500">{{ errors.id_empleado }}</span>
                                </div>
                                <div class="-mx-3 flex flex-wrap">
                                    <div class="w-full px-3 sm:w-1/3">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Fecha </label>
                                        <div class="mb-4  md:mb-0">
                                            <flat-pickr placeholder="DD/MM/YYYY" v-model="fecha_evaluacion_personal"
                                                class="w-[120px] text-xs text-center cursor-pointer rounded-[5px] border h-7 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                                :config="config" />
                                            <span class="text-xs text-red-500">{{ errors.fecha_evaluacion_personal }}</span>
                                        </div>
                                    </div>
                                    <div class="w-full px-3 sm:w-1/2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Periodo
                                            evaluación</label>
                                        <input type="text" name="lName" id="lName" placeholder="MES DIA-AÑO - MES DIA-AÑO"
                                            v-model="periodo_evaluacion_personal"
                                            class="w-[230px] text-xs  rounded-[5px] border h-7 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none" />
                                        <span class="text-xs text-red-500">{{ errors.periodo_evaluacion_personal }}</span>
                                    </div>
                                </div>

                                <button @click="submitEvaluacionDocument()"
                                    class="bg-indigo-900 rounded-sm shadow text-center text-white text-base font-light w-full py-2 mt-5">Nueva
                                    evaluación</button>
                            </div>
                        </div>
                    </div>
                    <div
                        class="max-h-[calc(100vh-350px)] overflow-y-auto col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300">
                        <div class="p-3">
                            <div class="max-h-[600px] ">
                                <table class="table-auto w-full">
                                    <tbody class="text-sm divide-y divide-slate-100">
                                        <tr>
                                            <td>
                                                <template
                                                    v-for="evaluacion, i in  registrosEvaluacionesRentimientoPersonal.evaluaciones_personal "
                                                    :key="i">
                                                    <AccordionBasicVue containerClass=" rounded-sm border border-slate-200"
                                                        bodyClass="text-red-500">
                                                        <template v-slot:titleContent>
                                                            <h1 class="text-center font-medium pl-4 py-3">
                                                                <ul class="ml-4 list-circle">
                                                                    <li
                                                                        class="relative  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        {{ evaluacion.periodo_evaluacion_personal }} </li>
                                                                </ul>
                                                            </h1>
                                                        </template>
                                                        <template v-slot:bodyContent>
                                                            <div
                                                                class="bg-slate-300/40 border-l-[4px] border-y-0 border-r-0 border-l-indigo-500 ">
                                                                <ul class="ml-10 list-circle py-4">
                                                                    <li
                                                                        class="relative  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        Puntuacion</li>
                                                                    <p class="font-medium">-{{
                                                                        evaluacion.puntaje_evaluacion_personal }}</p>
                                                                    <li
                                                                        class="relative  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        Fecha Evaluado</li>
                                                                    <p class="font-medium">-{{
                                                                        evaluacion.fecha_evaluacion_personal }}</p>
                                                                    <li
                                                                        class=" items-center relative before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        <span class="mr-2 mb-3">Ver evaluacion: </span>
                                                                        <button
                                                                            @click="getPersonalEvaluation(evaluacion.id_evaluacion_rendimiento); registroEvaluacionRendimientoPersonal = evaluacion"
                                                                            class="flex gap-2 items-center rounded-lg bg-orange-600 px-5 py-0 text-[8pt] mb-3 font-medium text-white transition duration-200 hover:bg-orange-600 active:bg-orange-700">
                                                                            <span>Ver evaluación</span>
                                                                            <lord-icon
                                                                                src="https://cdn.lordicon.com/jxwksgwv.json"
                                                                                trigger="hover" colors="primary:white"
                                                                                style="width:22px;height:22px"></lord-icon>
                                                                        </button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </template>
                                                    </AccordionBasicVue>
                                                </template>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                        <h2 class="flex gap-2 text-xl text-slate-800 font-bold mb-6">Evaluación del desempeño para personal
                            administrativo
                        </h2>
                        <!-- Start -->
                        <div class="mb-1 border-b border-slate-200">
                            <ul class="text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 ">
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="toShow === 'DocumentoEvalacionVue' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class=" whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current mr-2" viewBox=" 0 0 16 16">
                                            <path
                                                d="M12.311 9.527c-1.161-.393-1.85-.825-2.143-1.175A3.991 3.991 0 0012 5V4c0-2.206-1.794-4-4-4S4 1.794 4 4v1c0 1.406.732 2.639 1.832 3.352-.292.35-.981.782-2.142 1.175A3.942 3.942 0 001 13.26V16h14v-2.74c0-1.69-1.081-3.19-2.689-3.733zM6 4c0-1.103.897-2 2-2s2 .897 2 2v1c0 1.103-.897 2-2 2s-2-.897-2-2V4zm7 10H3v-.74c0-.831.534-1.569 1.33-1.838 1.845-.624 3-1.436 3.452-2.422h.436c.452.986 1.607 1.798 3.453 2.422A1.943 1.943 0 0113 13.26V14z" />
                                        </svg>
                                        <span @click="toShow = 'DocumentoEvalacionVue'">Medición de Competencias</span>
                                    </a>
                                </li>
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="toShow === 'DocumentoAnalisisDesempeñoVue' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class="  whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current  mr-2"
                                            :class="toShow === 'DocumentoAnalisisDesempeñoVue' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                            viewBox=" 0 0 16 16">
                                            <path
                                                d="M14.3.3c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-8 8c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l8-8zM15 7c.6 0 1 .4 1 1 0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8c.6 0 1 .4 1 1s-.4 1-1 1C4.7 2 2 4.7 2 8s2.7 6 6 6 6-2.7 6-6c0-.6.4-1 1-1z" />
                                        </svg>
                                        <span @click="toShow = 'DocumentoAnalisisDesempeñoVue'">Análisis de Desempeño</span>
                                    </a>
                                </li>
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="toShow === 'ImpresionDeDocumentos' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class="  whitespace-nowrap flex items-center cursor-pointer">
                                         <svg :fill="toShow === 'ImpresionDeDocumentos' ? '#6366f1' : '#94a3b8'"
                                            class="w-4 h-4" viewBox="0 0 32.00 32.00"
                                            :stroke="toShow === 'ImpresionDeDocumentos' ? '#6366f1' : '#94a3b8'">
                                            <path
                                                d="M30 13.75h-2.75v-7.75c0-0 0-0.001 0-0.001 0-0.345-0.14-0.657-0.365-0.883l-4-4c-0.226-0.226-0.539-0.366-0.885-0.366-0 0-0 0-0 0h-17c-0.69 0-1.25 0.56-1.25 1.25v0 11.75h-1.75c-0.69 0-1.25 0.56-1.25 1.25v0 9c0 0.69 0.56 1.25 1.25 1.25s1.25-0.56 1.25-1.25v0-7.75h25.5v7.75c0 0.69 0.56 1.25 1.25 1.25s1.25-0.56 1.25-1.25v0-9c-0-0.69-0.56-1.25-1.25-1.25h-0zM6.25 3.25h15.232l3.268 3.268v7.232h-18.5zM26 20.75h-20c-0.69 0-1.25 0.56-1.25 1.25v8c0 0.69 0.56 1.25 1.25 1.25h20c0.69-0.001 1.249-0.56 1.25-1.25v-8c-0.001-0.69-0.56-1.249-1.25-1.25h-0zM24.75 28.75h-17.5v-5.5h17.5zM26.879 17.62c-0.228-0.228-0.544-0.37-0.893-0.37-0.168 0-0.329 0.033-0.475 0.093l0.008-0.003c-0.16 0.060-0.295 0.156-0.399 0.279l-0.001 0.001c-0.119 0.109-0.213 0.242-0.277 0.392l-0.003 0.007c-0.059 0.142-0.095 0.306-0.1 0.479l-0 0.002c0.002 0.346 0.147 0.657 0.378 0.878l0 0c0.226 0.223 0.537 0.361 0.88 0.361s0.654-0.138 0.88-0.361l-0 0c0.233-0.222 0.378-0.533 0.381-0.878v-0c-0.005-0.174-0.041-0.339-0.103-0.49l0.003 0.009c-0.066-0.158-0.161-0.291-0.28-0.399l-0.001-0.001z">
                                            </path>
                                        </svg>

                                        <!-- <svg class="h-6 w-6" viewBox="-0.5 0 25 25" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19 3.32001H16C14.8954 3.32001 14 4.21544 14 5.32001V8.32001C14 9.42458 14.8954 10.32 16 10.32H19C20.1046 10.32 21 9.42458 21 8.32001V5.32001C21 4.21544 20.1046 3.32001 19 3.32001Z
                                                M8 3.32001H5C3.89543 3.32001 3 4.21544 3 5.32001V8.32001C3 9.42458 3.89543 10.32 5 10.32H8C9.10457 10.32 10 9.42458 10 8.32001V5.32001C10 4.21544 9.10457 3.32001 8 3.32001Z
                                                M19 14.32H16C14.8954 14.32 14 15.2154 14 16.32V19.32C14 20.4246 14.8954 21.32 16 21.32H19C20.1046 21.32 21 20.4246 21 19.32V16.32C21 15.2154 20.1046 14.32 19 14.32Z
                                                M8 14.32H5C3.89543 14.32 3 15.2154 3 16.32V19.32C3 20.4246 3.89543 21.32 5 21.32H8C9.10457 21.32 10 20.4246 10 19.32V16.32C10 15.2154 9.10457 14.32 8 14.32Z"
                                                stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                        </svg> -->

                                        <span class="ml-2" @click="toShow = 'ImpresionDeDocumentos'">Acciones</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- End -->
                    </div>

                    <DocumentoEvaluacionVue :contenidoEvaluacionRendimiento="contenidoEvaluacionRendimiento"
                        :registroEvaluacionRendimientoPersonal="registroEvaluacionRendimientoPersonal"
                        :info-employee="registrosEvaluacionesRentimientoPersonal"
                        @actualizar-table-data="$emit('cerrar-modal')" :showMe="toShow" />
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
export default {
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
        // Almacena las evaluaciones del empleado
        evaluacionEmpleadoDBData: {
            type: Object,
            default: [],
        }
    },
    data() {
        return {
            errors: {},// Menaja los mensajes de rrores
            id_empleado: '', // Guarda el id de empleado a evaluar
            fecha_evaluacion_personal: '',
            periodo_evaluacion_personal: '',
            searchTimeout: null, // Maneja tiempo de espera para empezar a buscar empleado
            registroEvaluacionRendimientoPersonal: [], // Almacena una evaluacion con su detalle para enviar al modal
            registrosEvaluacionesRentimientoPersonal: [],// Almacena todas las evaluaciones del empleado (Se envia para tomar la informacion del empleado)
            employeOptions: [], // Opciones del multiselect
            contenidoEvaluacionRendimiento: {}, // Data que almacena todas las preguntas con su categorias y rubricas
            config: {
                altInput: true,
                static: true,
                monthSelectorType: 'static',
                altFormat: "d/m/Y",
                dateFormat: 'Y-m-d',
                maxDate: new Date(), // Bloquear fechas futuras
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    },
                    months: {
                        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    },
                },
            },
            toShow: 'DocumentoEvalacionVue', // Esta variable maneja que parte de la evaluacion mostrar
        }
    },
    methods: {
        /**
         * 
         * @param {int} id_evaluacion_rendimiento // almacena el id de la version que pertenece la evaluación
         */
        async getPersonalEvaluation(id_evaluacion_rendimiento) {
            try {
                this.isLoading = true
                const response = await axios.post('/get-evaluacion', { id_evaluacion_rendimiento: id_evaluacion_rendimiento });
                this.contenidoEvaluacionRendimiento = response.data
            } catch (error) {
                console.log('Error en la búsqueda:', error)
            } finally {
                this.isLoading = false
            }
        },
        /**
         * 
         * @param {String} query // Almacena el nombre del empleado a buscar
         */
        handleEmployeeSearchChange(query) {
            clearTimeout(this.searchTimeout);
            if (query.trim() !== '') { // Verificar si query no está vacío o solo contiene espacios en blanco
                this.searchTimeout = setTimeout(() => {
                    this.searchingUsers(query);
                }, 700); // Tiempo de espera de menos de 1 segundo
            }
        },
        /**
         * 
         * @param {String} query // Almacena el nombre del empleado
         */
        async searchingUsers(query) {
            // Nota: No funcionara si el empleado ya tiene evaluaciones asignadas
            try {
                this.isLoading = true
                const response = await axios.post('/search-employees-for-evaluations', { data: query });
                const newDataEmployees = response.data.map(item => {
                    return {
                        value: item.id_empleado,
                        label: `${item.pnombre_persona} ${item.papellido_persona}`
                    };
                });
                this.employeOptions = newDataEmployees
            } catch (error) {
                console.log('Error en la búsqueda:', error)
            } finally {
                this.isLoading = false
            }

        },
        /**
         * Peticion async enviada al backed, reject la peticion si hay un error ( revisar consola )
         */
        createEvaluacionRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/create-new-evaluacion', {
                        id_empleado: this.id_empleado,
                        fecha_evaluacion_personal: this.fecha_evaluacion_personal,
                        periodo_evaluacion_personal: this.periodo_evaluacion_personal,
                    })
                    console.log(resp.data);
                    this.registrosEvaluacionesRentimientoPersonal = resp.data

                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa
                } catch (error) {
                    console.log(error);
                    if (error.response.status === 422) {
                        let data = error.response.data.errors
                        var myData = new Object();
                        for (const errorBack in data) {
                            myData[errorBack] = data[errorBack][0]
                        }
                        this.errors = myData
                        console.table(myData);
                        setTimeout(() => {
                            this.errors = [];
                        }, 5000);
                    }
                    reject(error); // Rechazamos la promesa en caso de excepción
                }
            });
        },
        /**
         * Funcion llamada en el boton para enviar la solicitud ( llama al metodo anteriro )
         */
        async submitEvaluacionDocument() {

            this.$swal.fire({
                title: '<p class="text-[20pt] text-center">¿Esta seguro de editar los datos?</p>',
                icon: 'question',
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: 'Si, Editar',
                confirmButtonColor: '#001b47',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true,

            }).then(async (result) => {
                if (result.isConfirmed) {
                    this.executeRequest(
                        this.createEvaluacionRequest(),
                        '¡Los datos se han agregado correctamente!'
                    )
                }
            })

        },
    },
    watch: {
        showModal() {
            if (this.showModal) {
                this.registrosEvaluacionesRentimientoPersonal = this.evaluacionEmpleadoDBData || []
                // Accion cuando abre el modal
                if (this.evaluacionEmpleadoDBData != '') {
                    const optionsMultiselect = [{
                        value: this.evaluacionEmpleadoDBData.id_empleado,
                        label: `${this.evaluacionEmpleadoDBData.persona.pnombre_persona} ${this.evaluacionEmpleadoDBData.persona.papellido_persona}`
                    }];
                    this.employeOptions = optionsMultiselect
                    this.id_empleado = this.evaluacionEmpleadoDBData.id_empleado
                }
            } else {
                //Accion cuando cierra el modal
                this.registrosEvaluacionesRentimientoPersonal = []
                this.contenidoEvaluacionRendimiento = []
                this.registroEvaluacionRendimientoPersonal = []
                this.id_empleado = ''
                this.employeOptions = []
                this.$emit("reload-table")
                this.fecha_evaluacion_personal = ''
                this.periodo_evaluacion_personal = ''
            }
        }
    }
}
</script>
