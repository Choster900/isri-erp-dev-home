<script setup>
import GeneralInformationVue from './GeneralInformation.vue'
import FooterServiciosBodyVue from './FooterServiciosBody.vue'
import moment from 'moment';
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
</script>
<template>
    <div class="grow flex flex-col md:translate-x-0 transition-transform duration-300 ease-in-out"
        :class="profileSidebarOpen ? 'translate-x-1/3' : 'translate-x-0'">

        <!-- Profile background -->
        <div class="relative h-56">
            <img class="object-cover h-full w-full "
                src="https://images.pexels.com/photos/316093/pexels-photo-316093.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                alt="Profile background" />
            <!-- Close button -->
            <button class="md:hidden absolute top-4 left-4 sm:left-6 text-white opacity-80 hover:opacity-100"
                @click.stop="$emit('toggle-profilesidebar')" aria-controls="profile-sidebar"
                :aria-expanded="profileSidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="relative px-4 sm:px-6 pb-8">
            <!-- Pre-header -->
            <div class="-mt-16 mb-6 sm:mb-3">
                <div class="flex flex-col items-center sm:flex-row sm:justify-between sm:items-end">
                    <!-- Avatar -->
                    <div class="image-container">
                        <img class="rounded-full border-4 border-white scale-100"
                            :src="userData && userData.fotos && userData.fotos.length > 0 ? userData.fotos[userData['fotos'].length - 1].url_foto : 'https://img.freepik.com/free-icon/user_318-159711.jpg?w=2000'" />
                    </div>
                </div>
            </div>

            <!-- Header -->
            <header class="text-center sm:text-left mb-6">
                <!-- Name -->
                <div class="inline-flex items-start mb-2" v-if="userData != ''">
                    <h1 class="text-2xl text-slate-800 font-bold"> {{ userData != '' ? `${userData.empleado.codigo_empleado}
                                            - ${userData.pnombre_persona ? userData.pnombre_persona : ''} ${userData.snombre_persona ?
                            userData.snombre_persona : ''} ${userData.tnombre_persona ? userData.snombre_persona : ''}
                                            ${userData.papellido_persona ? userData.papellido_persona : ''} ${userData.sapellido_persona ?
                            userData.sapellido_persona : ''} ${userData.tapellido_persona ? userData.tapellido_persona : ''}
                        `: '' }}</h1>
                    <div class="flex space-x-2 sm:mb-2 ">
                        <DropDownOptions position="right-up">
                            <div @click="printPdf" class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                <div class="font-semibold text-xs">Imprimir todo</div>
                            </div>
                            <div @click="pruebaDeOtroPdf" class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                <div class="font-semibold text-xs w-full truncate">Imprimir lo selecionado</div>
                            </div>
                        </DropDownOptions>
                    </div>
                </div>

                <div class="animate-pulse pb-2" v-if="userData == ''">
                    <div class="h-7 bg-slate-400 rounded w-[500px]"></div>
                </div>


                <div class="flex flex-wrap justify-center sm:justify-start space-x-4">
                    <div class="flex items-center ">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z"
                                stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path
                                d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z"
                                stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                        <span v-if="userData" class="text-sm font-medium uppercase whitespace-nowrap text-slate-500 ml-2">
                            {{ userData !== undefined && userData.municipio !== undefined ? `
                            ${userData.municipio.departamento.pais.id_pais}-${userData.municipio.departamento.pais.nombre_pais}
                                                        ${userData.municipio.departamento.id_departamento}-${userData.municipio.departamento.nombre_departamento}
                                                        ${userData.municipio.id_municipio}-${userData.municipio.nombre_municipio}
                            ` : '' }}
                        </span>
                        <div class="animate-pulse" v-if="userData == ''">
                            <div class="h-3 bg-slate-400 rounded w-[350px]"></div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 fill-current shrink-0 text-slate-400" viewBox="0 0 16 16">
                            <path
                                d="M11 0c1.3 0 2.6.5 3.5 1.5 1 .9 1.5 2.2 1.5 3.5 0 1.3-.5 2.6-1.4 3.5l-1.2 1.2c-.2.2-.5.3-.7.3-.2 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1.1-1.2c.6-.5.9-1.3.9-2.1s-.3-1.6-.9-2.2C12 1.7 10 1.7 8.9 2.8L7.7 4c-.4.4-1 .4-1.4 0-.4-.4-.4-1 0-1.4l1.2-1.1C8.4.5 9.7 0 11 0ZM8.3 12c.4-.4 1-.5 1.4-.1.4.4.4 1 0 1.4l-1.2 1.2C7.6 15.5 6.3 16 5 16c-1.3 0-2.6-.5-3.5-1.5C.5 13.6 0 12.3 0 11c0-1.3.5-2.6 1.5-3.5l1.1-1.2c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4L2.9 8.9c-.6.5-.9 1.3-.9 2.1s.3 1.6.9 2.2c1.1 1.1 3.1 1.1 4.2 0L8.3 12Zm1.1-6.8c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-4.2 4.2c-.2.2-.5.3-.7.3-.2 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l4.2-4.2Z" />
                        </svg>
                        <div v-if="userData !== undefined && userData.empleado !== undefined"
                            @click="copyToClipboard(userData.empleado.email_institucional_empleado)"
                            class="text-sm font-medium whitespace-nowrap text-indigo-500 hover:text-indigo-600 ml-2 cursor-pointer">
                            {{ userData.empleado.email_institucional_empleado }}</div>
                        <div class="animate-pulse pl-1" v-if="userData == ''">
                            <div class="h-3 bg-slate-400 rounded w-[200px]"></div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center sm:justify-start space-x-4 mt-1">

                    <div class="flex items-center">
                        <svg class="h-5 w-5" viewBox="0 0 24.00 24.00" fill="none" stroke="#64748b">

                            <path
                                d="M3 9H21M7 3V5M17 3V5M6 12H8M11 12H13M16 12H18M6 15H8M11 15H13M16 15H18M6 18H8M11 18H13M16 18H18M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                                stroke="#64748b" stroke-width="1.752" stroke-linecap="round"></path>

                        </svg>
                        <a class="text-sm font-medium whitespace-nowrap text-slate-500 ml-2">Fecha de ingreso: {{
                            userData !== '' && userData.empleado !== undefined ?
                            moment(userData.empleado.fecha_contratacion_empleado).format('dddd, MMMM D, YYYY')
                            : '' }}</a>

                        <div class="animate-pulse pl-1" v-if="userData == ''">
                            <div class="h-3 bg-slate-400 rounded w-[200px]"></div>
                        </div>

                    </div>

                    <div class="flex items-center">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 17.9998C16.142 18.0343 19.5937 14.0798 19.5603 9.8043C19.5268 5.52875 16.142 2.03476 12 2.00026C7.858 1.96576 4.52734 5.4038 4.56077 9.67936C4.59419 13.9549 7.858 17.9653 12 17.9998Z"
                                stroke="#64748b" stroke-width="1.5" stroke-linecap="round"></path>
                            <path d="M15.5 9C15.4867 7.35641 14.1436 6.01326 12.5 6" stroke="#64748b" stroke-width="1.5"
                                stroke-linecap="round"></path>
                            <path
                                d="M12 20.3502C12.3212 20.3502 12.4818 20.3502 12.5933 20.3283C13.2466 20.1999 13.6441 19.5557 13.4511 18.9384C13.4181 18.833 13.342 18.6962 13.1896 18.4227M12 20.3502C11.6788 20.3502 11.5182 20.3502 11.4067 20.3283C10.7534 20.1999 10.3559 19.5557 10.5489 18.9384C10.5819 18.833 10.658 18.6962 10.8104 18.4227M12 20.3502V22.5"
                                stroke="#64748b" stroke-width="1.5" stroke-linecap="round"></path>

                        </svg>
                        <a class="text-sm font-medium whitespace-nowrap text-slate-500 ml-2">Fecha de nacimiento: {{
                            userData !== '' && userData.empleado !== undefined ?
                            moment(userData.fecha_nac_persona).format('dddd, MMMM D, YYYY')
                            : '' }}</a>

                        <div class="animate-pulse pl-1" v-if="userData == ''">
                            <div class="h-3 bg-slate-400 rounded w-[200px]"></div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Tabs -->
            <div class="relative mb-6">
                <div class="absolute bottom-0 w-full h-px bg-slate-200" aria-hidden="true"></div>
                <ul class="relative text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8">
                    <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                        <a class="block pb-3 cursor-pointer  whitespace-nowrap "
                            @click="isSelected.showInformation = true; isSelected.showAcuerdos = false"
                            :class="isSelected.showInformation ? 'border-b-2 text-indigo-500 border-indigo-500' : 'text-slate-500'">General</a>
                    </li>
                    <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                        <a class="block pb-3 cursor-pointer hover:text-slate-600 whitespace-nowrap"
                            @click="isSelected.showAcuerdos = true; isSelected.showInformation = false"
                            :class="isSelected.showAcuerdos ? 'border-b-2 text-indigo-500 border-indigo-500' : 'text-slate-500'">Acuerdos</a>
                    </li>
                </ul>
            </div>
            <!--  {{ pruebaDeOtroPdf() }} -->
            <!-- GENERAL -->
            <GeneralInformationVue :showInformation="isSelected.showInformation"
                :moreInformacionEmployee="userData != '' ? userData : ''" />
            <FooterServiciosBodyVue :showAcuerdos="isSelected.showAcuerdos"
                :deals="userData != '' ? userData.empleado.acuerdo_laboral : []" />
        </div>
    </div>
</template>
  
<script>
import { toast } from 'vue3-toastify';
import { createApp, h } from 'vue'
import HojaServiciosPdfVue from '@/pdf/RRHH/HojaServiciosPdf.vue';
export default {
    props: {
        userData: {
            type: Array,
            required: true,
            default: [],
        }
    },
    data() {
        return {
            isSelected: {
                showInformation: true,
                showAcuerdos: false,
            }
        }
    },
    methods: {
        copyToClipboard(text) {
            toast.info("Correo copiado!", {
                autoClose: 500,
                multiple: false,
                transition: toast.TRANSITIONS.ZOOM,
                position: toast.POSITION.TOP_CENTER,
            });
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        },
        printPdf(dataQuedan) {
            // Opciones de configuración para generar el PDF
            let fecha = moment().format('DD-MM-YYYY');
            let name = 'HOJA DE SERVICIO'// Nombre del pdf
            // Propiedades del pdf
            const opt = {
                //margin: 0.5, DEJANDO EL MARGEN POR DEFAUL
                filename: name,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                // pagebreak: { mode: 'avoid-all', before: '#page2el' },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            };

            // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
            const app = createApp(HojaServiciosPdfVue,{
                userData: this.userData,
            });// El pdf en cuestion

            // Crear un elemento div y montar la instancia de la aplicación en él
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            // Generar y guardar el PDF utilizando html2pdf
            html2pdf().set(opt).from(html).save();
        },

    },
    watch: {
        userData() {
            this.$emit("profileSelected", this.userData)
        }
    }
}
</script>
<style>
.image-container {
    width: 125px;
    height: 130px;
    overflow: hidden;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>