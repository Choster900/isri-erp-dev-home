<template>
    <tr @dblclick="deleteRow(id_fila)">
        <td class="border-2 border-black py-14" contenteditable="true" field-name="numeroFactura"
            @input="procesingData($event, 'numero_factura_det_quedan', allDataQuedan.id_det_quedan)">
            {{ allDataQuedan.numero_factura_det_quedan }}</td>

        <td class="border-2 border-black" colspan="2">
            <div class="relative  flex h-8 w-full flex-row-reverse ">
                <Multiselect v-model="dependencia"
                    @select="procesingSelect($event, 'id_dependencia', allDataQuedan.id_det_quedan)" :options="dependencias"
                    :searchable="true" ref="Select" />
            </div>
        </td>
        <td class="border-2 border-black">
            <div class="grid grid-cols-2 gap-2 ml-3">
                <div class="text-end">Producto</div>
                <div class="px-0">
                    <RadioButton modelValue="1" :checked="allDataQuedan.id_tipo_prestacion === 1"
                        :name="allDataQuedan.id_det_quedan" fieldName="tipoPrestaciones"
                        @update:modelValue="procesingInput(1, 'id_tipo_prestacion', allDataQuedan.id_det_quedan)"
                        ref="id_tipo_prestacion" />
                </div>
                <div>Servicio</div>
                <div>
                    <RadioButton modelValue="2" :checked="allDataQuedan.id_tipo_prestacion === 2"
                        :name="allDataQuedan.id_det_quedan" fieldName="tipoPrestaciones"
                        @update:modelValue="procesingInput(2, 'id_tipo_prestacion', allDataQuedan.id_det_quedan)"
                        ref="id_tipo_prestacion" />
                </div>
            </div>
        </td>

        <td class="border-2 border-black ...">
            <div class="relative flex h-8 w-full flex-row-reverse ">
                <Multiselect v-model="acuerdoCompra"
                    @select="procesingSelect($event, 'id_acuerdo_compra', allDataQuedan.id_det_quedan)"
                    :options="acuerdoCompras" :searchable="true" ref="Select" />
            </div>
        </td>

        <td class="border-2 border-black px-1 text-[12px]" field-name="RespaldoQuedan"
            @input="procesingData($event, 'numero_acuerdo_det_quedan', allDataQuedan.id_det_quedan)" contenteditable="true">
            {{ allDataQuedan.numero_acuerdo_det_quedan }}
        </td>

        <td class="border-2 border-black" contenteditable="true" field-name="DescripcionFactura"
            @input="procesingData($event, 'descripcion_det_quedan', allDataQuedan.id_det_quedan)">
            {{ allDataQuedan.descripcion_det_quedan }}
        </td>

        <td class="border-2 border-black px-1" field-name="Monto"
            @input="procesingData($event, 'total_factura_det_quedan', allDataQuedan.id_det_quedan)" contenteditable="true">
            {{ allDataQuedan.total_factura_det_quedan }}
        </td>
    </tr>
</template>
    
<script>
import Multiselect from "@vueform/multiselect";
import RadioButton from "@/Components-ISRI/ComponentsToForms/RadioButtonUpgrade.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";


export default {
    props: {//INTENTEMOS MANDARLE TODO AQUI (ID QUEDAN Y SU DETALLE PARA MANIPULARLO AQUI)
        allDataQuedan: {
            type: Array,
            required: false
        }
    },
    components: {
        Multiselect,
        RadioButton,
        VueSweetalert2
    },

    data: function () {
        return {
            codigoFactura: '',
            eliminado: false,
            dependencias: [],//Option select
            acuerdoCompras: [],//Option select
            dependencia: this.allDataQuedan.id_dependencia,//v-model
            acuerdoCompra: this.allDataQuedan.id_acuerdo_compra,//v-model

        }
    },
    methods: {

        procesingData(event, campo, fila) {
            const value = event.target.textContent;
            const tdName = event.target.getAttribute('field-name');

            const data = {
                campos: campo,
                value: value,
                id_det_quedan: fila,
                id_quedan: this.allDataQuedan.id_quedan,
            }

            axios.post('/update-detalle-quedan', data).then((response) => {
                console.log(response);
            }).catch((error) => {
                console.log(error);
            });

        },
        procesingInput(event, campo, fila) {

            const data = {
                campos: campo,
                value: event,
                id_det_quedan: fila,
                id_quedan: this.allDataQuedan.id_quedan,
            }

            console.log(data);
            axios.post('/update-detalle-quedan', data).then((response) => {
                console.log(response);
            }).catch((error) => {
                console.log(error);
            });

        },
        procesingSelect(event, nombre, fila) {
            const data = {
                campos: nombre,
                value: event,
                id_det_quedan: fila,
                id_quedan: this.allDataQuedan.id_quedan,
            }
            axios.post('/update-detalle-quedan', data).then((response) => {
                console.log(response);
            }).catch((error) => {
                console.log(error);
            });
        },
        deleteRow(id_fila) {
            alert("eliminado fila " + id_detalle_quedan)
            this.eliminado = !this.eliminado
        },

        getListForSelect() {
            axios.get('/get-list-select').then((response) => {
                this.dependencias = response.data.dependencias;
                this.acuerdoCompras = response.data.acuerdoCompras;
            }).catch((error) => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.getListForSelect()
    },
    watch: {
        allDataQuedan: function (value, OldValue) {
            alert("")
            this.dependencia = allDataQuedan.id_dependencia
            this.acuerdoCompra = allDataQuedan.id_acuerdo_compra
        }
    }

}
</script>

<style></style>