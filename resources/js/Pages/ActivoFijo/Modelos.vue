<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalAdminModeloVue from '@/Components-ISRI/ActivoFijo/ModalAdminModelo.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
  <Head title="Administracion" />
  <AppLayoutVue nameSubModule="Activo Fijo - Modelos">
    <div class="sm:flex sm:justify-end sm:items-center mb-2">
      <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
        <GeneralButton @click="addModel()" v-if="permits.insertar==1" color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add" />
      </div>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
      <header class="px-5 py-4">
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
            <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
              <Multiselect v-model="tableData.length" @select="getModels()" :options="perPage" :searchable="true" />
              <LabelToInput icon="date" />
            </div>
          </div>
          <h2 class="font-semibold text-slate-800 pt-1">Total Modelos <span class="text-slate-400 font-medium">{{
          tableData.total
        }}</span></h2>
        </div>
      </header>

      <div class="overflow-x-auto">
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
        @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getModels()">
          <tbody class="text-sm divide-y divide-slate-200">
            <tr v-for="model in models" :key="model.id_modelo">
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800 text-center">{{ model.id_modelo }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px td-data-table">
                <div class="font-medium text-slate-800 ellipsis text-center">{{ model.nombre_marca }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px td-data-table">
                <div class="font-medium text-slate-800 ellipsis text-center">{{ model.nombre_modelo }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800 text-center">{{ formatearFecha(model.fecha_reg_modelo) }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800 text-center">
                  <div v-if="(model.estado_modelo == 1)"
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
                <div class="space-x-1 text-center">
                  <DropDownOptions>
                    <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                      v-if="permits.actualizar == 1 && model.estado_modelo == 1"
                      @click="editModel(model)">
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
                      @click="changeStateModel(model)"
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
                        {{ model.estado_modelo ? 'Desactivar' : 'Activar' }}
                      </div>
                    </div>
                  </DropDownOptions>
                </div>
              </td>
            </tr>
          </tbody>
        </datatable>
      </div>
      <div v-if="empty_object" class="flex text-center py-2">
        <p class="font-semibold text-[16px]" style="margin: 0 auto; text-align: center;">No se encontraron registros.</p>
      </div>
    </div>

    <div v-if="!empty_object" class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
      <div>
        <nav class="flex justify-between" role="navigation" aria-label="Navigation">
          <div class="grow text-center">
            <ul class="inline-flex text-sm font-medium -space-x-px">
              <li v-for="link in links" :key="link.label">
                <span v-if="(link.label == 'Anterior')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                  <div class="flex-1 text-right ml-2">
                    <a @click="getModels(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                      &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                    </a>
                  </div>
                </span>
                <span v-else-if="(link.label == 'Siguiente')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                  <div class="flex-1 text-right ml-2">
                    <a @click="getModels(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                      <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                    </a>
                  </div>
                </span>
                <span class="cursor-pointer" v-else
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                    class=" w-5" @click="getModels(link.url)">{{ link.label }}</span>
                </span>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>

    <ModalAdminModeloVue :showModal="showModal" :modalData="modalData"
            @cerrar-modal="showModal=false" @get-table="getModels(tableData.currentPage)"/>

  </AppLayoutVue>
  
</template>

<script>
export default {
  created() {
    this.getModels()
    this.getPermits()
  },
  data(){
    let sortOrders = {};
    let columns = [
      { width: "10%", label: "ID", name: "id_modelo", type: "text" },
      { width: "20%", label: "Nombre Marca", name: "nombre_marca", type: "text" },
      { width: "25%", label: "Nombre Modelo", name: "nombre_modelo", type: "text" },
      { width: "25%", label: "Fecha Registro", name: "fecha_reg_modelo", type: "date" },
      {
        width: "10%", label: "Estado", name: "estado_modelo", type: "select",
        options: [
          {value: "1", label: "Activo"},
          {value: "0", label: "Inactivo"}
        ]
      },
      { width: "10%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      if (column.name === 'id_modelo')
        sortOrders[column.name] = 1;
      else
        sortOrders[column.name] = -1;
    });
    return {
      permits : [],
      models: [],
      links: [],
      columns: columns,
      sortKey: "id_modelo",
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
      showModal:false,
      modalData : [],
    }
  },
  methods:{
    formatearFecha(date) {
      return moment(date).format('DD/MM/YYYY');
    },
    editModel(model){
        this.modalData = model
        this.showModal=true
    },
    addModel(){
      this.modalData=[]
      this.showModal=true
    },
    changeStateModel(model){
        let msg
        model.estado_modelo == 1 ? msg = "Desactivar" : msg = "Activar"
        this.$swal.fire({
          title: msg + ' Modelo ' + model.nombre_modelo + '.',
          text: "¿Estas seguro?",
          icon: 'warning',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, ' + msg
        }).then((result) => {
          if (result.isConfirmed) {
            axios.post("/change-state-model", {
              id_model: model.id_modelo,
              state_model: model.estado_modelo
            })
              .then((response) => {
                this.$swal.fire({
                  text: response.data.mensaje,
                  icon: 'success',
                  timer: 5000
                })
                this.getModels(this.tableData.currentPage);
              })
              .catch((errors) => {
                  let msg = this.manageError(errors)
                  this.$swal.fire({
                  title: 'Operación cancelada',
                  text: msg,
                  icon: 'warning',
                  timer:5000
                  })
              })
          }
        })
    },
    async getModels(url = "/modelos") {
      this.tableData.draw++;
      this.tableData.currentPage=url
      await axios.post(url,this.tableData).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          this.tableData.total = data.data.total;
          this.links[0].label = "Anterior";
          this.links[this.links.length - 1].label = "Siguiente";
          this.models = data.data.data;
          this.models.length>0 ? this.empty_object=false : this.empty_object=true
        }
      }).catch((errors) => {
          let msg = this.manageError(errors)
          this.$swal.fire({
            title: 'Operación cancelada',
            text: msg,
            icon: 'warning',
            timer:5000
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
        this.getModels();
      }
    },
    getIndex(array, key, value) {
      return array.findIndex((i) => i[key] == value);
    },
    getPermits(){
      var URLactual = window.location.pathname
      let data = this.$page.props.menu;
      let menu = JSON.parse(JSON.stringify(data['urls']))
      menu.forEach((value, index) => {
        value.submenu.forEach((value2, index2) => {
          if(value2.url===URLactual){
            var array = {'insertar':value2.insertar,'actualizar':value2.actualizar,'eliminar':value2.eliminar,'ejecutar':value2.ejecutar}
            this.permits = array
          }
        })
      })
    },
    handleData(myEventData) {
      this.tableData.search = myEventData;
      const data = Object.values(myEventData);
      if (data.every(error => error === '')) {
        this.getModels()
      }
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
