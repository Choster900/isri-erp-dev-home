
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
        <Modal v-else :show="showModalBoss" @close="$emit('cerrar-modal')" :modal-title="'Cambiar empleado a cargo. '"
            maxWidth="xl">
            <div class="px-5 py-4">
                <div class="text-sm">
                    <!-- Second row -->
                    <div class="mb-4 md:flex flex-row justify-items-start py-4">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Empleado a cargo
                                <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="font-semibold relative flex h-10 w-full flex-row-reverse ">
                                <Multiselect placeholder="Digite nombre empleado" v-model="depInfo.personId"
                                    :options="load && depInfo.id ? baseOptions : employees" :searchable="true"
                                    :loading="isLoadingEmployee" :internal-search="false" :filter-results="false"
                                    @search-change="handleSearchChange" :clear-on-search="true"
                                    :noResultsText="'Sin resultados'" :noOptionsText="'Escriba para buscar...'" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#001c48]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                            <InputError v-for="(item, index) in errors.personId" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <GeneralButton v-if="depInfo.id != ''" @click="change()" color="bg-orange-700  hover:bg-orange-800"
                            text="Actualizar" icon="update" />
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
import { useDependencia } from '@/Composables/RRHH/Dependencia/useDependencia.js';
import { ref, toRefs, onMounted, } from 'vue';
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import "vue3-toastify/dist/index.css";

export default {
    components: { Modal, InputError, InputText },
    props: {
        showModalBoss: {
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
            depInfo,
            isLoadingEmployee,
            employees,
            asyncFindEmployee,
            errors,
            getInfoForModalDependencias,
            changeBoss
        } = useDependencia(context);

        const handleSearchChange = async (query) => {
            load.value = false
            await asyncFindEmployee(query);
        }

        const change = async () => {
            await changeBoss(depInfo.value, '/change-dep-boss')
        }

        onMounted(
            async () => {
                await getInfoForModalDependencias(dependencyId.value)
            }
        )

        return {
            isLoadingRequest,
            depInfo,
            isLoadingEmployee,
            employees,
            baseOptions,
            load,
            errors,
            urlToSearch,
            handleSearchChange,
            getInfoForModalDependencias,
            change
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
