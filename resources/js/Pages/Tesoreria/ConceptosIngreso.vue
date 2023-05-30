<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalIncomeConceptVue from '@/Components-ISRI/Tesoreria/ModalIncomeConcept.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
  <Head title="Catalogo - Conceptos" />
  <AppLayoutVue nameSubModule="Tesoreria - Conceptos de Ingreso">
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
              <Multiselect v-model="tableData.length" @select="getIncomeConcept()" :options="perPage" :searchable="true"
                placeholder="Cantidad a mostrar" />
              <LabelToInput icon="list2" />
            </div>
          </div>
          <h2 class="font-semibold text-slate-800 pt-1">Total Conceptos Ingreso <span
              class="text-slate-400 font-medium">{{
                tableData.total
              }}</span></h2>
        </div>
      </header>

      <div class="overflow-x-auto">
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
          @datos-enviados="handleData($event)">
          <tbody class="text-sm divide-y divide-slate-200">
            <tr v-for="service in income_concept" :key="service.id_concepto_ingreso">
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ service.id_concepto_ingreso }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                <div class="font-medium text-slate-800 ellipsis">
                  {{ service.codigo_dependencia && service.nombre_dependencia ? service.codigo_dependencia + ' - ' +
                    service.nombre_dependencia : '' }}
                </div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                <div class="font-medium text-slate-800 ellipsis">{{ service.nombre_concepto_ingreso }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ service.id_ccta_presupuestal }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">
                  <div v-if="(service.estado_concepto_ingreso == 1)"
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
                  <DropDownOptions>
                    <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                      v-if="permits.actualizar == 1 && service.estado_concepto_ingreso == 1"
                      @click="editIncomeConcept(service)">
                      <div class="w-8 text-green-900">
                        <span class="text-xs">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                          </svg>
                        </span>
                      </div>
                      <div class="font-semibold">Editar</div>
                    </div>
                    <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                      @click="changeStateIncomeConcept(service.id_concepto_ingreso, service.nombre_concepto_ingreso, service.estado_concepto_ingreso)"
                      v-if="permits.eliminar == 1">
                      <div class="w-8 text-red-900"><span class="text-xs">
                          <svg fill="#7F1D1D" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" width="20px"
                            height="20px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 97.994 97.994"
                            xml:space="preserve" stroke="#7F1D1D">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                              <g>
                                <g>
                                  <path
                                    d="M97.155,9.939c-0.582-0.416-1.341-0.49-1.991-0.193l-10.848,4.935C74.08,5.29,60.815,0.118,46.966,0.118 c-15.632,0-30.602,6.666-41.07,18.289c-0.359,0.399-0.543,0.926-0.51,1.461c0.033,0.536,0.28,1.036,0.686,1.388l11.301,9.801 c0.818,0.711,2.055,0.639,2.787-0.162c6.866-7.512,16.636-11.821,26.806-11.821c6.135,0,12.229,1.584,17.622,4.583l-7.826,3.561 c-0.65,0.296-1.095,0.916-1.163,1.627c-0.069,0.711,0.247,1.405,0.828,1.82l34.329,24.52c0.346,0.246,0.753,0.373,1.163,0.373 c0.281,0,0.563-0.06,0.828-0.181c0.65-0.296,1.095-0.916,1.163-1.627l4.075-41.989C98.053,11.049,97.737,10.355,97.155,9.939z">
                                  </path>
                                  <path
                                    d="M80.619,66.937c-0.819-0.709-2.055-0.639-2.787,0.162c-6.866,7.514-16.638,11.822-26.806,11.822 c-6.135,0-12.229-1.584-17.622-4.583l7.827-3.561c0.65-0.296,1.094-0.916,1.163-1.628c0.069-0.711-0.247-1.404-0.828-1.819 L7.237,42.811c-0.583-0.416-1.341-0.49-1.991-0.193c-0.65,0.296-1.094,0.916-1.163,1.627L0.009,86.233 c-0.069,0.712,0.247,1.406,0.828,1.822c0.583,0.416,1.341,0.488,1.991,0.192l10.848-4.935 c10.237,9.391,23.502,14.562,37.351,14.562c15.632,0,30.602-6.666,41.07-18.289c0.358-0.398,0.543-0.926,0.51-1.461 c-0.033-0.536-0.28-1.036-0.687-1.388L80.619,66.937z">
                                  </path>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </span></div>
                      <div class="font-semibold">
                        {{ service.estado_concepto_ingreso ? 'Desactivar' : 'Activar' }}
                      </div>
                    </div>
                  </DropDownOptions>
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
                    <a @click="getIncomeConcept(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                      &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                    </a>
                  </div>
                </span>
                <span v-else-if="(link.label == 'Siguiente')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                  <div class="flex-1 text-right ml-2">
                    <a @click="getIncomeConcept(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                      <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                    </a>
                  </div>
                </span>
                <span class="cursor-pointer" v-else
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                    class=" w-5" @click="getIncomeConcept(link.url)">{{ link.label }}</span>
                </span>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>

    <ModalIncomeConceptVue :showModalIncome="showModalIncome" :modalData="modalData"
      :financing_sources="financing_sources" :budget_accounts="budget_accounts" :dependencies="dependencies"
      @cerrar-modal="showModalIncome = false" @get-table="getIncomeConcept(tableData.currentPage)" />

  </AppLayoutVue>
</template>

<script>
export default {
  created() {
    this.getIncomeConcept()
    this.getPermits()
    this.getSelectsIncomeConcept()
  },
  data() {
    let sortOrders = {};
    let columns = [
      { width: "10%", label: "ID", name: "id_concepto_ingreso", type: "text" },
      { width: "45%", label: "Dependencia", name: "nombre_dependencia", type: "text" },
      { width: "30%", label: "Concepto Ingreso", name: "nombre_concepto_ingreso", type: "text" },
      { width: "10%", label: "Especifico", name: "id_ccta_presupuestal", type: "text" },
      {
        width: "10%", label: "Estado", name: "estado_concepto_ingreso", type: "select",
        options: [
          { value: "1", label: "Activo" },
          { value: "0", label: "Inactivo" }
        ]
      },
      { width: "1%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      if (column.name === 'id_concepto_ingreso')
        sortOrders[column.name] = 1;
      else
        sortOrders[column.name] = -1;
    });
    return {
      //Data for datatable
      income_concept: [],
      //Data for modal
      showModalIncome: false,
      modalData: [],

      permits: [],
      budget_accounts: [],
      dependencies: [],
      financing_sources: [],
      links: [],
      columns: columns,
      sortKey: "id_concepto_ingreso",
      sortOrders: sortOrders,
      perPage: ["10", "20", "30"],
      tableData: {
        currentPage: '',
        draw: 0,
        length: 5,
        search: "",
        column: 0,
        dir: "desc",
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
    getSelectsIncomeConcept() {
      axios.get("/get-selects-income-concept")
        .then((response) => {
          this.budget_accounts = response.data.budget_accounts
          this.dependencies = response.data.dependencies
          this.financing_sources = response.data.financing_sources
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
    changeStateIncomeConcept(id_service, name_service, state_service) {
      let msg
      state_service == 1 ? msg = "Desactivar" : msg = "Activar"
      this.$swal.fire({
        title: msg + ' concepto de ingreso: ' + name_service + '.',
        text: "¿Estas seguro?",
        icon: "question",
        iconHtml: "❓",
        confirmButtonText: 'Si, ' + msg,
        confirmButtonColor: "#001b47",
        cancelButtonText: "Cancelar",
        showCancelButton: true,
        showCloseButton: true
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/change-state-income-concept", {
            id_service: id_service,
            state_service: state_service
          })
            .then((response) => {
              this.$swal.fire({
                text: response.data.mensaje,
                icon: 'success',
                timer: 5000
              })
              this.getIncomeConcept(this.tableData.currentPage);
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
    async getIncomeConcept(url = "/ingresos") {
      this.tableData.draw++;
      this.tableData.currentPage = url
      await axios.post(url, this.tableData).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          this.tableData.total = data.data.total;
          this.links[0].label = "Anterior";
          this.links[this.links.length - 1].label = "Siguiente";
          this.income_concept = data.data.data;
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
        this.getIncomeConcept();
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
      this.getIncomeConcept()
    }
  }
}
</script>

<style>
.td-data-table {
  max-width: 100px;
  white-space: nowrap;
  height: 50px;
}

.ellipsis {
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>