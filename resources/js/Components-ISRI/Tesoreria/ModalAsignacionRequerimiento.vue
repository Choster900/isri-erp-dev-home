<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import axios from 'axios';

</script>
<template>
    <Modal modal-title="Asignando numero de requerimiento" maxWidth="6xl" :show="modalIsOpen"
        @close="$emit('close-definitive')">

        <div class="flex flex-col md:flex-row  md:space-y-0 p-3">
            <div class="flex-1 px-3 pt-3 shadow-2xl   rounded-xl shadow-black border bg-gray-100 ">
                <div class="mb-7 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                        <label class="block mb-2 text-xs font-light text-gray-600">
                            Proveedor <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative font-semibold  flex h-8  flex-row-reverse">
                            <Multiselect id="estado-civil" :options="dataForSelect.proveedor" :searchable="true"
                                v-model="filter.suppiler"
                                @select="filter.suppiler == null ? filter.suppiler = '' : filter.suppiler = filter.suppiler" />
                            <LabelToInput icon="list" />
                        </div>
                    </div>
                </div>
                <div class="mb-7 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0">
                        <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                            Filtrado de fecha [Desde - Hasta]<span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative flex h-8  md:w-auto flex-row-reverse">
                            <flat-pickr v-model="filter.rangeDate"
                                class="peer w-[250px] text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                :config="config" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>
                </div>
                <div class="mb-7 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                        <TextInput id="primer-nombre" type="text" placeholder="Saldo en IVA" v-model="filter.iva">
                            <LabelToInput icon="money" forLabel="primer-nombre" />
                        </TextInput>
                    </div>
                </div>
                <div class="mb-7 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                        <TextInput id="primer-nombre" type="text" placeholder="Saldo en ISR" v-model="filter.isr">
                            <LabelToInput icon="money" forLabel="primer-nombre" />
                        </TextInput>
                    </div>
                </div>
                <div class="mb-7 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                        <TextInput id="primer-nombre" type="text" placeholder="Saldo total" v-model="filter.monto">
                            <LabelToInput icon="money" forLabel="primer-nombre" />
                        </TextInput>
                    </div>
                </div>
                <div class="sm:flex sm:justify-end sm:items-center pt-12 pb-2">
                    <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                        <GeneralButton color="bg-[#006205]  hover:bg-[#006205]/90" text="Buscar" icon="search"
                            @click="filterQuedan()" />
                    </div>
                    <div class="grid px-2 grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                        <GeneralButton color="bg-[#7a0000]  hover:bg-[#7a0000]/90" text="Limpiar todo" icon="delete"
                            @click="dataQuedan = []; resetData()" />
                    </div>
                </div>
            </div>
            <div class="px-4 py-2 rounded-md overflow-x-auto">
                <div class="sm:flex sm:justify-between sm:items-center mb-4">
                    <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                            <label class="block text-xs font-light text-gray-600">
                                Numero de requerimiento <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative flex h-7  flex-row-reverse select-bg">
                                <Multiselect :options="numeroRequerimientoFiltrado"
                                    @input="seleccionarRequerimiento($event)" v-model="id_requerimiento_pago"
                                    :searchable="true" />
                            </div>
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full mt-4 h-5"
                            :class="(collectItems != '' && id_requerimiento_pago != '' ? 'animate-bounce' : '')">
                            <button @click="addNumber()"
                                class="inline-flex items-center justify-center px-3 h-6 text-[9pt] py-1 text-white rounded-md shadow"
                                :class="(collectItems != '' && id_requerimiento_pago != '' ? ' bg-orange-600' : ' bg-gray-600')">
                                Agregar a requerimiento
                            </button>
                        </div>

                        <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                            <table id="resumen" class="table table-bordered text-center p-0 mt-2 w-[380px]">
                                <tbody>
                                    <tr>
                                        <td class="border-2 px-3  text-[8pt]">NUMERO<br />
                                            <span class="text-green-600 text-xs">
                                                {{ infoReqForTableInfo.numeroReq != "" ? infoReqForTableInfo.numeroReq :
                                                    "0" }}
                                            </span>
                                        </td>
                                        <td class="border-2 px-3 text-[8pt]">MONTO<br />
                                            <span class="text-green-600 text-xs">$
                                                {{ infoReqForTableInfo.montoReq != "" ? infoReqForTableInfo.montoReq :
                                                    "0.00" }}
                                            </span>
                                        </td>
                                        <td class="border-2 px-3 text-[8pt]">DIFERENCIA<br />
                                            <span class="text-green-600 text-xs"
                                                v-if="infoReqForTableInfo.cantSelected === '' && infoReqForTableInfo.restanteReq === ''">
                                                $0.00
                                            </span>
                                            <span v-else
                                                :class="infoReqForTableInfo.restanteReq < infoReqForTableInfo.cantSelected ? 'text-red-600 text-xs' : 'text-green-600 text-xs'">
                                                $
                                                {{ (infoReqForTableInfo.cantSelected === '' ?
                                                    infoReqForTableInfo.restanteReq :
                                                    (parseFloat(infoReqForTableInfo.restanteReq) -
                                                        parseFloat(infoReqForTableInfo.cantSelected) || 0).toFixed(2)) }}
                                            </span>
                                        </td>

                                        <td class=" border-2 px-3 text-[8pt]">CANT. SELECT<br />
                                            <span
                                                :class="infoReqForTableInfo.restanteReq < infoReqForTableInfo.cantSelected ? 'text-red-600 text-xs' : 'text-green-600 text-xs'">$
                                                {{ infoReqForTableInfo.cantSelected != ""
                                                    ? (infoReqForTableInfo.cantSelected).toFixed(2) : "0.00" }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-2 text-[8pt] max-w-[350px] min-w-w-[350px]" colspan="4">
                                            DESCRIPCION:
                                            <span class="text-[8pt]">{{ infoReqForTableInfo.descripcionReq
                                            }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="sidebar-style-isri" style="overflow-x:auto; max-height: 435px; max-width: 800px;">
                    <table class="table-auto">
                        <thead
                            class="text-xs uppercase text-white bg-[#001b47]   border-slate-200 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 w-[1px]  rounded-tl-2xl">
                                    <div class="flex items-center">
                                        <Checkbox @click="selectAllItems()" :checked="selectAll ? true : false">
                                        </Checkbox>
                                    </div>
                                </th>
                                <th class="px-4 py-2 ">
                                    <div class="font-medium text-center text-[12px] w-[1px]">ID</div>
                                </th>
                                <th class="px-4 py-2  min-w-48 max-w-48 ">
                                    <div class="font-medium text-center w-40 text-[12px] tracking-wider">PROVEEDOR</div>
                                </th>
                                <th class="px-4 py-2 min-w-48 max-w-48 ">
                                    <div class="font-medium text-center w-40 text-[12px]">DETALLE</div>
                                </th>
                                <th class="px-4 py-2 min-w-20 max-w-20 ">
                                    <div class="font-medium text-center w-10 text-[12px]">FECHA</div>
                                </th>
                                <th class="px-4 py-2  min-w-10 max-w-10 ">
                                    <div class="font-medium text-center w-20  text-[12px]">RETENCIONES</div>
                                </th>
                                <th class="px-4 py-2 min-w-10 max-w-10 rounded-tr-2xl">
                                    <div class="font-medium text-center w-20 text-[12px]">MONTO</div>
                                </th>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody name="fade" class="text-sm text-gray-700 bg-white divide-y divide-slate-200">
                            <template v-for="quedan in dataQuedan" :key="quedan.id_quedan">

                                <transition enter-active-class="slide-out-right-enter-active"
                                    leave-active-class="slide-out-right-leave-active">
                                    <tr v-show="quedan.mostrar">
                                        <td class="px-4 py-3 w-[1px]">
                                            <div class="flex items-center">
                                                <Checkbox :value="quedan.id_quedan" :checked="selectAll ? true : false"
                                                    @update:checked="(value) => selectItem(quedan.id_quedan, value, quedan.monto_liquido_quedan)">
                                                </Checkbox>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center text-[12px]">{{ quedan.id_quedan }}</div>
                                        </td>
                                        <td class="px-2 ">
                                            <div class="text-center text-[12px] wrapTable whitespace-pre-wrap">
                                                {{ quedan.proveedor.razon_social_proveedor }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-2">
                                            <div class="max-h-[165px] overflow-y-auto scrollbar">
                                                <template v-for="(detalle, i) in quedan.detalle_quedan" :key="i">
                                                    <div class="mb-2 text-center">
                                                        <p class="text-[10pt]">
                                                            <span class="font-medium">FACTURA </span>{{
                                                                detalle.numero_factura_det_quedan
                                                            }}<br>
                                                            <span class="font-medium">MONTO:</span> ${{
                                                                (parseFloat(detalle.servicio_factura_det_quedan) || 0) +
                                                                (parseFloat(detalle.producto_factura_det_quedan) || 0) }}
                                                        </p>
                                                    </div>
                                                    <template v-if="i < quedan.detalle_quedan.length - 1">
                                                        <hr class="my-2 border-t border-gray-300">
                                                    </template>
                                                </template>
                                            </div>
                                        </td>

                                        <td class="px-2">
                                            <div class="text-center  text-[12px]">{{
                                                moment(quedan.fecha_emision_quedan).format('DD/MM/YYYY') }}
                                            </div>
                                        </td>
                                        <td class="px-2 ">
                                            <div class="text-center text-red-600 text-[12px]">
                                                - IVA: ${{ quedan.monto_iva_quedan.toLocaleString() }}<br>
                                                - ISR: ${{ quedan.monto_isr_quedan }}
                                            </div>
                                        </td>

                                        <td class="px-2">
                                            <div class="text-center  text-[12px] font-semibold text-green-600">
                                                ${{ quedan.monto_liquido_quedan }}</div>
                                        </td>
                                    </tr>

                                </transition>

                            </template>



                            <tr v-if="dataQuedan.length == 0">
                                <td colspan="8" class="pb-3 text-xs whitespace-nowrap text-center h-20">
                                    <span v-if="withoutDataInDb">SIN DATOS EN LA BASE DE DATOS...</span>
                                    <span v-else>Utilize los filtros o simplemente presione buscar.</span>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </Modal>
</template>


<script>

export default {
    emits: ['close-definitive','reload-table'], // Declara el evento personalizado que emite el componente
    props: {
        modalIsOpen: {
            type: Boolean,
            default: false,
        },
        dataForSelect: {
            type: Object,
            required: true,
        },

    },
    data: (props) => ({
        dataQuedan: [],
        collectItems: [],
        id_requerimiento_pago: '',
        numero_requerimiento: '',
        withoutDataInDb: false,
        isChecked: false,
        filter: {
            suppiler: '',
            rangeDate: '',
            iva: '',
            isr: '',
            monto: '',
        },
        infoReqForTableInfo: {
            numeroReq: '',
            montoReq: 0,
            restanteReq: 0,
            cantSelected: 0,
        },
        selectAll: false,
        config: {
            mode: 'range',
            altInput: true,
            static: true,
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
    }),
    methods: {
        filterQuedan() {
            this.dataQuedan = []
            axios.get('/filter-quedan', { params: { data: this.filter } })
                .then(res => {
                    let newDataQuedan = JSON.parse(JSON.stringify(res.data))
                    newDataQuedan = newDataQuedan.map(quedan => {
                        return {
                            ...quedan,
                            //agregando una nueva key a mi arreglo de quedans
                            mostrar: true
                        }
                    })
                    this.dataQuedan = newDataQuedan
                    this.withoutDataInDb = true



                    this.collectItems = []
                    this.id_requerimiento_pago = ''
                    this.numero_requerimiento = ''
                    this.selectAll = false
                    this.infoReqForTableInfo.numeroReq = ''
                    this.infoReqForTableInfo.montoReq = ''
                    this.infoReqForTableInfo.restanteReq = ''
                    this.infoReqForTableInfo.descripcionReq = ''
                    this.infoReqForTableInfo.cantSelected = ''

                })
                .catch(errors => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                })
        },

        selectItem(id_quedan, checked, monto_liquido_quedan) {
            checked
                ? this.collectItems.push({ id_quedan: id_quedan, checked: checked, monto_liquido_quedan: monto_liquido_quedan })
                : this.collectItems.splice(this.collectItems.findIndex(item => item.id_quedan === id_quedan), 1);

            let sum = this.collectItems.reduce((monto, element) => monto + parseFloat(element.monto_liquido_quedan), 0);
            this.infoReqForTableInfo.cantSelected = sum
        },
        selectAllItems() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                const collectIds = this.collectItems.map(item => item.id_quedan);
                const newItems = this.dataQuedan.filter(item => !collectIds.includes(item.id_quedan));
                newItems.forEach(item => {
                    this.collectItems.push({ id_quedan: item.id_quedan, checked: true, monto_liquido_quedan: item.monto_liquido_quedan });
                });
            } else {
                this.collectItems = [];
            }
            let sum = this.collectItems.reduce((monto, element) => monto + parseFloat(element.monto_liquido_quedan), 0);
            this.infoReqForTableInfo.cantSelected = sum

        },
        hiddenQuedan() {//funcion que pone en false la key mostrar para que desaparesca de la tabla
            const newDataQuedan = this.dataQuedan.map((quedan) => {
                if (this.collectItems.some((collect) => quedan.id_quedan === collect.id_quedan)) {
                    return { ...quedan, mostrar: false };
                } else {
                    return quedan;
                }
            });
            this.dataQuedan = newDataQuedan;
        },
        async addNumber() {
            if (this.collectItems != '') {
                const confirmed = await this.$swal.fire({
                    title: `<p class="text-[16px]">¿Está seguro de agregar estos quedan al requerimiento ${this.numero_requerimiento[0].label}? Recuerda que una vez asignado el quedan, podrás eliminarlo mientras no hayas realizado algún pago.</p>`,
                    icon: 'question',
                    iconHtml: "❓",
                    confirmButtonText: 'Si, Agregar',
                    confirmButtonColor: '#001b47',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                });
                if (confirmed.isConfirmed) {

                    axios.post('/add-numberRequest-quedan', {
                        itemsToAddNumber: this.collectItems,
                        numberRequest: this.id_requerimiento_pago
                    }).then((response) => {
                        toast.success(`Se agregaron los quedan al requerimiento ${this.numero_requerimiento[0].label}`, {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                        this.hiddenQuedan()
                        this.resetData()
                        this.$emit("reload-table")
                        this.collectItems = []
                        this.id_requerimiento_pago = ''
                        this.numero_requerimiento = ''
                    }).catch((errors) => {
                        if (errors.response.status === 422) {
                            toast.error(errors.response.data, {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });

                        } else {
                            let msg = this.manageError(errors);
                            this.$swal.fire({
                                title: "Operación cancelada",
                                text: msg,
                                icon: "warning",
                                timer: 5000,
                            });
                        }
                    });


                }
            } else {
                toast.error("Al parecer no has seleccionado ningun quedan", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }

        },
        seleccionarRequerimiento(event) {
            if (event !== null) {
                const opcionSeleccionada = this.dataForSelect.numeroRequerimiento.filter(item => item.value === event);
                this.numero_requerimiento = opcionSeleccionada

                let data = this.dataForSelect.numeroRequerimiento.filter(item => item.value === event)

                this.infoReqForTableInfo.numeroReq = data[0].numero_requerimiento_pago
                this.infoReqForTableInfo.montoReq = data[0].monto_requerimiento_pago
                this.infoReqForTableInfo.restanteReq = data[0].restante_requerimiento
                this.infoReqForTableInfo.descripcionReq = data[0].descripcion_requerimiento_pago
            } else {
                this.resetData()

            }

        },
        formatearMontoTotal() {
            const montoTotal = this.dataQuedan.reduce((total, quedan) => {
                return total + parseFloat(quedan.monto_liquido_quedan);
            }, 0);

            return montoTotal.toLocaleString(); // Agrega comas como separadores de miles
        },
        resetData() {
           // this.dataQuedan = []
            this.collectItems = []
            this.id_requerimiento_pago = ''
            this.numero_requerimiento = ''
            this.filter.suppiler = ''
            this.filter.rangeDate = ''
            this.filter.iva = ''
            this.filter.iva = ''
            this.filter.isr = ''
            this.filter.monto = ''
            this.selectAll = false
            this.infoReqForTableInfo.numeroReq = ''
            this.infoReqForTableInfo.montoReq = ''
            this.infoReqForTableInfo.restanteReq = ''
            this.infoReqForTableInfo.descripcionReq = ''
            this.infoReqForTableInfo.cantSelected = ''
        },
    },
    computed: {
        numeroRequerimientoFiltrado: function () {
            return this.dataForSelect?.numeroRequerimiento?.filter(item => item.id_estado_req_pago === 1) || [];
        }
    },
    watch: {
        modalIsOpen: function (newValue, oldValue) {

            this.resetData()
            this.dataQuedan = []
        },
    },
}
</script>
<style scoped>
.wrapTable,
.wrapTable2 {
    width: 100%;
    white-space: pre-line;
}

.overflow-x-auto table tbody tr:hover {
    background-color: rgb(226 232 240 / 0.5)
}


.sidebar-style-isri {
    overflow: auto;
    scrollbar-width: none;
}

.sidebar-style-isri::-webkit-scrollbar {
    width: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 2s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.slide-out-right-enter-active {
    animation: slide-out-right-enter 1s ease-out both;
}

.slide-out-right-leave-active {
    animation: slide-out-right-leave 1s ease-out both;
}

@keyframes slide-out-right-enter {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out-right-leave {
    from {
        transform: translateX(0);
        opacity: 1;
    }

    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>

