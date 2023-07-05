<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModal" @close="$emit('cerrar-modal')" modal-title="Designación de Beneficiario" maxWidth="2xl">
            <div class="px-5 py-4">
                <div class="mb-4  md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <label class="text-xs text-gray-500 font-normal">Issued in</label>
                        <p class="text-xs text-black font-semibold">Jul 23,2023</p>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <label class="text-sm text-gray-500 font-normal">Due in</label>
                        <p class="text-xs text-black font-semibold">Aug 23,2023</p>
                    </div>
                </div>
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <label class="text-xs text-gray-500 font-normal">Bill From</label>
                        <div class="mt-1">
                            <p class="text-xs text-black font-semibold pt-">Alex Parkinson</p>
                            <p class="text-xs text-gray-500 font-normal">3897 Hickory St, Sait Lake City Utah, United States
                                84104</p>
                        </div>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                        <label class="text-xs text-gray-500 font-normal">Bill To</label>
                        <div class="mt-1">
                            <p class="text-xs text-black font-semibold"> Musemind Digital Agency</p>
                            <p class="text-xs text-gray-500 font-normal">421 Losly River Suite 478
                                Syaney, Austata
                            </p>
                        </div>
                    </div>

                </div>
                <div class=" mt-10 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Empleado/a <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-8  flex-row-reverse">
                                    <Multiselect :allow-absent="true" :searchable="true"
                                        :resolve-on-load="false" :delay="0" :min-chars="3" :options="async (query) => {
                                            return await fetchLanguages(query)
                                        }" v-model="value" noOptionsText="<p class='text-xs'>sin requerimientos<p>"
                                        noResultsText="<p class='text-xs'>requerimiento no encontrado<p>"
                                        placeholder="Busqueda por nombre" />
                                    <LabelToInput icon="list" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Relacion de parentesco <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative  flex h-8  flex-row-reverse">
                                    <Multiselect :options="[]" :searchable="true"
                                        noOptionsText="<p class='text-xs'>sin requerimientos<p>"
                                        noResultsText="<p class='text-xs'>requerimiento no encontrado<p>" placeholder="" />
                                    <LabelToInput icon="list" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class=" border p-4 rounded-md bg-gray-200">

                    <p class="text-sm text-black font-semibold pb-5">Card Information</p>
                    <div class="mb-4 md:mr-2 md:mb-0 w-full">
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <TextInput type="text" placeholder="Nombre familiar ">
                                    <LabelToInput icon="personalInformation" forLabel="contacto-proveedor" />
                                </TextInput>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 w-full">
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <TextInput type="number" placeholder="Porcentaje Familiar" min="1" max="100">
                                    <LabelToInput icon="percent" forLabel="contacto-proveedor" />
                                </TextInput>
                            </div>
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

        showModal: {
            type: Boolean,
            default: false,
        },
    },
    data: () => ({
        value: [
            { value: 'Java', label: 'Java' },
            { value: 'JavaScript', label: 'JavaScript' },
        ]
    }),
    methods: {
        async fetchLanguages(query) {
            try {
                // Realizar una llamada asíncrona, por ejemplo a una API
                const respuesta = await axios.get('/search-people-by-name/' + query);

                // Procesar la respuesta o realizar otras operaciones necesarias
                console.log(respuesta)
                // Retornar el resultado deseado
                return respuesta.data;
            } catch (error) {
                // Manejar el error en caso de que ocurra
                console.error(error);
                throw error;
            }
        }
    },
    watch: {
        showModal: function (value, oldValue) {

        },
    },
};
</script>
