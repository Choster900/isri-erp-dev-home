
<template>
    <div class="m-1.5">
        <div v-if="isLoadingRequest"
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
        <Modal v-else :show="showModalDependencies" @close="$emit('cerrar-modal')"
            :modal-title="'Administracion dependencias. '" maxWidth="3xl">
            <div class="px-5 py-4">
                <div class="text-sm">
                    <!-- First row -->
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                            <input-text :required="true" label="Nombre dependencia" id="nombre" v-model="depInfo.depName"
                                type="text" placeholder="Nombre dependencia" :validation="{ limit: 85, upper: true }">
                            </input-text>
                            <InputError v-for="(item, index) in errors.depName" :key="index" class="mt-2" :message="item" />
                        </div>
                    </div>
                    <!-- Second row -->
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Centro de
                                atención
                                <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="font-semibold relative flex h-10 w-full flex-row-reverse ">
                                <Multiselect placeholder="Seleccione centro" v-model="depInfo.centerId"
                                    :options="mainCenters" :searchable="true" :noResultsText="'Sin resultados'" :noOptionsText="'Sin resultados'" 
                                    @change="depInfo.parentId = ''"/>
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#001c48]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                            <InputError v-for="(item, index) in errors.centerId" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Dependencia
                                jerarquica <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-10 w-full flex-row-reverse">
                                <Multiselect v-model="depInfo.parentId" :options="depFilter" :searchable="true"
                                    placeholder="Seleccione centro" :disabled="depInfo.jerarquia === 0" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#001c48]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Empleado a cargo
                                <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="font-semibold relative flex h-10 w-full flex-row-reverse ">
                                <Multiselect placeholder="Digite nombre empleado" v-model="depInfo.personId"
                                    :options="load && depInfo.id ? baseOptions : employees" :searchable="true"
                                    :loading="isLoadingEmployee" :internal-search="false"
                                    @search-change="handleSearchChange" :clear-on-search="true"
                                    :noResultsText="'Sin resultados'" :noOptionsText="'Sin resultados'" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg class="w-5 h-5 text-[#001c48] dark:text-gray-600" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <rect width="24" height="24" fill="white"></rect>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9999 6C9.79077 6 7.99991 7.79086 7.99991 10C7.99991 12.2091 9.79077 14 11.9999 14C14.209 14 15.9999 12.2091 15.9999 10C15.9999 7.79086 14.209 6 11.9999 6ZM17.1115 15.9974C17.8693 16.4854 17.8323 17.5491 17.1422 18.1288C15.7517 19.2966 13.9581 20 12.0001 20C10.0551 20 8.27215 19.3059 6.88556 18.1518C6.18931 17.5723 6.15242 16.5032 6.91351 16.012C7.15044 15.8591 7.40846 15.7251 7.68849 15.6097C8.81516 15.1452 10.2542 15 12 15C13.7546 15 15.2018 15.1359 16.3314 15.5954C16.6136 15.7102 16.8734 15.8441 17.1115 15.9974Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <InputError v-for="(item, index) in errors.personId" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <input-text label="Correo" iconName="email" id="email" v-model="depInfo.email" type="text"
                                placeholder="Correo dependencia" :validation="{ limit: 35 }">
                            </input-text>
                            <InputError v-for="(item, index) in errors.email" :key="index" class="mt-2" :message="item" />
                        </div>
                    </div>
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Empleado a cargo
                                <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="font-semibold relative flex h-10 w-full flex-row-reverse ">
                                <Multiselect placeholder="Digite nombre empleado" v-model="depInfo.personId"
                                    :options="load && depInfo.id ? baseOptions : employees" :searchable="true"
                                    :loading="isLoadingEmployee" :internal-search="false"
                                    @search-change="handleSearchChange" :clear-on-search="true"
                                    :noResultsText="'Sin resultados'" :noOptionsText="'Sin resultados'" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg class="w-5 h-5 text-[#001c48] dark:text-gray-600" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <rect width="24" height="24" fill="white"></rect>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9999 6C9.79077 6 7.99991 7.79086 7.99991 10C7.99991 12.2091 9.79077 14 11.9999 14C14.209 14 15.9999 12.2091 15.9999 10C15.9999 7.79086 14.209 6 11.9999 6ZM17.1115 15.9974C17.8693 16.4854 17.8323 17.5491 17.1422 18.1288C15.7517 19.2966 13.9581 20 12.0001 20C10.0551 20 8.27215 19.3059 6.88556 18.1518C6.18931 17.5723 6.15242 16.5032 6.91351 16.012C7.15044 15.8591 7.40846 15.7251 7.68849 15.6097C8.81516 15.1452 10.2542 15 12 15C13.7546 15 15.2018 15.1359 16.3314 15.5954C16.6136 15.7102 16.8734 15.8441 17.1115 15.9974Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <InputError v-for="(item, index) in errors.personId" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <input-text label="Correo" iconName="email" id="email" v-model="depInfo.email" type="text"
                                placeholder="Correo dependencia" :validation="{ limit: 35 }">
                            </input-text>
                            <InputError v-for="(item, index) in errors.email" :key="index" class="mt-2" :message="item" />
                        </div>
                    </div>
                    <!-- Third row -->
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <input-text :required="true" label="Código dependencia" iconName="code" id="code"
                                v-model="depInfo.code" type="text" placeholder="Código dependencia"
                                :validation="{ limit: 16, upper: true }">
                            </input-text>
                            <InputError v-for="(item, index) in errors.code" :key="index" class="mt-2" :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <input-text label="Teléfono" iconName="oldPhone" id="phone" v-model="depInfo.phoneNumber"
                                type="text" placeholder="Número de teléfono" :validation="{ limit: 9, phoneNumber: true }">
                            </input-text>
                            <InputError v-for="(item, index) in errors.phoneNumber" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                            <input-text label="Direccion" iconName="address" id="address" v-model="depInfo.address"
                                type="text" placeholder="Escriba dirección" :validation="{ limit: 100 }">
                            </input-text>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <GeneralButton v-if="depInfo.id != ''" @click="update()" color="bg-orange-700  hover:bg-orange-800"
                            text="Actualizar" icon="update" />
                        <GeneralButton v-else @click="save()" color="bg-green-700  hover:bg-green-800" text="Agregar"
                            icon="add" />
                        <div class="mb-4 md:mr-2 md:mb-0 px-1">
                            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
                        </div>
                    </div>

                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { useValidateInput } from "@/Composables/General/useValidateInput.js";
import { useDependencia } from '@/Composables/RRHH/Dependencia/useDependencia.js';
import { ref, toRefs, onMounted, } from 'vue';
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import "vue3-toastify/dist/index.css";

export default {
    components: { Modal, InputError, InputText },
    props: {
        showModalDependencies: {
            type: Boolean,
            default: false
        },
        dependencyId: {
            type: Number,
            default: 0
        }
    },
    emits: ["cerrar-modal", "get-table"],
    setup(props, context) {
        const load = ref(true)
        const urlToSearch = ref('/search-employee')

        const { dependencyId } = toRefs(props)

        const {
            isLoadingRequest,
            baseOptions,
            mainCenters,
            depInfo,
            isLoadingEmployee,
            employees,
            asyncFindEmployee,
            errors,
            depFilter,
            getInfoForModalDependencias,
            fetchData,
            storeDependency,
            updateDependency
        } = useDependencia(context);

        const handleSearchChange = async (query) => {
            load.value = false
            if (query != '') {
                await asyncFindEmployee(query);
            }
        }

        const save = async () => {
            await storeDependency(depInfo.value, '/store-dependency')
        }

        const update = async () => {
            await updateDependency(depInfo.value, '/update-dependency')
        }

        onMounted(
            async () => {
                await fetchData(dependencyId.value)
            }
        )

        return {
            isLoadingRequest,
            depInfo,
            mainCenters,
            isLoadingEmployee,
            employees,
            baseOptions,
            load,
            errors,
            urlToSearch,
            depFilter,
            handleSearchChange,
            getInfoForModalDependencias,
            storeDependency,
            updateDependency,
            save,
            update,
        };
    },
};
</script>

<style>
/* Customize the loader's appearance using Tailwind CSS classes or your custom styles */
.loader {
    border-top-color: #3498db;
    border-left-color: #3498db;
}
</style>
