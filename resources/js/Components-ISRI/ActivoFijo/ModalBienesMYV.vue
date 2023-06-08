<script setup>
import Modal from "@/Components/Modal.vue";
import ModalBasicVue from "@/Components-ISRI/AllModal/ModalBasic.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <ModalBasicVue :modalOpen="show_modal_asset" @close-modal="$emit('cerrar-modal')"
            :title="'Gestión Bienes Muebles y Vehiculos '" maxWidth="4xl">
            <div class="px-5 py-4 h-full">
                <div class="flex flex-col">
                    <div class="w-full mx-auto my-4 border-b-2 pb-2">
                        <div class="flex pb-3">
                            <div class="flex-1">
                            </div>

                            <div class="flex-1">
                                <div
                                    class="w-10 h-10 bg-blue-400 border-2 border-blue-400 mx-auto rounded-full text-lg text-white flex items-center">
                                </div>
                            </div>


                            <div class="w-[22%] align-center items-center align-middle content-center flex">
                                <div class="w-full bg-gray-300 rounded items-center align-middle align-center flex-1">
                                    <div class="bg-blue-400 text-xs leading-none py-1 text-center text-gray-darkest rounded"
                                        :style="{ width: page1_w + '%' }"></div>
                                </div>
                            </div>


                            <div class="flex-1">
                                <div
                                    class="w-10 h-10 bg-white border-2 border-gray-300 mx-auto rounded-full text-lg text-white flex items-center">
                                    <span class="text-black text-center w-full">2</span>
                                </div>
                            </div>


                            <div class="w-[22%] align-center items-center align-middle content-center flex">
                                <div class="w-full bg-gray-300 rounded items-center align-middle align-center flex-1">
                                    <div class="bg-green-400 text-xs leading-none py-1 text-center text-gray-darkest rounded"
                                        style="width: 0%"></div>
                                </div>
                            </div>


                            <div class="flex-1">
                                <div
                                    class="w-10 h-10 bg-white border-2 border-gray-300 mx-auto rounded-full text-lg text-white flex items-center">
                                    <span class="text-black text-center w-full">3</span>
                                </div>
                            </div>


                            <div class="flex-1">
                            </div>
                        </div>

                        <div class="flex text-xs content-center text-center">
                            <div class="w-1/3">
                                Imformacion general
                            </div>

                            <div class="w-1/3">
                                Detalles
                            </div>

                            <div class="w-1/3">
                                Vehiculo
                            </div>
                        </div>
                    </div>
                    <!-- <div class="flex justify-center items-center">
                        <div class="mx-4" :class="{ 'border-slate-800 border-b-2 ': currentPage === 1 }">
                            <p class="text-lg font-semibold text-slate-800">
                                Informacion general</p>
                        </div>
                        <div class="mx-4" :class="{ 'border-slate-800 border-b-2 ': currentPage === 2 }">
                            <p class="text-lg font-semibold text-slate-800">
                                Detalles</p>
                        </div>
                        <div class="mx-4" :class="{ 'border-slate-800 border-b-2 ': currentPage === 3 }"
                            v-if="selected_budget == 61105">
                            <p class="text-lg font-semibold text-slate-800">
                                Vehiculo</p>
                        </div>
                    </div> -->

                    <!-- General information -->
                    <div id="general-information-page" class="border-2 rounded-lg" v-show="currentPage === 1">
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Bien Especifico <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.specific_asset_id" :options="specific_assets"
                                        placeholder="Seleccione Bien" :searchable="true"
                                        @select="selectSpecificAsset($event)" @clear="clearSpecificAsset()" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.type_asset_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Condicion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.condition_id" :options="conditions_assets"
                                        placeholder="Seleccione condicion" :searchable="true" @input="updateWidth" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.condition_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Fuente de financiamiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.financing_source_id" :options="financing_sources"
                                        placeholder="Seleccion financiamiento" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.financing_source_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Proveedor <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.financing_source_id" :options="financing_sources"
                                        placeholder="Seleccione proveedor" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.financing_source_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="valor-adquisicion" v-model="asset.acquisition_value"
                                    :value="asset.acquisition_value" type="text" placeholder="Valor adquisicion"
                                    @update:modelValue="typeValAdquisicion()">
                                    <LabelToInput icon="money" forLabel="valor-adquisicion" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.acquisition_value" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="fecha-adquisicion" v-model="asset.acquisition_date"
                                    :value="asset.acquisition_date" type="date" placeholder="Fecha adquisicion">
                                    <LabelToInput icon="date" forLabel="fecha-adquisicion" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.acquisition_date" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3 ">
                                <TextInput id="vida-util" v-model="asset.shelf_life" :value="asset.shelf_life" type="number"
                                    placeholder="Vida util">
                                    <LabelToInput icon="standard" forLabel="vida-util" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.shelf_life" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 pr-4 md:mb-0 basis-1/3 flex items-center justify-start">
                                <div class="text-center pt-6">
                                    <input id="depreciable" type="checkbox" v-model="asset.depreciable"
                                        :value="asset.depreciable">
                                    <label class="ml-2 text-xs font-light text-gray-600" for="depreciable">
                                        Cambiar Vida Util
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Details -->
                    <div class="border-2 rounded-lg" v-show="currentPage === 2">
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Dependencia <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.dependency_id" :options="dependencies"
                                        placeholder="Seleccione dependencia" :searchable="true"
                                        @select="getEnvironments($event)" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.dependency_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Ambiente <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.environment_id" :options="environments"
                                        placeholder="Seleccione ambiente" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.environment_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Marca <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.brand_id" :options="brands" placeholder="Seleccione marca"
                                        :searchable="true" @select="getModels($event)" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.brand_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <!-- Model, color and serial -->
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Modelo <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.model_id" :options="models" placeholder="Seleccione modelo"
                                        :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.model_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="serie" v-model="asset.serial_number" :value="asset.serial_number" type="text"
                                    placeholder="Serie">
                                    <LabelToInput icon="standard" forLabel="serie" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.serial_number" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="color" v-model="asset.colour" :value="asset.colour" type="text"
                                    placeholder="Color">
                                    <LabelToInput icon="standard" forLabel="color" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.colour" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <!-- Observation -->
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full"
                                style="border: none; background-color: transparent;">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                                    Observacion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <textarea v-model="asset.observation" id="observacion" name="observacion"
                                    class="resize-none w-full h-12 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                            </textarea>
                                <InputError v-for="(item, index) in errors.observation" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                    </div>
                    <!-- Vehicle information -->
                    <div id="vehicle-page" class="border-2 rounded-lg" v-if="currentPage === 3 && selected_budget == 61105">
                        <!-- First row vehicle -->
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                                <TextInput id="año-vehiculo" v-model="asset.vehicle_year" :value="asset.vehicle_year"
                                    type="number" placeholder="Año">
                                    <LabelToInput icon="standard" forLabel="año-vehiculo" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.vehicle_year" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                                <TextInput id="tarjeta-circulacion" v-model="asset.vehicle_card" :value="asset.vehicle_card"
                                    type="text" placeholder="Tarjeta de circulacion">
                                    <LabelToInput icon="standard" forLabel="tarjeta-circulacion" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.vehicle_card" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                                <TextInput id="numero-placa" v-model="asset.vehicle_plate_number"
                                    :value="asset.vehicle_plate_number" type="text" placeholder="Placa">
                                    <LabelToInput icon="standard" forLabel="numero-placa" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.vehicle_plate_number" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                                <TextInput id="asientos" v-model="asset.vehicle_seats" :value="asset.vehicle_seats"
                                    type="text" placeholder="Asientos" @update:modelValue="typeSeats()">
                                    <LabelToInput icon="standard" forLabel="asientos" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.vehicle_seats" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <!-- Second row vehicle -->
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="numero-motor" v-model="asset.vehicle_motor_number"
                                    :value="asset.vehicle_motor_number" type="text" placeholder="Numero motor">
                                    <LabelToInput icon="standard" forLabel="numero-motor" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.vehicle_motor_number" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="numero-chasis" v-model="asset.vehicle_chassis_number"
                                    :value="asset.vehicle_chassis_number" type="text" placeholder="Numero chasis">
                                    <LabelToInput icon="standard" forLabel="numero-chasis" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.vehicle_chassis_number" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="numero-vin" v-model="asset.vehicle_vin_number"
                                    :value="asset.vehicle_vin_number" type="text" placeholder="Numero VIN">
                                    <LabelToInput icon="standard" forLabel="numero-vin" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.vehicle_vin_number" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <div class="h-14"></div>
                    </div>
                </div>
                <!-- Buttons to navigate -->
                <div class="flex justify-center mt-5">
                    <button class="flex items-center bg-gray-500 hover:bg-gray-600 text-white py-1 px-1 rounded"
                        :disabled="currentPage === 1" @click="goToPreviousPage">
                        <svg fill="#ffffff" height="18px" width="18px" version="1.1" id="Layer_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 24 24" xml:space="preserve" stroke="#ffffff" class="mr-0">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M18,3v18L5,12L18,3z M8.5,12l7.5,5V7L8.5,12z"></path>
                            </g>
                        </svg>
                        Anterior
                    </button>
                    <button class="flex items-center ml-2 bg-blue-500 hover:bg-blue-600 text-white py-1 pl-1 rounded"
                        :disabled="disabled_next_button" @click="goToNextPage">
                        Siguiente
                        <svg fill="#ffffff" height="18px" width="18px" viewBox="0 0 24 24" id="next" data-name="Line Color"
                            xmlns="http://www.w3.org/2000/svg" class="icon line-color ml-0.5" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path id="primary" d="M17,12,5,21V3Z"
                                    style="fill: none; stroke: #ffffff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                </path>
                            </g>
                        </svg>
                    </button>
                </div>
            </div>
        </ModalBasicVue>
    </div>
</template>

<script>
export default {
    props: {
        show_modal_asset: {
            type: Boolean,
            default: false,
        },
        modalData: {
            type: Array,
            default: []
        },
        dependencies: {
            type: Array,
            default: []
        },
        specific_assets: {
            type: Array,
            default: []
        },
        conditions_assets: {
            type: Array,
            default: []
        },
        financing_sources: {
            type: Array,
            default: []
        }
    },
    created() { },
    data: function (data) {
        return {
            page1_w: 0,
            currentPage: 1,
            vehicle: false,
            selected_budget: '',
            models: [],
            errors: [],
            environments: [],
            budget_codes: [
                { value: 61101, label: '61101 MOBILIARIO' },
                { value: 61102, label: '61102 MAQUINARIAS Y EQUIPO' },
                { value: 61103, label: '61103 EQUIPOS MEDICOS Y DE LABORATORIOS' },
                { value: 61104, label: '61104 EQUIPOS INFORMATICOS' },
                { value: 61108, label: '61108 HERRAMIENTAS Y REPUESTOS PRINCIPALES' },
                { value: 61110, label: '61110 MAQUINARIA Y EQUIPO PARA APOYO INSTITUCIONAL' },
                { value: 61199, label: '61199 BIENES MUEBLES DIVERSOS' }
            ],
            asset: {
                asset_id: '',
                type_asset_id: '',
                asset_state_id: '',
                model_id: '',
                vehicle_id: '',
                specific_asset_id: '',
                supplier_id: '',
                financing_source_id: '',
                depreciable_asset_id: '',
                code: '',
                old_code: '',
                serie: '',
                acquisition_date: '',
                acquisition_value: '',
                useful_life: '',
                acquisition_doc: '',
            },
        };
    },
    methods: {
        goToPreviousPage() {
            this.currentPage--;
        },
        goToNextPage() {
            this.currentPage++;
        },
        selectSpecificAsset(id) {
            const filteredAssets = this.specific_assets.filter(asset => asset.value === id);
            const selectedAsset = filteredAssets.find(asset => asset.value === id);
            if (selectedAsset) {
                this.selected_budget = selectedAsset.id_ccta_presupuestal;
                this.selected_budget === 61105 ? this.vehicle = true : this.vehicle = false
            }
            this.updateWidth()
        },
        clearSpecificAsset() {
            this.selected_budget = ''
            this.asset.specific_asset_id = ''
            this.vehicle = false
            this.updateWidth()
        },
        updateWidth() {
            let filledCount = 0;
            // Verificar cuántos campos están llenos
            if (this.asset.specific_asset_id !== '') filledCount++;
            // if (this.input2 !== '') filledCount++;
            // if (this.input3 !== '') filledCount++;
            // if (this.input4 !== '') filledCount++;
            // if (this.input5 !== '') filledCount++;
            console.log(filledCount);
            // Calcular el nuevo ancho
            this.page1_w = filledCount * (100 / 6);
        },
        async getEnvironments(dependency_id) {
            await axios.get('/get-environments', {
                params: {
                    dependency_id: dependency_id
                }
            })
                .then((response) => {
                    this.environments = response.data.environments
                })
                .catch((errors) => {
                    let msg = this.manageError(errors)
                    this.$swal.fire({
                        title: 'Operación cancelada',
                        text: msg,
                        icon: 'warning',
                        timer: 5000
                    })
                })
        },
        async getModels(brand_id) {
            await axios.get('/get-models', {
                params: {
                    brand_id: brand_id
                }
            })
                .then((response) => {
                    this.models = response.data.models
                })
                .catch((errors) => {
                    let msg = this.manageError(errors)
                    this.$swal.fire({
                        title: 'Operación cancelada',
                        text: msg,
                        icon: 'warning',
                        timer: 5000
                    })
                })
        },
        typeSeats() {
            var x = this.asset.vehicle_seats.replace(/\D/g, '').match(/(\d{0,2})/);
            this.asset.vehicle_seats = !x[2] ? x[1] : '';
        },
        typeQuantity() {
            var x = this.asset.quantity.replace(/\D/g, '').match(/(\d{0,2})/);
            if (x[1] > 20) {
                this.asset.quantity = 1;
            } else {
                this.asset.quantity = x[1];
            }
        },
        typeValAdquisicion() {
            let x = this.asset.acquisition_value.replace(/^\./, '').replace(/[^0-9.]/g, '')
            this.asset.acquisition_value = x
            const regex = /^(\d+)?([.]?\d{0,2})?$/
            if (!regex.test(this.asset.acquisition_value)) {
                this.asset.acquisition_value = this.asset.acquisition_value.match(regex) || x.substring(0, x.length - 1)
            }
            if (!this.asset.acquisition_value == '') {
                this.asset.acquisition_value = '$' + this.asset.acquisition_value
            }
        }
    },
    computed: {
        disabled_next_button() {
            if (this.currentPage == 1) {
                return false
            } else {
                if (this.currentPage == 3) {
                    return true
                } else {
                    if (this.vehicle) {
                        return false
                    } else {
                        return true
                    }
                }
            }
        }
    },
    watch: {
        show_modal_asset: function (value, oldValue) {
            if (value) {
                this.errors = [];
                this.vehicle = false
                this.currentPage = 1
                this.asset = Object.assign({}, {
                    asset_id: this.modalData.id_af ?? '',
                    type_asset_id: this.modalData.id_tipo_af ?? '',
                    asset_state_id: this.modalData.id_estado_af ?? '',
                    model_id: this.modalData.id_modelo ?? '',
                    vehicle_id: this.modalData.id_vehiculo ?? '',
                    specific_asset_id: this.modalData.id_bien_especifico ?? '',
                    supplier_id: this.modalData.id_proveedor ?? '',
                    financing_source_id: this.modalData.id_proy_financiado ?? '',
                    depreciable_asset_id: this.modalData.id_bien_depreciable ?? '',
                    code: this.modalData.codigo_af ? this.modalData.codigo_af : '',
                    old_code: this.modalData.codigo_antiguo_af ?? '',
                    serie: this.modalData.serie_af ?? '',
                    acquisition_date: this.modalData.fecha_adquisicion_af ?? '',
                    acquisition_value: this.modalData.valor_adquisicion_af ?? '',
                    useful_life: this.modalData.vida_util_af ?? '',
                    acquisition_doc: this.modalData.doc_adquisicion_af ?? '',
                    observation: this.modalData.observacion_af ?? '',
                });
                if (this.modalData != '') {
                    this.selectSpecificAsset(this.modalData.id_bien_especifico)
                } else {
                    this.selected_budget = ''
                }
            }
        },
    },
};
</script>

