<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import moment from 'moment';
import InputError from "@/Components/InputError.vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'

import axios from 'axios';

</script>

<template>
    <Head title="Reporte - Ingreso diario" />
    <AppLayoutVue nameSubModule="Tesoreria - Reporte de Ingresos Diarios">
        <div class="border border-gray-600 rounded p-4 mb-4">
            <h2 class="text-lg font-bold mb-4 text-center">Reporte Ingresos Diarios</h2>
            <div class="mb-7 md:flex flex-row justify-items-start">
                <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
                    <label class="block mb-2 text-xs font-light text-gray-600">
                        Fecha <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative md:w-auto flex h-8 flex-row-reverse">
                        <flat-pickr
                            class="mr-1 peer w-[460px] text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                            :config="config" v-model="report_data.start_date" :placeholder="'Seleccione Fecha'" />
                        <LabelToInput icon="date" />
                    </div>
                    <InputError v-for="(item, index) in errors.start_date" :key="index" class="mt-2" :message="item" />
                </div>
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
                <GeneralButton @click="exportPDF()" color="bg-red-700 hover:bg-red-800" text="PDF" icon="pdf" />
            </div>
        </div>
    </AppLayoutVue>
</template>

<script>
import DailyIncomeReportPDF from '@/pdf/Tesoreria/DailyIncomeReportPDF.vue';
import { createApp, h } from 'vue'
import Datatable from '@/Components-ISRI/Datatable.vue';
export default {
    created() {
        this.getPermits()
        this.getDataSelect()
    },
    data() {
        return {
            financing_sources: [],
            errors: [],
            daily_income_report: [],
            receipt_numbers: [],
            report_data: {
                start_date: '',
                financing_source_id: ''
            },
            permits: [],
            config: {
                altInput: true,
                static: true,
                monthSelectorType: 'static',
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                maxDate: new Date(),
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
        async exportPDF() {
            let label
            let total_remittance = 0
            let all_totals = {
                code14199: 0,
                code14202: 0,
                code14204: 0,
                code14299: 0,
                code15799: 0,
                code15502: 0,
                code16304: 0,
            }
            let array_receipt_numbers = ''
            this.financing_sources.forEach((value, index) => {
                if (value.value == this.report_data.financing_source_id) {
                    label = value.label
                }
            })
            let date
            date = moment(this.report_data.start_date).locale('es').format('dddd D [de] MMMM [de] YYYY').toUpperCase();
            await axios.post("/get-daily-income-report",this.report_data)
                .then((response) => {
                    console.log(response.data.numeros_recibo);
                    this.errors = []
                    this.daily_income_report = response.data.daily_income_report
                    this.receipt_numbers = response.data.numeros_recibo

                    this.daily_income_report.forEach((value, key) => {
                        total_remittance += parseFloat(value.total)
                        all_totals.code14199 += parseFloat(value['14199'])
                        all_totals.code14202 += parseFloat(value['14202'])
                        all_totals.code14204 += parseFloat(value['14204'])
                        all_totals.code14299 += parseFloat(value['14299'])
                        all_totals.code15799 += parseFloat(value['15799'])
                        all_totals.code15502 += parseFloat(value['15502'])
                        all_totals.code16304 += parseFloat(value['16304'])
                    })
                    total_remittance = total_remittance.toFixed(2);
                    all_totals.code14199 = all_totals.code14199.toFixed(2);
                    all_totals.code14202 = all_totals.code14202.toFixed(2);
                    all_totals.code14204 = all_totals.code14204.toFixed(2);
                    all_totals.code14299 = all_totals.code14299.toFixed(2);
                    all_totals.code15799 = all_totals.code15799.toFixed(2);
                    all_totals.code15502 = all_totals.code15502.toFixed(2);
                    all_totals.code16304 = all_totals.code16304.toFixed(2);

                    const app = createApp(DailyIncomeReportPDF, {
                        daily_income_report: this.daily_income_report,
                        financing_source: label,
                        date: date,
                        total_remittance: total_remittance,
                        all_totals: all_totals,
                        array_receipt_numbers: this.receipt_numbers[0].numero_recibo_ingreso
                    });
                    const div = document.createElement('div');
                    const pdfPrint = app.mount(div);
                    const html = div.outerHTML;

                    //const currentDateTime = new Date().toLocaleString();
                    const currentDateTime = moment().format('DD/MM/YYYY, HH:mm:ss');
                    let fecha = moment().format('DD-MM-YYYY');

                    html2pdf()
                        .set({
                            margin: 0.2,
                            filename: 'RPT-INGRESO-DIARIO-'+fecha,
                            image: {
                                type: 'jpeg',
                                quality: 0.98
                            },
                            html2canvas: {
                                scale: 3, // A mayor escala, mejores gráficos, pero más peso
                                letterRendering: true,
                                useCORS: true
                            },
                            jsPDF: {
                                unit: "in",
                                format: "letter",
                                orientation: 'landscape' // landscape o portrait
                            },
                        })
                        //codigo para paginar 
                        .from(html).toPdf().get('pdf').then(function (pdf) {
                            var totalPages = pdf.internal.getNumberOfPages();
                            for (var i = 1; i <= totalPages; i++) {
                                pdf.setPage(i);
                                pdf.setFontSize(10);
                                //Text for the page number
                                let text = 'Página ' + i + ' de ' + totalPages;
                                const centerX = pdf.internal.pageSize.getWidth() / 2;
                                //Get the text width
                                const textWidth1 = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                                //Get the middle position including the text width
                                const textX = centerX - (textWidth1 / 2);
                                //Write the text in the desired coordinates.
                                pdf.text(textX, (pdf.internal.pageSize.getHeight() - 0.4), text);
                                //Text for the date and time.
                                let date_text = 'Generado: ' + currentDateTime
                                //Get the text width
                                const textWidth = pdf.getStringUnitWidth(date_text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                                //Write the text in the desired coordinates.
                                pdf.text(pdf.internal.pageSize.getWidth() - textWidth - 0.2, pdf.internal.pageSize.getHeight() - 0.4, date_text);
                            }

                        })
                        .save()
                        .catch(err => console.log(err));

                })
                .catch((errors) => {
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
                        this.errors = errors.response.data.errors;
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


            //html2pdf().set(opt).from(html).output('dataurlnewwindow');
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