<script setup>
import ModalBasicVue from "@/Components-ISRI/AllModal/ModalBasic.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <ModalBasicVue :modalOpen="show_modal_acq_doc" @close-modal="$emit('cerrar-modal')"
            :title="'Administración de Documentos de Adquisicion.'" maxWidth="4xl">
            <div class="px-5 py-4">
                <div class="text-sm">
                    <!-- Page 1 -->
                    <div id="page1" v-show="currentPage === 1">
                        <div class="mb-2 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                Información general
                            </span>
                            <span class="font-semibold text-slate-800 text-[14px]">
                                Página 1 de 2
                            </span>
                        </div>
                        <!-- First row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
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
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="doc-number" v-model="acq_doc.number" :value="acq_doc.number" type="text"
                                    placeholder="Numero documento" @update:modelValue="validateGeneralInput('number', 20)">
                                    <LabelToInput icon="objects" forLabel="doc-number" />
                                </TextInput>
                                <InputError v-for="(item, index) in backend_errors.number" :key="index" class="mt-2"
                                    :message="item" />
                                <InputError class="mt-2" :message="errors.number" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="mngm-number" v-model="acq_doc.management_number"
                                    :value="acq_doc.management_number" type="text" placeholder="Numero gestion"
                                    @update:modelValue="validateGeneralInput('management_number', 20)">
                                    <LabelToInput icon="objects" forLabel="mngm-number" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.management_number" />
                            </div>
                        </div>
                        <!-- Second row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
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
                        </div>
                        <!-- Third row -->
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha adjudicacion<span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr
                                        class="peer text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="acq_doc.award_date"
                                        :placeholder="'Seleccione fecha adjudicacion'" />
                                </div>
                                <InputError class="mt-2" :message="errors.award_date" />
                            </div>
                            <div class="mb-4 md:mx-2 md:mb-0 basis-1/2">
                                <TextInput id="mngm-number" v-model="acq_doc.award_number" :value="acq_doc.award_number"
                                    type="text" placeholder="Numero adjudicacion" :required="false"
                                    @update:modelValue="validateGeneralInput('award_number', 20)">
                                    <LabelToInput icon="objects" forLabel="mngm-number" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.award_number" />
                            </div>
                        </div>
                    </div>
                    <!-- Page 2 -->
                    <div id="page2" v-show="currentPage === 2">
                        <div class="mb-2 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                Detalles
                            </span>
                            <span class="font-semibold text-slate-800 text-[14px]">
                                Página 2 de 2
                            </span>
                        </div>
                        <!-- Item inputs -->
                        <!-- First row -->
                        <div class="mb-4 md:flex flex-row justify   Detalles-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="array_item.financing_source_id" :options="financing_sources"
                                        placeholder="Seleccion financiamiento" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="item_errors.financing_source_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="commt-number" v-model="array_item.commitment_number"
                                    :value="array_item.commitment_number" type="text" placeholder="Numero compromiso(s)"
                                    @update:modelValue="validateItemInput('commitment_number', 20, false, commitment_number = true)">
                                    <LabelToInput icon="objects" forLabel="commt-number" />
                                </TextInput>
                                <InputError
                                    v-for="(item, index) in backend_errors['items.' + array_item.index + '.commitment_number']"
                                    :key="index" class="mt-2" :message="item" />
                                <InputError class="mt-2" :message="item_errors.commitment_number" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="amount" v-model="array_item.amount" :value="array_item.amount" type="text"
                                    placeholder="Monto compromiso"
                                    @update:modelValue="validateItemInput('amount', 11, monto = true)">
                                    <LabelToInput icon="money" forLabel="amount" />
                                </TextInput>
                                <InputError class="mt-2" :message="item_errors.amount" />
                            </div>
                        </div>
                        <!-- Second row -->
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <TextInput id="item-name" v-model="array_item.name" :value="array_item.name" type="text"
                                    placeholder="Nombre Item"
                                    @update:modelValue="validateItemInput('name', 250, monto = false)">
                                    <LabelToInput icon="standard" forLabel="item-name" />
                                </TextInput>
                                <InputError class="mt-2" :message="item_errors.name" />
                            </div>
                        </div>
                        <!-- third row -->
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full" style="border: none; background-color: transparent;">
                            <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                                Administrador de documento
                            </label>
                            <textarea v-model="array_item.contract_manager" id="cotract-manager" name="contract-manager"
                                class="resize-none w-full h-10 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                @input="validateItemInput('contract_manager', limit = 500)">
                            </textarea>
                            <InputError class="mt-2" :message="item_errors.contract_manager" />
                        </div>
                        <!-- Add item button -->
                        <div class="flex justify-center mb-2 mt-1">
                            <div class="flex items-center">
                                <GeneralButton class="mr-1" :text="new_item ? 'Limpiar' : 'Cancelar'"
                                    color="bg-red-600 hover:bg-red-700" icon="delete" @click="cleanArrayItem()" />
                                <GeneralButton class="ml-1" color="bg-blue-600 hover:bg-blue-700"
                                    :text="new_item ? 'Agregar' : 'Actualizar'" icon="add" @click="addItem()" />
                            </div>
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
                                        <tr v-if="item.deleted == false"
                                            :class="[
                                                'hover:bg-[#141414]/10',
                                                'border-b-2',
                                                item.selected ? 'bg-green-300 hover:bg-green-400' :
                                                    index_errors.includes(index) ? 'bg-red-300 hover:bg-red-400' : '']">
                                            <td class="text-center">{{ item.commitment_number }}</td>
                                            <td class="text-center">{{ item.name }}</td>
                                            <td class="text-center">{{ showFinancingSource(item.financing_source_id) }}</td>
                                            <td class="text-center">${{ item.amount }}</td>
                                            <td class="text-center">
                                                <div class="space-x-1">
                                                    <button class="text-slate-400 hover:text-slate-500 rounded-full"
                                                        @click="editItem(item, index)">
                                                        <span class="sr-only">Edit</span>
                                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                                                            <path
                                                                d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button class="text-rose-500 hover:text-rose-600 rounded-full"
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
                    </div>
                    <!-- Buttons to navigate -->
                    <div class="flex justify-center mt-5">
                        <div class="flex items-center mr-1">
                            <button v-if="currentPage != 1"
                                class="flex items-center bg-gray-600 hover:bg-gray-700 text-white pl-2 pr-3 py-1.5 text-center mb-2 rounded"
                                :disabled="currentPage === 1" @click="goToPreviousPage">
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
                        <div class="flex items-center ml-1">
                            <button v-if="currentPage != 2"
                                class="flex items-center bg-blue-600 hover:bg-blue-700 text-white pl-3 pr-2 py-1.5 text-center mb-2 rounded"
                                :disabled="disabled_next_button" @click="goToNextPage">
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

                            <GeneralButton v-if="modalData != '' && currentPage === 2" @click="updateNewAcqDoc()"
                                color="bg-orange-700 hover:bg-orange-800" text="Actualizar doc." icon="update" />
                            <GeneralButton v-if="modalData == '' && currentPage === 2" @click="saveNewAcqDoc()"
                                color="bg-green-700 hover:bg-green-800" text="Guardar doc." icon="add" />
                        </div>

                    </div>
                </div>
            </div>

        </ModalBasicVue>
    </div>
</template>

<script>
export default {
    props: {
        modalData: {
            type: Array,
            default: [],
        },
        show_modal_acq_doc: {
            type: Boolean,
            default: false,
        },
        financing_sources: {
            type: Array,
            default: []
        },
        management_types: {
            type: Array,
            default: []
        },
        doc_types: {
            type: Array,
            default: []
        },
        suppliers: {
            type: Array,
            default: []
        },
    },
    created() { },
    data: function (data) {
        return {
            new_item: true,
            currentPage: 1,
            errors: [],
            backend_errors: [],
            item_errors: [],
            index_errors: [],
            acq_doc: {
                id: '',
                type_id: '',
                management_type_id: '',
                supplier_id: '',
                number: '',
                management_number: '',
                award_number: '',
                award_date: '',
                total: ''
            },
            array_item: {
                id: '',
                index: '',
                financing_source_id: '',
                commitment_number: '',
                amount: '',
                contract_manager: '',
                name: '',
                selected: false,
                deleted: false
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
        validateGeneralInput(field, limit) {
            if (this.acq_doc[field] && this.acq_doc[field].length > limit) {
                this.acq_doc[field] = this.acq_doc[field].substring(0, limit);
            }
        },
        validateItemInput(field, limit, amount, commitment_number) {
            if (this.array_item[field] && this.array_item[field].length > limit) {
                this.array_item[field] = this.array_item[field].substring(0, limit);
            }
            if (amount) {
                this.typeAmount(field)
            }
            if (field === 'commitment_number') {
                this.index_errors = []
                this.backend_errors = []
                const newValue = this.array_item.commitment_number.replace(/[^\d, ]/g, '');
                if (newValue !== this.array_item.commitment_number) {
                    this.array_item.commitment_number = newValue;
                }
            }
            if (commitment_number) {
                this.array_item.commitment_number.replace(/[^\d, ]/g, '')
            }
        },
        typeAmount(field) {
            let x = this.array_item[field].replace(/^\./, '').replace(/[^0-9.]/g, '')
            this.array_item[field] = x
            const regex = /^(\d+)?([.]?\d{0,2})?$/
            if (!regex.test(this.array_item[field])) {
                this.array_item[field] = this.array_item[field].match(regex) || x.substring(0, x.length - 1)
            }
        },
        cleanAndUpdateTotal() {
            this.updateTotal()
            this.new_item = true
            // Empty the array
            Object.keys(this.array_item).forEach(key => {
                this.array_item[key] = '';
            });
        },
        saveNewAcqDoc() {
            const all_deleted = this.acq_doc.items.every(item => item.deleted === true);
            if (all_deleted) {
                toast.warning(
                    "Debes agregar al menos un item al documento.",
                    {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    }
                );
            } else {
                this.$swal
                    .fire({
                        title: '¿Está seguro de guardar el documento de adquisicion?',
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
                                .post("/save-acq-doc", this.acq_doc)
                                .then((response) => {
                                    toast.success(response.data.mensaje, {
                                        autoClose: 3000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    });
                                    this.$emit("get-table");
                                    this.$emit("cerrar-modal");
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
                                        this.backend_errors = errors.response.data.errors;
                                        // Itera sobre las propiedades del objeto de errores
                                        for (const key in this.backend_errors) {
                                            var parts = key.split(".");
                                            if (parts.length > 1) {
                                                var index = parseInt(parts[1]);
                                                this.index_errors.push(index)
                                            } else {
                                                this.currentPage = 1
                                            }
                                        }
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
                        }
                    });
            }
        },
        updateNewAcqDoc() {
            const all_deleted = this.acq_doc.items.every(item => item.deleted === true);
            if (all_deleted) {
                toast.warning(
                    "Debes agregar al menos un item al documento.",
                    {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    }
                );
            } else {
                this.$swal
                    .fire({
                        title: '¿Está seguro de actualizar el documento de adquisicion?',
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
                                .post("/update-acq-doc", this.acq_doc)
                                .then((response) => {
                                    toast.success(response.data.mensaje, {
                                        autoClose: 3000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    });
                                    this.$emit("get-table");
                                    this.$emit("cerrar-modal");
                                })
                                .catch((errors) => {
                                    if (errors.response.status === 422) {
                                        //console.log(errors);
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
                                            this.$emit("get-table");
                                            this.$emit("cerrar-modal");
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
                                            this.backend_errors = errors.response.data.errors;
                                            // Itera sobre las propiedades del objeto de errores
                                            for (const key in this.backend_errors) {
                                                var parts = key.split(".");
                                                if (parts.length > 1) {
                                                    var index = parseInt(parts[1]);
                                                    this.index_errors.push(index)
                                                } else {
                                                    this.currentPage = 1
                                                }
                                            }
                                        }
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
                        }
                    });
            }
        },
        goToNextPage() {
            const fieldMappings = {
                type_id: 'Tipo documento',
                management_type_id: 'Tipo gestion',
                supplier_id: 'Proveedor',
                number: 'Numero documento',
                management_number: 'Numero gestion',
                award_date: 'Fecha adjudicacion',
            };

            let page_with_errors = '';

            if (this.currentPage === 1) {
                const requiredFields = [
                    'type_id',
                    'management_type_id',
                    'supplier_id',
                    'number',
                    'management_number',
                    'award_date'
                ];
                requiredFields.forEach(field => {
                    if (this.acq_doc[field]) {
                        this.errors[field] = '';
                    } else {
                        this.errors[field] = `El campo ${fieldMappings[field]} es obligatorio.`;
                    }
                });
                if (Object.values(this.errors).some(error => error !== '')) {
                    page_with_errors = 1;
                }
            }

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
        goToPreviousPage() {
            this.errors = [];
            this.currentPage--;
        },
        addItem() {
            const fieldMappingsItem = {
                financing_source_id: 'Fuente financiamiento',
                commitment_number: 'Numero compromiso',
                amount: 'Monto Compromiso',
                name: 'Nombre item',
            };
            const requiredFields = [
                'financing_source_id',
                'commitment_number',
                'amount',
                'name',
            ];
            requiredFields.forEach(field => {
                if (this.array_item[field]) {
                    this.item_errors[field] = '';
                } else {
                    this.item_errors[field] = `El campo ${fieldMappingsItem[field]} es obligatorio.`;
                }
            });
            const errors = Object.values(this.item_errors);
            if (errors.every(error => error === '')) {
                const { id, index, financing_source_id, commitment_number, amount, contract_manager, name } = this.array_item;
                const parsedAmount = parseFloat(amount);
                const formattedAmount = parsedAmount.toFixed(2);
                const arrayInsert = {
                    id: id ? id : '',
                    index: index ? index : '',
                    financing_source_id: financing_source_id ? financing_source_id : '',
                    commitment_number: commitment_number ? commitment_number : '',
                    amount: formattedAmount ? formattedAmount : '',
                    contract_manager: contract_manager ? contract_manager : '',
                    name: name ? name : '',
                    selected: false,
                    deleted: false
                };
                if (this.acq_doc.items.some(item => item.commitment_number === commitment_number)) {
                    const index_to_compare = this.acq_doc.items.findIndex(item => item.commitment_number === commitment_number)
                    if (this.acq_doc.items[index_to_compare].selected) {
                        Object.assign(this.acq_doc.items[index_to_compare], arrayInsert);
                        this.cleanAndUpdateTotal()
                    } else {
                        if (this.acq_doc.items[index_to_compare].deleted) {
                            Object.assign(this.acq_doc.items[index_to_compare], arrayInsert);
                            this.cleanAndUpdateTotal()
                        } else {
                            toast.warning(
                                "El numero de compromiso " + commitment_number + " ya ha sido agregado.",
                                {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                }
                            );
                        }
                    }
                } else {
                    if (this.acq_doc.items.some(item => item.id === id)) {
                        Object.assign(this.acq_doc.items[index], arrayInsert)
                    } else {
                        this.acq_doc.items.push(arrayInsert)
                    }
                    this.cleanAndUpdateTotal()
                }
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
        },
        editItem(item, index) {
            this.new_item = false
            this.item_errors = []
            const itemToClean = this.acq_doc.items.find(item => item.selected);
            if (itemToClean) {
                itemToClean.selected = false;
            }
            this.array_item.id = item.id
            this.array_item.name = item.name
            this.array_item.commitment_number = item.commitment_number
            this.array_item.amount = item.amount
            this.array_item.contract_manager = item.contract_manager
            this.array_item.index = index
            this.array_item.financing_source_id = item.financing_source_id
            this.array_item.deleted = false
            this.array_item.selected = true
            this.acq_doc.items[index].selected = true
        },
        deleteItem(index) {
            this.$swal.fire({
                title: 'Eliminar item.',
                text: "¿Estas seguro?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#4B5563',
                confirmButtonText: 'Si, Eliminar.'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (this.acq_doc.items[index].selected) {
                        // Empty the array
                        Object.keys(this.array_item).forEach(key => {
                            this.array_item[key] = '';
                        });
                    }
                    if (this.acq_doc.items[index].id === '') {
                        this.acq_doc.items.splice(index, 1)
                    } else {
                        this.acq_doc.items[index].deleted = true
                        this.acq_doc.items[index].selected = false
                    }
                    this.updateTotal()
                }
            })
        },
        showFinancingSource(id) {
            let financing_source = ''
            this.financing_sources.forEach((value, index) => {
                if (value.value === id) {
                    financing_source = value.codigo_proy_financiado
                }
            })
            return financing_source
        },
        updateTotal() {
            var sum = 0;
            for (var i = 0; i < this.acq_doc.items.length; i++) {
                if (this.acq_doc.items[i].deleted == false) {
                    var amount = parseFloat(this.acq_doc.items[i].amount);
                    if (!isNaN(amount)) {
                        sum += amount;
                    }
                }
            }
            this.acq_doc.total = sum.toFixed(2);
        },
        cleanArrayItem() {
            if (this.array_item.index != -1) {
                if (this.acq_doc.items[this.array_item.index]) {
                    this.acq_doc.items[this.array_item.index].selected = false
                }
            }
            // Empty the array
            Object.keys(this.array_item).forEach(key => {
                this.array_item[key] = '';
            });
            this.new_item = true
        }
    },
    watch: {
        show_modal_acq_doc: function (value, oldValue) {
            if (value) {
                this.errors = []
                this.currentPage = 1
                this.array_item = []
                this.backend_errors = []
                this.item_errors = []
                this.index_errors = []
                this.acq_doc = Object.assign({}, {
                    id: this.modalData.id_doc_adquisicion ?? '',
                    type_id: this.modalData.id_tipo_doc_adquisicion ?? '',
                    management_type_id: this.modalData.id_tipo_gestion_compra ?? '',
                    supplier_id: this.modalData.id_proveedor ?? '',
                    number: this.modalData.numero_doc_adquisicion ?? '',
                    management_number: this.modalData.numero_gestion_doc_adquisicion ?? '',
                    award_number: this.modalData.numero_adjudicacion_doc_adquisicion ?? '',
                    award_date: this.modalData.fecha_adjudicacion_doc_adquisicion ?? '',
                    total: this.modalData.monto_doc_adquisicion ?? ''
                });
                this.acq_doc.items = []
                if (this.modalData != '') {
                    this.modalData.detalles.forEach((value, index) => {
                        var arrayInsert = {
                            id: value.id_det_doc_adquisicion,
                            index: index,
                            financing_source_id: value.id_proy_financiado,
                            commitment_number: value.compromiso_ppto_det_doc_adquisicion,
                            amount: value.monto_det_doc_adquisicion,
                            contract_manager: value.admon_det_doc_adquisicion,
                            name: value.nombre_det_doc_adquisicion,
                            selected: false,
                            deleted: false,
                        };
                        this.acq_doc.items.push(arrayInsert)
                    })
                    this.updateTotal()
                }
            }
        },
    },
    computed: {
        item_available() {
            let exist = false
            if (this.acq_doc.items) {
                for (var i = 0; i < this.acq_doc.items.length; i++) {
                    if (this.acq_doc.items[i].deleted == false) {
                        exist = true
                    }
                }
                if (exist) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
};
</script>

<style></style>