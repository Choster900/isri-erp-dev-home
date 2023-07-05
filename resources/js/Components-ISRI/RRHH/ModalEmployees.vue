<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <Modal :show="show_modal_employee" @close="$emit('cerrar-modal')" :modal-title="'Administracion empleados. '"
            :maxWidth="correct_dui ? '3xl' : 'lg'">
            <div class="px-5 py-4">
                <div class="text-sm">

                    <div v-if="!correct_dui" class="mb-4 md:flex flex-row justify-center">
                        <div class="text-center">
                            <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                Ingrese DUI
                            </span>
                        </div>
                    </div>
                    <!-- Search dui -->
                    <div v-if="!correct_dui" class="flex justify-center">
                        <div class="flex flex-col items-center w-full">
                            <input id="dui" type="text" class="p-1 mb-2 w-[75%] rounded-lg" placeholder="Ingresa DUI"
                                v-model="employee.person_info.dui" @keyup.enter="searchDui()" @input="typeDUI()">
                            <div class="flex justify-center">
                                <button
                                    class="ml-1.5 btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                                    @click="searchDui()">Buscar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Page 1: Personal information -->
                    <div v-if="correct_dui && current_page == 1">
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion Personal
                                </span>
                            </div>
                        </div>
                        <!-- First row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="primer-nombre" v-model="employee.person_info.pnombre_persona" type="text"
                                    placeholder="Primer nombre">
                                    <LabelToInput icon="personalInformation" forLabel="primer-nombre" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.pnombre_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="segundo-nombre" v-model="employee.person_info.snombre_persona"
                                    :required="false" type="text" placeholder="Segundo nombre">
                                    <LabelToInput icon="personalInformation" forLabel="segundo-nombre" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.snombre_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="tercer-nombre" v-model="employee.person_info.tnombre_persona"
                                    :required="false" type="text" placeholder="Tercer nombre">
                                    <LabelToInput icon="personalInformation" forLabel="tercer-nombre" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.tnombre_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                        </div>
                        <!-- End first row -->
                        <!-- Second row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="primer-apellido" v-model="employee.person_info.papellido_persona" type="text"
                                    placeholder="Primer apellido">
                                    <LabelToInput icon="personalInformation" forLabel="primer-apellido" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.papellido_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="segundo-apellido" v-model="employee.person_info.sapellido_persona"
                                    :required="false" type="text" placeholder="Segundo apellido">
                                    <LabelToInput icon="personalInformation" forLabel="segundo-apellido" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.sapellido_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="tercer-apellido" v-model="employee.person_info.tapellido_persona"
                                    :required="false" type="text" placeholder="Tercer apellido">
                                    <LabelToInput icon="personalInformation" forLabel="tercer-apellido" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.tapellido_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                        </div>
                        <!-- End second row -->
                        <!-- Third row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="telefono" v-model="employee.person_info.telefono_persona" type="text"
                                    placeholder="Telefono" @update:modelValue="typePhoneNumber()">
                                    <LabelToInput icon="personalInformation" forLabel="telefono" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.telefono_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="DUI" v-model="employee.person_info.dui" type="text" placeholder="DUI"
                                    @update:modelValue="typeDUI()">
                                    <LabelToInput icon="personalInformation" forLabel="DUI" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.dui" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="Correo" v-model="employee.person_info.email_persona" type="email"
                                    placeholder="Correo electronico">
                                    <LabelToInput icon="email" forLabel="Correo" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.email_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                        </div>
                        <!-- End third row -->
                        <!-- Fourth row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-4 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Genero <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Genero" v-model="employee.person_info.id_genero"
                                        :options="select_options.gender" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_genero" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="w-2/3 ml-2"></div>
                        </div>
                        <!-- End fourth row -->
                    </div>

                    <!-- Page 2: Personal details -->
                    <div v-if="correct_dui && current_page == 2">
                        <!-- Complementary information -->
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion Complementaria
                                </span>
                            </div>
                        </div>
                        <!-- First row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio residencia <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Municipio" v-model="employee.person_info.id_municipio"
                                        :options="select_options.municipality" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_municipio" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Estado civil <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Estado civil" v-model="employee.person_info.id_estado_civil"
                                        id="estado-civil" :options="select_options.marital_status" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_estado_civil" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="nombre-conyuge" v-model="employee.person_info.nombre_conyuge_persona"
                                    :required="false" type="text" placeholder="Nombre cónyuge">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-conyuge" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.nombre_conyuge_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                        </div>
                        <!-- Second row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha de nacimiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr
                                        class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="employee.person_info.fecha_nac_persona"
                                        :placeholder="'Seleccione fecha nacimiento'" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.fecha_nac_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="nombre-madre" v-model="employee.person_info.nombre_madre_persona" type="text"
                                    :required="false" placeholder="Nombre - madre (completo)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-madre" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.nombre_madre_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="nombre-padre" v-model="employee.person_info.nombre_padre_persona"
                                    type="text" :required="false"
                                    placeholder="Nombre - padre (completo)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-padre" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- Professional information -->
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion Profesional
                                </span>
                            </div>
                        </div>
                        <!-- Third row -->
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Nivel educativo <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Nivel educativo" v-model="employee.person_info.id_nivel_educativo"
                                        :options="select_options.educational_level" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_nivel_educativo" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-4 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Nivel profesion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Nivel profesion" v-model="employee.person_info.id_profesion"
                                        :options="select_options.occupation" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_profesion" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="w-1/3"></div>
                        </div>
                    </div>

                    <div class="flex items-center ml-1 mt-2">
                        <div class="flex" :class="correct_dui ? 'justify-end w-1/2' : 'justify-center w-full'">
                            <GeneralButton v-if="current_page == 1" class="mr-1" text="Cancelar" icon="delete"
                                @click="$emit('cerrar-modal')" />

                            <button v-if="current_page != 1"
                                class="flex items-center bg-gray-600 hover:bg-gray-700 text-white pl-2 pr-3 py-1.5 text-center mb-2 rounded"
                                @click="goToPreviousPage()">
                                <svg width="20px" height="20px" viewBox="-3 0 32 32" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    fill="#ffffff" stroke="#ffffff">
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
                                <span class="ml-1 px-1 py-2.5 text-base text-gray-100 border-l-2 border-gray-100"></span>
                                <div class="text-[12px]">ANTERIOR</div>
                            </button>
                        </div>
                        <div v-if="correct_dui" class="w-1/2">
                            <button
                                class="ml-1 flex items-center bg-blue-600 hover:bg-blue-700 text-white pl-3 pr-2 py-1.5 text-center mb-2 rounded"
                                @click="goToNextPage()">
                                <div class="text-[12px]">SIGUIENTE</div>
                                <span
                                    class="ml-1 pl-1 pr-0 py-2.5 text-base text-gray-100 border-l-2 border-gray-100"></span>
                                <svg width="20px" height="20px" viewBox="-3 0 32 32" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    fill="#ffffff" stroke="#ffffff">
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
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    props: {
        modalData: {
            type: Array,
            default: [],
        },
        show_modal_employee: {
            type: Boolean,
            default: false,
        },
        select_options: {
            type: Array,
            default: []
        },
    },
    created() { },
    data: function (data) {
        return {
            correct_dui: false,
            found_employee: false,
            found_person: false,
            person_info: [],
            current_page: 1,
            errors: {
                person_info: []
            },
            employee: {
                person_info: {
                    id: '',
                    dui: '',
                    pnombre_persona: '',
                    snombre_persona: '',
                    tnombre_persona: '',
                    papellido_persona: '',
                    sapellido_persona: '',
                    tapellido_persona: '',
                    id_genero: '',
                    id_estado_civil: '',
                    nombre_conyuge_persona: '',
                    nombre_madre_persona: '',
                    nombre_padre_persona:'',
                    fecha_nac_persona:''
                },
            },
            config: {
                altInput: true,
                //static: true,
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
        };
    },
    methods: {
        //Function to validate data entry
        validateInput(field, limit, upper_case) {
            if (this.income_concept[field] && this.income_concept[field].length > limit) {
                this.income_concept[field] = this.income_concept[field].substring(0, limit);
            }
            if (upper_case) {
                this.toUpperCase(field)
            }
        },
        toUpperCase(field) {
            //Converts field to uppercase
            this.income_concept[field] = this.income_concept[field].toUpperCase()
        },
        typeDUI() {
            var x = this.employee.person_info.dui.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.employee.person_info.dui = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
        },
        searchDui() {
            if (this.employee.person_info.dui == "") {
                toast.warning(
                    "Debe digitar el numero de dui.",
                    {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    })
            } else {
                axios.get('/search-person-by-dui', {
                    params: {
                        dui: this.employee.person_info.dui
                    }
                })
                    .then((response) => {
                        this.correct_dui = true
                        let person_info = response.data.person
                        if (person_info === "") {
                            this.found_employee = false
                            this.found_person = false
                            this.$swal.fire({
                                title: 'Information',
                                text: 'Person and employee have not yet been created.',
                                icon: 'warning',
                                timer: 5000
                            })
                        } else {
                            this.found_person = true
                            if (person_info.id_empleado) {
                                this.found_employee = true
                                this.$swal.fire({
                                    title: 'Information',
                                    text: 'Person and employee have already been created.',
                                    icon: 'warning',
                                    timer: 5000
                                })
                            } else {
                                this.found_employee = false
                            }
                        }
                    })
                    .catch((errors) => {
                        if (errors.response.data.logical_error) {
                            toast.error(
                                errors.response.data.logical_error,
                                {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                }
                            );
                        } else {
                            let msg = this.manageError(errors)
                            this.$swal.fire({
                                title: 'Operación cancelada',
                                text: msg,
                                icon: 'warning',
                                timer: 5000
                            })
                        }
                    })
            }
        },
        goToNextPage() {
            this.current_page++
        },
        goToPreviousPage() {
            this.current_page--
        }

    },
    watch: {
        showModalIncome: function (value, oldValue) {
            if (value) {
                this.errors = [];
                this.income_concept.income_concept_id = this.modalData.id_concepto_ingreso;
                this.income_concept.dependency_id = this.modalData.id_dependencia;
                this.income_concept.budget_account_id = this.modalData.id_ccta_presupuestal;
                this.income_concept.name = this.modalData.nombre_concepto_ingreso;
                this.income_concept.financing_source_id = this.modalData.id_proy_financiado;
                this.income_concept.detail = this.modalData.detalle_concepto_ingreso;
            }
        },
    },
};
</script>
