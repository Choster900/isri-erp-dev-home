<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import moment from 'moment';

</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModal" @close="$emit('cerrar-modal')" modal-title="Designación de Beneficiario" maxWidth="xl">

            <div class='flex items-center justify-center h-[440px]' v-if="loading">
                <div style="border-top-color:transparent"
                    class="w-8 h-8 border-4 border-blue-200 rounded-full animate-spin"></div>
                <p class="ml-2">cargando...</p>
            </div>

            <div class="px-5 py-4" v-else :class="dataBeneficiarios != '' ? 'animate-fade-up' : ''">
                <div class="mb-4  md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <label class="text-xs text-gray-500 font-normal uppercase">Fecha de creacion</label>
                        <p class="text-xs text-black font-semibold">{{
                            dataToShow.createdAt === null || dataToShow.createdAt == '' ?
                            '00/00/0000' :
                            moment(dataToShow.createdAt).calendar()

                        }}</p>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3 uppercase">
                        <label class="text-xs text-gray-500 font-normal">Ultima moficicacion</label>
                        <p class="text-xs text-black font-semibold">
                            {{
                                dataToShow.updatedAt === null || dataToShow.updatedAt == '' ?
                                'Nunca modificado' :
                                moment(dataToShow.updatedAt).fromNow()
                            }}
                        </p>
                    </div>
                </div>
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <label class="text-xs text-gray-500 font-normal">{{
                            dataToShow.gender === null || dataToShow.gender == '' ?
                            'GENERO' :
                            dataToShow.gender
                        }}</label>
                        <div class="mt-1">
                            <p class="text-xs text-black font-semibold pt-">
                                {{
                                    dataToShow.name === null || dataToShow.name == '' ?
                                    'NOMBRE DEL EMPLEADO' :
                                    dataToShow.name
                                }}</p>
                            <p class="text-xs text-gray-500 font-normal">{{

                                dataToShow.bornPlace === null || dataToShow.bornPlace == '' ?
                                'LUGAR DE NACIMIENTO ' :
                                dataToShow.bornPlace


                            }}</p>
                        </div>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                        <label class="text-xs text-gray-500 font-normal">{{
                            dataToShow.civilStatus === null || dataToShow.civilStatus == '' ?
                            'ESTADO CIVIL' :
                            dataToShow.civilStatus


                        }}</label>
                        <div class="mt-1">
                            <p class="text-xs text-black font-semibold"> {{
                                dataToShow.levelEducation === null || dataToShow.levelEducation == '' ?
                                'NIVEL DE EDUCACION' :
                                dataToShow.levelEducation }}</p>
                            <p class="text-xs text-gray-500 font-normal">{{
                                dataToShow.profesion === null || dataToShow.profesion == '' ?
                                'NOMBRE DE LA PROFESION' :
                                dataToShow.profesion
                            }}</p>
                        </div>
                    </div>

                </div>
                <div class=" mt-10 md:flex flex-row justify-items-start">
                    <div class=" md:mr-2 md:mb-0 basis-full">
                        <div class="mb-2 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Empleado/a <span class="text-red-600 font-extrabold">*</span>
                                </label>


                                <div class="relative font-medium  flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="dataSent.id_persona"
                                        @search-change="handleSearch({ by: 'name', query: $event })" :clearOnSearch="true"
                                        @select="personaWasSelected($event)" @deselect=" deleteInfoBeneficiarios(false)"
                                        @clear=" deleteInfoBeneficiarios(false)"
                                        placeholder="FILTRAR POR APELLIDO (SI NO APARECE ES POSIBLE QUE YA TENGA REGISTROS)"
                                        :filter-results="false" :min-chars="10" :resolve-on-load="true" :searchable="true"
                                        :options="personaOptions" noOptionsText="<p class='text-xs'>Sin registros<p>"
                                        :classes="{
                                            placeholder: 'flex items-center h-full absolute text-[8pt] left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                        }" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_persona" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="md:flex flex-row justify-items-start mb-1">
                    <a @click="addRow" :href="`#familiar-${(dataSent.dataRow.length - 1)}`"
                        title="Agregar una nueva fila para agregar un nuevo familiar"
                        class="bg-green-600 text-white  font-bold uppercase text-xs px-2 py-1 rounded-full shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                        type="button">
                        <div class="flex items- gap-1">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                            </svg>
                            <span class="pr-1">Agregar un nuevo familiar</span>
                        </div>
                    </a>
                </div>

                <template v-for="(  row, rowIndex  ) in    dataSent.dataRow   " :key="rowIndex">
                    <div :id="`familiar-${rowIndex}`" class=" p-2 rounded-md bg-gray-200 mb-3 relative border "
                        v-if="!row.isDelete" :class="row.onEdit ? 'animate-shake' : ''">
                        <div class=" md:flex flex-row justify-items-start">
                            <div class=" md:mr-2 md:mb-0 basis-1/12 ">
                                <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill="#002352"
                                            d="M628.736 528.896A416 416 0 0 1 928 928H96a415.872 415.872 0 0 1 299.264-399.104L512 704l116.736-175.104zM720 304a208 208 0 1 1-416 0 208 208 0 0 1 416 0z">
                                        </path>
                                    </g>
                                </svg>
                            </div>
                            <div class="md:mr-2 md:mb-0 basis-1/2">
                                <h5 class="text-sm font-medium" :class="row.nombre_familiar == '' ? 'py-2' : ''"
                                    v-if="!row.onEdit">{{
                                        row.nombre_familiar }} - {{ row.nombre_parentesco }}</h5>

                                <div v-else class="flex items-center gap-1">
                                    <div>
                                        <input v-model="row.nombre_familiar" type="text" :class="row.onEdit ? '' : ''"
                                            class="rounded w-52 h-7 text-xs font-medium" placeholder="Nombre del familiar"
                                            @input="row.nombre_familiar = row.nombre_familiar.toUpperCase()">

                                    </div>
                                    <!--                   {{ errosModel.id_parentesco[0] }} -->
                                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                        <div class="relative w-40 flex h-7 flex-row-reverse">
                                            <Multiselect :options="optionsParentesco" v-model="row.id_parentesco"
                                                @select="(selected) => selectParentesco(selected, rowIndex)"
                                                @deselect="row.nombre_parentesco = ''" @clear="row.nombre_parentesco = ''"
                                                :searchable="true" noOptionsText="<p class='text-xs'>sin requerimientos<p>"
                                                :classes="{
                                                    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                }
                                                    " noResultsText="<p class='text-xs'>requerimiento no encontrado<p>"
                                                placeholder="Parentesco" />
                                        </div>


                                    </div>

                                </div>
                                <div class="flex items-center gap-3">

                                    <InputError class="mt-2" :message="errosModel[`dataRow.${rowIndex}.nombre_familiar`]" />

                                    <InputError class="mt-2" :message="errosModel[`dataRow.${rowIndex}.id_parentesco`]" />
                                </div>
                                <h5 class="text-sm ">0 al 100 %</h5>
                                <div class="flex items-center gap-3" style="width: 445px;">
                                    <span class="text-2xl cursor-pointer"
                                        @click="increaseOrDecreaseDesignacionDePorcentajes(rowIndex, 'resta')">-</span>
                                    <input @input="calcularDesignacionDePorcentajes(rowIndex)"
                                        v-model="row.porcentaje_familiar"
                                        class="cursor-grabbing	 rounded-lg overflow-hidden appearance-none bg-gray-400 h-3 w-full"
                                        type="range" min="0" max="100" step="1" />
                                    <span class="text-sm font-medium ml-2">{{ row.porcentaje_familiar }}%</span>
                                    <span class="text-2xl cursor-pointer"
                                        @click="increaseOrDecreaseDesignacionDePorcentajes(rowIndex, 'suma')">+</span>
                                </div>
                                <InputError class="mt-2" :message="errosModel[`dataRow.${rowIndex}.porcentaje_familiar`]" />
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
                                        M O D I F I C A R</div>
                                </div>
                                <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                    <div class="font-semibold text-xs text-red-700 w-full"
                                        @click="deleteFamiliarFromList(rowIndex)">
                                        E L I M I N A R
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
                            @click.stop="$emit('cerrar-modal')">Cerrar</button>

                        <GeneralButton v-if="dataBeneficiarios == ''" @click="addRelatives()"
                            color="bg-green-700  hover:bg-green-800" text="agregar" icon="add" />

                        <GeneralButton v-else @click="updateRelatives()" color="bg-orange-700  hover:bg-orange-800"
                            text="Modificar" icon="add" />

                        <!-- <button @click="displayPromise">display promise</button> -->

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
        personaOptions: [],
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
            id_persona: '',
            dataRow: [],
        },
        selectedOptions: [],
        options: [],
        totalPorcentajeAsignado: 0,
        loading: false,
        isLoadingToSearch: false,
        errosModel: [],
        dataToShow: {
            createdAt: '',
            updatedAt: '',
            name: '',
            bornPlace: '',
            civilStatus: '',
            gender: '',
            levelEducation: '',
            profesion: '',
            dui_persona: '',
            birthDay: '',
        },
    }),
    methods: {
        async handleSearch(query) {
            if (query !== '') {
                try {
                    if (query.by === 'name' && query.query.length >= 5) {
                        this.isLoadingToSearch = true;
                        const response = await axios.post('/search-people', query);
                        this.personaOptions = response.data;
                    } else if (query.by === 'id') {
                        this.loading = true;
                        const response = await axios.post('/search-people', query);
                        this.personaOptions = response.data;
                        this.personaWasSelected(query.query);
                    }
                } catch (error) {
                    console.log('Error en la búsqueda:', error);
                } finally {
                    this.isLoadingToSearch = false;
                    this.loading = false;
                }
            }
        },
        personaWasSelected(id_persona) {
            let persona = JSON.parse(JSON.stringify(this.personaOptions.find((index) => index.value == id_persona)));
            console.log(persona);
            this.dataToShow.createdAt = persona.fecha_reg_persona
            this.dataToShow.updatedAt = persona.fecha_mod_persona
            this.dataToShow.bornPlace = persona.localidad
            this.dataToShow.birthDay = persona.fecha_nac_persona
            this.dataToShow.civilStatus = persona.nombre_estado_civil
            this.dataToShow.gender = persona.nombre_genero
            this.dataToShow.levelEducation = persona.nombre_nivel_educativo
            this.dataToShow.profesion = persona.nombre_profesion_rnpn
            this.dataToShow.dui_persona = persona.dui_persona
            this.dataToShow.name = persona.label
        },

        areAllPercentagesAboveOne() {
            // Traemos los registros que no hallan sido eliminados
            const allData = this.dataSent.dataRow.filter((obj) => obj.isDelete === false)
            console.log(allData);
            for (let i = 0; i < allData.length; i++) {
                const element = allData[i];
                if (element.porcentaje_familiar < 1) {
                    return false; // Al menos uno tiene un porcentaje menor a 1, retornar false
                }
            }
            return true; // Ninguno tiene un porcentaje menor a 1, retornar true
        },

        createRelativesRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/add-relatives', this.dataSent);
                    this.$emit("actualizar-table-data");
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa

                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        let data = error.response.data.errors;
                        var myData = {};
                        for (const nombreErrorBack in data) {
                            myData[nombreErrorBack] = data[nombreErrorBack][0];
                        }
                        this.errosModel = myData;

                        setTimeout(() => {
                            this.errosModel = [];
                        }, 50000);
                    } else {
                        console.log("Error en la solicitud: ", error);
                    }
                    reject(error); // Rechazamos la promesa en caso de excepción
                }
            });
        },
        async addRelatives() {
            if (this.totalPorcentajeAsignado >= 100) {
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
                        if (this.areAllPercentagesAboveOne()) {

                            this.executeRequest(
                                this.createRelativesRequest(),
                                '¡Los datos se han ingresado correctamente!'
                            )
                        } else {
                            toast.warning("El porcentaje debe ser distribuido en cada familiar", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                        }
                    }
                })
            } else {
                toast.warning("La asignación de porcentaje a tus familiares debe sumar el 100%", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }
        },
        updateRelativesRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/update-relatives', this.dataSent);
                    this.$emit("actualizar-table-data");
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa (OBLIGATORIA)
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        let data = error.response.data.errors;
                        var myData = {};
                        for (const nombreErrorBack in data) {
                            myData[nombreErrorBack] = data[nombreErrorBack][0];
                        }
                        this.errosModel = myData;

                        setTimeout(() => {
                            this.errosModel = [];
                        }, 50000);
                    } else {
                        console.log("Error en la solicitud: ", error);
                    }
                    reject(error); // Rechazamos la promesa en caso de excepción (OBLIGATORIO)
                }
            });
        },
        async updateRelatives() {
            if (this.totalPorcentajeAsignado >= 100) {
                this.$swal.fire({
                    title: '<p class="text-[20pt] text-center">¿Esta seguro de modificar los datos?</p>',
                    icon: 'question',
                    iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                    confirmButtonText: 'Si, Editar',
                    confirmButtonColor: '#001b47',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                }).then(async (result) => {
                    if (this.areAllPercentagesAboveOne()) {
                        if (result.isConfirmed) {
                            this.executeRequest(
                                this.updateRelativesRequest(),
                                '¡Los datos se han actualizado correctamente!'
                            )
                        }
                    } else {
                        toast.warning("Cada miembro debe tener asignado al menos un 1% de porcentaje", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });

                    }
                })
            } else {
                toast.warning("La asignación de porcentaje a tus familiares debe sumar el 100%", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }

        },
        selectParentesco(value, row) {
            let parentesco = JSON.parse(JSON.stringify(this.optionsParentesco.find((index) => index.value == value)));
            this.dataSent.dataRow[row].nombre_parentesco = parentesco.label
        },
        deleteFamiliarFromList(indexRow) {
            this.$swal.fire({
                title: '<p class="text-[16px]">¿Esta seguro de eliminar este familiar de esta lista?. Recuerda que los cambios se aplicaran hasta que el actualizes la informacion</p>',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: '<p class="text-[14px]">Si, eliminar<p>',
                confirmButtonColor: '#950909',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.dataSent.dataRow[indexRow].isDelete = true;
                    this.totalPorcentajeAsignado = parseInt(this.totalPorcentajeAsignado) - parseInt(this.dataSent.dataRow[indexRow].porcentaje_familiar)


                }
            })
        },
        addRow() {
            this.dataSent.dataRow.push({
                id_familiar: '',
                nombre_familiar: '',
                id_parentesco: '',
                nombre_parentesco: '',
                beneficiado_familiar: 1,
                porcentaje_familiar: 0,
                estado_familiar: '',
                onEdit: true,
                isDelete: false
            },)
        },
        calcularDesignacionDePorcentajes(rowIndex) {
            // Calcular la suma total de los porcentajes
            this.totalPorcentajeAsignado = this.dataSent.dataRow.reduce((suma, obj) => suma + parseFloat(obj.porcentaje_familiar), 0);
            if (this.totalPorcentajeAsignado > 100) {
                // Calcular el excedente
                const ajuste = parseInt(this.totalPorcentajeAsignado) - 100;

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
            this.totalPorcentajeAsignado = this.dataSent.dataRow.reduce((suma, obj) => suma + parseFloat(obj.porcentaje_familiar), 0);
            console.log(this.totalPorcentajeAsignado);
        },


        increaseOrDecreaseDesignacionDePorcentajes(rowIndex, operation) {
            switch (operation) {
                case "suma":
                    if (this.totalPorcentajeAsignado < 100) {
                        this.dataSent.dataRow[rowIndex].porcentaje_familiar = (this.dataSent.dataRow[rowIndex].porcentaje_familiar + 1)

                    }
                    if (this.totalPorcentajeAsignado == 100) {
                        alert("Estas justo en las 100")
                    }
                    break;
                case "resta":
                    this.dataSent.dataRow[rowIndex].porcentaje_familiar = (this.dataSent.dataRow[rowIndex].porcentaje_familiar - 1)
                    break;
                default:
                    break;
            }
            this.totalPorcentajeAsignado = this.dataSent.dataRow.reduce((suma, obj) => suma + parseFloat(obj.porcentaje_familiar), 0);
            if (this.totalPorcentajeAsignado > 100) {
                // Calcular el excedente
                const ajuste = parseInt(this.totalPorcentajeAsignado) - 100;

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
            this.totalPorcentajeAsignado = this.dataSent.dataRow.reduce((suma, obj) => suma + parseFloat(obj.porcentaje_familiar), 0);
            console.log(this.totalPorcentajeAsignado);
        },
        setInfoBeneficiarios(info) {
            console.log(info.familiar.reduce((suma, obj) => suma + parseFloat(obj.porcentaje_familiar), 0));
            this.dataSent.id_persona = info.id_persona
            this.totalPorcentajeAsignado = info.familiar.reduce((suma, obj) => suma + parseFloat(obj.porcentaje_familiar), 0);
        },
        deleteInfoBeneficiarios(deleteAll = true) {
            if (deleteAll) {
                this.dataSent.dataRow = []
                this.errosModel = []
                this.dataSent.id_persona = ''
                this.personaOptions = [];
                this.loading = false;
                this.isLoadingToSearch = false;
                this.dataToShow.createdAt = ''
                this.dataToShow.updatedAt = ''
                this.dataToShow.bornPlace = ''
                this.dataToShow.birthDay = ''
                this.dataToShow.civilStatus = ''
                this.dataToShow.gender = ''
                this.dataToShow.levelEducation = ''
                this.dataToShow.profesion = ''
                this.dataToShow.dui_persona = ''
                this.dataToShow.name = ''
            } else {
                this.dataSent.id_persona = ''
                this.personaOptions = [];
                this.loading = false;
                this.isLoadingToSearch = false;
                this.dataToShow.createdAt = ''
                this.dataToShow.updatedAt = ''
                this.dataToShow.bornPlace = ''
                this.dataToShow.birthDay = ''
                this.dataToShow.civilStatus = ''
                this.dataToShow.gender = ''
                this.dataToShow.levelEducation = ''
                this.dataToShow.profesion = ''
                this.dataToShow.dui_persona = ''
                this.dataToShow.name = ''
            }

        }
    },
    watch: {
        showModal: function (value, oldValue) {
            if (this.showModal) {
                if (this.dataBeneficiarios != '') {
                    const newDataBeneficiarios = JSON.parse(JSON.stringify(this.dataBeneficiarios));
                    this.handleSearch({ by: 'id', query: newDataBeneficiarios.id_persona })
                    this.setInfoBeneficiarios(newDataBeneficiarios)
                    newDataBeneficiarios.familiar.forEach((obj, index) => {
                        this.dataSent.dataRow.push({
                            id_familiar: obj.id_familiar,
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
                this.deleteInfoBeneficiarios()
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
