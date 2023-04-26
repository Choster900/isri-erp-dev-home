<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalIncomeConceptVue from '@/Components-ISRI/Tesoreria/ModalIncomeConcept.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Tesoreria" />
    <AppLayoutVue>
        <div class="border border-gray-600 rounded p-4 mb-4">
            <h2 class="text-lg font-bold mb-4 text-center">Cuadro de deuda</h2>
            <div class="mb-7 md:flex flex-row justify-items-start">
                <div class="mb-4 md:mr-2 md:mb-0">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                        Fecha Inicio<span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative flex h-8 flex-row-reverse">
                        <flat-pickr
                            class="peer w-[287px] text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                            :config="config" v-model="debt_table.start_date" :placeholder="'Seleccione Fecha Inicial'" />
                        <LabelToInput icon="date" />
                    </div>
                </div>
                <div class="mb-4 md:mr-2 md:mb-0">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                        Fecha Fin<span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative flex flex-row-reverse">
                        <flat-pickr
                            class="peer w-[287px] text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                            :config="config" v-model="debt_table.end_date" :placeholder="'Seleccione Fecha Final'" />
                        <LabelToInput icon="date" />
                    </div>
                </div>
                <div class="mb-4 md:mr-1 md:mb-0 basis-1/3">
                    <label class="block mb-2 text-xs font-light text-gray-600">
                        Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                        <Multiselect placeholder="Seleccione Financiamiento" :searchable="true" 
                        :options="financing_sources" v-model="debt_table.financing_source_id"/>
                        <LabelToInput icon="list" />
                    </div>
                </div>
            </div>
            <div class="mb-4 md:flex flex justify-center">
                <GeneralButton color="bg-green-700  hover:bg-green-800" text="Exportar" icon="add" />
            </div>
        </div>


    </AppLayoutVue>
</template>

<script>
export default {
    created() {
        this.getPermits()
        this.getDataSelect()
    },
    data() {
        return {
            debt_table: {
                start_date:'',
                end_date:'',
                financing_source_id:''
            },
            permits: [],
            financing_sources:[],
            config: {
                altInput: true,
                static: true,
                monthSelectorType: 'static',
                altFormat: "F-j Y",
                dateFormat: "Y-m-d",
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
        getDataSelect() {
            axios.get("/get-selects-income-concept")
                .then((response) => {
                    this.financing_sources = response.data.financing_sources
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                    this.$emit("cerrar-modal");
                });
        },
        getPermits() {
            var URLactual = window.location.pathname
            let data = this.$page.props.menu;
            let menu = JSON.parse(JSON.stringify(data['urls']))
            menu.forEach((value, index) => {
                value.submenu.forEach((value2, index2) => {
                    if (value2.url === URLactual) {
                        var array = { 'insertar': value2.insertar, 'actualizar': value2.actualizar, 'eliminar': value2.eliminar, 'ejecutar': value2.ejecutar }
                        this.permits = array
                    }
                })
            })
        },
    }
}
</script>