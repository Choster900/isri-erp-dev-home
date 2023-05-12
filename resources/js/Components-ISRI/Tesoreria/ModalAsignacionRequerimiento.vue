<script setup>
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import axios from 'axios';

</script>
<template>
    <ModalBasicVue title="Asignando numero de requerimiento" id="scrollbar-modal" maxWidth="6xl"
        :modalOpen="scrollbarModalOpen" @close-modal="$emit('close-definitive')">

        <div class="flex flex-col md:flex-row  md:space-y-0  ">
            <div class="flex-1 px-3 pt-3 shadow-sm  bg-white shadow-[#001b47] border-r border-r-gray-500/20">
                <div class="mb-7 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                        <label class="block mb-2 text-xs font-light text-gray-600">
                            Proveedor <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative font-semibold  flex h-8  flex-row-reverse">
                            <Multiselect id="estado-civil" :options="dataForSelect.proveedor" :searchable="true"
                                v-model="filter.suppiler" />
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
                        <GeneralButton color="bg-[#7a0000]  hover:bg-[#7a0000]/90" text="Limpiar Filtro" icon="search"
                            @click="dataQuedan = []" />
                    </div>
                </div>
            </div>
            <div class="flex-1">
                <div class=" p-4 rounded-md overflow-x-auto">
                    <div class="sm:flex sm:justify-between sm:items-center mb-4">
                        <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <label class="block text-xs font-light text-gray-600">
                                    Numero de requerimiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-7  flex-row-reverse select-bg">
                                    <Multiselect :options="dataForSelect.numeroRequerimiento"
                                        @select="seleccionarRequerimiento($event)" v-model="id_requerimiento_pago"
                                        :searchable="true" />
                                </div>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full mt-4 h-5"
                                :class="(collectItems != '' && id_requerimiento_pago != '' ? 'animate-bounce' : '')">
                                <button @click="addNumber()"
                                    class="inline-flex items-center justify-center px-3 h-6 text-sm py-1 text-white rounded-md shadow"
                                    :class="(collectItems != '' && id_requerimiento_pago != '' ? ' bg-orange-600' : ' bg-gray-600')">
                                    Agregar requerimiento
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-style-isri w-full" style="overflow-x:auto; max-height: 440px;">
                        <table class="table-auto">
                            <thead
                                class="text-xs font-semibold uppercase text-white bg-[#001b47] border-t border-b border-slate-200 sticky top-0">
                                <tr>
                                    <th class="px-4 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                        <div class="flex items-center">
                                            <Checkbox @click="selectAllItems()"></Checkbox>
                                        </div>
                                    </th>
                                    <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                        <div class="font-semibold text-center text-[12px] w-1">I D</div>
                                    </th>
                                    <th class="px-4 py-2 first:pl-5 last:pr-5 whitespace-nowrap">
                                        <div class="font-semibold text-center w-44 text-[12px] tracking-wider">P R O V E E D
                                            O R</div>
                                    </th>
                                    <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                        <div class="font-semibold text-center w-44 text-[12px]">D E T A L L E</div>
                                    </th>
                                    <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                        <div class="font-semibold text-center text-[12px]">F E C H A </div>
                                    </th>
                                    <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                        <div class="font-semibold text-center text-[12px]">I V A</div>
                                    </th>
                                    <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                        <div class="font-semibold text-right text-[12px]">I S R</div>
                                    </th>
                                    <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                        <div class="font-semibold text-right text-[12px]">M O N T O</div>
                                    </th>
                                </tr>
                            </thead>
                            <!-- Table body -->
                            <tbody name="fade" class="text-sm text-gray-700 bg-white divide-y divide-slate-200">
                                <template v-for="quedan in dataQuedan" :key="quedan.id_quedan">

                                    <transition enter-active-class="slide-out-right-enter-active"
                                        leave-active-class="slide-out-right-leave-active">
                                        <tr v-show="quedan.mostrar">
                                            <td class="px-4 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                                <div class="flex items-center">
                                                    <Checkbox :value="quedan.id_quedan" :checked="selectAll ? true : false"
                                                        @update:checked="(value) => selectItem(quedan.id_quedan, value, quedan.monto_liquido_quedan)">
                                                    </Checkbox>
                                                </div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                <div class="text-center  text-[12px]">{{ quedan.id_quedan }}</div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                <div class="text-center text-[12px] wrapTable whitespace-pre-wrap">
                                                    {{ quedan.proveedor.razon_social_proveedor }}
                                                </div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                <div v-for="(detalle, i) in  quedan.detalle_quedan" :key="i"
                                                    class="font-medium text-slate-800 text-[12px] text-center wrap flex justify-center items-center">
                                                    <div
                                                        :class="{ 'border-b-2 border-b-gray-500': i < quedan.detalle_quedan.length - 1 && quedan.detalle_quedan.length > 1 }">
                                                        FACTURA: {{ detalle.numero_factura_det_quedan }}
                                                        <br>
                                                        PRODUCTO: ${{ detalle.producto_factura_det_quedan }}
                                                        <br>
                                                        SERVICIO: ${{ detalle.servicio_factura_det_quedan }}
                                                        <br>
                                                        <div class="font-semibold">TOTAL: ${{
                                                            (parseFloat(detalle.servicio_factura_det_quedan)
                                                                +
                                                                parseFloat(detalle.producto_factura_det_quedan)).toFixed(2)
                                                        }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                <div class="text-center  text-[12px]">{{ quedan.fecha_emision_quedan }}
                                                </div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                <div class="text-center text-red-600 text-[12px]">-${{
                                                    quedan.monto_iva_quedan
                                                }}
                                                </div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                <div class="text-center text-red-600 text-[12px]">-${{
                                                    quedan.monto_isr_quedan
                                                }}
                                                </div>
                                            </td>
                                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap ">
                                                <div class="text-center  text-[12px] font-semibold text-green-600">${{
                                                    quedan.monto_liquido_quedan }}</div>
                                            </td>
                                        </tr>

                                    </transition>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </ModalBasicVue>
</template>
  

<script>

export default {
    props: {
        scrollbarModalOpen: {
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
        filter: {
            suppiler: '',
            rangeDate: '',
            iva: '',
            isr: '',
            monto: '',
        },
        selectAll: false,
        config: {
            mode: 'range',
            altInput: true,
            static: true,
            monthSelectorType: 'static',
            altFormat: "F-j Y",
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
        },
        takeOfQUedan() {//funcion que pone en false la key mostrar para que desaparesca de la tabla
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
                    title: `<div class="text-[15px]">¿Esta seguro de agregar el numero de requerimiento
                        <p class="text-[22px] underline">${this.numero_requerimiento[0].label} </p>  a estos quedan?</div>`,
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, Agregar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                });
                if (confirmed.isConfirmed) {

                    axios.post('/add-numberRequest-quedan', {
                        itemsToAddNumber: this.collectItems,
                        numberRequest: this.id_requerimiento_pago
                    }).then((response) => {
                        toast.success("El numero de quedan ", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                        this.takeOfQUedan()

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
                toast.error("Al parecer no haz seleccionado ningun quedan", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }

        },
        seleccionarRequerimiento(event) {
            const opcionSeleccionada = this.dataForSelect.numeroRequerimiento.filter(item => item.value === event);
            this.numero_requerimiento = opcionSeleccionada
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

