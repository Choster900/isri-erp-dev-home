<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModal" @close="$emit('cerrar-modal')" modal-title="Designación de Beneficiario" maxWidth="xl">
            <div class="px-5 py-4">
                <div class="mb-4  md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <label class="text-xs text-gray-500 font-normal">Issued in</label>
                        <p class="text-xs text-black font-semibold">Jul 23,2023</p>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <label class="text-sm text-gray-500 font-normal">Due in</label>
                        <p class="text-xs text-black font-semibold">Aug 23,2023</p>
                    </div>
                </div>
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <label class="text-xs text-gray-500 font-normal">Bill From</label>
                        <div class="mt-1">
                            <p class="text-xs text-black font-semibold pt-">Alex Parkinson</p>
                            <p class="text-xs text-gray-500 font-normal">3897 Hickory St, Sait Lake City Utah, United States
                                84104</p>
                        </div>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                        <label class="text-xs text-gray-500 font-normal">Bill To</label>
                        <div class="mt-1">
                            <p class="text-xs text-black font-semibold"> Musemind Digital Agency</p>
                            <p class="text-xs text-gray-500 font-normal">421 Losly River Suite 478
                                Syaney, Austata
                            </p>
                        </div>
                    </div>

                </div>
                <div class=" mt-10 md:flex flex-row justify-items-start">
                    <div class="mb-2 md:mr-2 md:mb-0 basis-full">
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Empleado/a <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-8  flex-row-reverse">
                                    <!--  <Multiselect :allow-absent="true" :searchable="true" :resolve-on-load="false" :delay="0"
                                        :min-chars="3" :options="async (query) => {
                                            return await fetchLanguages(query)
                                        }" v-model="dataSent.id_persona"Ñ
                                        noOptionsText="<p class='text-xs'>sin requerimientos<p>"
                                        noResultsText="<p class='text-xs'>requerimiento no encontrado<p>"
                                        placeholder="Busqueda por nombre" />
                                    <LabelToInput icon="list" /> -->

                                    <Multiselect :allow-absent="true"  :searchable="true"  :resolve-on-load="false" :delay="0"
                                        :min-chars="3" :options="personaOptions" v-model="dataSent.id_persona"
                                        noOptionsText="<p class='text-xs'>sin requerimientos<p>"
                                        noResultsText="<p class='text-xs'>requerimiento no encontrado<p>"
                                        placeholder="Busqueda por nombre" />
                                    <LabelToInput icon="list" />


                                    <!--     <multiselect v-model="selectedOptions" :options="options" label="name" track-by="id"
                                        :multiple="true" :loading="loading" @search="handleSearch"></multiselect> -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="md:flex flex-row justify-items-start">
                    <a @click="addRow" :href="`#familiar-${(dataSent.dataRow.length - 1)}`"
                        class="bg-blue-500 text-white  font-bold uppercase text-xs px-2 py-1 rounded-full shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                        type="button">
                        <div class="flex items- gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                            </svg>
                            <span>Agregar familiar</span>
                        </div>
                    </a>
                </div>

                <template v-for="( row, rowIndex ) in   dataSent.dataRow  " :key="rowIndex">
                    <div :id="`familiar-${rowIndex}`" class=" p-2 rounded-md bg-gray-200 mb-3 relative border "
                        v-if="!row.isDelete" :class="row.onEdit ? 'animate-shake' : ''">
                        <div class=" md:flex flex-row justify-items-start">
                            <div class=" md:mr-2 md:mb-0 basis-1/12 ">
                                <svg viewBox="0 0 50 50" fill="#000000" class="w-12 h-12">
                                    <g>
                                        <g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M12.2,29.5c0.6,0.7,1,1.5,1,2.5c0,2-1.7,3.6-3.7,3.6c-0.4,0-0.8-0.1-1.2-0.2 c0.7,0.7,1.6,1.2,2.6,1.2c2,0,3.6-1.6,3.7-3.6C14.6,31.4,13.6,30,12.2,29.5z"
                                                        style="fill:#ffffff;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M10.5,36.7C10.5,36.7,10.5,36.7,10.5,36.7c-1.2,0-2.2-0.5-3-1.3c-0.8-0.8-1.2-1.9-1.2-3 c0-1.1,0.5-2.2,1.3-3c0.8-0.8,1.8-1.2,3-1.2c0,0,0,0,0,0c2.3,0,4.2,1.9,4.2,4.3l0,0C14.7,34.8,12.8,36.7,10.5,36.7z M10.5,29.4 c-0.8,0-1.5,0.3-2.1,0.9c-0.6,0.6-0.9,1.3-0.9,2.1c0,0.8,0.3,1.6,0.9,2.1c0.6,0.6,1.3,0.9,2.1,0.9c0,0,0,0,0,0c1.7,0,3-1.3,3-3 C13.5,30.8,12.2,29.4,10.5,29.4C10.5,29.4,10.5,29.4,10.5,29.4z"
                                                        style="fill:#0D5FC3;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M15.7,43.4l0-1.2c0-2-1.2-3.7-2.9-4.4c0.9,0.9,1.4,2.1,1.4,3.4l0,2.1"
                                                        style="fill:#ffffff;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M15.8,43h-1.2v-1.2c0-1.1-0.4-2.1-1.2-2.9s-1.8-1.2-2.9-1.2c0,0,0,0,0,0c-2.2,0-4.1,1.8-4.1,4.1 V43H5.2v-1.3c0-2.9,2.4-5.3,5.3-5.3c0,0,0,0,0.1,0c2.9,0,5.3,2.4,5.2,5.4V43z"
                                                        style="fill:#0D5FC3;"></path>
                                                </g>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M40.8,29.5c0.6,0.7,1,1.5,1,2.5c0,2-1.7,3.6-3.7,3.6c-0.4,0-0.8-0.1-1.2-0.2 c0.7,0.7,1.6,1.2,2.6,1.2c2,0,3.6-1.6,3.7-3.6C43.2,31.4,42.2,30,40.8,29.5z"
                                                        style="fill:#ffffff;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M39.1,36.7C39,36.7,39,36.7,39.1,36.7c-1.2,0-2.2-0.5-3-1.3c-0.8-0.8-1.2-1.9-1.2-3 c0-1.1,0.5-2.2,1.3-3c0.8-0.8,1.8-1.2,3-1.2c0,0,0,0,0,0c2.3,0,4.2,1.9,4.2,4.3l0,0C43.3,34.8,41.4,36.7,39.1,36.7z M39,29.4 c-0.8,0-1.5,0.3-2.1,0.9c-0.6,0.6-0.9,1.3-0.9,2.1c0,0.8,0.3,1.6,0.9,2.1c0.6,0.6,1.3,0.9,2.1,0.9c0,0,0,0,0,0c1.7,0,3-1.3,3-3 C42.1,30.8,40.8,29.4,39,29.4C39.1,29.4,39.1,29.4,39,29.4z"
                                                        style="fill:#0D5FC3;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M44.2,43.4l0-1.2c0-2-1.2-3.7-2.9-4.4c0.9,0.9,1.4,2.1,1.4,3.4l0,2.1"
                                                        style="fill:#ffffff;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M44.3,43h-1.2v-1.2c0-1.1-0.4-2.1-1.2-2.9s-1.8-1.2-2.9-1.2c0,0,0,0,0,0c-2.2,0-4.1,1.8-4.1,4.1 V43h-1.2v-1.3c0-2.9,2.4-5.3,5.3-5.3c0,0,0,0,0.1,0c2.9,0,5.3,2.4,5.2,5.4V43z"
                                                        style="fill:#0D5FC3;"></path>
                                                </g>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M26.2,7.3c0.6,0.7,1,1.5,1,2.5c0,2-1.7,3.6-3.7,3.6c-0.4,0-0.8-0.1-1.2-0.2 c0.7,0.7,1.6,1.2,2.6,1.2c2,0,3.6-1.6,3.7-3.6C28.6,9.1,27.6,7.8,26.2,7.3z"
                                                        style="fill:#ffffff;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M24.5,14.4C24.5,14.4,24.5,14.4,24.5,14.4c-1.2,0-2.2-0.5-3-1.3s-1.2-1.9-1.2-3 c0-1.1,0.5-2.2,1.3-3c0.8-0.8,1.8-1.2,3-1.2c0,0,0,0,0,0c1.1,0,2.2,0.5,3,1.3c0.8,0.8,1.2,1.9,1.2,3l0,0c0,1.1-0.5,2.2-1.3,3 C26.7,14,25.6,14.4,24.5,14.4z M24.5,7.2c-0.8,0-1.5,0.3-2.1,0.9c-0.6,0.6-0.9,1.3-0.9,2.1c0,1.7,1.3,3,3,3.1 c0.8,0,1.6-0.3,2.1-0.9c0.6-0.6,0.9-1.3,0.9-2.1c0-0.8-0.3-1.6-0.9-2.1C26.1,7.5,25.4,7.2,24.5,7.2C24.5,7.2,24.5,7.2,24.5,7.2z "
                                                        style="fill:#0D5FC3;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M29.7,21.2l0-1.2c0-2-1.2-3.7-2.9-4.4c0.9,0.9,1.4,2.1,1.4,3.4l0,2.1"
                                                        style="fill:#ffffff;"></path>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M28.6,20.7l0-1.2c0-1.1-0.4-2.1-1.2-2.9c-0.8-0.8-1.8-1.2-2.9-1.2c-1.1,0-2.1,0.4-2.9,1.2 s-1.2,1.8-1.2,2.9v1.3h-1.2v-1.3c0-1.4,0.6-2.8,1.6-3.7c1-1,2.3-1.5,3.7-1.5c0,0,0,0,0.1,0c1.4,0,2.7,0.6,3.7,1.6 c1,1,1.5,2.4,1.5,3.8l0,1.2L28.6,20.7z"
                                                        style="fill:#0D5FC3;"></path>
                                                </g>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <g>
                                                    <rect height="7.6" style="fill:#0D5FC3;" width="1.2" x="24.2" y="22.9">
                                                    </rect>
                                                </g>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <g>
                                                    <rect height="7.6" style="fill:#0D5FC3;"
                                                        transform="matrix(0.4998 0.8661 -0.8661 0.4998 39.9488 -0.3482)"
                                                        width="1.2" x="19.7" y="30.6"></rect>
                                                </g>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <g>
                                                    <rect height="1.2" style="fill:#0D5FC3;"
                                                        transform="matrix(0.866 0.5 -0.5 0.866 21.1219 -10.0016)"
                                                        width="7.6" x="25.4" y="33.8"></rect>
                                                </g>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M24.8,33.8c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S25.8,33.8,24.8,33.8z M24.8,31.1 c-0.4,0-0.8,0.3-0.8,0.8s0.3,0.8,0.8,0.8s0.8-0.3,0.8-0.8S25.2,31.1,24.8,31.1z"
                                                    style="fill:#0D5FC3;"></path>
                                            </g>
                                        </g>
                                    </g>

                                </svg>
                            </div>
                            <div class="md:mr-2 md:mb-0 basis-1/2">
                                <h5 class="text-sm font-medium" :class="row.nombre_familiar == '' ? 'py-2' : ''"
                                    v-if="!row.onEdit">{{
                                        row.nombre_familiar }} - {{ row.nombre_parentesco }}</h5>

                                <div v-else class="flex items-center gap-1">
                                    <input v-model="row.nombre_familiar" type="text" :class="row.onEdit ? '' : ''"
                                        class=" rounded  w-56 h-7 text-sm font-medium ">

                                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                        <div class="relative w-40 flex h-7  flex-row-reverse">
                                            <Multiselect :options="optionsParentesco" v-model="row.id_parentesco"
                                                @select="(selected) => prueba(selected, rowIndex)" :searchable="true"
                                                noOptionsText="<p class='text-xs'>sin requerimientos<p>" :classes="{
                                                    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                }" noResultsText="<p class='text-xs'>requerimiento no encontrado<p>"
                                                placeholder="" />
                                        </div>
                                    </div>
                                </div>
                                <h5 class="text-sm ">0 al 100 %</h5>
                                <div class="flex items-center" style="width: 445px;">
                                    <input @input="calcularDesignacionDePorcentajes(rowIndex)"
                                        v-model="row.porcentaje_familiar"
                                        class="cursor-col-resize rounded-lg overflow-hidden appearance-none bg-gray-400 h-3 w-full"
                                        type="range" min="0" max="100" step="1" />
                                    <span class="text-sm font-medium ml-2">{{ row.porcentaje_familiar }}%</span>
                                </div>
                                <div class="flex gap-1 pt-1" v-if="row.onEdit">
                                    <button class="text-[10px] bg-red-500 rounded-md py-0.5 px-2 font-medium text-white"
                                        @click="row.onEdit = false">Hecho</button>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-0 right-0 mt-2 mr-2">
                            <DropDownOptions v-if="!row.onEdit">
                                <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                    <div class="font-semibold text-xs text-green-700 w-full" @click="row.onEdit = true">
                                        Modificar</div>
                                </div>
                                <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                    <div class="font-semibold text-xs text-red-700 w-full" @click="deleteRow(rowIndex)">
                                        Eliminar
                                    </div>
                                </div>
                            </DropDownOptions>
                        </div>
                    </div>
                </template>

                <div class="sticky bottom-0 px-5 pt-2 bg-white border-t-2 border-slate-200">
                    <div class="flex flex-wrap justify-end space-x-3">

                        <button
                            class="btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                            @click.stop="$emit('close-modal')">Cerrar</button>

                        <GeneralButton @click="agregarFamiliaresADB()" color="bg-green-700  hover:bg-green-800"
                            text="agregar" icon="add" />
                    </div>
                </div>
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
        dataBeneficiarios: {
            type: Object,
            default: [],
        },
    },
    data: () => ({
        personaOptions:[],
        optionsParentesco: [
            { value: '1', label: 'MADRE', unico_parentesco: 1 },
            { value: '2', label: 'PADRE', unico_parentesco: 1 },
            { value: '3', label: 'ABUELO/A', unico_parentesco: 0 },
            { value: '4', label: 'BISABUELO/A', unico_parentesco: 0 },
            { value: '5', label: 'HERMANO/A', unico_parentesco: 0 },
            { value: '6', label: 'CONYUGE', unico_parentesco: 0 },
            { value: '7', label: 'HIJO/A', unico_parentesco: 0 },
            { value: '8', label: 'NIETO/A', unico_parentesco: 0 },
            { value: '9', label: 'BISNIETO/A', unico_parentesco: 0 },
            { value: '10', label: 'TIO/A', unico_parentesco: 0 },
            { value: '11', label: 'PRIMO/A', unico_parentesco: 0 },
            { value: '12', label: 'SOBRINO/A', unico_parentesco: 0 },
            { value: '13', label: 'SUEGRO/A', unico_parentesco: 0 },
            { value: '14', label: 'NUERA', unico_parentesco: 0 },
            { value: '15', label: 'YERNO', unico_parentesco: 0 },
            { value: '16', label: 'CUÑADO/A', unico_parentesco: 0 },
            { value: '17', label: 'PADRASTRO', unico_parentesco: 0 },
            { value: '18', label: 'MADASTRA', unico_parentesco: 0 },
            { value: '19', label: 'HIJASTRO/A', unico_parentesco: 0 },
            { value: '20', label: 'AMIGO/A', unico_parentesco: 0 },
        ],
        dataSent: {
            id_familiar: '',
            //   id_parentesco: '',
            id_persona: '',
            dataRow: [],
        },
        selectedOptions: [],
        options: [],
        loading: false,

    }),
    methods: {
        async handleSearch(searchTerm) {
            this.loading = true;
            // Realizar la llamada a la API o servicio
            //  const response = await fetch(`URL_DE_TU_API?search=${searchTerm}`);
            const response = await axios.get('/search-people-by-name/' + searchTerm);
            const data = await response.data;
            // Actualizar las opciones con los resultados de la búsqueda
            this.options = data;
            this.loading = false;
        },
        async agregarFamiliaresADB() {
            this.$swal.fire({
                title: '¿Esta seguro de guardar los datos?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Guardar',
                confirmButtonColor: '#001b47',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/add-familiares', this.dataSent).then((response) => {
                        toast.success("El registro se ha agregado correctamente", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                        console.log(response);
                    }).catch((Error) => {
                        console.log(Error);

                    });
                }
            })
        },
        async fetchLanguages(query) {
            try {
                // Realizar una llamada asíncrona, por ejemplo a una API
                const respuesta = await axios.get('/search-people-by-name/' + query);

                // Procesar la respuesta o realizar otras operaciones necesarias
                console.log(respuesta)
                // Retornar el resultado deseado
                return respuesta.data;
            } catch (error) {
                // Manejar el error en caso de que ocurra
                console.error(error);
                throw error;
            }
        },
        prueba(value, row) {
            let parentesco = JSON.parse(JSON.stringify(this.optionsParentesco.find((index) => index.value == value)));
            this.dataSent.dataRow[row].nombre_parentesco = parentesco.label
        },
        deleteRow(indexRow) {
            alert(indexRow)
            this.dataSent.dataRow[indexRow].isDelete = true;
        },
        addRow() {
            this.dataSent.dataRow.push({ nombre_familiar: '', id_parentesco: '', nombre_parentesco: '', beneficiado_familiar: 1, porcentaje_familiar: 0, estado_familiar: '', onEdit: true, isDelete: false },)
        },
        calcularDesignacionDePorcentajes(rowIndex) {
            // Calcular la suma total de los porcentajes
            const totalPorcentajeAsignado = this.dataSent.dataRow.reduce((suma, obj) => suma + parseFloat(obj.porcentaje_familiar), 0);
            if (totalPorcentajeAsignado > 100) {
                // Calcular el excedente
                const ajuste = totalPorcentajeAsignado - 100;

                // Ajustar los porcentajes de las filas diferentes a la actual
                this.dataSent.dataRow.forEach((obj, index) => {
                    if (index !== rowIndex) {
                        if (obj.porcentaje_familiar >= ajuste) {
                            // Restar el excedente al porcentaje
                            obj.porcentaje_familiar -= ajuste;
                        } else {
                            // Establecer el porcentaje en 0 si es menor que el excedente
                            obj.porcentaje_familiar = 0;
                        }
                    }
                });
            }
        },
    },
    watch: {
        showModal: function (value, oldValue) {
            if (this.showModal) {
                if (this.dataBeneficiarios != '') {
                    let newDataBeneficiarios = JSON.parse(JSON.stringify(this.dataBeneficiarios));
                    console.log(newDataBeneficiarios);
                    newDataBeneficiarios.familiar.forEach((obj, index) => {
                        this.dataSent.dataRow.push({
                            nombre_familiar: obj.nombre_familiar,
                            id_parentesco: obj.parentesco.id_parentesco,
                            nombre_parentesco: obj.parentesco.nombre_parentesco,
                            beneficiado_familiar: 1,
                            porcentaje_familiar: obj.porcentaje_familiar,
                            estado_familiar: 1,
                            onEdit: false,
                            isDelete: false
                        },)
                    });
                } else {
                    this.addRow()
                }
            } else {
                this.dataSent.dataRow = []
            }

        },
    },
};
</script>
<style>
@media screen and (-webkit-min-device-pixel-ratio: 0) {

    input[type="range"]::-webkit-slider-thumb {
        width: 15px;
        -webkit-appearance: none;
        appearance: none;
        height: 15px;
        background: #FFF;
        box-shadow: -405px 0 0 400px #001c48;
        border-radius: 50%;

    }
}
</style>
