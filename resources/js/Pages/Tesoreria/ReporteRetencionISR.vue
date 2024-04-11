<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import moment from 'moment';
import InputError from "@/Components/InputError.vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Reporte - Retencion ISR" />
    <AppLayoutVue nameSubModule="Tesoreria - Reporte de Retencion ISR">
        <div v-if="isLoadingTop"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin  fill-blue-800"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class="bg-gray-200 rounded-lg p-1 font-semibold">
                    <span class="text-blue-800">CARGANDO...</span>
                </div>
            </div>
        </div>
        <div class="border border-gray-300 rounded p-4 mb-4 mx-auto w-[75%]">
            <h2 class="text-lg font-bold mb-4 text-center">Reporte Retencion Renta</h2>
            <div class="mb-7 md:flex flex-row justify-items-start">
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                        Fecha Inicio<span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative flex">
                        <LabelToInput icon="date" />
                        <flat-pickr
                            class="peer text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                            :config="config" v-model="report_data.start_date" :placeholder="'Seleccione Fecha Inicial'" />
                    </div>
                    <InputError v-for="(item, index) in errors.start_date" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="mb-4 md:ml-1 md:mb-0 basis-1/2">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                        Fecha Fin<span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative flex">
                        <LabelToInput icon="date" />
                        <flat-pickr
                            class="peer text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                            :config="config" v-model="report_data.end_date" :placeholder="'Seleccione Fecha Final'" />
                    </div>
                    <InputError v-for="(item, index) in errors.end_date" :key="index" class="mt-2" :message="item" />
                </div>
            </div>
            <div class="mb-7 md:flex flex-row justify-center">
                <div class="mb-4 md:mr-1 md:mb-0 basis-1/2">
                    <label class="block mb-2 text-xs font-light text-gray-600">
                        Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                        <Multiselect placeholder="Seleccione Financiamiento" :searchable="true" :options="financing_sources"
                            v-model="report_data.financing_source_id" />
                        <LabelToInput icon="list" />
                    </div>
                    <InputError v-for="(item, index) in errors.financing_source_id" :key="index" class="mt-2"
                        :message="item" />
                </div>
            </div>
            <div class="mb-4 md:flex flex justify-center">
                <GeneralButton @click="exportExcel()" color="bg-green-700 hover:bg-green-800" text="Excel" icon="add" />
            </div>
        </div>
    </AppLayoutVue>
</template>

<script>
export default {
    created() {
        this.getPermissions(this)
        this.getDataSelect()
    },
    data() {
        return {
            financing_sources: [],
            errors: [],
            report_data: {
                start_date: '',
                end_date: '',
                financing_source_id: ''
            },
            permits: [],
            isLoadingTop:false,
            config: {
                altInput: true,
                //static: true,
                monthSelectorType: 'static',
                altFormat: "d/m/Y",
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
        async exportExcel() {
            this.isLoadingTop = true;
            await axios.post('/create-income-tax-report', this.report_data, {
                responseType: 'blob'
            })
                .then(response => {
                    this.errors = []
                    console.log(response.data);
                    let fecha = moment().format('DD-MM-YYYY');
                    let filename = 'RPT-RETENCION-ISR-' + fecha + '.xlsx';
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(errors => {
                    console.log(errors);
                    if (errors.response.status === 422) {
                        toast.warning(
                            "Tienes algunos errores por favor verifica tus datos.",
                            {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            }
                        );
                        const reader = new FileReader();
                        reader.readAsText(errors.response.data);
                        reader.onload = () => {
                            const errors = JSON.parse(reader.result).errors;
                            this.errors = errors
                        };

                    } else {
                        if (errors.response.status === 404) {
                            this.errors = []
                            toast.warning(
                                "No se encuentran registros para los criterios de busqueda.",
                                {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                }
                            );
                        } else {
                            const reader = new FileReader();
                            reader.readAsText(errors.response.data);
                            reader.onload = () => {
                                const errors = JSON.parse(reader.result);
                                //console.log(errors);
                            };
                            this.manageError(errors, this)
                        }

                    }
                })
                .finally(() => {
                    this.isLoadingTop = false;
                });
        },
        getDataSelect() {
            axios.get("/get-selects-withholding-tax-report")
                .then((response) => {
                    this.financing_sources = response.data.financing_sources
                })
                .catch((errors) => {
                    this.manageError(errors, this)
                });
        },
    }
}
</script>