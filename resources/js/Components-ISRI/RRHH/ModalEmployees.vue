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
                                v-model="employee.person_info.dui_persona" @keyup.enter="searchDui()" @input="typeDUI()">
                            <div class="flex justify-center">
                                <button
                                    class="ml-1.5 btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                                    @click="searchDui()">Buscar</button>
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
                                    Página 1 de 4
                                </span>
                            </div>
                        </div>
                        <!-- First row Page1 -->
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
                        <!-- End first row Page1 -->
                        <!-- Second row Page1 -->
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
                        <!-- End second row Page1 -->
                        <!-- Third row Page1 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="DUI" v-model="employee.person_info.dui_persona" type="text" placeholder="DUI"
                                    @update:modelValue="typeDUI()">
                                    <LabelToInput icon="personalInformation" forLabel="DUI" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.dui_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="telefono" v-model="employee.person_info.telefono_persona" type="text"
                                    placeholder="Telefono" @update:modelValue="typePhoneNumber()">
                                    <LabelToInput icon="personalInformation" forLabel="telefono" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.telefono_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="Correo" v-model="employee.person_info.email_persona" type="email"
                                    placeholder="Correo electronico" :required="false">
                                    <LabelToInput icon="email" forLabel="Correo" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.email_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                        </div>
                        <!-- End third row Page1 -->
                        <!-- Fourth row Page1 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-4 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Genero <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione genero" v-model="employee.person_info.id_genero"
                                        :options="select_options.gender" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_genero" :key="index" class="mt-2"
                                    :message="item" />
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
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha de nacimiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr
                                        class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="employee.person_info.fecha_nac_persona"
                                        :placeholder="'Seleccione fecha'" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.fecha_nac_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio nacimiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione municipio"
                                        v-model="employee.person_info.id_municipio" :options="select_options.municipality"
                                        :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_municipio" :key="index"
                                    class="mt-2" :message="item" />
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
                                    Página 2 de 4
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
                                        v-model="employee.person_info.id_estado_civil" id="estado-civil"
                                        :options="select_options.marital_status" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_estado_civil" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-conyuge" v-model="employee.person_info.nombre_conyuge_persona"
                                    :required="false" type="text" placeholder="Nombre cónyuge">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-conyuge" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.nombre_conyuge_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                        </div>
                        <!-- Second row Page2 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-madre" v-model="employee.person_info.nombre_madre_persona" type="text"
                                    :required="false" placeholder="Nombre - madre (completo)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-madre" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.nombre_madre_persona" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-padre" v-model="employee.person_info.nombre_padre_persona" type="text"
                                    :required="false" placeholder="Nombre - padre (completo)">
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
                                        v-model="employee.person_info.id_nivel_educativo"
                                        :options="select_options.educational_level" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_nivel_educativo" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Nivel profesion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione nivel profesion"
                                        v-model="employee.person_info.id_profesion" :options="select_options.occupation"
                                        :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.id_profesion" :key="index"
                                    class="mt-2" :message="item" />
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
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio residencia <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione municipio"
                                        v-model="employee.person_info.residencias[0].id_municipio"
                                        :options="select_options.municipality" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.person_info.residencias.id_municipio"
                                    :key="index" class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                                <TextInput id="residence" v-model="employee.person_info.residencias[0].direccion_residencia"
                                    type="text" :required="false" placeholder="Direccion">
                                    <LabelToInput icon="standard" forLabel="residence" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.person_info.residencias.direccion_residencia"
                                    :key="index" class="mt-2" :message="item" />
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
                                    Página 3 de 4
                                </span>
                            </div>
                        </div>
                        <!-- First row Page3 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tipo pension <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione tipo" v-model="employee.pension_type_id"
                                        :options="select_options.pension_type" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.pension_type_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="nup" v-model="employee.nup" type="text" placeholder="NUP">
                                    <LabelToInput icon="standard" forLabel="nup" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.nup" :key="index" class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="isss" v-model="employee.isss" type="text" placeholder="Numero ISSS">
                                    <LabelToInput icon="standard" forLabel="isss" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.isss" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <!-- End first row Page3 -->
                        <!-- Second row Page3 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Titulo profesional <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione titulo profesional"
                                        v-model="employee.professional_title_id"
                                        :options="select_options.professional_title" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.professional_title_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha de contratacion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr
                                        class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="employee.date_of_hire"
                                        :placeholder="'Seleccione fecha'" />
                                </div>
                                <InputError v-for="(item, index) in errors.date_of_hire" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <!-- End second row Page3 -->
                        <!-- Third row Page3 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="email1" v-model="employee.institutional_email" :required="false" type="email"
                                    placeholder="Email institucional">
                                    <LabelToInput icon="email" forLabel="email1" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.institutional_email" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="email2" v-model="employee.alternative_email" :required="false" type="email"
                                    placeholder="Email alternativo">
                                    <LabelToInput icon="email" forLabel="email2" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.alternative_email" :key="index" class="mt-2"
                                    :message="item" />
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
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Banco
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione banco" v-model="employee.bank_id"
                                        :options="select_options.bank" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.bank_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="bank-account" v-model="employee.bank_account" type="text"
                                    placeholder="Numero de cuenta de banco" :required="false">
                                    <LabelToInput icon="standard" forLabel="bank-account" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.bank_account" :key="index" class="mt-2"
                                    :message="item" />
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
                                    Informacion de la plaza
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
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Dependencia <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione dependencia" v-model="employee.dependency_id"
                                        :options="select_options.dependencies" :searchable="true"
                                        @select="getUplt($event)" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.dependency_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Unidad presupuestaria - Linea de trabajo <span
                                        class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione Uplt" v-model="employee.uplt_id"
                                        :options="select_options.uplt" :searchable="true" @select="getActivities($event)" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.uplt_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <!-- End first row Page4 -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Actividad <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione Actividad" v-model="employee.activity_id"
                                        :options="select_options.activities" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.activity_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione Fuente Financiamiento"
                                        v-model="employee.financing_source_id" :options="select_options.financing_sources"
                                        :searchable="true" @select="getJobPositions($event)" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.financing_source_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <div class="mb-4 md:flex flex-row justify-start">
                            <div class="text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion de contratacion
                                </span>
                            </div>
                        </div>
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Plaza <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione Plaza" v-model="employee.job_position_id"
                                        :options="select_options.job_positions" :searchable="true" 
                                        @select="setSalaryLimits($event)"/>
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.job_position_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/2">
                                <TextInput id="salary" v-model="employee.salary" type="text" 
                                :placeholder="'Salario($'+lower_salary_limit+' - $'+upper_salary_limit+')'">
                                    <LabelToInput icon="money" forLabel="salary" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.salary" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tipo contratacion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione tipo" v-model="employee.contract_type_id"
                                        :options="select_options.contract_types" :searchable="true" 
                                        />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.contract_type_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <TextInput id="account" v-model="employee.account" type="text" 
                                placeholder="Partida">
                                    <LabelToInput icon="standard" forLabel="account" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.salary" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                <TextInput id="subaccount" v-model="employee.subaccount" type="text" 
                                placeholder="Subpartida">
                                    <LabelToInput icon="standard" forLabel="subaccount" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.salary" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                    </div>

                    <!-- buttons -->
                    <div class="flex items-center ml-1 mt-2">
                        <div class="flex" :class="correct_dui ? 'justify-end w-1/2' : 'justify-center w-full'">
                            <GeneralButton v-if="current_page == 1" class="mr-1" text="Cancelar" icon="delete"
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
                        <div v-if="correct_dui" class="w-1/2">
                            <button v-if="current_page != 4"
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
                            <!-- <GeneralButton v-if="modalData != '' && currentPage === 2 && !itemSelected"
                                    @click="updateAcqDoc()" color="bg-orange-700 hover:bg-orange-800" text="Actualizar"
                                    icon="update" class="" /> -->
                            <GeneralButton v-if="current_page === 4" @click="storeNewEmployee()"
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
            lower_salary_limit:'',
            upper_salary_limit:'',
            correct_dui: false,
            found_employee: false,
            found_person: false,
            current_page: 1,
            errors: {
                person_info: {
                    residencias: []
                }
            },
            employee: {
                id: '',
                pension_type_id: '',
                isss: '',
                nup: '',
                professional_title_id: '',
                date_of_hire: '',
                institutional_email: '',
                alternative_email: '',
                bank_id: '',
                bank_account: '',
                dependency_id: '',
                uplt_id: '',
                activity_id: '',
                person_info: {
                    id_persona: '',
                    dui_persona: '',
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
                    nombre_padre_persona: '',
                    fecha_nac_persona: '',
                    id_nivel_educativo: '',
                    id_profesion: '',
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
                uplt: [],
                activities: [],
                financing_sources: [],
                job_positions: [],
                contract_types:[]
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
            var x = this.employee.person_info.dui_persona.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.employee.person_info.dui_persona = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
        },
        searchDui() {
            if (this.employee.person_info.dui_persona == "") {
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
                        dui: this.employee.person_info.dui_persona
                    }
                })
                    .then((response) => {
                        this.correct_dui = true
                        const person_info = response.data.person
                        //console.log(response.data.person);
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
                                this.employee.person_info = { ...person_info };
                                console.log(this.employee.person_info);
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
        },
        getSelectsEmployeeModal() {
            axios.get("/get-selects-options-employee")
                .then((response) => {
                    this.select_options.marital_status = response.data.marital_status
                    this.select_options.municipality = response.data.municipality
                    this.select_options.gender = response.data.gender
                    this.select_options.educational_level = response.data.educational_level
                    this.select_options.occupation = response.data.occupation
                    this.select_options.bank = response.data.bank
                    this.select_options.professional_title = response.data.professional_title
                    this.select_options.pension_type = response.data.pension_type
                    this.select_options.dependencies = response.data.dependencies
                    this.select_options.financing_sources = response.data.financing_sources
                    this.select_options.contract_types = response.data.contract_types
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                    this.$emit("cerrar-modal");
                });
        },
        getUplt(dependency_id) {
            //console.log(dependency_id)
            axios.get("/get-uplt", { params: { dependency_id: dependency_id } })
                .then((response) => {
                    this.select_options.uplt = response.data.uplt
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                    //this.$emit("cerrar-modal");
                });
        },
        getActivities(field_work_id) {
            //console.log(dependency_id)
            axios.get("/get-institutional-activities", { params: { field_work_id: field_work_id } })
                .then((response) => {
                    this.select_options.activities = response.data.activities
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                    //this.$emit("cerrar-modal");
                });
        },
        getJobPositions(financing_source_id) {
            axios.get("/get-job-positions", { params: { financing_source_id: financing_source_id } })
                .then((response) => {
                    this.select_options.job_positions = response.data.job_positions
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                    //this.$emit("cerrar-modal");
                });
        },
        setSalaryLimits(job_position_id){
            this.select_options.job_positions.forEach((value,index) => {
                if(value.value === job_position_id){
                    this.lower_salary_limit = value.salario_base_plaza
                    this.upper_salary_limit = value.salario_tope_plaza
                }
            })
        },
        loadOptions() {
            this.select_options.gender = []
            this.select_options.marital_status = []
            this.select_options.educational_level = []
            this.select_options.municipality = []
            this.select_options.occupation = []
            this.getSelectsEmployeeModal()
        }

    },
    watch: {
        show_modal_employee: function (value, oldValue) {
            if (value) {
                this.loadOptions()
                //this.errors = [];
            }
        },
    },
};
</script>
