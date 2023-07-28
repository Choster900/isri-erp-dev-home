<script setup>
import { toast } from 'vue3-toastify'; // Importar el módulo toast de vue3-toastify
import 'vue3-toastify/dist/index.css'; // Importar los estilos de vue3-toastify
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'; // Importar el componente ProcessModal
import TooltipVue from '../Tesoreria/Tooltip.vue';
import moment from 'moment';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";

</script>

<template>
    <div class="m-1.5">
        <!-- Componente del modal ProcessModal -->
        <Modal maxWidth="4xl" :show="showModal" modal-title="Acuerdos laborales" @close="$emit('cerrar-modal')">
            <div class="px-4 py-2">
                <div class="flex flex-col md:flex-row ">
                    <div class="w-full md:w-1/2 ">

                        <div class="flex justify-start">
                            <div class="flex-1 px-0.5 pt-3">
                                <div class="md:flex flex-row justify-items-start mb-4">
                                    <div class="mb-4 md:mr-2 md:mb-0 basis-full ">
                                        <label class="block mb-1 text-xs font-light text-gray-600">
                                            Empleado <span class="text-red-600 font-extrabold">*</span>
                                        </label>
                                        <div class="relative font-medium  flex h-8 w-full flex-row-reverse">
                                            <Multiselect v-model="dataForm.id_empleado"
                                                @search-change="handleSearch({ by: 'name', query: $event })"
                                                :clearOnSearch="true"
                                                placeholder="FILTRAR POR APELLIDO (SI NO APARECE ES POSIBLE QUE YA TENGA REGISTROS)"
                                                :filter-results="false" :min-chars="10" :resolve-on-load="true"
                                                :searchable="true" :options="employeOptions"
                                                noOptionsText="<p class='text-xs'>Sin registros<p>" :classes="{
                                                    placeholder: 'flex items-center h-full absolute text-[9px] left-0 top-0 pointer-events-none bg-transparent leading-snug pl-2 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                }" />

                                            <LabelToInput icon="personalInformation" />
                                        </div>
                                    </div>
                                </div>
                                <div class="md:flex flex-row justify-items-start mb-4">
                                    <div class="mb-4 md:mr-2 md:mb-0 basis-full ">
                                        <label class="block mb-1 text-xs font-light text-gray-600">
                                            Acuerdo laboral <span class="text-red-600 font-extrabold pl-1">*</span>
                                        </label>
                                        <div class="relative font-medium  flex h-8 w-full flex-row-reverse">
                                            <Multiselect v-model="dataForm.deal.id_tipo_acuerdo_laboral"
                                                @select="dataForm.deal.nombre_tipo_acuerdo_laboral = JSON.parse(JSON.stringify(dataForSelect.tipo_acuerdo_laboral.find((index) => index.value == dataForm.deal.id_tipo_acuerdo_laboral))).label"
                                                :clearOnSearch="true" :searchable="true"
                                                :options="dataForSelect.tipo_acuerdo_laboral"
                                                noResultsText="<p class='text-xs'>Registro no encontrado<p>"
                                                noOptionsText="<p class='text-xs'>Sin registros<p>" :classes="{
                                                    placeholder: 'flex items-center h-full absolute text-[9px] left-0 top-0 pointer-events-none bg-transparent leading-snug pl-2 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                }" />
                                            <LabelToInput icon="list" />
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-4 md:flex flex-row justify-items-start">
                                    <div class="mb-4 md:ml-1 md:mb-0 basis-1/2">
                                        <label class="block mb-1 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                            Fecha<span class="text-red-600 font-extrabold pl-1">*</span>
                                        </label>
                                        <div class="relative flex">
                                            <LabelToInput icon="date" />
                                            <flat-pickr v-model="dataForm.deal.fecha_acuerdo_laboral"
                                                class="peer text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300  focus:outline-none w-full"
                                                :config="config" :placeholder="'Seleccione Fecha Final'" />
                                        </div>
                                    </div>
                                    <div class="mb-4 md:ml-1 md:mb-0 basis-1/2">
                                        <label class="block mb-1 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                            Fecha [Desde - Hasta]
                                            <span class="text-red-600 font-extrabold">*</span>
                                        </label>
                                        <div class="relative flex">
                                            <LabelToInput icon="date" />
                                            <flat-pickr v-model="dataForm.deal.fecha_inicio_fecha_fin_acuerdo_laboral"
                                                class="peer text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 f focus:outline-none w-full"
                                                :config="configSecondInput" :placeholder="'Seleccione Fecha Final'" />
                                        </div>
                                    </div>


                                </div>
                                <div class="mb-4 md:flex flex-row justify-items-start">
                                    <div class="mb-1 md:mr-2 md:mb-0 basis-full">
                                        <TextInput id="oficio-acuerdo" type="text" placeholder="Numero de referencia"
                                            v-model="dataForm.deal.oficio_acuerdo_laboral"
                                            @update:modelValue="dataForm.deal.oficio_acuerdo_laboral = dataForm.deal.oficio_acuerdo_laboral.toUpperCase()">
                                            <LabelToInput icon="standard" forLabel="primer-nombre" />
                                        </TextInput>
                                    </div>
                                </div>


                                <div class="basis-full" style="border: none; background-color: transparent;">
                                    <label class="block mb-1 text-xs font-light text-gray-600" for="descripcion">
                                        Descripción
                                        <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <textarea id="descripcion" name="descripcion" style="overflow-x: hidden;"
                                        v-model="dataForm.deal.comentario_acuerdo_laboral"
                                        class="resize-none w-full h-24 overflow-y-auto peer text-xs font-normal rounded-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-none focus:outline-none"></textarea>

                                </div>

                            </div>
                        </div>
                        <div class=" flex justify-center">
                            <div v-show="errorForm" :class="{ 'animate-shake': shouldShowErrorAnimation }"
                                class=" flex w-full items-center font-medium py-1.5 pr-2  rounded-md text-red-700 bg-red-100 border border-red-300 ">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-alert-octagon w-4 h-4 mx-2">
                                        <polygon
                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                        </polygon>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                                <div class="text-xs font-normal  max-w-full flex-initial">{{ messageError }}</div>
                                <div class="flex flex-auto flex-row-reverse">
                                    <div @click="errorForm = false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-x cursor-pointer hover:text-red-400 rounded-full w-4 h-4 ml-2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- Columna izquierda -->
                    <!-- Columna derecha -->
                    <div class="w-full md:w-1/2 ">
                        <div class="px-1 md:px-2">
                            <span class="text-slate-500 whitespace-nowrap flex items-center justify-center gap-2">
                                <span class="flex py-1 gap-2">
                                    <span>Seguimiento</span>
                                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6">
                                        <path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </span>

                            <div class="bg-slate-200/60 rounded-lg border border-slate-200" style="overflow-x: hidden;">
                                <div class="flex flex-col justify-end items-center">
                                    <div class="text-center">
                                        <div class="sm:flex sm:justify-end sm:items-center pt-4">
                                            <div @click="createNewDeal()"
                                                class="flex justify-center gap-2 text-[10pt] cursor-pointer hover:border-indigo-400 hover:text-indigo-400  border-2 border-dashed border-indigo-500 text-indigo-500 h-14 w-96 rounded-lg bg-transparent text-center py-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                <span class="text-selection-disable">{{ dataDeals.some((deal) =>
                                                    deal.isEditingDeal) ?
                                                    'AGREGAR NUEVA MODIFICACION' : 'AGREGAR NUEVO ACUERDO' }}</span>
                                            </div>
                                        </div>
                                        <!--  {{ dataDeals }} -->
                                        <div class="h-[285px] max-h-full overflow-y-auto mt-2 mb-1 px-1"
                                            v-if="dataDeals.filter((i) => !i.isDelete).length !== 0">
                                            <div class="sm:flex sm:items-center text-selection-disable"
                                                v-for="(deal, i) in dataDeals" :key="i">
                                                <div class="flex overflow-hidden  cursor-pointer" :class="{
                                                    'mb-4': dataDeals.length != i + 1,
                                                    'rounded-lg border border-gray-400': deal.isEditingDeal,
                                                }" v-if="!deal.isDelete">
                                                    <div @click="deal.isEditingDeal ? cancelEdition(deal.indexDeal) : sendInformationWhenIsClicked(deal)"
                                                        :class="dataDeals.some((deal) => deal.isEditingDeal) ?
                                                            deal.isEditingDeal ? 'bg-white' : 'cursor-not-allowed bg-white' : 'bg-white'"
                                                        class="w-[336px] rounded-l-lg  py-3   max-h-full overflow-y-auto">
                                                        <div class="text-[8pt]">
                                                            <div class="flex flex-wrap items-center -m-1.5 mx-3 mb-1 gap-1">
                                                                <span class="inline-flex font-medium">&#8226;{{
                                                                    moment(deal.fecha_acuerdo_laboral).format('L') }}</span>
                                                                <div :class="[
                                                                    deal.id_tipo_acuerdo_laboral == 1 ? 'bg-indigo-100 text-indigo-600 ' :
                                                                        deal.id_tipo_acuerdo_laboral == 2 ? 'bg-sky-100 text-sky-600' :
                                                                            deal.id_tipo_acuerdo_laboral == 3 ? 'bg-emerald-100 text-emerald-600' :
                                                                                deal.id_tipo_acuerdo_laboral == 4 ? 'bg-amber-100 text-amber-600' :
                                                                                    deal.id_tipo_acuerdo_laboral == 5 ? 'bg-rose-100 text-rose-600' :
                                                                                        deal.id_tipo_acuerdo_laboral == 6 ? 'bg-slate-700 text-slate-100 ' : 'bg-slate-100 text-slate-500',
                                                                ]"
                                                                    class="inline-flex font-medium  rounded-full text-center px-1.5 py-.5">
                                                                    {{ deal.nombre_tipo_acuerdo_laboral }}
                                                                </div>
                                                            </div>
                                                            <h1 class="mx-3 font-semibold text-justify">
                                                                {{ deal.oficio_acuerdo_laboral }}
                                                            </h1>
                                                            <p class="mx-3 text-justify">
                                                                {{ deal.comentario_acuerdo_laboral }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div @click="deleteDealWhenIsClicked(deal.indexDeal)"
                                                        class="flex items-center justify-center rounded-r-lg hover:bg-slate-400/50  bg-slate-300 shadow-border hover:bg-blue-dark text-sm py-4 px-3 uppercase font-bold">
                                                        <svg class="w-5 h-5" viewBox="0 0 1024.00 1024.00"
                                                            stroke-width="0.01024">
                                                            <path
                                                                d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z"
                                                                fill="#818cf8"></path>
                                                            <path
                                                                d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z"
                                                                fill="#818cf8"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full h-[300px] px-1 text-selection-disable" v-else>
                                            <div class="flex flex-col items-center justify-center h-full">
                                                <img src="../../../img/noData.svg" class="h-48 rounded-full mx-auto"
                                                    alt="SVG Image" draggable="false">
                                                <h1 class="font-medium text-center">Parece que aún no hay acuerdos
                                                    registrados</h1>
                                                <p class="text-[9pt] text-center">Cuando registres nuevos acuerdos, se
                                                    mostrarán en esta área.</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-start pt-1 gap-3"><!-- TODO: DEFINIR POSICION -->
                                <select v-model.number="year" @change="filterDealsByYear()"
                                    class="border border-gray-300 rounded-lg text-gray-600 h-8 text-xs  bg-white hover:border-gray-400 focus:outline-none appearance">
                                    <option disabled>Filtrar por año</option>
                                    <option v-for="yearOption in listYears" :key="yearOption" :value="yearOption">
                                        {{ yearOption }}
                                    </option>

                                </select>
                                <GeneralButton @click="newDataDeals.filter((i) => !i.isDelete) != '' ? addDeals() : ''"
                                    v-if="dataAcuerdos == ''" color="bg-green-700  hover:bg-green-800"
                                    text="Agregar todos los acuerdos" icon="add" />

                                <GeneralButton
                                    @click="[...copyDataDeals, ...newDataDeals].filter((i) => !i.isDelete) != '' ? updateDeals() : ''"
                                    v-else color="bg-orange-700  hover:bg-orange-800" text="Editar todos los acuerdos"
                                    icon="add" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Calendario de contribuciones -->
                <div class="p-2 bg-slate-200/60 rounded-lg border border-slate-200">
                    <div class="flex  justify-center">
                        <div class="grid grid-rows-1 grid-flow-col gap-10">
                            <template v-for="(month, mothIndex) in monthName" :key="mothIndex">
                                <span class="text-xs">{{ month }}</span>
                            </template>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="w-1/1 ">
                            <div class="flex flex-col justify-center items-center h-full">
                                <div class="text-end space-y-3">
                                    <div class="text-xs pr-1 text-black">Lun</div>
                                    <div class="text-xs pr-1">Mie</div>
                                    <div class="text-xs pr-1">Vie</div>
                                </div>
                            </div>
                        </div>
                        <div class="w-3/1 ">
                            <div class="flex justify-center items-center h-full">
                                <div class="grid grid-rows-7 grid-flow-col">
                                    <template v-for="(month, mothIndex) in month" :key="mothIndex">
                                        <TooltipVue bg="dark" v-for="(day, weekIndex) in dayGenerater()[mothIndex]"
                                            :key="weekIndex">
                                            <template v-slot:contenido>
                                                <div class="h-[11px] w-[11px]  m-[1.5px] rounded-sm"
                                                    :class="isDateInDataDeals(moment(`${year}-${month}-${day}`).format('L')) ? 'bg-green-900/90' : 'bg-gray-500'">
                                                </div>
                                            </template>
                                            <template v-slot:message>
                                                <div class="text-xs text-slate-200 whitespace-nowrap">{{
                                                    transFormDat(day, month, year) }}</div>
                                            </template>
                                        </TooltipVue>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--// Calendario de contribuciones -->
            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
        dataForSelect: {
            type: Object,
            default: [],
        },
        dataAcuerdos: {
            type: Object,
            default: [],
        }
    },
    data() {
        return {
            employeOptions: [],
            month: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            monthName: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            year: '',
            listYears: [],
            config: {
                altInput: true,
                static: true,
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
            configSecondInput: {
                mode: 'range',
                altInput: true,
                static: true,
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
            errorForm: '',
            messageError: '',
            shouldShowErrorAnimation: false, // Nueva propiedad para controlar la animación
            dataForm:
            {
                id_empleado: '',
                deal: {
                    indexDeal: '',
                    id_acuerdo_laboral: '',
                    id_tipo_acuerdo_laboral: '',
                    nombre_tipo_acuerdo_laboral: '',
                    fecha_acuerdo_laboral: '',
                    oficio_acuerdo_laboral: '',
                    comentario_acuerdo_laboral: '',
                    fecha_inicio_fecha_fin_acuerdo_laboral: '',
                    isDelete: false,
                    isEditingDeal: false,
                }
            },
            dataDeals: [], // Global data que se usaria para mostrar en la lista de acuerdos laborales
            newDataDeals: [],// New data you add
            copyDataDeals: [],// Data comers from DB
        }
    },
    methods: {

        isDateInDataDeals(date) {
            return this.dataDeals.some((deal) => moment(deal.fecha_acuerdo_laboral).format('L') == date);
        },
        isDataValid() {
            // Perform form validation here
            const deal = this.dataForm.deal;
            return (
                this.dataForm.id_empleado !== '' &&
                deal.id_tipo_acuerdo_laboral !== '' &&
                (deal.fecha_acuerdo_laboral !== '' && deal.fecha_acuerdo_laboral !== null) &&
                deal.oficio_acuerdo_laboral !== '' &&
                (deal.fecha_inicio_fecha_fin_acuerdo_laboral !== '' && deal.fecha_inicio_fecha_fin_acuerdo_laboral !== null) &&
                deal.comentario_acuerdo_laboral !== ''
            );
        },

        resetForm() {
            this.dataForm.deal.indexDeal = ''
            //this.dataForm.deal.id_acuerdo_laboral = ''
            this.dataForm.deal.id_tipo_acuerdo_laboral = ''
            this.dataForm.deal.fecha_acuerdo_laboral = ''
            this.dataForm.deal.fecha_inicio_fecha_fin_acuerdo_laboral = ''
            this.dataForm.deal.oficio_acuerdo_laboral = ''
            this.dataForm.deal.comentario_acuerdo_laboral = ''
            this.dataForm.deal.isEditingDeal = false

        },
        generateShortUniqueId() {
            // Longitud deseada del ID corto único (cambiado a 4)
            const length = 4;
            // Caracteres que se utilizarán para generar el ID
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let id = '';

            // Generar un ID corto único concatenando caracteres aleatorios hasta alcanzar la longitud deseada
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                id += characters[randomIndex];
            }

            // Agregar la marca de tiempo para asegurarse de que sea único en cada generación
            id += Date.now();

            // Puedes usar este ID único en tu lógica de negocio o donde lo necesites
            console.log('ID único generado:', id);

            return id;
        },

        createNewDeal() {
            this.errorForm = false
            if (this.isDataValid()) {
                const oficioExists = this.dataDeals.some((deal) => deal.oficio_acuerdo_laboral === this.dataForm.deal.oficio_acuerdo_laboral);
                // OficioExists verifica que no exista duplicidad en los acuerdo
                // this.dataForm.deal.isEditingDeal es una variable que ve el estado del formularo (esta editando o no)
                if (!oficioExists || this.dataForm.deal.isEditingDeal) {
                    this.errorForm = false;//set false (0 errors)

                    const isEditingDeal = this.dataForm.deal.indexDeal !== '' || this.dataForm.deal.indexDeal === 0;
                    // Función auxiliar para actualizar o agregar un acuerdo
                    const updateOrCreateDeal = () => {
                        if (isEditingDeal) {
                            // Editar
                            const indexCopy = this.copyDataDeals.findIndex((deal) => deal.indexDeal === this.dataForm.deal.indexDeal);
                            const indexNew = this.newDataDeals.findIndex((deal) => deal.indexDeal === this.dataForm.deal.indexDeal);
                            const indexShow = this.dataDeals.findIndex((deal) => deal.indexDeal === this.dataForm.deal.indexDeal);


                            const foundDealCoyp = this.copyDataDeals[indexCopy];
                            const foundDealShow = this.dataDeals[indexShow];
                            const foundDealNew = this.dataDeals[indexNew];


                            Object.assign(foundDealNew, this.dataForm.deal);//COMBINANDO EL FORM CON LA DATA QUE SE MUESTRA
                            if (foundDealCoyp) {
                                Object.assign(foundDealCoyp, this.dataForm.deal); // COMBINANDO EL FORMULARIO CON LA COPIA

                                foundDealCoyp.isDelete = false;
                                foundDealCoyp.isEditingDeal = false;
                            }
                            Object.assign(foundDealShow, this.dataForm.deal);//COMBINANDO EL FORM CON LA DATA QUE SE MUESTRA

                            foundDealShow.isDelete = false;
                            foundDealShow.isEditingDeal = false;
                            foundDealNew.isDelete = false;
                            foundDealNew.isEditingDeal = false;
                            this.getUniqueYears()

                        } else {
                            // Crear
                            const newDeal = { ...this.dataForm.deal };
                            /* newDeal.indexDeal = this.dataDeals.length + 1;
                            this.dataDeals.unshift(newDeal); */
                            newDeal.indexDeal = this.generateShortUniqueId();// FIXME:  CUANDO AGREGAR UN REGISTRO DIFERENTE AL AÑO ACTUAL NO LO AGREGAR A DATAdEALS
                            console.log(newDeal);
                            this.dataDeals.unshift(newDeal);
                            this.newDataDeals.unshift(newDeal);

                            this.getUniqueYears()
                            this.year = moment(newDeal.fecha_acuerdo_laboral).year()

                        }
                    };

                    updateOrCreateDeal();
                    //this.getUniqueYears()
                    this.filterDealsByYear()
                    this.resetForm(); // Restablecer los campos del formulario después de guardar/actualizar correctamente

                    console.log(this.newDataDeals);
                    console.log(this.dataDeals);
                } else {
                    this.errorForm = true;
                    this.messageError = 'El numero de referencia ya existe por favor intenta otro'
                    this.showErrorAnimation();
                }
            } else {
                this.errorForm = true;
                this.messageError = 'Todos los campos son requeridos de este formulario'
                this.showErrorAnimation();
            }

        },

        sendInformationWhenIsClicked(data) {
            // Verificar si algún acuerdo ya está en estado de edición
            const isAnyDealEditing = this.dataDeals.some((deal) => deal.isEditingDeal);
            // Si algún acuerdo ya está en edición, no se ejecuta la función
            if (isAnyDealEditing) {
                //alert("ya estas editando")
                return;
            }
            this.dataForm.deal.indexDeal = data.indexDeal;
            this.dataForm.deal.id_tipo_acuerdo_laboral = data.id_tipo_acuerdo_laboral;
            this.dataForm.deal.nombre_tipo_acuerdo_laboral = data.nombre_tipo_acuerdo_laboral;
            this.dataForm.deal.id_acuerdo_laboral = data.id_acuerdo_laboral;
            this.dataForm.deal.fecha_acuerdo_laboral = data.fecha_acuerdo_laboral;
            this.dataForm.deal.oficio_acuerdo_laboral = data.oficio_acuerdo_laboral;
            this.dataForm.deal.fecha_inicio_fecha_fin_acuerdo_laboral = data.fecha_inicio_fecha_fin_acuerdo_laboral;
            this.dataForm.deal.comentario_acuerdo_laboral = data.comentario_acuerdo_laboral;
            this.dataForm.deal.isEditingDeal = true;

            const index = this.dataDeals.findIndex((deal) => deal.indexDeal === this.dataForm.deal.indexDeal);
            const foundDeal = this.dataDeals[index];
            foundDeal.isEditingDeal = true

        },

        deleteDealWhenIsClicked(index) {
            this.$swal.fire({
                title: '<p class="text-[12pt] text-center">¿Está seguro de eliminar el contrato?. Todos los cambios que haga no se veran reflejados hasta que envie toda la informacion<p>',
                icon: 'question',
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: 'Si, Eliminar',
                confirmButtonColor: '#D2691E',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Modificar el objeto para que la fila desaparezca de la tabla (valor en posición 7 se establece como false)
                    /*   this.dataDeals[index]["isDelete"] = true;
                      this.dataDeals[index]["isDelete"] = true;
                      this.dataDeals[index]["isDelete"] = true; */

                    const indexCopy = this.copyDataDeals.findIndex((deal) => deal.indexDeal === index);
                    const indexNew = this.newDataDeals.findIndex((deal) => deal.indexDeal === index);
                    const indexShow = this.dataDeals.findIndex((deal) => deal.indexDeal === index);


                    const foundDealCoyp = this.copyDataDeals[indexCopy];
                    const foundDealShow = this.dataDeals[indexShow];
                    const foundDealNew = this.dataDeals[indexNew];

                    if (foundDealCoyp) {

                        foundDealCoyp.isDelete = true;
                    }

                    if (foundDealShow) {
                        foundDealShow.isDelete = true;
                    }



                    if (foundDealNew) {
                        foundDealNew.isDelete = true;
                    }



                    this.getUniqueYears()
                    this.filterDealsByYear()
                    // Mostrar mensaje de advertencia
                    toast.warning("El acuerdo se elimino temporalmente hasta que envias la informacion", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            });
        },

        createDealsRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/add-deals', { id_empleado: this.dataForm.id_empleado, deals: this.dataDeals });
                    this.$emit("actualizar-table-data");
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa

                } catch (error) {
                    console.log(error);
                    reject(error); // Rechazamos la promesa en caso de excepción
                }
            });
        },


        async addDeals() {

            this.$swal.fire({
                title: '<p class="text-[20pt] text-center">¿Esta seguro de guardar los datos?</p>',
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
                        this.createDealsRequest(),
                        '¡Los datos se han ingresado correctamente!'
                    )

                }
            })

        },

        updateDealsRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/update-deals', { id_empleado: this.dataForm.id_empleado, deals: [...this.copyDataDeals, ...this.newDataDeals] });
                    this.$emit("actualizar-table-data");
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa

                } catch (error) {
                    console.log(error);
                    reject(error); // Rechazamos la promesa en caso de excepción
                }
            });
        },


        async updateDeals() {

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
                        this.updateDealsRequest(),
                        '¡Los datos se han modificado correctamente!'
                    )

                }
            })

        },


        async handleSearch(query) {
            if (query !== '') {
                try {
                    if (query.by === 'name' && query.query.length >= 5) {
                        this.isLoadingToSearch = true
                        const response = await axios.post('/search-employe', query);
                        this.employeOptions = response.data;
                    } else if (query.by === 'id') {
                        this.loading = true;
                        const response = await axios.post('/search-employe', query);
                        this.employeOptions = response.data
                        this.dataForm.id_empleado = this.employeOptions[0].value
                    }
                } catch (error) {
                    console.log('Error en la búsqueda:', error)
                } finally {
                    this.isLoadingToSearch = false
                    this.loading = false
                }
            }
        },

        showErrorAnimation() {
            // Activa la animación estableciendo shouldShowErrorAnimation en true
            this.shouldShowErrorAnimation = true;
            // Establece un temporizador para desactivar la animación después de un breve período de tiempo (por ejemplo, 1 segundo)
            setTimeout(() => {
                this.shouldShowErrorAnimation = false;
            }, 500); // 1000 milisegundos = 1 segundo (ajústalo según la duración de tu animación)
        },
        cancelEdition(i) {
            this.resetForm();
            const index = this.dataDeals.findIndex((deal) => deal.indexDeal === i);
            const foundDeal = this.dataDeals[index];
            foundDeal.isDelete = false;
            foundDeal.isEditingDeal = false;
        },

        daysOfMonth(yearin) {
            const kabisa = (yearin) => (yearin % 4 === 0 && yearin % 100 !== 0 && yearin % 400 !== 0) || (yearin % 100 === 0 && yearin % 400 === 0);
            const fevral = (yearin) => (kabisa(yearin) ? 29 : 28);
            return [31, fevral(this.year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        },

        dayGenerater() {
            const days = [];
            for (let k = 0; k < this.daysOfMonth().length; k++) {
                days.push([]);
                for (let i = 1; i <= this.daysOfMonth()[k]; i++) {
                    days[k].push(i);
                }
            }
            return days;
        },
        getCurrentYear() {
            const today = new Date()
            this.year = today.getFullYear()
        },

        transFormDat(dia, mes, año) {
            const fechaProporcionada = `${año}-${mes}-${dia}`
            //console.log(moment(fechaProporcionada).format('dddd, MMMM Do YYYY'));
            return moment(fechaProporcionada).format('dddd, MMMM D, YYYY')
        },
        getUniqueYears() {
            if (this.dataDeals != "") {
                const uniqueYearsSet = new Set();

                const dealFilterByYearAndNewDeals = [
                    ...this.copyDataDeals.filter((i) => !i.isDelete),
                    ...this.newDataDeals.filter((i) => !i.isDelete)
                ];

                // Iteramos sobre el arreglo dataDeals y agregamos los años al conjunto
                dealFilterByYearAndNewDeals.forEach((obj, index) => {
                    // Extraemos el año de la fecha_acuerdo_laboral y lo agregamos al conjunto
                    if (obj.fecha_acuerdo_laboral) {
                        const year = moment(obj.fecha_acuerdo_laboral).year();
                        uniqueYearsSet.add(year);
                    }
                });
                // Convertimos el conjunto a un nuevo arreglo y lo ordenamos
                const uniqueYearsArray = Array.from(uniqueYearsSet).sort();
                console.log({ uniqueYearsArray });
                this.listYears = uniqueYearsArray
            }
        },
        filterDealsByYear() {
            if (this.year !== '') {
                const dealFilterByYearAndNewDeals = [
                    ...this.newDataDeals.filter((i) => moment(i.fecha_acuerdo_laboral).year() == this.year),
                    ...this.copyDataDeals.filter((i) => moment(i.fecha_acuerdo_laboral).year() == this.year)
                ];
                this.dataDeals = dealFilterByYearAndNewDeals;
            } else {
                const dealFilterByAllYears = [
                    ...this.newDataDeals,
                    ...this.copyDataDeals
                ];
                this.dataDeals = dealFilterByAllYears;
            }
        },

    },

    created() {
        // Al cargar el componente, establecer el año actual

    },
    watch: {
        showModal() {
            this.getCurrentYear();
            if (this.showModal) {
                if (this.dataAcuerdos != '') {
                    this.handleSearch({ by: 'id', query: this.dataAcuerdos.id_empleado })
                    this.dataAcuerdos.acuerdo_laboral.forEach((obj, index) => {
                        //this.dataDeals.push({
                        this.copyDataDeals.push({
                            indexDeal: this.generateShortUniqueId(),
                            id_acuerdo_laboral: obj.id_acuerdo_laboral,
                            id_tipo_acuerdo_laboral: obj.tipo_acuerdo_laboral["id_tipo_acuerdo_laboral"],
                            nombre_tipo_acuerdo_laboral: obj.tipo_acuerdo_laboral["nombre_tipo_acuerdo_laboral"],
                            fecha_acuerdo_laboral: obj.fecha_acuerdo_laboral,
                            oficio_acuerdo_laboral: obj.oficio_acuerdo_laboral,
                            comentario_acuerdo_laboral: obj.comentario_acuerdo_laboral,
                            fecha_inicio_fecha_fin_acuerdo_laboral: `${obj.fecha_inicio_acuerdo_laboral} to ${obj.fecha_fin_acuerdo_laboral}`,
                            isDelete: false,
                            isEditingDeal: false,
                        })
                    })
                    this.filterDealsByYear()
                    this.getUniqueYears()

                } else {

                }
            } else {

                setTimeout(() => {
                    this.dataDeals = []//vaciando medio segundo despues que cierre el modal
                    this.copyDataDeals = []//vaciando medio segundo despues que cierre el modal
                    this.newDataDeals = []//vaciando medio segundo despues que cierre el modal
                }, 500);
                this.dataForm.id_empleado = ''
                this.employeOptions = []
                this.listYears = []
                //reset form cuando cierre
            }
        }
    }
}
</script>


<style>
/* Estilos del scrollbar para navegadores WebKit (Chrome y Safari) */
/* Cambia los colores y dimensiones según tu preferencia */
div::-webkit-scrollbar {
    width: 3px;
}

div::-webkit-scrollbar-thumb {
    background-color: #6B7280;
    border-radius: 4px;
}

div::-webkit-scrollbar-thumb:hover {
    background-color: #4B5563;
}
</style>