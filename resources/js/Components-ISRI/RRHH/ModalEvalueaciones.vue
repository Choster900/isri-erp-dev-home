<script setup>
import { toast } from 'vue3-toastify'; // Importar el módulo toast de vue3-toastify
import 'vue3-toastify/dist/index.css'; // Importar los estilos de vue3-toastify
import TooltipVue from '../Tooltip.vue';
import moment from 'moment';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import AccordionBasicVue from '../AccordionBasic.vue';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import DocumentoEvaluacionVue from './DocumentoEvaluacion.vue';
</script>

<template>
    <div class="m-1.5">
        <!-- Componente del modal ProcessModal -->
        <ProcessModal maxWidth="7xl" :show="showModal" @close="$emit('cerrar-modal')">

            <div class="flex flex-col md:flex-row md:space-y-0 max-h-screen ">
                <div class="w-full md:w-2/6 bg-slate-200/40 p-4 border">

                    <div
                        class="max-h-[calc(100vh-90px)]  col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300">
                        <header class="px-5 py-4 border-b-4 border-indigo-500">
                            <h2 class="font-semibold text-slate-800">FORMULARIO</h2>
                        </header>
                        <div class="p-3">
                            <div class="max-h-[600px] ">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre del
                                        empleado</label>

                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect v-model="id_empleado"
                                            :disabled="dataEvaluacionEmpleado != '' ? true : false"
                                            @search-change="handleEmployeeSearchChange($event)" :clearOnSearch="true"
                                            placeholder="Puedes filtrar por apellidos" :filter-results="false"
                                            :min-chars="10" :resolve-on-load="true" :searchable="true"
                                            :options="employeOptions" noOptionsText="Sin opciones" :classes="{
                                                noOptions: 'py-2 px-3 text-gray-600 bg-white text-left text-sm ',
                                                placeholder: 'text-sm flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-slate-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ',
                                            }" />
                                    </div>
                                </div>
                                <div class="-mx-3 flex flex-wrap">
                                    <div class="w-full px-3 sm:w-1/3">

                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Fecha
                                        </label>
                                        <div class="mb-4  md:mb-0">
                                            <flat-pickr placeholder="DD/MM/YYYY" v-model="fecha_evaluacion_personal"
                                                class="w-[120px] text-xs text-center cursor-pointer rounded-[5px] border h-7 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                                :config="config" />
                                        </div>

                                    </div>
                                    <div class="w-full px-3 sm:w-1/2">

                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Periodo
                                            evaluacion</label>
                                        <input type="text" name="lName" id="lName" placeholder="MES DIA-AÑO - MES DIA-AÑO"
                                            v-model="periodo_evaluacion_personal"
                                            class="w-[230px] text-xs  rounded-[5px] border h-7 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none" />

                                    </div>
                                </div>
                                <button @click="submitEvaluacionDocument()"
                                    class="bg-indigo-900 rounded-sm shadow text-center text-white text-base font-light w-full py-1 mt-5">Nueva
                                    evaluacion</button>
                            </div>
                        </div>
                    </div>
                    <div
                        class="max-h-[calc(100vh-350px)] overflow-y-auto col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300">
                        <!--   <header class="px-5 py-4 border-b border-slate-100">
                            <h2 class="font-semibold text-slate-800">LO001 - Sergio Adonay Lopez Mejia</h2>
                        </header> -->
                        <div class="p-3">
                            <div class="max-h-[600px] ">
                                <table class="table-auto w-full">
                                    <tbody class="text-sm divide-y divide-slate-100"><!-- Row -->
                                        <tr>
                                            <td>
                                                <template v-for="e, i in  evaluaciones " :key="i">
                                                    <AccordionBasicVue containerClass=" rounded-sm border border-slate-200"
                                                        bodyClass="text-red-500">
                                                        <template v-slot:titleContent>
                                                            <h1 class="text-center font-medium pl-4 py-3">


                                                                <ul class="ml-4 list-circle">
                                                                    <li
                                                                        class="relative  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        {{ e.periodo_evaluacion_personal }} </li>

                                                                </ul>

                                                            </h1>
                                                        </template>
                                                        <template v-slot:bodyContent>
                                                            <div
                                                                class="bg-slate-300/40 border-l-[4px] border-y-0 border-r-0 border-l-indigo-500 ">
                                                                <h1
                                                                    class="text-start font-semibold pl-3 pt-2 text-indigo-500">
                                                                    Lorem ipsum dolor sit amet consectetur</h1>
                                                                <ul class="ml-10 list-circle py-4">
                                                                    <li
                                                                        class="relative  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        Puntuacion</li>
                                                                    <p>{{ e.puntaje_evaluacion_personal }}</p>
                                                                    <li
                                                                        class="relative  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        Fecha Evaluado</li>
                                                                    <p>{{ e.fecha_evaluacion_personal }}</p>
                                                                    <li
                                                                        class="flex items-center relative before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        <span class="mr-2">Ver evaluacion: </span>
                                                                        <lord-icon class="cursor-pointer"
                                                                            @click="getPersonalEvaluation(e.id_evaluacion_rendimiento)"
                                                                            src="https://cdn.lordicon.com/vufjamqa.json"
                                                                            trigger="hover" colors="primary:#121331"
                                                                            style="width:20px;height:20px"></lord-icon>
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

                <div class=" justify-center w-4/6">
                    <div class=" flex  justify-center pt-2 content-between">
                        <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                            @click="$emit('cerrar-modal')">
                            <path fill="currentColor"
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                        </svg>

                    </div>
                    <div class="pl-4 pt-4">
                        <h2 class="text-2xl text-slate-800 font-bold mb-6">Nombre del modal</h2><!-- Start -->
                        <div class="mb-3 border-b border-slate-200">
                            <ul class="text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 ">
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a class="text-indigo-500 whitespace-nowrap flex items-center" href="#0"><svg
                                            class="w-4 h-4 shrink-0 fill-current mr-2" viewBox=" 0 0 16 16">
                                            <path
                                                d="M12.311 9.527c-1.161-.393-1.85-.825-2.143-1.175A3.991 3.991 0 0012 5V4c0-2.206-1.794-4-4-4S4 1.794 4 4v1c0 1.406.732 2.639 1.832 3.352-.292.35-.981.782-2.142 1.175A3.942 3.942 0 001 13.26V16h14v-2.74c0-1.69-1.081-3.19-2.689-3.733zM6 4c0-1.103.897-2 2-2s2 .897 2 2v1c0 1.103-.897 2-2 2s-2-.897-2-2V4zm7 10H3v-.74c0-.831.534-1.569 1.33-1.838 1.845-.624 3-1.436 3.452-2.422h.436c.452.986 1.607 1.798 3.453 2.422A1.943 1.943 0 0113 13.26V14z">
                                            </path>
                                        </svg>
                                        <span>Comming soon</span>
                                    </a>
                                </li>
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a class="text-slate-500 hover:text-slate-600 whitespace-nowrap flex items-center"
                                        href="#0"><svg class="w-4 h-4 shrink-0 fill-current text-slate-400 mr-2"
                                            viewBox=" 0 0 16 16">
                                            <path
                                                d="M14.3.3c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-8 8c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l8-8zM15 7c.6 0 1 .4 1 1 0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8c.6 0 1 .4 1 1s-.4 1-1 1C4.7 2 2 4.7 2 8s2.7 6 6 6 6-2.7 6-6c0-.6.4-1 1-1z">
                                            </path>
                                        </svg>
                                        <span>Comming soon</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                  <!--   <pre>
                        {{ evaluaciones }}
                    </pre> -->
                    <DocumentoEvaluacionVue :dataEvaluacion="dataEvaluacionPersonal" :dataPersonalEvaluacion="evaluaciones" />

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
        dataEvaluacionEmpleado: {
            type: Object,
            default: [],
        }
    },
    data() {
        return {
            id_empleado: '',
            fecha_evaluacion_personal: '',
            periodo_evaluacion_personal: '',
            searchTimeout: null,
            evaluaciones: [], // Almacena las evaluaciones del empleado
            employeOptions: [], // Opciones del multiselect
            //   dataDocumentoEvaluacion: {},
            dataEvaluacionPersonal: {},
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
        }
    },
    methods: {
        async getPersonalEvaluation(id_evaluacion_rendimiento) {
            try {
                this.isLoading = true
                const response = await axios.post('/get-evaluacion', { id_evaluacion_rendimiento: id_evaluacion_rendimiento });
                //console.log(response.data);
                this.dataEvaluacionPersonal = response.data
            } catch (error) {
                console.log('Error en la búsqueda:', error)
            } finally {
                this.isLoading = false
            }
        },
        handleEmployeeSearchChange(query) {
            clearTimeout(this.searchTimeout);
            if (query.trim() !== '') { // Verificar si query no está vacío o solo contiene espacios en blanco
                this.searchTimeout = setTimeout(() => {
                    this.searchingUsers(query);
                }, 700); // Tiempo de espera de menos de 1 segundo
            }
        },
        // Metodo para buscar empleado
        async searchingUsers(query) {
            // Nota: No funcionara si el empleado ya tiene evaluaciones asignadas
            try {
                this.isLoading = true
                const response = await axios.post('/search-employees', { data: query });
                console.log(response.data);

                // Objeto que almacenara las opciones en multiselect
                const newDataEmployees = response.data.map(item => {
                    return {
                        value: item.id_empleado,
                        label: `${item.pnombre_persona} ${item.papellido_persona}`
                    };
                });
                this.employeOptions = newDataEmployees
                console.log(newDataEmployees);
            } catch (error) {
                console.log('Error en la búsqueda:', error)
            } finally {
                this.isLoading = false
            }

        },
        async getDocumentoEvaluacion() {
            await axios.post('/get-evaluacion-rendimiento').then((response) => {
                //this.dataDocumentoEvaluacion = response.data
            })
        },
        createEvaluacionRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/create-new-evaluacion', {
                        id_empleado: this.id_empleado,
                        fecha_evaluacion_personal: this.fecha_evaluacion_personal,
                        periodo_evaluacion_personal: this.periodo_evaluacion_personal,
                    })
                    console.log(resp);
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa
                } catch (error) {

                    reject(error); // Rechazamos la promesa en caso de excepción
                }
            });
        },
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



    created() {

        this.getDocumentoEvaluacion()
    },
    watch: {
        showModal() {
            if (this.showModal) {

                this.evaluaciones = this.dataEvaluacionEmpleado.evaluaciones_personal
               // console.log(this.dataEvaluacionEmpleado);

            } else {
                this.dataEvaluacionPersonal = []
            }
        }
    }
}
</script>
