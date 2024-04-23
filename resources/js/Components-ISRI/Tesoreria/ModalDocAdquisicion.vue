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
        <Modal v-else :show="show_modal_acq_doc" @close="$emit('cerrar-modal')" :closeOutSide="false"
            modal-title="Administración de Documentos de Adquisicion." maxWidth="3xl">
            <div class="px-5 py-4">
                <div class="text-sm">
                    <!-- Page 1 -->
                    <div id="page1" v-show="currentPage === 1">
                        <div class="mb-2 md:flex flex-row justify-between">
                            <div class="md:w-1/2">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Informacion general
                                </span>
                            </div>
                            <div class="md:w-1/2 md:text-right">
                                <span class="font-semibold text-slate-800 text-[14px]">
                                    Página 1 de 2
                                </span>
                            </div>
                        </div>

                        <!-- First row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tipo Documento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="acq_doc.type_id" :options="doc_types"
                                        placeholder="Seleccione documento" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.type_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                                <TextInput id="doc-number" v-model="acq_doc.number" type="text"
                                    placeholder="Numero documento"
                                    @update:modelValue="handleValidation('number', { limit: 20, upper: true })">
                                    <LabelToInput icon="objects" forLabel="doc-number" />
                                </TextInput>
                                <InputError v-for="(item, index) in backend_errors.number" :key="index" class="mt-2"
                                    :message="item" />
                                <InputError class="mt-2" :message="errors.number" />
                            </div>
                            <div class="mb-4 md:mr-0 md:mb-0 basis-1/4">
                                <TextInput id="mngm-number" v-model="acq_doc.management_number" type="text"
                                    placeholder="Numero gestion"
                                    @update:modelValue="handleValidation('management_number', { limit: 20 })">
                                    <LabelToInput icon="objects" forLabel="mngm-number" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.management_number" />
                            </div>
                            <div class="mb-4 md:mx-2 md:mb-0 basis-1/4">
                                <TextInput id="mngm-number" v-model="acq_doc.award_number" type="text"
                                    placeholder="Numero adjudicacion" :required="false"
                                    @update:modelValue="handleValidation('award_number', { limit: 20 })">
                                    <LabelToInput icon="objects" forLabel="mngm-number" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.award_number" />
                            </div>
                        </div>
                        <!-- Second row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Proveedor <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="acq_doc.supplier_id" :options="suppliers"
                                        placeholder="Seleccione proveedor" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.supplier_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tipo Gestión <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="acq_doc.management_type_id" :options="management_types"
                                        placeholder="Seleccione gestion" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.management_type_id" />
                            </div>
                            <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha adjudicacion<span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr
                                        class="font-semibold peer text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="acq_doc.award_date"
                                        :placeholder="'Seleccione fecha adjudicacion'" />
                                </div>
                                <InputError class="mt-2" :message="errors.award_date" />
                            </div>
                        </div>
                        <!-- Third row -->
                        <!-- !WORKING ON IT -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Proceso compra <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="acq_doc.procesoCompraId" :options="procesoCompra"
                                        @select="onSelectMultiSelectProcesoCompra($event)"
                                        placeholder="Seleccione proveedor" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <!-- !MODIFICAR -->
                                <!-- <InputError class="mt-2" :message="errors.supplier_id" /> -->
                                <!--    {{ procesoCompra }} -->
                                <!--        tipo: {{ tipoProcesoCompraValue }} -->
                            </div>

                        </div>

                        <div class="mb-2 md:flex flex-row justify-between">
                            <div class="md:w-1/2">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Administradores de documento
                                </span>
                            </div>
                        </div>
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Empleados <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-auto min-h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="acq_doc.employees" :options="employees"
                                        placeholder="Seleccione empleado" @change="selectEmployees($event)" mode="tags"
                                        :searchable="true" :classes="{
            tagsSearch: 'absolute inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base font-sans box-border w-full',
            tags: 'flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 min-w-0 rtl:pl-0 rtl:pr-2',
            tag: 'bg-[#002b5f] bg-opacity-90 text-white text-[12px] py-1 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap min-w-0 rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1',
        }" />
                                </div>
                                <InputError v-for="(item, index) in backend_errors.employees" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>


                        <div class="flex justify-center pt-8 pb-4">
                            <button v-if="currentPage != 2"
                                class="flex items-center bg-blue-600 hover:bg-blue-700 text-white pl-3 pr-2 py-1.5 text-center mb-2 rounded"
                                @click="goToNextPage">
                                <div class="text-[12px]">SIGUIENTE</div>
                                <span
                                    class="ml-1 pl-1 py-2.5 text-base text-gray-100 border-l-2 border-gray-100"></span>
                                <div class="w-[20px] h-[20px] text-white">
                                    <icon-m :iconName="'right-arrow-circle'">
                                    </icon-m>
                                </div>
                            </button>
                        </div>


                    </div>
                    <!-- Page 2 -->
                    <div id="page2" v-show="currentPage === 2">
                        <div class="mb-4 md:flex flex-row justify-between">
                            <div class="md:w-1/2">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Items
                                </span>
                            </div>
                            <div class="md:w-1/2 md:text-right">
                                <span class="font-semibold text-slate-800 text-[14px]">
                                    Página 2 de 2
                                </span>
                            </div>
                        </div>
                        <transition name="fade" mode="out-in">
                            <div v-if="showItemInfo" id="item-info">
                                <!-- Item inputs -->
                                <!-- First row -->
                                <div class="mb-4 md:flex flex-row justify-start">
                                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                        <label class="block mb-2 text-xs font-light text-gray-600">
                                            Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                                        </label>
                                        <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                            <Multiselect v-model="array_item.financing_source_id"
                                                :options="financing_sources" placeholder="Seleccion financiamiento"
                                                :searchable="true" />
                                            <LabelToInput icon="list" />
                                        </div>
                                        <InputError class="mt-2" :message="item_errors.financing_source_id" />
                                    </div>
                                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                        <TextInput id="commt-number" v-model="array_item.commitment_number" type="text"
                                            placeholder="Numero(s) compromiso(s)"
                                            @update:modelValue="handleValidation('commitment_number', { limit: 20, numbersCommasAndSpaces: true })">
                                            <LabelToInput icon="objects" forLabel="commt-number" />
                                        </TextInput>
                                        <InputError
                                            v-for="(item, index) in backend_errors['items.' + array_item.index + '.commitment_number']"
                                            :key="index" class="mt-2" :message="item" />
                                        <InputError class="mt-2" :message="item_errors.commitment_number" />
                                    </div>
                                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                        <TextInput id="amount" v-model="array_item.amount" type="text"
                                            placeholder="Monto detalle"
                                            @update:modelValue="handleValidation('amount', { limit: 10, amount: true })">
                                            <LabelToInput icon="money" forLabel="amount" />
                                        </TextInput>
                                        <InputError
                                            v-for="(item, index) in backend_errors['items.' + array_item.index + '.amount']"
                                            :key="index" class="mt-2" :message="item" />
                                        <InputError class="mt-2" :message="item_errors.amount" />
                                    </div>
                                </div>
                                <!-- Second row -->
                                <div class="mb-4 md:flex flex-row justify-items-start">
                                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                        <TextInput id="item-name" v-model="array_item.name" type="text"
                                            placeholder="Nombre"
                                            @update:modelValue="handleValidation('name', { limit: 250, upper:true })">
                                            <LabelToInput icon="standard" forLabel="item-name" />
                                        </TextInput>
                                        <InputError class="mt-2" :message="item_errors.name" />
                                    </div>
                                </div>
                                <!-- third row -->
                                <!-- <div class="mb-4 md:mr-2 md:mb-0 basis-full"
                                    style="border: none; background-color: transparent;">
                                    <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                                        Administrador de documento
                                    </label>
                                    <textarea v-model="array_item.contract_manager" id="cotract-manager"
                                        name="contract-manager"
                                        class="resize-none w-full h-10 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                        @input="handleValidation('contract_manager', { limit: 250 })">
                                    </textarea>
                                    <InputError class="mt-2" :message="item_errors.contract_manager" />
                                </div> -->
                                <!-- Add item button -->
                                <div class="flex justify-center mb-2 mt-1">
                                    <div class="flex items-center">
                                        <GeneralButton class="mr-1" :text="'Cancelar'"
                                            color="bg-gray-600 hover:bg-gray-700" icon="delete"
                                            @click="cleanArrayItem()" />
                                        <GeneralButton class="ml-1" color="bg-blue-600 hover:bg-blue-700"
                                            :text="new_item ? 'Agregar' : 'Actualizar'" icon="add" @click="addItem()" />
                                    </div>
                                </div>
                            </div>

                            <div v-else id="table-info">

                                <div class="my-2 flex justify-end">
                                    <button type="button" @click="showItemInfo = true"
                                        class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-2.5 py-1 text-center mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">AGREGAR
                                        ITEM</button>
                                </div>
                                <!-- Items table -->
                                <div class="tabla-modal">
                                    <table class="w-full" id="">
                                        <thead class="bg-[#1F3558] text-white">
                                            <tr class="">
                                                <th class="rounded-tl-lg w-[15%] py-1">COMPROMISO(S)</th>
                                                <th class="w-[40%]">NOMBRE</th>
                                                <th class="w-[10%]">FUENTE</th>
                                                <th class="w-[20%]">MONTO</th>
                                                <th class="rounded-tr-lg w-[15%]">ACCIONES</th>
                                            </tr>
                                        </thead>
                                        <template v-for="(item, index) in acq_doc.items" :key="index">
                                            <tbody class="text-sm divide-y divide-slate-200">
                                                <tr v-if="item.deleted == false" :class="[
            'hover:bg-[#141414]/10',
            'border-b-2',
            index_errors.includes(index) ? 'bg-red-300 hover:bg-red-400' : '']">
                                                    <td class="text-center">{{ item.commitment_number }}</td>
                                                    <td class="text-center max-w-[100px]">
                                                        {{ item.name }}
                                                    </td>
                                                    <td class="text-center">{{ item.name_financing_source }}</td>
                                                    <td class="text-center">${{ isNaN(item.amount) ? '00.00' :
            item.amount }}</td>
                                                    <td class="text-center">
                                                        <div class="space-x-1">
                                                            <button
                                                                class="text-slate-400 hover:text-slate-500 rounded-full"
                                                                @click="editItem(item, index)">
                                                                <span class="sr-only">Edit</span>
                                                                <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                                                                    <path
                                                                        d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                            <button
                                                                class="text-rose-500 hover:text-rose-600 rounded-full"
                                                                @click="deleteItem(index)">
                                                                <span class="sr-only">Delete</span>
                                                                <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                                                                    <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                                                                    <path
                                                                        d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </table>
                                </div>
                                <!-- Total amount of items -->
                                <div v-if="item_available" class="w-full flex flex-row mt-1">
                                    <div class="w-[15%]"></div>
                                    <div class="w-[50%]">
                                        <p class="text-[14px] text-right font-semibold">TOTAL DOCUMENTO</p>
                                    </div>
                                    <div class="w-[20%]">
                                        <p class="text-[14px] text-green-700 text-center font-semibold">
                                            ${{ acq_doc.total }}
                                        </p>
                                    </div>
                                    <div class="w-[15%]"></div>
                                </div>
                                <div v-else class="mt-2">
                                    <p class="text-[14px] text-black text-center font-semibold">
                                        SIN ITEMS ASIGNADOS
                                    </p>
                                </div>
                                <!-- Buttons to navigate -->
                                <div class="flex justify-center mt-5">
                                    <div class="flex items-center ml-1">
                                        <div class="flex w-1/2">
                                            <button
                                                class="flex items-center bg-gray-600 hover:bg-gray-700 text-white pl-2 pr-3 py-1.5 text-center mb-2 rounded mr-2"
                                                :disabled="currentPage === 1" @click="errors = []; currentPage--;">
                                                <div class="w-[20px] h-[20px] text-white">
                                                    <icon-m :iconName="'left-arrow-circle'">
                                                    </icon-m>
                                                </div>
                                                <span
                                                    class="ml-1 px-1 py-2.5 text-base text-gray-100 border-l-2 border-gray-100"></span>
                                                <div class="text-[12px]">ANTERIOR</div>
                                            </button>
                                        </div>

                                        <div class="md:flex flex-row justify-center">
                                            <GeneralButton v-if="docAdquisicionId > 0"
                                                @click="updateDocumentoAdquisicion(acq_doc)"
                                                color="bg-orange-700 hover:bg-orange-800" text="Actualizar"
                                                icon="update" />
                                            <GeneralButton v-if="docAdquisicionId <= 0"
                                                @click="storeDocumentoAdquisicion(acq_doc)"
                                                color="bg-green-700 hover:bg-green-800" text="Guardar" icon="add" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>

                </div>
            </div>

        </Modal>
    </div>
</template>

<script>
import { useDocumentoAdquisicion } from '@/Composables/Tesoreria/DocumentoAdquisicion/useDocumentoAdquisicion.js';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import GeneralButton from "@/Components-ISRI/ComponentsToForms/GeneralButton.vue";
import TextInput from "@/Components-ISRI/ComponentsToForms/TextInput.vue";
import LabelToInput from "@/Components-ISRI/ComponentsToForms/LabelToInput.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";


import { ref, toRefs, onMounted, } from 'vue';

import { useValidateInput } from '@/Composables/General/useValidateInput';

export default {
    components: { Modal, InputError, GeneralButton, TextInput, LabelToInput, IconM },
    props: {
        docAdquisicionId: {
            type: Number,
            default: 0,
        },
        show_modal_acq_doc: {
            type: Boolean,
            default: false,
        },
    },
    setup(props, context) {

        const { docAdquisicionId } = toRefs(props);

        const {
            isLoadingRequest, acq_doc, errors, tipoProcesoCompraValue,
            config, array_item, index_errors,
            item_errors, backend_errors, new_item, currentPage, procesoCompra,
            doc_types, management_types, financing_sources, suppliers,
            item_available, itemSelected, showItemInfo, employees, onSelectMultiSelectProcesoCompra,
            getInfoForModalDocumentoAdquisicion, storeDocumentoAdquisicion, updateDocumentoAdquisicion,
            goToNextPage, addItem, cleanArrayItem, editItem, deleteItem, selectEmployees
        } = useDocumentoAdquisicion(context);

        const {
            validateInput
        } = useValidateInput()

        const handleValidation = (input, validation) => {
            if (input === 'commitment_number' || input === 'amount' || input === 'contract_manager' || input === 'name') {
                index_errors.value = []
                backend_errors.value = []
                item_errors.value = []
                array_item.value[input] = validateInput(array_item.value[input], validation)
            } else {
                acq_doc.value[input] = validateInput(acq_doc.value[input], validation)
            }
        }

        onMounted(
            async () => {
                await getInfoForModalDocumentoAdquisicion(docAdquisicionId.value)
            }
        )

        return {
            isLoadingRequest, acq_doc, errors, docAdquisicionId, tipoProcesoCompraValue,
            config, array_item, index_errors,
            item_errors, backend_errors, new_item, currentPage, procesoCompra,
            financing_sources, doc_types, management_types, suppliers,
            item_available, itemSelected, showItemInfo, employees, onSelectMultiSelectProcesoCompra,
            storeDocumentoAdquisicion, updateDocumentoAdquisicion, handleValidation,
            goToNextPage, addItem, cleanArrayItem, editItem, deleteItem, selectEmployees
        }
    }
};
</script>

<style>
.multi-select-wrapper input {
    padding-left: 0px;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.td-data-table {
    max-width: 100px;
    white-space: nowrap;
}

.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
