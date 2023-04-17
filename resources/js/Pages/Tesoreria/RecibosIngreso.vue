<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalIncomeReceiptVue from '@/Components-ISRI/Tesoreria/ModalIncomeReceipt.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
  <Head title="Administracion" />
  <AppLayoutVue>
    <div class="sm:flex sm:justify-end sm:items-center mb-2">
      <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
        <GeneralButton @click="addIncomeConcept()" v-if="permits.insertar == 1" color="bg-green-700  hover:bg-green-800"
          text="Agregar Elemento" icon="add" />
      </div>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
      <header class="px-5 py-4">
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
            <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
              <Multiselect v-model="tableData.length" @select="getIncomeConcept()" :options="perPage"
                :searchable="true" />
              <LabelToInput icon="date" />
            </div>
          </div>
          <h2 class="font-semibold text-slate-800 pt-1">Total Recibos Ingreso <span class="text-slate-400 font-medium">{{
            tableData.total
          }}</span></h2>
        </div>
      </header>

      <div class="overflow-x-auto">
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
          @datos-enviados="handleData($event)">
          <tbody class="text-sm divide-y divide-slate-200">
            <tr v-for="receipt in income_receipts" :key="receipt.id_recibo_ingreso">
            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
              <div class="font-medium text-slate-800">{{ receipt.numero_recibo_ingreso }}</div>
            </td>
            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
              <div class="font-medium text-slate-800">{{ receipt.cliente_recibo_ingreso }}</div>
            </td>
            <td class="px-2 first:pl-5 last:pr-5  whitespace-normal w-px break-words">
              <div class="font-medium text-slate-800">{{ receipt.descripcion_recibo_ingreso }}</div>
            </td>
            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
              <div class="font-medium text-slate-800">{{ receipt.id_ccta_presupuestal }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">$ {{ receipt.monto_recibo_ingreso }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                  <div class="font-medium text-slate-800">
                    <div v-if="(receipt.estado_recibo_ingreso == 1)"
                      class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-500">
                      Activo
                    </div>
                    <div v-else
                      class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-rose-100 text-rose-600">
                    Inactivo
                  </div>
                </div>
              </td>
            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
              <div class="space-x-1">
                <button @click="editIncomeConcept(receipt)" v-if="permits.actualizar == 1"
                  class="text-slate-400 hover:text-slate-500 rounded-full">
                  <span class="sr-only">Edit</span>
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                      <path
                        d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                      </path>
                    </svg>
                  </button>
                  <button @click="changeStateIncomeReceipt(receipt.id_recibo_ingreso,receipt.numero_recibo_ingreso,receipt.estado_recibo_ingreso)" v-if="permits.eliminar==1" 
                      class="text-rose-500 hover:text-rose-600 rounded-full">
                      <span class="sr-only">Delete</span><svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                        <path d="M13 15h2v6h-2zM17 15h2v6h-2z">
                        </path>
                        <path
                          d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                        </path>
                      </svg>
                    </button>
                </div>
              </td>
            </tr>
          </tbody>
        </datatable>

      </div>
    </div>

    <div class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
      <div>
        <nav class="flex justify-between" role="navigation" aria-label="Navigation">
          <div class="grow text-center">
            <ul class="inline-flex text-sm font-medium -space-x-px">
              <li v-for="link in links" :key="link.label">
                <span v-if="(link.label == 'Anterior')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                  <div class="flex-1 text-right ml-2">
                    <a @click="getIncomeReceipts(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                    text-indigo-500">
                      &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                    </a>
                  </div>
                </span>
                <span v-else-if="(link.label == 'Siguiente')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                  <div class="flex-1 text-right ml-2">
                    <a @click="getIncomeReceipts(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                    text-indigo-500">
                    <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                  </a>
                  </div>
                </span>
                <span class="cursor-pointer" v-else
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                    class=" w-5" @click="getIncomeReceipts(link.url)">{{ link.label }}</span>
                </span>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>

    <ModalIncomeReceiptVue :showModalIncome="showModalIncome" :modalData="modalData" :budget_accounts="budget_accounts" 
              @cerrar-modal="showModalIncome=false" @get-table="this.getIncomeReceipts(this.tableData.currentPage)"/>

  </AppLayoutVue>
</template>

<script>
export default {
  created() {
    this.getIncomeReceipts()
    this.getPermits()
    this.getTreasuryBudgetAccounts()
  },
  data() {
    let sortOrders = {};
    let columns = [
      { width: "10%", label: "Numero", name: "numero_recibo_ingreso", type: "text" },
      { width: "25%", label: "Cliente", name: "cliente_recibo_ingreso", type: "text" },
      { width: "30%", label: "Descripcion", name: "descripcion_recibo_ingreso", type: "text" },
      { width: "8%", label: "Especifico", name: "id_ccta_presupuestal", type: "text" },
      { width: "10%", label: "Monto", name: "monto_recibo_ingreso", type: "text" },
      {
        width: "7%", label: "Estado", name: "estado_recibo_ingreso", type: "select",
        options: [
          { value: "1", label: "Activo" },
          { value: "0", label: "Inactivo" }
        ]
      },
      { width: "10%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      if (column.name === 'id_recibo_ingreso')
        sortOrders[column.name] = 1;
      else
        sortOrders[column.name] = -1;
    });
    return {
      //Data for datatable
      income_receipts: [],
      //Data for modal
      showModalIncome: false,
      modalData: [],
      permits: [],
      budget_accounts: [],
      links: [],
      columns: columns,
      sortKey: "id_recibo_ingreso",
      sortOrders: sortOrders,
      perPage: ["10", "20", "30"],
      tableData: {
        currentPage: '',
        draw: 0,
        length: 5,
        search: "",
        column: 0,
        dir: "asc",
        total: ""
      },
    }
  },
  methods: {
    editIncomeConcept(income_concept) {
      //var array = {nombre_marca:marca.nombre_marca}
      this.modalData = income_concept
      this.showModalIncome = true
    },
    addIncomeConcept() {
      this.modalData = []
      this.showModalIncome = true
    },
    getTreasuryBudgetAccounts() {
      axios.get("/get-treasury-budget-accounts")
        .then((response) => {
          this.budget_accounts = response.data.budget_accounts
        })
        .catch((errors) => {
          let msg = this.manageError(errors);
          this.$swal.fire({
            title: "Operación cancelada",
            text: msg,
            icon: "warning",
            timer: 5000,
          });
          this.$emit("cerrar-modal");
        });
    },
    changeStateIncomeReceipt(income_receipt_id, income_receipt_number, income_receipt_state) {
      let msg
      income_receipt_state == 1 ? msg = "Desactivar" : msg = "Activar"
      this.$swal.fire({
        title: msg + ' recibo de ingreso numero: ' + income_receipt_number + '.',
        text: "¿Estas seguro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, ' + msg
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/change-state-income-receipt", {
            income_receipt_id: income_receipt_id,
            income_receipt_state: income_receipt_state
          })
            .then((response) => {
              this.$swal.fire({
                text: response.data.mensaje,
                icon: 'success',
                timer: 2000
              })
              this.getIncomeReceipts(this.tableData.currentPage);
            })
            .catch((errors) => {
              let msg = this.manageError(errors)
              this.$swal.fire({
                title: 'Operación cancelada',
                text: msg,
                icon: 'warning',
                timer: 5000
              })
            })
        }
      })
    },
    async getIncomeReceipts(url = "/recibos-ingreso") {
      this.tableData.draw++;
      this.tableData.currentPage = url
      await axios.get(url, { params: this.tableData }).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          this.tableData.total = data.data.total;
          this.links[0].label = "Anterior";
          this.links[this.links.length - 1].label = "Siguiente";
          this.income_receipts = data.data.data;
        }
      }).catch((errors) => {
        let msg = this.manageError(errors)
        this.$swal.fire({
          title: 'Operación cancelada',
          text: msg,
          icon: 'warning',
          timer: 5000
        })
        //console.log(errors);
      })
    },
    sortBy(key) {
      if (key != "Acciones") {
        this.sortKey = key;
        this.sortOrders[key] = this.sortOrders[key] * -1;
        this.tableData.column = this.getIndex(this.columns, "name", key);
        this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
        this.getIncomeReceipts();
      }
    },
    getIndex(array, key, value) {
      return array.findIndex((i) => i[key] == value);
    },
    getPermits() {
      var URLactual = window.location.pathname
      let data = this.$page.props.menu;
      let menu = JSON.parse(JSON.stringify(data['urls']))
      menu.forEach((value, index) => {
        value.submenu.forEach((value2, index2) => {
          if (value2.url === URLactual) {
            var array = { 'insertar': value2.insertar, 'actualizar': value2.actualizar, 'eliminar': value2.eliminar, 'ejecutar': value2.ejecutar }
            this.permits = array
          }
        })
      })
    },
    handleData(myEventData) {
      this.tableData.search = myEventData;
      this.getIncomeReceipts()
    }
  }
}
</script>
