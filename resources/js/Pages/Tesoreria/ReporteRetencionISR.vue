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
    <AppLayoutVue>
        <div class="border border-gray-600 rounded p-4 mb-4">
            <h2 class="text-lg font-bold mb-4 text-center">Reporte Retencion Renta</h2>
            <div class="mb-7 md:flex flex-row justify-items-start">
                <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                        Fecha Inicio<span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative flex h-8 flex-row-reverse">
                        <flat-pickr
                            class="peer w-[460px] text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                            :config="config" v-model="report_data.start_date"
                            :placeholder="'Seleccione Fecha Inicial'" />
                        <LabelToInput icon="date" />
                    </div>
                    <InputError v-for="(item, index) in errors.start_date" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                        Fecha Fin<span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative flex flex-row-reverse">
                        <flat-pickr
                            class="peer w-[460px] text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                            :config="config" v-model="report_data.end_date" :placeholder="'Seleccione Fecha Final'" />
                        <LabelToInput icon="date" />
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
        this.getPermits()
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
        exportExcel() {
            axios.get('/create-income-tax-report',
                {
                    responseType: 'blob',
                    params: this.report_data
                }
            )
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
                                console.log(errors);
                            };

                            let msg = this.manageError(errors);
                            this.$swal.fire({
                                title: "Operación cancelada",
                                text: msg,
                                icon: "warning",
                                timer: 5000,
                            });
                        }

                    }
                });
        },
        getDataSelect() {
            axios.get("/get-selects-withholding-tax-report")
                .then((response) => {
                    this.financing_sources = response.data.financing_sources
                })
                .catch((errors) => {
                    console.log(errors);
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
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