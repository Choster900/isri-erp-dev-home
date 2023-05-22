<script setup>
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import axios from 'axios';
</script>
<template>
    <ModalBasicVue :title="numeroRequerimiento" id="scrollbar-modal" maxWidth="5xl" :modalOpen="modalIsOpen"
        @close-modal="$emit('close-definitive')">

        <div class=" px-10 py-5 ">
            <table class="table-auto">
                <thead class="text-sm  uppercase   ">
                    <tr>
                        <th>

                        </th>
                        <th colspan="2" class="pl-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap rounded-tr-2xl ">

                            <div class="flex justify-between">
                                <div class="text-center" :class="restanteIngreso < 0 ? 'text-red-600' : ''">
                                    <label class="flex items-center">
                                        <div class="checkbox-icon bg-green-600"></div>
                                        <span class="ml-2 text-[10px] text-green-600">Liquidado</span>
                                    </label>
                                    <label class="flex items-center">
                                        <div class="checkbox-icon bg-blue-600 border border-black/10"></div>
                                        <span class="ml-2 text-[10px] text-blue-600">Parcialmente liquidado</span>
                                    </label>
                                </div>

                                <div class="text-start pr-5" :class="restanteIngreso < 0 ? 'text-red-600' : ''">
                                    <label class="flex items-center">
                                        <div class="checkbox-icon bg-red-600"></div>
                                        <span class="ml-2 text-[10px] text-red-600">Error de las liquidaciones</span>
                                    </label>
                                </div>
                            </div>

                        </th>
                        <th colspan=""
                            class="text-white bg-[#001b47] px-4 py-2 first:pl-5 last:pr-5 whitespace-nowrap rounded-tl-2xl border-r-2">
                            <div class=" text-center  tracking-wider">
                                MONTO DEL REQ: <br>
                                <span class="text-sm">{{ dataRequest.monto_requerimiento_pago }}</span>

                            </div>
                        </th>
                        <th
                            class="text-white bg-[#001b47] w-[400px] max-w-[400px] py-2 first:pl-5 last:pr-5 whitespace-nowrap border-r-2">
                            <div class=" text-center  tracking-wider">
                                TOTAL PAGADO:
                                <br>
                                <span class="text-sm">{{ totalPagadoToShow }}</span>
                            </div>

                        </th>
                        <th
                            class="text-white bg-[#001b47] px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap rounded-tr-2xl">
                            <div class=" text-center  ">POR PAGAR: <br> 
                                <span class="text-sm">{{ restRequest }}</span>

                            </div>
                        </th>

                        <th class=" py-2 first:pl-5 last:pr-5   whitespace-nowrap rounded-tr-2xl">
                        </th>
                    </tr>
                </thead>
                <thead class="text-sm  uppercase text-white  sticky top-0">
                    <tr>

                        <th class="" style="background-color: transparent !important;">
                        </th>
                        <th
                            class="px-4 py-2 first:pl-5 last:pr-5 whitespace-nowrap bg-[#001b47] border-t border-b border-slate-200">
                            <div class=" text-center  tracking-wider">Q U E D A N</div>
                        </th>

                        <th
                            class="px-4 py-2 first:pl-5 last:pr-5  max-w-[200px]  whitespace-nowrap bg-[#001b47] border-t border-b border-slate-200">
                            <div class=" text-center ">D E S C R I P C I O N </div>
                        </th>
                        <th
                            class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap bg-[#001b47] border-t border-b border-slate-200">
                            <div class=" text-center ">S A L D O</div>
                        </th>
                        <th
                            class=" py-2 first:pl-5 last:pr-5 w-[100px]  whitespace-nowrap bg-[#001b47] border-t border-b border-slate-200">
                            <div class=" text-center ">L I Q U I D A C I O N</div>
                        </th>

                        <th
                            class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap bg-[#001b47] border-t border-b border-slate-200">
                            <div class="text-center ">R E S T A N T E</div>
                        </th>
                        <th class="" style="background-color: transparent !important;">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(data, i ) in  dataForRows " :key="i">

                        <transition name="slide-out-right" enter-active-class="slide-out-right-enter-active"
                            leave-active-class="slide-out-right-leave-active">
                            <tr v-show="data.eliminadoLogicoQuedan">
                                <td>
                                    <svg @click="showDetail(i)" v-if="data.liquidacion_quedan != ''" width="22px"
                                        height="22px" viewBox="0 0 24.00 24.00"
                                        class="mr-2 cursor-pointer hover:-translate-y-1 duration-500 "
                                        xmlns="http://www.w3.org/2000/svg" fill="#545454" stroke="#545454">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <title></title>
                                            <g id="Complete">
                                                <g id="align-left">
                                                    <g>
                                                        <polygon fill="#ffffff"
                                                            points="12.9 18 4.1 18 4.1 18 12.9 18 12.9 18" stroke="#8c8c8c"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.296"></polygon>
                                                        <polygon fill="#ffffff" points="20 14 4 14 4 14 20 14 20 14"
                                                            stroke="#8c8c8c" stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.296"></polygon>
                                                        <polygon fill="#ffffff"
                                                            points="12.9 10 4.1 10 4.1 10 12.9 10 12.9 10" stroke="#8c8c8c"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.296"></polygon>
                                                        <polygon fill="#ffffff" points="20 6 4 6 4 6 20 6 20 6"
                                                            stroke="#8c8c8c" stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.296"></polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </td>
                                <td :class="[data.restanteToShow == 0 ? 'bg-green-600' :
                                    data.restanteToShow < 0 ? 'bg-red-600' :
                                        data.monto_liquidacion_quedan < 0 ? 'bg-red-600' :
                                            data.id_estado_quedan == 3 ? 'bg-blue-600' : 'bg-[#1f355833]']"
                                    class="pb-1 pt-1 focus:bg-white  text-sm  h-12 rounded-md px-3  bg-opacity-20 text-center border-2 border-white"
                                    contenteditable="false">
                                    {{ data.id_quedan }}
                                </td>
                                <td :class="[data.restanteToShow == 0 ? 'bg-green-600' :
                                    data.restanteToShow < 0 ? 'bg-red-600' :
                                        data.monto_liquidacion_quedan < 0 ? 'bg-red-600' :
                                            data.id_estado_quedan == 3 ? 'bg-blue-600' : 'bg-[#1f355833]']"
                                    @input="liquidarMonto(data.id_quedan, $event, 'notifica_liquidacion_quedan')"
                                    class="pb-1 pt-1 text-sm   w-[400px] max-w-[400px] rounded-md px-3  bg-opacity-20 text-center border-2 border-white"
                                    :contenteditable="data.id_estado_quedan != 4 ? 'true' : 'false'">

                                </td>
                                <td :class="[data.restanteToShow == 0 ? 'bg-green-600' :
                                    data.restanteToShow < 0 ? 'bg-red-600' :
                                        data.monto_liquidacion_quedan < 0 ? 'bg-red-600' :
                                            data.id_estado_quedan == 3 ? 'bg-blue-600' : 'bg-[#1f355833]']"
                                    class="pb-1 pt-1 focusbg-white text-sm  w-[200px]  rounded-md px-3  bg-opacity-20 text-center border-2 border-white"
                                    contenteditable="false">
                                    {{ data.monto_liquido_quedan }}
                                </td>

                                <td :contenteditable="data.id_estado_quedan != 4 ? 'true' : 'false'" :class="[data.restanteToShow == 0 ? 'bg-green-600' :
                                    data.restanteToShow < 0 ? 'bg-red-600' :
                                        data.monto_liquidacion_quedan < 0 ? 'bg-red-600' :
                                            data.id_estado_quedan == 3 ? 'bg-blue-600' : 'bg-[#1f355833]']"
                                    @input="liquidarMonto(data.id_quedan, $event, 'monto_liquidacion_quedan')"
                                    class="pb-1 pt-1 text-sm w-[100px]  rounded-md px-3  bg-opacity-20 text-center border-2 border-white">
                                </td>

                                <td :class="[data.restanteToShow == 0 ? 'bg-green-600' :
                                    data.restanteToShow < 0 ? 'bg-red-600' :
                                        data.monto_liquidacion_quedan < 0 ? 'bg-red-600' :
                                            data.id_estado_quedan == 3 ? 'bg-blue-600' : 'bg-[#1f355833]']"
                                    class="pb-1 pt-1 focus:bg-white text-sm  max-w-[50px]  rounded-md px-3  bg-opacity-20 text-center border-2 border-white"
                                    contenteditable="false">
                                    {{ data.restanteToShow }}
                                </td>
                                <td contenteditable="false" class="pb-1 pt-1">
                                    <svg width="20px" @click="deleteFromRequest(data.id_quedan, data.seguimiento_pagos)"
                                        v-if="data.id_estado_quedan != 4" height="20px" viewBox="0 0 1024 1024"
                                        :fill="data.seguimiento_pagos == 0 ? '#ff0000' : '#646464'"
                                        class="icon cursor-pointer"
                                        :class="data.seguimiento_pagos == 0 ? 'cursor-pointer' : 'cursor-not-allowed'"
                                        version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#ff0000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M512 897.6c-108 0-209.6-42.4-285.6-118.4-76-76-118.4-177.6-118.4-285.6 0-108 42.4-209.6 118.4-285.6 76-76 177.6-118.4 285.6-118.4 108 0 209.6 42.4 285.6 118.4 157.6 157.6 157.6 413.6 0 571.2-76 76-177.6 118.4-285.6 118.4z m0-760c-95.2 0-184.8 36.8-252 104-67.2 67.2-104 156.8-104 252s36.8 184.8 104 252c67.2 67.2 156.8 104 252 104 95.2 0 184.8-36.8 252-104 139.2-139.2 139.2-364.8 0-504-67.2-67.2-156.8-104-252-104z"
                                                fill=""></path>
                                            <path d="M707.872 329.392L348.096 689.16l-31.68-31.68 359.776-359.768z" fill="">
                                            </path>
                                            <path d="M328 340.8l32-31.2 348 348-32 32z" fill=""></path>
                                        </g>
                                    </svg>

                                </td>
                            </tr>

                        </transition>


                        <template v-for="(liquidacion, j) in data.liquidacion_quedan" :key="j">

                            <transition enter-from-class="translate-x-[150%] opacity-0"
                                leave-to-class="translate-x-[150%] opacity-0" enter-active-class="transition duration-300"
                                leave-active-class="transition duration-300">

                                <tr v-if="data.mostrarDetalle">
                                    <td></td>
                                    <td colspan="2" class="text-end py-2"
                                        :class="{ 'border-b border-b-gray-300': j < data.liquidacion_quedan.length - 1 && data.liquidacion_quedan.length > 1 }">
                                        <span class="font-medium">{{ formatDate(liquidacion.fecha_liquidacion_quedan) }} -
                                        </span>


                                        {{ liquidacion.notifica_liquidacion_quedan }}
                                    </td>
                                    <td class="text-center text-blue-900"
                                        :class="{ 'border-b border-b-gray-300': j < data.liquidacion_quedan.length - 1 && data.liquidacion_quedan.length > 1 }">
                                        {{ data.monto_liquido_quedan }}
                                    </td>
                                    <td class="text-center w-[100px]"
                                        :class="{ 'border-b border-b-gray-300': j < data.liquidacion_quedan.length - 1 && data.liquidacion_quedan.length > 1 }">
                                        <span class="text-green-600">+ {{ liquidacion.monto_liquidacion_quedan }}</span>
                                    </td>
                                    <td class="text-center"
                                        :class="{ 'border-b border-b-gray-300': j < data.liquidacion_quedan.length - 1 && data.liquidacion_quedan.length > 1 }">
                                        <span :class="calculateDifference(i, j) != 0 ? 'text-red-600' : 'text-green-600'">
                                            -{{ calculateDifference(i, j) }}</span>
                                    </td>
                                </tr>
                            </transition>
                        </template>
                    </template>
                </tbody>
            </table>
            <div class="flex justify-center items-center">

                <div class="px-2 pt-3" v-if="dataRequest.id_estado_req_pago != 2">
                    <GeneralButton @click="setLiquidaciones()" color="bg-orange-700  hover:bg-orange-800" text="Liquidar"
                        icon="add" />
                </div>

                <div class="px-2 pt-3">
                    <GeneralButton @click="$emit('close-definitive')" text="Cerrar" icon="delete" />
                </div>

            </div>

        </div>
    </ModalBasicVue>
</template>
  
<script>
export default {
    props: {
        modalIsOpen: {
            type: Boolean,
            default: false,
        },
        dataLiquidaciones: {
            type: Object,
            required: true,
        },
    },
    data: (props) => ({
        dataRequest: [],//Objeto que contiene los datos del requerimiento actual
        restRequest: 0,//Data que almacena el restante por pagar 
        totalPagadoToShow: 0,//Data que almacena el total pagado
        totalPagadoForCalculate: 0,//Data que almacena el total pagado para calcular
        sumSeguimientPago: 0,//Data que almacena la suma de seguimiento de pagos en la tabla liquidaciones
        dataForRows: [],//data que se mostrara en la tabla
    }),

    methods: {
        setDataForRows() {
            this.dataRequest.quedan.forEach((element, index) => {
                //Obtenemos un arreglo de todos los pagos que tiene ese quedan
                let ArraySeguimientoPago = element.liquidacion_quedan
                //Sumamos todos los pagos para asiganrlos a una variable
                let sumaSeguimiento = ArraySeguimientoPago.reduce((monto, element) => monto + parseFloat(element.monto_liquidacion_quedan), 0);
                //Pusheando la data al arreglo que se imprimira en pantalla
                this.dataForRows.push({
                    id_quedan: element.id_quedan,
                    id_estado_quedan: element.id_estado_quedan,
                    notifica_liquidacion_quedan: '',
                    monto_liquido_quedan: element.monto_liquido_quedan,
                    seguimiento_pagos: sumaSeguimiento,
                    restanteToCalculate: (parseFloat(element.monto_liquido_quedan) - parseFloat(sumaSeguimiento)).toFixed(2),//una fila para usar como calulos generales
                    restanteToShow: (parseFloat(element.monto_liquido_quedan) - parseFloat(sumaSeguimiento)).toFixed(2),//la fila que se mostrara en la tabla
                    monto_liquidacion_quedan: 0,
                    eliminadoLogicoQuedan: true,
                    mostrarDetalle: false,
                    liquidacion_quedan: element.liquidacion_quedan,//le puce .reverse() pero para algo extraño que una vez me lo despliega al reves y otra no xd
                })
                this.totalPagadoForCalculate = (parseFloat(this.totalPagadoToShow) + parseFloat(sumaSeguimiento)).toFixed(2)
                this.totalPagadoToShow = (parseFloat(this.totalPagadoToShow) + parseFloat(sumaSeguimiento)).toFixed(2)
                this.sumSeguimientPago = this.totalPagadoToShow
                this.restRequest = (parseFloat(this.dataRequest.monto_requerimiento_pago) - (parseFloat(this.totalPagadoToShow))).toFixed(2)

            })
        },
        liquidarMonto(id_quedan, event, campo) {
            if (campo === 'monto_liquidacion_quedan') {
                let inputValue = event.target.textContent;
                const regex = /^(\d+)?(\.\d{0,2})?$/;
                inputValue = inputValue.replace(/^\./, ''); // Eliminar punto al inicio
                inputValue = inputValue.replace(/\.(?=.*\.)/g, ''); // Eliminar puntos adicionales
                inputValue = inputValue.replace(/[^0-9.]/g, ''); // Eliminar caracteres no permitidos

                if (!regex.test(inputValue)) {
                    const previousValue = event.target.textContent;
                    event.target.textContent = previousValue.substring(0, previousValue.length - 1);
                    const selection = window.getSelection();
                    const range = document.createRange();
                    range.selectNodeContents(event.target);
                    range.collapse(false); // Colapsar al final del rango
                    selection.removeAllRanges();
                    selection.addRange(range);
                } else {
                    if (event.target.textContent != inputValue) {
                        event.target.textContent = inputValue
                        const selection = window.getSelection();
                        const range = document.createRange();
                        range.selectNodeContents(event.target);
                        range.collapse(false); // Colapsar al final del rango
                        selection.removeAllRanges();
                        selection.addRange(range);
                    } else {
                        event.target.textContent = inputValue;
                    }
                }
            }

            //TODO: arreglar que solo puedan ingresar numeros con dos decimales (tambien en el quedan)
            const index = this.dataForRows.findIndex(quedan => quedan.id_quedan === id_quedan);
            if (index !== -1) {
                let liquidacion = event.target.textContent
                this.dataForRows[index][campo] = liquidacion;

                if (campo == 'monto_liquidacion_quedan') {
                    this.calculateRestForRow(id_quedan, event)
                }
            }
        },
        showDetail(i) {
            this.dataForRows[i]["mostrarDetalle"] = !this.dataForRows[i]["mostrarDetalle"];
        },
        calculateRestForRow(id_quedan, event) {
            //toma los valores de del restante por quedan y lo resta con lo ingresado en la liquidacion y asi calcular el faltante
            // Encontrar el índice de la fila con el id_quedan dado
            const index = this.dataForRows.findIndex(row => row.id_quedan === id_quedan);
            if (index !== -1) {
                const { restanteToCalculate } = this.dataForRows[index]; // Obtener el valor de restanteToCalculate de la fila
                const restanteToShow = (restanteToCalculate - event.target.textContent).toFixed(2); // Calcular el valor de restanteToShow restando el contenido del evento al restanteToCalculate y formateándolo con dos decimales
                this.dataForRows[index] = { ...this.dataForRows[index], restanteToShow }; // Actualizar el valor de restanteToShow en la fila correspondiente
                this.sumAmountPayAndAmountRest(); // Calcular la suma de amount_pay y amount_rest
            }
        },
        sumAmountPayAndAmountRest() {
            //sumamos el total pagado por requerimiento
            const newItems = this.dataForRows.filter(item => item.eliminadoLogicoQuedan == true);
            let data = newItems.reduce((monto, element) => {
                const montoLiquidacion = parseFloat(element.monto_liquidacion_quedan);
                return isNaN(montoLiquidacion) ? monto : monto + montoLiquidacion;
            }, 0).toFixed(2);

            //se calcula el monto que hemos pagamos
            this.totalPagadoToShow = (parseFloat(this.totalPagadoForCalculate) + parseFloat(data)).toFixed(2);
            //se calcula el restante que debemos por requerimiento
            this.restRequest = (parseFloat(this.dataRequest.monto_requerimiento_pago) - (parseFloat(this.totalPagadoToShow))).toFixed(2)
        },

        watchTheRest() {
            // Validando que ningún número sea mayor a lo que se debe por quedan
            let filterQuedanEnLiquidacion = this.dataForRows.filter((item, i) => item.id_estado_quedan != 4);
            let flag = 0;//variable que guarda la cantidad de campos vacios que hay
            for (let index = 0; index < filterQuedanEnLiquidacion.length; index++) {
                // Si se encuentra un números menor a cero, retornar false y salir del bucle
                if (filterQuedanEnLiquidacion[index]["restanteToShow"] < 0 || filterQuedanEnLiquidacion[index]["monto_liquidacion_quedan"] < 0) {
                    toast.error("Las liquidaciones tienen datos que no cuadran. Verifica los montos ingresados", {
                        autoClose: 7000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                    return false;
                }
                //contar cuantos campos vacios hay en monto_liquidacion_quedan
                if (filterQuedanEnLiquidacion[index]["monto_liquidacion_quedan"] == 0) {
                    flag++
                }
            }
            //validando que el numero de campos vacios en monto_liquidacion_quedan no sean igual a la cantidad de campos que hay
            if (flag == filterQuedanEnLiquidacion.length) {
                toast.warning("Tienes campos vacios", {
                    autoClose: 4000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
                return false; //  si todos los campos vacios no se ingresara  la liquidacion
            } else {
                return true; // si por lo menos hay un campo lleno se ingresara a la bas
            }
        },

        //Funcion que recibe dos parametros id quedan y un monto que con esto validaremos si se puede eliminar o no
        async deleteFromRequest(id_quedan, weDeleteIf) {
            try {
                if (weDeleteIf == 0) {
                    this.$swal.fire({
                        title: '<p class="text-[16px]">¿Esta seguro de eliminar este quedan de este requerimiento? . Recuerda que puedes volver a agregarlo cuando quieras, mientras el requerimiento este en el rango establecido<p>',
                        icon: 'question',
                        iconHtml: '❓',
                        confirmButtonText: '<p class="text-[14px]">Si, eliminar<p>',
                        confirmButtonColor: '#D2691E',
                        cancelButtonText: 'Cancelar',
                        showCancelButton: true,
                        showCloseButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const index = this.dataForRows.findIndex(r => r.id_quedan === id_quedan);
                            this.dataForRows[index]["eliminadoLogicoQuedan"] = false;//seteamos el eliminado logico a false para no mostrarlo mas
                            this.dataForRows[index]["mostrarDetalle"] = false;//seteamos el eliminado logico a false para no mostrarlo mas

                            let total = this.dataForRows[index].liquidacion_quedan.reduce((acc, obj) => acc + parseFloat(obj.monto_liquidacion_quedan), 0);

                            if (total != 0) {
                                this.restRequest = (parseFloat(this.restRequest) + parseFloat(total))
                                this.totalPagadoForCalculate = (parseFloat(this.totalPagadoForCalculate) - parseFloat(total))
                            }
                            this.sumAmountPayAndAmountRest()//llamando la funcion para que haga el recalculo de nuevo

                            // this.calculateWhenIsDelete(this.dataForRows[index].liquidacion_quedan)


                            let cantidadEliminada = this.dataForRows.filter((item, i) => item.eliminadoLogicoQuedan <= false);
                            console.log(cantidadEliminada.length);
                            console.log(this.dataForRows.length);

                            if (cantidadEliminada.length == this.dataForRows.length) {
                                this.$emit("reload-table-and-close")
                            }
                            axios.post('/delete-quedan-from-request', {
                                id_quedan: id_quedan,
                            }).then(response => {
                                this.$emit("reload-table")
                                this.$emit("reload-data-for-select")
                                toast.error(`Quedan ${id_quedan} eliminado del requerimiento`, {
                                    autoClose: 7000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                }); 3
                            }).catch(errors => {
                                let msg = this.manageError(errors);
                                this.$swal.fire({
                                    title: "Operación cancelada",
                                    text: msg,
                                    icon: "warning",
                                    timer: 5000,
                                });
                            })

                        }
                    });
                    return res;
                } else {
                    toast.error("Este quedan ya tiene un historial de liquidaciones. por lo tanto no se puede eliminar", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    })
                    return false
                }
            } catch (error) {
                return false; // indicate failure
            }
        },
        calculateWhenIsDelete(data) {
            console.log(
                data
            );
        },
        async setLiquidaciones() {
            //Funcion que envia la informacion a la tabla liquidacion_quedan
            try {
                if (this.watchTheRest()) {//funcion que retorna true o false


                    this.$swal.fire({
                        title: '<p class="text-[16px]">Esta seguro de agregar liquidaciones a este requerimiento?. Una vez agregada la liquidacion su seguimiento no se podra eliminar<p>',
                        icon: 'question',
                        iconHtml: '❓',
                        confirmButtonText: '<p class="text-[14px]">Si, Agregar<p>',
                        confirmButtonColor: '#196F3D',
                        cancelButtonText: 'Cancelar',
                        showCancelButton: true,
                        showCloseButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post('/liquidar-quedan', {
                                params: this.dataForRows,
                                liquidado: this.restRequest == 0 ? true : false,
                                id_requerimiento_pago: this.dataRequest.quedan[0].id_requerimiento_pago,
                            }).then(result => {//result obtenemos la cantidad total pagada por los quedan enviados
                                toast.success("Liquidaciones agregadas exitosamente", {
                                    autoClose: 4000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                                setTimeout(() => {
                                    this.$emit("reload-table-and-close")
                                }, 1000);
                            }).catch(errors => {
                                let msg = this.manageError(errors);
                                this.$swal.fire({
                                    title: "Operación cancelada",
                                    text: msg,
                                    icon: "warning",
                                    timer: 5000,
                                });
                            })

                        }
                    });
                }
            } catch (error) {
                return false; // indicate failure
            }
        },

        calculateDifference(indexQuedan, indexLiquidacion) {
            //Parseamos la informacion
            let newDataQuedan = JSON.parse(JSON.stringify(this.dataForRows));
            //Variable que almacena el saldo por quedan
            let saldo = newDataQuedan[indexQuedan].monto_liquido_quedan
            //Filtramos el detalle donde el index sea menor donde nos encontramos
            //Ya que esta funcion se llama cada vez que recorrer en el v-for se manda un indice y ese indice lo usamos como filtro para sumar
            //todas las liquidaciones
            let filterDetalle = newDataQuedan[indexQuedan].liquidacion_quedan.filter((item, i) => i <= indexLiquidacion);
            //Sumamos las liquidaciones anteriores con el filtro filterDetalle
            let total = filterDetalle.reduce((acc, obj) => acc + parseFloat(obj.monto_liquidacion_quedan), 0);
            //Restamos el saldo menos el total que es el tota de las liquidaciones
            return (saldo - total).toFixed(2)
        },

        formatDate(dateString) {
            //Funcion utilizada para formatear la fecha a : dd/mm/yyyy
            const date = new Date(dateString);
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear().toString();
            return `${day}/${month}/${year}`;
        }

    },
    watch: {
        modalIsOpen: function (newValue, oldValue) {
            if (this.modalIsOpen) {

                const proxyArray = this.dataLiquidaciones;
                const elementoArray = proxyArray[0];
                const objeto = Object.assign({}, elementoArray);
                this.dataRequest = objeto

                this.setDataForRows()

            } else {
                this.dataRequest = []
                this.restRequest = 0
                this.totalPagadoToShow = 0
                this.totalPagadoForCalculate = 0
                this.sumSeguimientPago = 0
                this.dataForRows = []
                this.liquidacionesHistorial = []
            }
        },
    },
    computed: {

    }

}
</script>
<style scoped>
td {
    outline: none;
    /* Desactiva el marco de enfoque */
}

.error {
    background-color: rgb(203, 56, 56) !important;
}

.error2 {
    background-color: rgb(191, 141, 15) !important;
}

.checkbox {
    display: flex;
    align-items: center;
}

.checkbox-icon {
    display: inline-block;
    width: 0.75rem;
    height: 0.75rem;
    margin-right: 0.5rem;
}

.checkbox-label {
    font-size: 0.75rem;
}

.slide-out-right-enter-active {
    animation: slide-out-right-enter 0.5s;
}

.slide-out-right-leave-active {
    animation: slide-out-right-leave 0.5s;
}

@keyframes slide-out-right-enter {
    from {
        opacity: 0;
        transform: translateX(-100%);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slide-out-right-leave {
    from {
        opacity: 1;
        transform: translateX(0);
    }

    to {
        opacity: 0;
        transform: translateX(100%);
    }
}
</style>

