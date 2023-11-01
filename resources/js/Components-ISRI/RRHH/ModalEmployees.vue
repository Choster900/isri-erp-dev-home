<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <div v-if="is_loading && correct_dui"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class="bg-gray-200 rounded-lg p-1 font-semibold">
                    <span class="text-blue-800">CARGANDO...</span>
                </div>
            </div>
        </div>
        <Modal v-else :show="show_modal_employee" @close="$emit('cerrar-modal')" :modal-title="'Administracion empleados. '"
            :maxWidth="correct_dui ? '3xl' : 'lg'" :closeOutSide="false">
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
                            <input id="dui" type="text" class="p-1 mb-2 w-[75%] rounded-lg text-center"
                                placeholder="Ingresa DUI" v-model="employee.persona.dui_persona" @keyup.enter="searchDui()"
                                @input="typeDUI()">
                            <div class="flex items-center">
                                <GeneralButton class="mr-1" text="Cerrar" icon="delete" @click="$emit('cerrar-modal')" />
                                <GeneralButton class="ml-1" color="bg-blue-600 hover:bg-blue-700" text="Buscar"
                                    icon="search" @click="searchDui()" />
                            </div>
                        </div>
                    </div>

                    <!-- Page 1: Personal information -->
                    <div v-if="correct_dui && current_page == 1">
                        <div class="mb-2 md:flex flex-row justify-between">
                            <div class="md:w-1/2">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion personal
                                </span>
                            </div>
                            <div class="md:w-1/2 md:text-right">
                                <span class="font-semibold text-slate-800 text-[14px]">
                                    Pagina 1 de {{ modalData == '' ? '4' : '3' }}
                                </span>
                            </div>
                        </div>
                        <!-- First row Page1 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="primer-nombre" v-model="employee.persona.pnombre_persona" type="text"
                                    placeholder="Primer nombre"
                                    @update:modelValue="validatePersonInputs('pnombre_persona', 22, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="primer-nombre" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.persona.pnombre_persona" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="segundo-nombre" v-model="employee.persona.snombre_persona" :required="false"
                                    type="text" placeholder="Segundo nombre"
                                    @update:modelValue="validatePersonInputs('snombre_persona', 22, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="segundo-nombre" />
                                </TextInput>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="tercer-nombre" v-model="employee.persona.tnombre_persona" :required="false"
                                    type="text" placeholder="Tercer nombre"
                                    @update:modelValue="validatePersonInputs('tnombre_persona', 22, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="tercer-nombre" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- End first row Page1 -->
                        <!-- Second row Page1 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="primer-apellido" v-model="employee.persona.papellido_persona" type="text"
                                    placeholder="Primer apellido"
                                    @update:modelValue="validatePersonInputs('papellido_persona', 22, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="primer-apellido" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.persona.papellido_persona" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="segundo-apellido" v-model="employee.persona.sapellido_persona"
                                    :required="false" type="text" placeholder="Segundo apellido"
                                    @update:modelValue="validatePersonInputs('sapellido_persona', 22, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="segundo-apellido" />
                                </TextInput>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="tercer-apellido" v-model="employee.persona.tapellido_persona"
                                    :required="false" type="text" placeholder="Tercer apellido"
                                    @update:modelValue="validatePersonInputs('tapellido_persona', 22, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="tercer-apellido" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- End second row Page1 -->
                        <!-- Third row Page1 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="DUI" v-model="employee.persona.dui_persona" type="text" placeholder="DUI"
                                    @update:modelValue="validatePersonInputs('dui_persona', 10, false, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="DUI" />
                                </TextInput>
                                <InputError v-for="(item, index) in backend_errors['persona.dui_persona']" :key="index"
                                    class="mt-2" :message="item" />
                                <InputError class="mt-2" :message="errors.persona.dui_persona" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="telefono" v-model="employee.persona.telefono_persona" type="text"
                                    placeholder="Telefono" :required="false"
                                    @update:modelValue="validatePersonInputs('telefono_persona', 12, false, false, true, false)">
                                    <LabelToInput icon="personalInformation" forLabel="telefono" />
                                </TextInput>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="Correo" v-model="employee.persona.email_persona" type="email"
                                    placeholder="Correo electronico" :required="false">
                                    <LabelToInput icon="email" forLabel="Correo" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- End third row Page1 -->
                        <!-- Fourth row Page1 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-4 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Genero <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect v-model="employee.persona.id_genero" :options="select_options.gender"
                                        :searchable="true" :placeholder="is_loading ? 'Cargando...' : 'Seleccione Genero'"
                                        :disabled="is_loading" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.persona.id_genero" />
                            </div>
                            <div class="w-2/3 ml-2"></div>
                        </div>
                        <!-- End fourth row Page1 -->
                        <!-- Birth information -->
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion de nacimiento
                                </span>
                            </div>
                        </div>
                        <!-- Fifth row Page1 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha de nacimiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr
                                        class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="employee.persona.fecha_nac_persona"
                                        :placeholder="'Seleccione fecha'" />
                                </div>
                                <InputError class="mt-2" :message="errors.persona.fecha_nac_persona" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio nacimiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect v-model="employee.persona.id_municipio"
                                        :options="select_options.municipality" :searchable="true"
                                        :placeholder="is_loading ? 'Cargando...' : 'Seleccione Municipio'"
                                        :disabled="is_loading" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.persona.id_municipio" />
                            </div>
                            <div class="w-1/3"></div>
                        </div>
                        <!-- End fifth row Page1 -->
                    </div>

                    <!-- Page 2: Personal details -->
                    <div v-if="correct_dui && current_page == 2">
                        <!-- Family information -->
                        <div class="mb-2 md:flex flex-row justify-between">
                            <div class="md:w-1/2">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion familiar
                                </span>
                            </div>
                            <div class="md:w-1/2 md:text-right">
                                <span class="font-semibold text-slate-800 text-[14px]">
                                    Pagina 2 de {{ modalData == '' ? '4' : '3' }}
                                </span>
                            </div>
                        </div>
                        <!-- First row Page2 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Estado civil <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione estado civil"
                                        v-model="employee.persona.id_estado_civil" id="estado-civil"
                                        :options="select_options.marital_status" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.persona.id_estado_civil" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-conyuge" v-model="employee.persona.nombre_conyuge_persona"
                                    :required="false" type="text" placeholder="Nombre cónyuge"
                                    @update:modelValue="validatePersonInputs('nombre_conyuge_persona', 90, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-conyuge" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- Second row Page2 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-madre" v-model="employee.persona.nombre_madre_persona" type="text"
                                    :required="false" placeholder="Nombre - madre (completo)"
                                    @update:modelValue="validatePersonInputs('nombre_madre_persona', 90, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-madre" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.persona.nombre_madre_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-padre" v-model="employee.persona.nombre_padre_persona" type="text"
                                    :required="false" placeholder="Nombre - padre (completo)"
                                    @update:modelValue="validatePersonInputs('nombre_padre_persona', 90, false, true, false, false, true)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-padre" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- Professional information -->
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion profesional
                                </span>
                            </div>
                        </div>
                        <!-- Third row Page2 -->
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Nivel educativo <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione nivel educativo"
                                        v-model="employee.persona.id_nivel_educativo"
                                        :options="select_options.educational_level" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.persona.id_nivel_educativo" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Profesion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione profesion" v-model="employee.persona.id_profesion"
                                        :options="select_options.occupation" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.persona.id_profesion" />
                            </div>
                        </div>
                        <!-- Residence information -->
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion de residencia
                                </span>
                            </div>
                        </div>
                        <!-- Fourth row Page2 -->
                        <div class="mb-10 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio residencia <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione municipio"
                                        v-model="employee.persona.residencias[0].id_municipio"
                                        :options="select_options.municipality" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.persona.residencias.id_municipio" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                                <TextInput id="residence" v-model="employee.persona.residencias[0].direccion_residencia"
                                    type="text" placeholder="Direccion"
                                    @update:modelValue="validateResidenceInputs('direccion_residencia', 250)">
                                    <LabelToInput icon="standard" forLabel="residence" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.persona.residencias.direccion_residencia" />
                            </div>
                        </div>
                        <!-- End fourth row Page2 -->
                    </div>

                    <!-- Page 3: All employee information -->
                    <div v-if="correct_dui && current_page == 3">
                        <!-- Employee information -->
                        <div class="mb-2 md:flex flex-row justify-between">
                            <div class="md:w-1/2">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion del empleado
                                </span>
                            </div>
                            <div class="md:w-1/2 md:text-right">
                                <span class="font-semibold text-slate-800 text-[14px]">
                                    Pagina 3 de {{ modalData == '' ? '4' : '3' }}
                                </span>
                            </div>
                        </div>
                        <!-- First row Page3 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tipo pension <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione tipo" v-model="employee.id_tipo_pension"
                                        :options="select_options.pension_type" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.id_tipo_pension" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="nup_empleado" v-model="employee.nup_empleado" type="text" placeholder="NUP"
                                    @update:modelValue="validateEmployeeInputs('nup_empleado', 10, true, false)">
                                    <LabelToInput icon="standard" forLabel="nup_empleado" />
                                </TextInput>
                                <InputError v-for="(item, index) in backend_errors['nup_empleado']" :key="index"
                                    class="mt-2" :message="item" />
                                <InputError class="mt-2" :message="errors.nup_empleado" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="isss-empleado" v-model="employee.isss_empleado" type="text"
                                    placeholder="Numero ISSS"
                                    @update:modelValue="validateEmployeeInputs('isss_empleado', 20, true, false)">
                                    <LabelToInput icon="standard" forLabel="isss-empleado" />
                                </TextInput>
                                <InputError v-for="(item, index) in backend_errors['isss_empleado']" :key="index"
                                    class="mt-2" :message="item" />
                                <InputError class="mt-2" :message="errors.isss_empleado" />
                            </div>
                        </div>
                        <!-- End first row Page3 -->
                        <!-- Second row Page3 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Titulo profesional <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione titulo profesional"
                                        v-model="employee.id_titulo_profesional"
                                        :options="select_options.professional_title" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.id_titulo_profesional" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha de contratacion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr :disabled="employee.id_empleado == '' ? false : true"
                                        class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="employee.fecha_contratacion_empleado"
                                        :placeholder="'Seleccione fecha'" />
                                </div>
                                <InputError class="mt-2" :message="errors.fecha_contratacion_empleado" />
                            </div>
                        </div>
                        <!-- End second row Page3 -->
                        <!-- Third row Page3 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="email1" v-model="employee.email_institucional_empleado" :required="false"
                                    type="email" placeholder="Email institucional"
                                    @update:modelValue="validateEmployeeInputs('email_institucional_empleado', 90)">
                                    <LabelToInput icon="email" forLabel="email1" />
                                </TextInput>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="email2" v-model="employee.email_alternativo_empleado" :required="false"
                                    type="email" placeholder="Email alternativo"
                                    @update:modelValue="validateEmployeeInputs('email_alternativo_empleado', 90)">
                                    <LabelToInput icon="email" forLabel="email2" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- End third row Page3 -->
                        <!-- Bank details -->
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion bancaria
                                </span>
                            </div>
                        </div>
                        <!-- Fourth row Page3 -->
                        <div class="mb-20 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Banco
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione banco" v-model="employee.id_banco"
                                        :options="select_options.bank" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="bank-account" v-model="employee.cuenta_banco_empleado" type="text"
                                    placeholder="Numero de cuenta de banco" :required="false"
                                    @update:modelValue="validateEmployeeInputs('cuenta_banco_empleado', 20, true, false)">
                                    <LabelToInput icon="standard" forLabel="bank-account" />
                                </TextInput>
                            </div>
                        </div>
                        <!-- End fourth row Page3 -->
                    </div>

                    <!-- Page 4: All employee information -->
                    <div v-if="correct_dui && current_page == 4">
                        <!-- Employee information -->
                        <div class="mb-2 md:flex flex-row justify-between">
                            <div class="md:w-1/2">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion de contratacion
                                </span>
                            </div>
                            <div class="md:w-1/2 md:text-right">
                                <span class="font-semibold text-slate-800 text-[14px]">
                                    Página 4 de 4
                                </span>
                            </div>
                        </div>
                        <!-- First row Page4 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Dependencia <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione dependencia" v-model="employee.dependency_id"
                                        :options="select_options.dependencies" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.dependency_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Plaza <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione Plaza" v-model="employee.job_position_id"
                                        :options="select_options.job_positions" :searchable="true"
                                        @change="setSalaryLimits($event)" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.job_position_id" />
                            </div>
                        </div>
                        <!-- End first row Page4 -->
                        <!-- Second row Page4 -->
                        <div class="mb-10 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <TextInput id="salary" v-model="employee.salary" type="text"
                                    :placeholder="selectedJobPosition ? 'Salario ($' + lower_salary_limit + ' - $' + upper_salary_limit + ')' : 'Salario (Seleccione plaza)'"
                                    :disabled="!selectedJobPosition"
                                    @update:modelValue="validateEmployeeInputs('salary', 7, false, true)">
                                    <LabelToInput icon="money" forLabel="salary" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.salary" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <TextInput id="account" v-model="employee.account" type="text" placeholder="Partida"
                                    @update:modelValue="validateEmployeeInputs('account', 3, true, false)">
                                    <LabelToInput icon="standard" forLabel="account" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.account" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <TextInput id="subaccount" v-model="employee.subaccount" type="text"
                                    placeholder="Subpartida"
                                    @update:modelValue="validateEmployeeInputs('subaccount', 3, true, false)">
                                    <LabelToInput icon="standard" forLabel="subaccount" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.subaccount" />
                            </div>
                        </div>
                        <!-- End second row Page4 -->
                    </div>

                    <!-- buttons -->
                    <div class="flex items-center ml-1 mt-2">
                        <div class="flex" :class="correct_dui ? 'justify-end w-1/2' : 'justify-center w-full'">
                            <GeneralButton v-if="current_page == 1 && correct_dui" class="mr-1" text="Cancelar" icon="delete"
                                @click="$emit('cerrar-modal')" />

                            <button v-if="current_page != 1"
                                class="mr-1 flex items-center bg-gray-600 hover:bg-gray-700 text-white pl-2 pr-3 py-1.5 text-center mb-2 rounded"
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
                        <div v-if="correct_dui">
                            <button v-if="validateButton"
                                class="ml-1 flex items-center bg-blue-600 hover:bg-blue-700 text-white pl-3 pr-2 py-1.5 text-center mb-2 rounded"
                                @click="goToNextPage(current_page)">
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
                            <GeneralButton v-if="modalData != '' && current_page === 3" @click="updateEmployee()"
                                color="bg-orange-700 hover:bg-orange-800" text="Actualizar" icon="update" class="ml-1" />
                            <GeneralButton v-if="current_page === 4 && modalData == ''" @click="storeNewEmployee()"
                                color="bg-green-700 hover:bg-green-800" text="Guardar" icon="add" class="ml-1" />
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
    },
    created() { },
    data: function (data) {
        return {
            is_loading: false,
            selectedJobPosition: false,
            lower_salary_limit: '',
            upper_salary_limit: '',
            correct_dui: false,
            found_person: false,
            current_page: 1,
            backend_errors: [],
            errors: {
                persona: {
                    residencias: {}
                }
            },
            //errors:[],
            employee: {
                id_empleado: '',
                id_tipo_pension: '',
                isss_empleado: '',
                nup_empleado: '',
                id_titulo_profesional: '',
                fecha_contratacion_empleado: '',
                email_institucional_empleado: '',
                email_alternativo_empleado: '',
                id_banco: '',
                cuenta_banco_empleado: '',
                //Fields for 'PlazaAsignada'
                dependency_id: '',
                work_area_id: '',
                job_position_id: '',
                salary: '',
                account: '',
                subaccount: '',
                //Until here
                persona: {
                    id_persona: '',
                    dui_persona: '',
                    pnombre_persona: '',
                    snombre_persona: '',
                    tnombre_persona: '',
                    papellido_persona: '',
                    sapellido_persona: '',
                    tapellido_persona: '',
                    id_genero: '',
                    telefono_persona: '',
                    id_estado_civil: '',
                    id_municipio: '',
                    nombre_conyuge_persona: '',
                    nombre_madre_persona: '',
                    nombre_padre_persona: '',
                    fecha_nac_persona: '',
                    id_nivel_educativo: '',
                    id_profesion: '',
                    email_persona: '',
                    residencias: [
                        {
                            id_municipio: '',
                            direccion_residencia: ''
                        }
                    ]
                },
            },
            select_options: {
                marital_status: [],
                gender: [],
                municipality: [],
                educational_level: [],
                occupation: [],
                pension_type: [],
                bank: [],
                professional_title: [],
                dependencies: [],
                job_positions: []
            },
            config: {
                altInput: true,
                //static: true,
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
        };
    },
    methods: {
        //Function to validate data entry
        validatePersonInputs(field, limit, text, upper_case, phone_number, dui, full_name) {
            // Limit the length of the input
            if (this.employee['persona'][field].length > limit) {
                this.employee['persona'][field] = this.employee['persona'][field].substring(0, limit);
            }

            // Remove non-letter characters using regular expression
            if (text) {
                this.employee['persona'][field] = this.employee['persona'][field].replace(/[^a-zA-Z\u00C0-\u00FF\u00f1\u00d1\u00E4\u00EB\u00EF\u00F6\u00FC]/g, '');
            }

            if (full_name) {
                this.employee['persona'][field] = this.employee['persona'][field].replace(/[^a-zA-Z\u00C0-\u00FF\u00f1\u00d1\u00E4\u00EB\u00EF\u00F6\u00FC\s]/g, '');
            }

            if (upper_case) {
                this.employee['persona'][field] = this.employee['persona'][field].toUpperCase();
            }

            if (phone_number) {
                var x = this.employee['persona'][field].replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})/);
                this.employee['persona'][field] = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
            }

            if (dui) {
                var x = this.employee['persona'][field].replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
                this.employee['persona'][field] = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
            }

        },
        validateResidenceInputs(field, limit) {
            if (this.employee['persona']['residencias'][0][field].length > limit) {
                this.employee['persona']['residencias'][0][field] = this.employee['persona']['residencias'][0][field].substring(0, limit);
            }
        },
        validateEmployeeInputs(field, limit, number, amount) {
            if (this.employee[field].length > limit) {
                this.employee[field] = this.employee[field].substring(0, limit);
            }
            if (number) {
                this.employee[field] = this.employee[field].replace(/[^0-9]/g, '');
            }
            if (amount) {
                let x = this.employee[field].replace(/^\./, '').replace(/[^0-9.]/g, '')
                this.employee[field] = x
                const regex = /^(\d+)?([.]?\d{0,2})?$/
                if (!regex.test(this.employee[field])) {
                    this.employee[field] = this.employee[field].match(regex) || x.substring(0, x.length - 1)
                }
            }
        },
        typeDUI() {
            var x = this.employee.persona.dui_persona.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.employee.persona.dui_persona = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
        },
        searchDui() {
            if (this.employee.persona.dui_persona == "") {
                this.showToast(toast.warning, "Debe digitar el numero de DUI.");
            } else {
                axios.get('/search-person-by-dui', {
                    params: {
                        dui: this.employee.persona.dui_persona
                    }
                })
                    .then((response) => {
                        const persona = response.data.person
                        if (persona === "") {
                            this.correct_dui = true
                        } else {
                            if (persona.id_empleado) {
                                this.correct_dui = false
                                this.showToast(toast.info, "La persona con este dui ya esta registrada como empleado.");
                            } else {
                                this.correct_dui = true
                                this.employee.persona = { ...persona };
                                if (this.employee.persona.residencias.length == 0) {
                                    var array = { id_residencia: '', id_municipio: '', direccion_residencia: '' }
                                    this.employee.persona.residencias.push(array)
                                }
                            }
                        }
                    })
                    .catch((errors) => {
                        if (errors.response.data.logical_error) {
                            this.showToast(toast.error, errors.response.data.logical_error);
                        } else {
                            this.manageError(errors, this)
                        }
                    })
            }
        },
        goToNextPage(page) {
            const fieldsPerson = {
                pnombre_persona: 'primer nombre',
                papellido_persona: 'primer apellido',
                dui_persona: 'dui',
                id_genero: 'genero',
                fecha_nac_persona: 'fecha nacimiento',
                id_municipio: 'municipio nacimiento',
                id_estado_civil: 'estado civil',
                id_nivel_educativo: 'nivel educativo',
                id_profesion: 'profesion',
            };
            const fieldsResidence = {
                id_municipio: 'municipio residencia',
                direccion_residencia: 'direccion residencia',
            };
            if (page === 1) {
                const requiredFieldsPage1 = [
                    'pnombre_persona',
                    'papellido_persona',
                    'dui_persona',
                    'id_genero',
                    'fecha_nac_persona',
                    'id_municipio'
                ];
                let has_errors_page1 = false;
                requiredFieldsPage1.forEach(field => {
                    if (this.employee['persona'][field]) {
                        this.errors['persona'][field] = '';
                    } else {
                        has_errors_page1 = true
                        this.errors['persona'][field] = `El campo ${fieldsPerson[field]} es obligatorio.`;
                    }
                });
                if (!has_errors_page1) {
                    this.current_page++;
                } else {
                    this.showErrorAlert()
                }
            }
            if (page === 2) {
                const requiredFieldsPersonPage2 = [
                    'id_estado_civil',
                    'id_nivel_educativo',
                    'id_profesion',
                ];
                const requiredFieldsResidencePage2 = [
                    'id_municipio',
                    'direccion_residencia',
                ];
                let has_errors_page2 = false
                requiredFieldsPersonPage2.forEach(field => {
                    if (this.employee['persona'][field]) {
                        this.errors['persona'][field] = '';
                    } else {
                        has_errors_page2 = true
                        this.errors['persona'][field] = `El campo ${fieldsPerson[field]} es obligatorio.`;
                    }
                });
                requiredFieldsResidencePage2.forEach(field => {
                    if (this.employee['persona']['residencias'][0][field]) {
                        this.errors['persona']['residencias'][field] = '';
                    } else {
                        has_errors_page2 = true
                        this.errors['persona']['residencias'][field] = `El campo ${fieldsResidence[field]} es obligatorio.`;
                    }
                });

                if (!has_errors_page2) {
                    this.current_page++
                } else {
                    this.showErrorAlert()
                }
            }
            if (page === 3) {
                if (this.validatePage3()) {
                    this.current_page++
                } else {
                    this.showErrorAlert()
                }
            }
        },
        validatePage3() {
            const fieldsEmployee = {
                id_tipo_pension: 'tipo pension',
                nup_empleado: 'numero nup',
                isss_empleado: 'numero isss',
                id_titulo_profesional: 'titulo profesional',
                fecha_contratacion_empleado: 'fecha contratacion',
            };

            const requiredFieldsEmployee = [
                'id_tipo_pension',
                'nup_empleado',
                'isss_empleado',
                'id_titulo_profesional',
                'fecha_contratacion_empleado'
            ];

            let has_errors_page3 = false;
            requiredFieldsEmployee.forEach(field => {
                if (this.employee[field]) {
                    this.errors[field] = '';
                } else {
                    has_errors_page3 = true
                    this.errors[field] = `El campo ${fieldsEmployee[field]} es obligatorio.`;
                }
            });
            if (!has_errors_page3) {
                return true
            } else {
                return false
            }
        },
        validatePage4() {
            const fieldsJobInformation = {
                dependency_id: 'dependencia',
                job_position_id: 'plaza',
                salary: 'salario',
                account: 'partida',
                subaccount: 'subpartida',
            };

            const requiredFieldsJobInformation = [
                'dependency_id',
                'job_position_id',
                'salary',
                'account',
                'subaccount'
            ];

            let has_errors_page4 = false;

            requiredFieldsJobInformation.forEach(field => {
                if (this.employee[field]) {
                    this.errors[field] = '';
                    if (field === 'salary') {
                        const salary = parseFloat(this.employee[field]);
                        const lowerLimit = parseFloat(this.lower_salary_limit);
                        const upperLimit = parseFloat(this.upper_salary_limit);
                        if (salary < lowerLimit || salary > upperLimit) {
                            has_errors_page4 = true;
                            this.errors[field] = `El salario debe estar en el rango.`;
                        }
                    }
                } else {
                    has_errors_page4 = true;
                    this.errors[field] = `El campo ${fieldsJobInformation[field]} es obligatorio.`;
                }
            });

            if (!has_errors_page4) {
                return true;
            } else {
                return false;
            }
        },
        goToPreviousPage() {
            this.current_page--
        },
        async getSelectsEmployeeModal() {
            try {
                this.is_loading = true;  // Activar el estado de carga
                const response = await axios.get("/get-selects-options-employee");
                this.select_options.marital_status = response.data.marital_status
                this.select_options.municipality = response.data.municipality
                this.select_options.gender = response.data.gender
                this.select_options.educational_level = response.data.educational_level
                this.select_options.occupation = response.data.occupation
                this.select_options.bank = response.data.bank
                this.select_options.professional_title = response.data.professional_title
                this.select_options.pension_type = response.data.pension_type
                this.select_options.dependencies = response.data.dependencies
                this.select_options.job_positions = response.data.job_positions
            } catch (errors) {
                this.manageError(errors, this)
            } finally {
                this.is_loading = false;  // Desactivar el estado de carga
            }
        },
        storeNewEmployee() {
            if (this.validatePage4()) {
                this.$swal.fire({
                    title: '¿Está seguro de guardar el empleado?',
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, Guardar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            axios
                                .post("/store-employee", this.employee)
                                .then((response) => {
                                    this.showToast(toast.success, response.data.mensaje);
                                    this.$emit("get-table");
                                    this.$emit("cerrar-modal");
                                })
                                .catch((errors) => {
                                    if (errors.response.status === 422) {
                                        this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                                    } else {
                                        this.manageError(errors, this)
                                        this.$emit("cerrar-modal");
                                    }
                                });
                        }
                    });
            } else {
                this.showErrorAlert()
            }
        },
        updateEmployee() {
            if (this.validatePage3()) {
                this.$swal.fire({
                    title: '¿Está seguro de actualizar empleado?',
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, Actualizar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            axios
                                .post("/update-employee", this.employee)
                                .then((response) => {
                                    this.showToast(toast.success, response.data.mensaje);
                                    this.$emit("get-table");
                                    this.$emit("cerrar-modal");
                                })
                                .catch((errors) => {
                                    if (errors.response.status === 422) {
                                        console.log(errors.response.data.errors);
                                        if (errors.response.data.logical_error) {
                                            this.showToast(toast.error, errors.response.data.logical_error);
                                            this.$emit("get-table");
                                            this.$emit("cerrar-modal");
                                        } else {
                                            this.backend_errors = errors.response.data.errors
                                            if (this.backend_errors['persona.dui_persona']) {
                                                this.current_page = 1
                                            }
                                            this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                                        }
                                    } else {
                                        this.manageError(errors, this)
                                        this.$emit("cerrar-modal");
                                    }
                                });
                        }
                    });
            } else {
                this.showErrorAlert()
            }
        },
        setSalaryLimits(job_position_id) {
            const selectedJob = this.select_options.job_positions.find(value => value.value === job_position_id);
            selectedJob ? this.lower_salary_limit = selectedJob.salario_base_plaza : this.lower_salary_limit = ''
            selectedJob ? this.upper_salary_limit = selectedJob.salario_tope_plaza : this.upper_salary_limit = ''
            selectedJob ? this.employee.work_area_id = selectedJob.id_lt : this.employee.work_area_id = ''
            selectedJob ? this.selectedJobPosition = true : this.selectedJobPosition = false
            !selectedJob ? this.employee.salary = '' : ''
        },
        loadOptions() {
            this.select_options.gender = []
            this.select_options.marital_status = []
            this.select_options.educational_level = []
            this.select_options.municipality = []
            this.select_options.occupation = []
            this.select_options.pension_type = []
            this.select_options.bank = []
            this.select_options.professional_title = []
            this.select_options.dependencies = []
            this.select_options.job_positions = []
            this.getSelectsEmployeeModal()
        },
        recursivelySetEmptyStrings(obj) {
            Object.keys(obj).forEach(key => {
                if (typeof obj[key] === 'object' && obj[key] !== null) {
                    this.recursivelySetEmptyStrings(obj[key]);
                } else {
                    obj[key] = '';
                }
            });
        },
        showErrorAlert() {
            this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
        }
    },
    watch: {
        show_modal_employee: function (value, oldValue) {
            if (value) {
                this.current_page = 1
                this.backend_errors = []
                this.recursivelySetEmptyStrings(this.errors);
                this.loadOptions()
                if (this.modalData != '') {
                    this.correct_dui = true
                    this.employee = JSON.parse(JSON.stringify(this.modalData));
                } else {
                    this.correct_dui = false
                    this.recursivelySetEmptyStrings(this.employee);
                }

            }
        },
    },
    computed: {
        validateButton() {
            if (this.current_page === 3) {
                if (this.modalData == '') {
                    return true
                } else {
                    return false
                }
            } else {
                if (this.current_page === 4) {
                    return false
                } else {
                    return true
                }
            }
        },
    }
};
</script>
