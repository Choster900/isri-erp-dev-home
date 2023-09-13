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

                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Fecha
                                        </label>
                                        <div class="mb-4  md:mb-0">
                                            <flat-pickr placeholder="DD/MM/YYYY" v-model="fecha_evaluacion_personal"
                                                class="w-[120px] text-xs text-center cursor-pointer rounded-[5px] border h-7 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                                :config="config" />
                                            <span class="text-xs text-red-500">{{ errors.fecha_evaluacion_personal }}</span>
                                        </div>


                                    </div>
                                    <div class="w-full px-3 sm:w-1/2">

                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Periodo
                                            evaluacion</label>
                                        <input type="text" name="lName" id="lName" placeholder="MES DIA-AÑO - MES DIA-AÑO"
                                            v-model="periodo_evaluacion_personal"
                                            class="w-[230px] text-xs  rounded-[5px] border h-7 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none" />
                                        <span class="text-xs text-red-500">{{ errors.periodo_evaluacion_personal }}</span>

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
                        <div class="p-3">
                            <div class="max-h-[600px] ">
                                <!--   <pre>
                                    {{ registrosEvaluacionesRentimientoPersonal }}
                                </pre> -->
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
                                                                    <p>{{ evaluacion.puntaje_evaluacion_personal }}</p>
                                                                    <li
                                                                        class="relative  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        Fecha Evaluado</li>
                                                                    <p>{{ evaluacion.fecha_evaluacion_personal }}</p>
                                                                    <li
                                                                        class="flex items-center relative before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                        <span class="mr-2">Ver evaluacion: </span>
                                                                        <lord-icon class="cursor-pointer"
                                                                            @click="getPersonalEvaluation(evaluacion.id_evaluacion_rendimiento);

                                                                            registroEvaluacionRendimientoPersonal = evaluacion"
                                                                            src="https://cdn.lordicon.com/vufjamqa.json"
                                                                            trigger="hover" colors="primary:#121331"
                                                                            style="width:22px;height:22px"></lord-icon>
                                                                    </li><!-- WE COULD PUT THE ICON NEXT TO THE LIST -->
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
                    <!-- Header evaluacion  -->
                    <div class=" flex  justify-center pt-2 content-between">
                        <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                            @click="$emit('cerrar-modal')">
                            <path fill="currentColor"
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                        </svg>

                    </div>

                    <!--/ Header evaluacion  -->

                    <DocumentoEvaluacionVue :contenidoEvaluacionRendimiento="contenidoEvaluacionRendimiento"
                        :registroEvaluacionRendimientoPersonal="registroEvaluacionRendimientoPersonal"
                        :info-employee="registrosEvaluacionesRentimientoPersonal" />

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
            errors: {},
            id_empleado: '',
            fecha_evaluacion_personal: '',
            periodo_evaluacion_personal: '',
            searchTimeout: null,
            registeredPersonalEvaluations: [], // Almacena todas las evaluaciones del empleado
            registroEvaluacionRendimientoPersonal: [], // Almacena una evaluacion con su detalle que se enviara al usuario
            registrosEvaluacionesRentimientoPersonal: [],// Almacena todas las evaluaciones del empleado
            employeOptions: [], // Opciones del multiselect
            contenidoEvaluacionRendimiento: {},
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
            informacionEmpleado: [],
        }
    },
    methods: {
        async getPersonalEvaluation(id_evaluacion_rendimiento) {
            try {
                this.isLoading = true
                const response = await axios.post('/get-evaluacion', { id_evaluacion_rendimiento: id_evaluacion_rendimiento });
                console.log(response.data);
                this.contenidoEvaluacionRendimiento = response.data
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
                this.registrosEvaluacionesRentimientoPersonal = []
                this.contenidoEvaluacionRendimiento = []
                this.registroEvaluacionRendimientoPersonal = []
                this.id_empleado = ''
                this.employeOptions = []
                this.$emit("reload-table")
                //Accion cuando cierra el modal

            }
        }
    }
}
</script>
