
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
        <ProcessModal v-else :show="showModalDetail" @close="$emit('cerrar-modal')" :center="true" :rounded=true
            :modal-title="'Administracion dependencias. '" maxWidth="2xl">
            <div class="px-5 py-4">
                <div class="text-sm">


                    <div class="flex items-center justify-center bg-[#1F3558] py-1.5 rounded-t-lg">
                        <svg class="text-white " width="22px" height="22px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M22 8.52V3.98C22 2.57 21.36 2 19.77 2H15.73C14.14 2 13.5 2.57 13.5 3.98V8.51C13.5 9.93 14.14 10.49 15.73 10.49H19.77C21.36 10.5 22 9.93 22 8.52ZM22 19.77V15.73C22 14.14 21.36 13.5 19.77 13.5H15.73C14.14 13.5 13.5 14.14 13.5 15.73V19.77C13.5 21.36 14.14 22 15.73 22H19.77C21.36 22 22 21.36 22 19.77ZM10.5 8.52V3.98C10.5 2.57 9.86 2 8.27 2H4.23C2.64 2 2 2.57 2 3.98V8.51C2 9.93 2.64 10.49 4.23 10.49H8.27C9.86 10.5 10.5 9.93 10.5 8.52ZM10.5 19.77V15.73C10.5 14.14 9.86 13.5 8.27 13.5H4.23C2.64 13.5 2 14.14 2 15.73V19.77C2 21.36 2.64 22 4.23 22H8.27C9.86 22 10.5 21.36 10.5 19.77Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                        <h1 class="ml-2 text-white">{{ depToShow.nombre_dependencia }}</h1>
                    </div>
                    <div class="flex w-full pb-4 border-b border-x border-gray-400 rounded-b-lg">
                        <div class="mt-3 w-full mx-4 relative">
                            <p class="text-gray-700 text-center text-[14px] font-semibold">INFORMACION GENERAL</p>
                            <div class="mt-2 flex">
                                <div class="w-[60%]">
                                    <p class="text-gray-500 text-[14px]">Empleado a cargo</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.jefatura ? depToShow.jefatura.pnombre_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.snombre_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.tnombre_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.papellido_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.sapellido_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.tapellido_persona : '' }}
                                    </p>
                                </div>
                                <div class="w-[40%]">
                                    <p class="text-gray-500 text-[14px]">Centro</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.centro_atencion ?
                                            depToShow.centro_atencion.codigo_dependencia : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 flex">
                                <div class="w-[25%]">
                                    <p class="text-gray-500 text-[14px]">Codigo</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.codigo_dependencia }}
                                    </p>
                                </div>
                                <div class="w-[35%]">
                                    <p class="text-gray-500 text-[14px]">Telefono</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.telefono_dependencia ?? 'Sin registro.' }}
                                    </p>
                                </div>
                                <div class="w-[40%]">
                                    <p class="text-gray-500 text-[14px]">Email</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.email_dependencia ?? 'Sin registro.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 flex">
                                <div class="w-full">
                                    <p class="text-gray-500 text-[14px]">Direccion</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.direccion_dependencia ? depToShow.direccion_dependencia : 'Sin registro.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 flex">
                                <div class="w-full">
                                    <p class="text-gray-500 text-[14px]">Dependencia jerarquica</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.dependencia_superior ? depToShow.dependencia_superior.nombre_dependencia + ' ('+depToShow.dependencia_superior.codigo_dependencia+')' : '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 flex">
                                <div class="w-[50%]">
                                    <p class="text-gray-500 text-[14px]">Fecha de registro</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.fecha_reg_dependencia ? moment(depToShow.fecha_reg_dependencia).format('DD/MM/YYYY') : 'Sin registro' }}
                                    </p>
                                </div>
                                <div class="w-[50%]">
                                    <p class="text-gray-500 text-[14px]">Fecha ultima modificación</p>
                                    <p class="text-navy-700 text-[13px]">
                                        {{ depToShow.fecha_mod_dependencia ? moment(depToShow.fecha_mod_dependencia).format('DD/MM/YYYY') : 'Sin registro.' }}
                                    </p>
                                </div>
                            </div>
                            
                        </div>
                    </div>






                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import ProcessModal from "@/Components-ISRI/AllModal/ProcessModal.vue";
import InputError from "@/Components/InputError.vue";
import { useDependencia } from '@/Composables/RRHH/Dependencia/useDependencia.js';
import { ref, toRefs, onMounted, } from 'vue';
import "vue3-toastify/dist/index.css";
import moment from 'moment';

export default {
    components: { ProcessModal, InputError },
    props: {
        showModalDetail: {
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
        const { dependencyId } = toRefs(props)
        const {
            isLoadingRequest,
            depToShow,
            fetchData,
        } = useDependencia(context);

        onMounted(
            async () => {
                await fetchData(dependencyId.value)
            }
        )

        return {
            isLoadingRequest,
            depToShow,
            moment
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
