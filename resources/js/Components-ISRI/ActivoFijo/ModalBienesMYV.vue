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
            <div class="px-5 py-4">
                <div class="text-sm">

                    <div class="mb-4 md:flex flex-row justify-between">
                        <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                            Información general
                        </span>
                        <!-- <a href="#abajo">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-arrow-narrow-down cursor-pointer" width="30" height="30"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <title>Ir al final</title>
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="16" y1="15" x2="12" y2="19" />
                                <line x1="8" y1="15" x2="12" y2="19" />
                            </svg>
                        </a> -->
                    </div>

                    <!-- First row -->
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
                                Fuente de financiamiento <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="asset.financing_source_id" :options="financing_sources"
                                    placeholder="Seleccione financiamiento" :searchable="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.financing_source_id" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Second row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Bien Especifico <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="asset.specific_asset_id" :options="specific_assets"
                                    placeholder="Seleccione Bien" :searchable="true"
                                    @select="selectSpecificAsset($event)" />
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
                                    placeholder="Seleccione condicion" :searchable="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.condition_id" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Third row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
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
                            <TextInput id="fecha-adquisicion" v-model="asset.acquisition_date"
                                :value="asset.acquisition_date" type="date" placeholder="Fecha adquisicion">
                                <LabelToInput icon="date" forLabel="fecha-adquisicion" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.acquisition_date" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <!-- <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                            <TextInput id="fecha-entrada" v-model="asset.date_entry" :value="asset.date_entry" type="date"
                                placeholder="Fecha entrada">
                                <LabelToInput icon="date" forLabel="fecha-entrada" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.date_entry" :key="index" class="mt-2"
                                :message="item" />
                        </div> -->
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

                    <!-- Fourth row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
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
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
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
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <TextInput id="serie" v-model="asset.serial_number" :value="asset.serial_number" type="text"
                                placeholder="Serie">
                                <LabelToInput icon="standard" forLabel="serie" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.serial_number" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Fifth row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                            <TextInput id="compromiso-presupuestario" v-model="asset.commitment" :value="asset.commitment"
                                type="text" placeholder="Compromiso presupuestario">
                                <LabelToInput icon="standard" forLabel="compromiso-presupuestario" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.commitment" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                            <TextInput id="acta" v-model="asset.act" :value="asset.act" type="text" placeholder="Acta">
                                <LabelToInput icon="standard" forLabel="acta" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.act" :key="index" class="mt-2" :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                            <TextInput id="factura" v-model="asset.bill" :value="asset.bill" type="number"
                                placeholder="Factura">
                                <LabelToInput icon="standard" forLabel="factura" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.bill" :key="index" class="mt-2" :message="item" />
                        </div>
                    </div>

                    <!-- Sixth row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                            <TextInput id="color" v-model="asset.colour" :value="asset.colour" type="text"
                                placeholder="Color">
                                <LabelToInput icon="standard" forLabel="color" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.colour" :key="index" class="mt-2" :message="item" />
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

                    <!-- Eigth row -->
                    <div class="mb-4 md:flex flex-row justify-items-start" id="abajo">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full" style="border: none; background-color: transparent;">
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

                    <!-- Informacion del vehiculo -->
                    <div id="informacion-vehiculo" v-if="selected_budget == 61105">
                        <div class="mb-7">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Información del vehiculo
                            </span>
                        </div>
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

                    </div>

                    <!-- Buttons -->
                    <div class="mb-4 md:flex flex-row justify-center">
                        <!-- <GeneralButton @click="updateModel()" color="bg-orange-700  hover:bg-orange-800" text="Actualizar"
                            icon="add" />
                        <GeneralButton @click="saveNewModel()" color="bg-green-700  hover:bg-green-800" text="Agregar"
                            icon="add" /> -->
                        <div class="mb-4 md:mr-2 md:mb-0 px-1">
                            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
                        </div>
                    </div>
                    <div class="text-xs text-slate-500">ISRI2023</div>
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
        selectSpecificAsset(id) {
            const filteredAssets = this.specific_assets.filter(asset => asset.value === id);
            const selectedAsset = filteredAssets.find(asset => asset.value === id);
            if (selectedAsset) {
                this.selected_budget = selectedAsset.id_ccta_presupuestal;
            }
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
    watch: {
        show_modal_asset: function (value, oldValue) {
            if (value) {
                this.errors = [];
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
                if(this.modalData!=''){
                    this.selectSpecificAsset(this.modalData.id_bien_especifico)
                }else{
                    this.selected_budget = ''
                }
            }
        },
    },
};
</script>

