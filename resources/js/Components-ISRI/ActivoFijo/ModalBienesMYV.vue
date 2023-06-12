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
                                    class="w-10 h-10 bg-green-400 border-2 border-green-400 mx-auto rounded-full text-lg text-white flex items-center">
                                </div>
                            </div>


                            <div class="align-center items-center align-middle content-center flex"
                                :class="{ 'w-[22%]': !vehicle, 'w-1/6': vehicle }">
                                <div class="w-full bg-gray-300 rounded items-center align-middle align-center flex-1">
                                    <div class="bg-green-400 text-xs leading-none py-1 text-center text-gray-darkest rounded"
                                        :style="{ width: vehicle ? page1_w + '%' : '100%' }"></div>
                                </div>
                            </div>



                            <div class="flex-1">
                                <div class="w-10 h-10 bg-white border-2 border-gray-300 mx-auto rounded-full text-lg text-white flex items-center"
                                    :class="{ 'bg-green-400 border-2 border-green-400': currentPage === 2 || currentPage === 3 }">
                                    <span v-if="currentPage == 1" class="text-black text-center w-full">2</span>
                                </div>
                            </div>


                            <div class="align-center items-center align-middle content-center flex"
                                :class="{ 'w-[22%]': !vehicle, 'w-1/6': vehicle }">
                                <div class="w-full bg-gray-300 rounded items-center align-middle align-center flex-1">
                                    <div class="bg-green-400 text-xs leading-none py-1 text-center text-gray-darkest rounded"
                                        :style="{ width: page2_w + '%' }"></div>
                                </div>
                            </div>


                            <div class="flex-1">
                                <div
                                    class="w-10 h-10 bg-white border-2 border-gray-300 mx-auto rounded-full text-lg text-white flex items-center">
                                    <span class="text-black text-center w-full">3</span>
                                </div>
                            </div>

                            <div v-if="vehicle" class="align-center items-center align-middle content-center flex"
                                :class="{ 'w-[22%]': !vehicle, 'w-1/6': vehicle }">
                                <div class="w-full bg-gray-300 rounded items-center align-middle align-center flex-1">
                                    <div class="bg-green-400 text-xs leading-none py-1 text-center text-gray-darkest rounded"
                                        :style="{ width: page3_w + '%' }"></div>
                                </div>
                            </div>

                            <div v-if="vehicle" class="flex-1">
                                <div
                                    class="w-10 h-10 bg-white border-2 border-gray-300 mx-auto rounded-full text-lg text-white flex items-center">
                                    <span class="text-black text-center w-full">4</span>
                                </div>
                            </div>


                            <div class="flex-1">
                            </div>
                        </div>

                        <div class="flex text-xs content-center text-center">
                            <div :class="{ 'w-1/3': !vehicle, 'w-1/4': vehicle }">
                                Informacion general
                            </div>
                            <div :class="{ 'w-1/3': !vehicle, 'w-1/4': vehicle }">
                                Detalles
                            </div>
                            <div v-if="vehicle" class="w-1/4">
                                Vehiculo
                            </div>
                            <div :class="{ 'w-1/3': !vehicle, 'w-1/4': vehicle }">
                                Confirmacion
                            </div>
                        </div>

                    </div>

                    <!-- General information -->
                    <div id="general-information-page" class="border-2 rounded-lg" v-show="currentPage === 1">
                        <!-- specific asset and condition -->
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
                                <InputError class="mt-2" :message="errors.specific_asset_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Condicion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.condition_id" :options="conditions"
                                        placeholder="Seleccione condicion" :searchable="true" @select="updateWidth()"
                                        @clear="clearSelect('condition_id')" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.condition_id" />
                            </div>
                        </div>
                        <!-- financing source, supplier and acquisition value -->
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Fuente de financiamiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.financing_source_id" :options="financing_sources"
                                        placeholder="Seleccion financiamiento" :searchable="true" @select="updateWidth"
                                        @clear="clearSelect('financing_source_id')" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.financing_source_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Proveedor <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.supplier_id" :options="suppliers"
                                        placeholder="Seleccione proveedor" :searchable="true" @select="updateWidth"
                                        @clear="clearSelect('supplier_id')" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.supplier_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="valor-adquisicion" v-model="asset.acquisition_value"
                                    :value="asset.acquisition_value" type="text" placeholder="Valor adquisicion"
                                    @update:modelValue="validateInput('acquisition_value', 11, false, monto = true)">
                                    <LabelToInput icon="money" forLabel="valor-adquisicion" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.acquisition_value" />
                            </div>
                        </div>
                        <!-- acquisition date and shelf life -->
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="fecha-adquisicion" v-model="asset.acquisition_date"
                                    :value="asset.acquisition_date" type="date" placeholder="Fecha adquisicion"
                                    @update:modelValue="updateWidth()">
                                    <LabelToInput icon="date" forLabel="fecha-adquisicion" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.acquisition_date" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3 ">
                                <TextInput id="vida-util" v-model="asset.shelf_life" :value="asset.shelf_life" type="text"
                                    placeholder="Vida util" :disabled="!asset.change_shelf_life"
                                    @update:modelValue="validateInput('shelf_life', 2, false, false, number = true)">
                                    <LabelToInput icon="standard" forLabel="vida-util" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.shelf_life" />
                            </div>
                            <div class="mb-4 md:mr-2 pr-4 md:mb-0 basis-1/3 flex items-center justify-start">
                                <div class="text-center pt-6"
                                    v-if="selected_shelf_life !== '' && selected_shelf_life !== 0">
                                    <input id="depreciable" type="checkbox" v-model="asset.change_shelf_life"
                                        :value="asset.change_shelf_life" @change="changeShelfLife()">
                                    <label class="ml-2 text-xs font-light text-gray-600" for="depreciable">
                                        Cambiar Vida Util
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Details -->
                    <div class="border-2 rounded-lg" v-show="currentPage === 2">
                        <!-- Dependency, environment and brand -->
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Dependencia <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.dependency_id" :options="dependencies"
                                        placeholder="Seleccione dependencia" :searchable="true"
                                        @select="getEnvironments($event)" @clear="clearSelect('dependency_id')" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.dependency_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Ambiente <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.environment_id" :options="environments"
                                        placeholder="Seleccione ambiente" :searchable="true" @select="updateWidth()"
                                        @clear="clearSelect('environment_id')" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.environment_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Marca <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="asset.brand_id" :options="brands" placeholder="Seleccione marca"
                                        :searchable="true" @select="getModels($event)" @clear="clearBrand()" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.brand_id" />
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
                                        :searchable="true" :noOptionsText="'Lista vacía'" @select="updateWidth()"
                                        @clear="clearSelect('model_id')" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.model_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="serie" v-model="asset.serial_number" :value="asset.serial_number" type="text"
                                    placeholder="Serie" @update:modelValue="validateInput('serial_number', 100)"
                                    :required="false">
                                    <LabelToInput icon="standard" forLabel="serie" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.serial_number" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="color" v-model="asset.color" :value="asset.color" type="text"
                                    placeholder="Color" :required="false">
                                    <LabelToInput icon="standard" forLabel="color" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.color" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <!-- Observation -->
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full"
                                style="border: none; background-color: transparent;">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                                    Observacion
                                </label>
                                <textarea v-model="asset.observation" id="observacion" name="observacion"
                                    class="resize-none w-full h-12 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                    @input="validateInput('observation', 500)">
                            </textarea>
                                <InputError v-for="(item, index) in errors.observation" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                    </div>
                    <!-- page 3 -->
                    <div class="border-2 rounded-lg" v-show="currentPage === 5">
                        <h2>PAGE 3</h2>
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
                    <!-- Final page -->
                    <div class="border-2 rounded-lg" v-show="currentPage === 5">
                        <h2>FINAL PAGE</h2>
                    </div>
                </div>
                <!-- Buttons to navigate -->
                <div class="flex justify-center mt-5">
                    <button class="flex items-center bg-gray-500 hover:bg-gray-600 text-white py-1 px-1 rounded"
                        :disabled="currentPage === 1" @click="goToPreviousPage">
                        <svg width="22px" height="22px" viewBox="-3 0 32 32" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff"
                            stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g id="icomoon-ignore"> </g>
                                <path
                                    d="M13.114 2.887c-7.243 0-13.114 5.871-13.114 13.113s5.871 13.113 13.114 13.113c7.242 0 13.112-5.871 13.112-13.113s-5.87-13.113-13.112-13.113zM13.114 28.064c-6.653 0-12.065-5.412-12.065-12.064s5.412-12.063 12.065-12.063c6.652 0 12.063 5.412 12.063 12.063s-5.411 12.064-12.063 12.064z"
                                    fill="#ffffff"> </path>
                                <path
                                    d="M12.318 10.363l-0.742-0.742-6.379 6.379 6.379 6.379 0.742-0.742-5.113-5.113h12.726v-1.049h-12.726z"
                                    fill="#ffffff"> </path>
                            </g>
                        </svg>
                        Anterior
                    </button>
                    <button class="flex items-center ml-2 bg-blue-500 hover:bg-blue-600 text-white py-1 pl-1 rounded"
                        :disabled="disabled_next_button" @click="goToNextPage">
                        Siguiente
                        <svg width="22px" height="22px" viewBox="-3 0 32 32" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff"
                            stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g id="icomoon-ignore"> </g>
                                <path
                                    d="M13.11 29.113c7.243 0 13.113-5.871 13.113-13.113s-5.87-13.113-13.113-13.113c-7.242 0-13.113 5.871-13.113 13.113s5.871 13.113 13.113 13.113zM13.11 3.936c6.652 0 12.064 5.412 12.064 12.064s-5.412 12.064-12.064 12.064c-6.653 0-12.064-5.412-12.064-12.064s5.411-12.064 12.064-12.064z"
                                    fill="#ffffff"> </path>
                                <path
                                    d="M13.906 21.637l0.742 0.742 6.378-6.379-6.378-6.379-0.742 0.742 5.112 5.112h-12.727v1.049h12.727z"
                                    fill="#ffffff"> </path>
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
        suppliers: {
            type: Array,
            default: []
        },
        specific_assets: {
            type: Array,
            default: []
        },
        conditions: {
            type: Array,
            default: []
        },
        financing_sources: {
            type: Array,
            default: []
        },
        specific_assets: {
            type: Array,
            default: []
        },
        conditions: {
            type: Array,
            default: []
        },
        dependencies: {
            type: Array,
            default: []
        },
        brands: {
            type: Array,
            default: []
        },
    },
    created() { },
    data: function (data) {
        return {
            page1_w: 0,
            page2_w: 0,
            page3_w:0,
            currentPage: 1,
            selected_shelf_life: '',
            vehicle: false,
            selected_budget: '',
            models: [],
            errors: [],
            environments: [
                { value: 1, label: 'Ambiente1' },
                { value: 2, label: 'Ambiente2' },
                { value: 3, label: 'Ambiente3' },
                { value: 4, label: 'Ambiente4' },
            ],
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
                brand_id:'',
                vehicle_id: '',
                condition_id: '',
                specific_asset_id: '',
                supplier_id: '',
                financing_source_id: '',
                depreciable_asset_id: '',
                code: '',
                old_code: '',
                serial_number: '',
                acquisition_date: '',
                acquisition_value: '',
                acquisition_doc: '',
                shelf_life: '',
                change_shelf_life: false
            },
        };
    },
    methods: {
        //Function to validate data entry
        validateInput(field, limit, upper_case, amount, number) {
            if (this.asset[field] && this.asset[field].length > limit) {
                this.asset[field] = this.asset[field].substring(0, limit);
            }
            if (upper_case) {
                this.toUpperCase(field)
            }
            if (amount) {
                this.typeAmount(field)
            }
            if (number) {
                this.typeShelfLife(field)
            }
            this.updateWidth()
        },
        toUpperCase(field) {
            //Converts field to uppercase
            this.asset[field] = this.asset[field].toUpperCase()
        },
        typeAmount(field) {
            let x = this.asset[field].replace(/^\./, '').replace(/[^0-9.]/g, '')
            this.asset[field] = x
            const regex = /^(\d+)?([.]?\d{0,2})?$/
            if (!regex.test(this.asset[field])) {
                this.asset[field] = this.asset[field].match(regex) || x.substring(0, x.length - 1)
            }
        },
        typeShelfLife(field) {
            this.asset[field] = this.asset[field].replace(/[^\d]/g, '')
            const num = parseInt(this.asset[field]);
            if (isNaN(num) || num < 1 || num > 40) {
                // Si el número está fuera del rango permitido, establecer el campo a un valor vacío
                this.asset[field] = '';
            }
        },
        goToPreviousPage() {
            this.errors = [];
            this.currentPage--;
        },
        goToNextPage() {
            const fieldMappings = {
                specific_asset_id: 'Bien Especifico',
                condition_id: 'Condicion',
                financing_source_id: 'Fuente de Financiamiento',
                supplier_id: 'Proveedor',
                acquisition_value: 'Valor de Adquisicion',
                acquisition_date: 'Fecha de Adquisicion',
                shelf_life: 'Vida Util',
                model_id: 'Modelo',
                dependency_id: 'Dependencia',
                environment_id: 'Ambiente',
                brand_id: 'Marca'
            };

            let page_with_errors = '';

            if (this.currentPage === 1) {
                const requiredFields = [
                    'specific_asset_id',
                    'condition_id',
                    'financing_source_id',
                    'supplier_id',
                    'acquisition_value',
                    'acquisition_date',
                    'shelf_life'
                ];
                requiredFields.forEach(field => {
                    if (this.asset[field]) {
                        this.errors[field] = '';
                    } else {
                        this.errors[field] = `El campo ${fieldMappings[field]} es obligatorio.`;
                    }
                });
                if (Object.values(this.errors).some(error => error !== '')) {
                    page_with_errors = 1;
                }
            }

            if (this.currentPage === 2) {
                const requiredFields = [
                    'model_id',
                    'dependency_id',
                    'environment_id',
                    'brand_id'
                ];
                requiredFields.forEach(field => {
                    if (this.asset[field]) {
                        this.errors[field] = '';
                    } else {
                        this.errors[field] = `El campo ${fieldMappings[field]} es obligatorio.`;
                    }
                });
                if (Object.values(this.errors).some(error => error !== '')) {
                    page_with_errors = 2;
                }
            }
            console.log(page_with_errors,this.currentPage);
            const errors = Object.values(this.errors);
            if (errors.every(error => error === '')) {
                this.currentPage++;
            } else {
                if (page_with_errors !== this.currentPage) {
                    this.currentPage++;
                } else {
                    toast.warning(
                        "Tienes algunos errores por favor verifica tus datos.",
                        {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        }
                    );
                }
            }
        },
        changeShelfLife() {
            if (this.asset.change_shelf_life === false && this.asset.shelf_life !== this.selected_shelf_life) {
                this.asset.shelf_life = this.selected_shelf_life
            }
        },
        selectSpecificAsset(id) {
            const filteredAssets = this.specific_assets.filter(asset => asset.value === id);
            const selectedAsset = filteredAssets.find(asset => asset.value === id);
            if (selectedAsset) {
                this.asset.change_shelf_life = false
                this.selected_budget = selectedAsset.id_ccta_presupuestal;
                this.selected_shelf_life = selectedAsset.vida_util_tipo_af
                this.asset.shelf_life = selectedAsset.vida_util_tipo_af
                this.selected_budget === 61105 ? this.vehicle = true : this.vehicle = false
            }
            this.updateWidth()
        },
        saveMoveableAssetAndVehicle() {
            axios
                .post("/save-mv-asset", this.asset, this.pages)
                .then((response) => {
                    if (this.pages.page1 && this.pages.page2) {

                    } else {
                        this.goToNextPage()
                    }
                })
                .catch((errors) => {
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
                        let msg = this.manageError(errors);
                        this.$swal.fire({
                            title: "Operación cancelada",
                            text: msg,
                            icon: "warning",
                            timer: 5000,
                        });
                        this.$emit("cerrar-modal");
                    }
                });
        },
        clearSpecificAsset() {
            this.selected_budget = ''
            this.asset.shelf_life = ''
            this.asset.specific_asset_id = ''
            this.vehicle = false
            this.selected_shelf_life = ''
            this.updateWidth()
        },
        clearBrand() {
            this.asset.brand_id = ''
            this.asset.model_id = ''
            this.models = []
            this.updateWidth()
        },
        clearSelect(field) {
            this.asset[field] = ''
            this.updateWidth()
        },
        updateWidth() {
            let filledCountPage1 = 0;
            let filledCountPage2 = 0;
            // Verificar cuántos campos están llenos en la pagina 1
            if (this.asset.specific_asset_id) filledCountPage1++;
            if (this.asset.condition_id) filledCountPage1++;
            if (this.asset.financing_source_id) filledCountPage1++;
            if (this.asset.supplier_id) filledCountPage1++;
            if (this.asset.acquisition_value) filledCountPage1++;
            if (this.asset.acquisition_date) filledCountPage1++;
            if (this.asset.shelf_life) filledCountPage1++;
            // Calcular el nuevo ancho
            this.page1_w = filledCountPage1 * (100 / 7);

            // Verificar cuántos campos están llenos en la pagina 2
            if (this.asset.dependency_id) filledCountPage2++;
            if (this.asset.environment_id) filledCountPage2++;
            if (this.asset.brand_id) filledCountPage2++;
            if (this.asset.model_id) filledCountPage2++;
            //if (this.asset.serial_number) filledCountPage2++;
            //if (this.asset.color) filledCountPage2++;
            //if (this.asset.observation) filledCountPage2++;
            // Calcular el nuevo ancho
            this.page2_w = filledCountPage2 * (100 / 4);
        },
        async getEnvironments(dependency_id) {
            //A la espera de la definicion de ambientes
            // await axios.get('/get-environments', {
            //     params: {
            //         dependency_id: dependency_id
            //     }
            // })
            //     .then((response) => {
            //         this.environments = response.data.environments
            //     })
            //     .catch((errors) => {
            //         let msg = this.manageError(errors)
            //         this.$swal.fire({
            //             title: 'Operación cancelada',
            //             text: msg,
            //             icon: 'warning',
            //             timer: 5000
            //         })
            //     })
            this.updateWidth()
        },
        async getModels(brand_id) {
            this.asset.model_id = this.modalData === '' ? '' : this.asset.model_id;
            this.updateWidth()
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
                console.log(this.modalData);
                this.errors = [];
                this.vehicle = false
                this.currentPage = 1
                this.page1_w = 0
                this.page2_w = 0
                this.asset = Object.assign({}, {
                    asset_id: this.modalData.id_af ?? '',
                    type_asset_id: this.modalData.id_tipo_af ?? '',
                    asset_state_id: this.modalData.id_estado_af ?? '',
                    brand_id: this.modalData.id_marca ?? '',
                    model_id: this.modalData.id_modelo ?? '',
                    vehicle_id: this.modalData.id_vehiculo ?? '',
                    condition_id: this.modalData.id_estado_af ?? '',
                    specific_asset_id: this.modalData.id_bien_especifico ?? '',
                    supplier_id: this.modalData.id_proveedor ?? '',
                    financing_source_id: this.modalData.id_proy_financiado ?? '',
                    depreciable_asset_id: this.modalData.id_bien_depreciable ?? '',
                    code: this.modalData.codigo_af ? this.modalData.codigo_af : '',
                    old_code: this.modalData.codigo_antiguo_af ?? '',
                    serial_number: this.modalData.serie_af ?? '',
                    shelf_life: this.modalData.vida_util_af ?? '',
                    acquisition_date: this.modalData.fecha_adquisicion_af ?? '',
                    acquisition_value: this.modalData.valor_adquisicion_af ?? '',
                    acquisition_doc: this.modalData.doc_adquisicion_af ?? '',
                    observation: this.modalData.observacion_af ?? '',
                    change_shelf_life: false
                });
                if (this.modalData != '') {
                    this.selectSpecificAsset(this.modalData.id_bien_especifico)
                    this.getModels(this.modalData.id_marca)
                } else {
                    this.selected_budget = ''
                }
            }
        },
    },
};
</script>

