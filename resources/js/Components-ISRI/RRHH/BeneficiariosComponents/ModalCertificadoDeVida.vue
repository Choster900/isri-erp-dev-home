<script setup>
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
</script>
<template>
    <ProcessModal maxWidth="3xl" :show="showModal" @close="$emit('cerrar-modal')">
        <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25" @click="$emit('cerrar-modal')">
            <path fill="currentColor"
                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
        </svg>

        <div class="my-7 mx-4">
            <!-- <div class="flex justify-center">
                <GeneralButton :color="'bg-red-700 hover:bg-red-800'" text="Imprimir" icon="pdf" @click="printPdf()" />
            </div> -->
            <!--  <pre>
                {{ beneficiarios }}
            </pre> -->
            <table border="0" cellpadding="0" cellspacing="0" class="border-2 border-black sheet0 ">
                <col class="col0">
                <col class="col1">
                <col class="col2">
                <col class="col3">
                <col class="col4">
                <col class="col5">
                <col class="col6">
                <col class="col7">
                <tbody>
                    <tr>
                        <td class="border border-black py-2 px-1" rowspan="2">
                            <img src="../../../../img/MINISTERIO_DE_HACIENDA.png" alt="">
                        </td>
                        <td class="border border-black" colspan="7">
                            <div class="flex items-center justify-center">
                                <div class="relative text-center text-lg"
                                    style="font-family: 'Alumni Sans', sans-serif; letter-spacing: 0.001em;">
                                    C E R T I F I C A D O &nbsp; D E &nbsp; S E G U R O &nbsp; C O L E C T I V O &nbsp; D E
                                    &nbsp; V I D A
                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    @click="printPdf" stroke="currentColor"
                                    class="w-5 h-5 ml-2 stroke-slate-400 hover:stroke-slate-500 cursor-pointer">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                </svg>
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td class="border border-black text-xs px-4 " colspan="7">
                            <div class="relative text-center" style="top: -7px;">
                                Reservado Ministerio de Hacienda: &nbsp;
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-[8pt] font-semibold pl-2" colspan="8"> I&#41; DATOS GENERALES</td>
                    </tr>
                    <tr>
                        <td class="border border-black text-[8pt] pl-2 pt-1.5" colspan="6">
                            <div class="relative " style="top: -6px;">
                                1&#41; Nombre del asegurado según DUI
                            </div>
                            <div class="relative pl-4 text-[7pt]" style="top: -7px;" v-if="beneficiarios != ''">
                                {{ `${beneficiarios.pnombre_persona || ''}` }}
                                {{ `${beneficiarios.snombre_persona || ''}` }}
                                {{ `${beneficiarios.tnombre_persona || ''}` }}
                                {{ `${beneficiarios.papellido_persona || ''}` }}
                                {{ `${beneficiarios.sapellido_persona || ''}` }}
                                {{ `${beneficiarios.tapellido_persona || ''}` }}
                            </div>
                        </td>
                        <td class="border border-black text-[8pt] pl-2 pt-1.5" colspan="2">
                            <div class="relative " style="top: -6px;">
                                2&#41; Número del DUI
                            </div>
                            <div class="relative pl-4 text-[7pt]" style="top: -7px;">
                                {{ beneficiarios.dui_persona }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="border border-black text-[8pt] pl-2 pt-1.5" colspan="2">
                            <div class="relative " style="top: -6px;">
                                3&#41; Lugar y fecha de Nacimiento:
                            </div>
                            <div class="relative pl-4 text-[6pt]" style="top: -7px;" v-if="beneficiarios != ''">
                                {{ beneficiarios.municipio.nombre_municipio || '' }}
                                {{ beneficiarios.municipio.departamento.nombre_departamento || '' }},
                                {{ moment(beneficiarios.fecha_nac_persona).format('D-MMM-YY') || '' }}
                            </div>
                        </td>
                        <td class="border border-black text-[8pt] pl-2 pt-1.5" colspan="2">
                            <div class="relative " style="top: -6px;">
                                4&#41; Cargo:
                            </div>
                            <div class="relative  text-[6pt]" style="top: -7px;" v-if="beneficiarios != ''">
                                <!--  {{ beneficiarios.empleado.plazas_asignadas.
                                    filter((plaza) => plaza.estado_plaza_asignada == 1).map((plaza, index) => {
                                        return `${plaza.detalle_plaza.plaza.nombre_plaza}`
                                    }).join(", ")
                                }} -->
                                <!-- <pre>
                                    {{ beneficiarios.empleado.plazas_asignadas[0].detalle_plaza.plaza.nombre_plaza }}
                                </pre> -->
                                {{ beneficiarios.empleado.plazas_asignadas[0].detalle_plaza.plaza.nombre_plaza }}
                            </div>
                        </td>
                        <td class="border border-black text-[8pt] pl-2 pt-1.5" colspan="4">
                            <div class="relative " style="top: -6px;">
                                5&#41; Dirección del Asegurado:
                            </div>
                            <div class="relative pl-4 text-[6pt]" style="top: -7px;" v-if="beneficiarios != ''">
                                {{ beneficiarios.residencias.find(index => index.estado_residencia ==
                                    1).direccion_residencia }}
                            </div>
                        </td>
                    </tr>
                    <tr class="row5">
                        <td class="border border-black text-[8pt] pl-2 pt-1.5" colspan="4">
                            <div class="relative " style="top: -6px;">
                                6&#41; Unidad Primaria en que labora
                            </div>
                            <div class="relative pl-4 text-[7pt]" style="top: -7px;">
                                MINISTERIO DE SALUD Y ASISTENCIA SOCIAL
                            </div>
                        </td>
                        <td class="border border-black text-[8pt] pl-2 pt-1.5" colspan="4">
                            <div class="relative " style="top: -6px;">
                                7&#41; Unidad Secundaria
                            </div>
                            <div class="relative pl-4 text-[7pt]" style="top: -7px;">
                                INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td class="text-[8pt] font-semibold pl-2 " colspan="8"> II&#41; BENEFICIARIOS</td>
                    </tr>
                    <tr>
                        <td class="border border-black text-[8pt] pl-2" colspan="5" style="letter-spacing: 0.1em;">NOMBRE Y
                            APELLIDO</td>
                        <td class="border border-black text-[8pt] text-center" colspan="2" style="letter-spacing: 0.2em;">
                            PARENTESCO</td>
                        <td class="border border-black text-[8pt] text-center">%</td>

                    </tr>
                    <tr v-for="i in 6" :key="i" v-if="beneficiarios != ''">
                        <td v-if="beneficiarios.familiar[i - 1]" class="border border-black text-[8pt] pl-2" colspan="5">{{
                            i }}.{{ beneficiarios.familiar[i - 1].nombre_familiar }}</td>
                        <td v-else class="border border-black text-[8pt] pl-2" colspan="5">{{ i }}.</td>

                        <td v-if="beneficiarios.familiar[i - 1]" class="border border-black text-[8pt] text-center"
                            colspan="2">{{ beneficiarios.familiar[i - 1].parentesco.nombre_parentesco }}</td>
                        <td v-else class="border border-black text-[8pt] text-center" colspan="2"></td>

                        <td v-if="beneficiarios.familiar[i - 1]" class="border border-black text-[8pt] text-center">{{
                            beneficiarios.familiar[i - 1].porcentaje_familiar }}</td>
                        <td v-else class="border border-black text-[8pt] text-center"></td>
                    </tr>
                    <tr class="row9">
                        <td class="text-[8pt] font-semibold pl-2 " colspan="8"> III&#41; AUTORIZACIÓN</td>
                    </tr>
                    <tr>
                        <td class="border border-black text-[8pt] pl-2"> 1&#41; Firma del Asegurado</td>
                        <td class="border border-black text-[8pt] pl-2" colspan="2"> 2 &#41; Lugar y Fecha</td>
                        <td class="border border-black text-[8pt] pl-2" colspan="3"> 3&#41; Sello de la Unidad</td>
                        <td class="border border-black text-[8pt] pl-2" colspan="2"> 4&#41; Firma de Autorizado</td>
                    </tr>
                    <tr>
                        <td class="py-5 border border-black"> </td>
                        <td class=" text-center border border-black  text-[8pt]" colspan="2"> SAN SALVADOR <br>{{
                            moment().format('D-MMM-YY') }}</td>
                        <td class="py-5 border border-black" colspan="3"> </td>
                        <td class="py-5 border border-black" colspan="2"> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </ProcessModal>
</template>

<script>
import { createApp, h, watch } from 'vue'
import CertificadoSeguroColectivoDeVida from '@/pdf/RRHH/CertificadoSeguroColectivoDeVida.vue';

export default {
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
        dataBeneficiarios: {
            type: Object,
            default: [],
        },
    },
    data: () => ({
        beneficiarios: [],
    }),
    methods: {
        printPdf() {
            console.log();
            // Opciones de configuración para generar el PDF
            const opt = {
                margin: [0.03, 0, 0, 1.45], //top, left, buttom, right,
                filename: 'CERTIFICACION DE SEGURO COLECTIVO DE VIDA ',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
            };

            // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
            const app = createApp(CertificadoSeguroColectivoDeVida, {
                beneficiarios: this.beneficiarios
            });

            // Crear un elemento div y montar la instancia de la aplicación en él
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            // Generar y guardar el PDF utilizando html2pdf
            html2pdf().set(opt).from(html).save();
        },
    },
    watch: {
        showModal: function (value, oldValue) {
            if (value) {
                if (this.dataBeneficiarios != '') {

                    this.beneficiarios = this.dataBeneficiarios
                } else {
                    this.beneficiarios = []
                }
            } else {

            }

        },
    },
}
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Alumni+Sans:wght@800&display=swap');

table.sheet0 col.col0 {
    width: 116.37777658pt
}

table.sheet0 col.col1 {
    width: 66.06666592pt
}

table.sheet0 col.col2 {
    width: 97.59999958pt
}

table.sheet0 col.col3 {
    width: 27pt
}

table.sheet0 col.col4 {
    width: 44.13333254pt
}

table.sheet0 col.col5 {
    width: 44.09999923pt
}

table.sheet0 col.col6 {
    width: 95.19999916pt
}

table.sheet0 col.col7 {
    width: 65.06666592pt
}
</style>
