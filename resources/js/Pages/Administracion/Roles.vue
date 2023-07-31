<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import GeneralButton from '@/Components-ISRI/ComponentsToForms/GeneralButton.vue';
import TextInput from '@/Components-ISRI/ComponentsToForms/TextInput.vue';
import LabelToInput from '@/Components-ISRI/ComponentsToForms/LabelToInput.vue';
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalRolesVue from '@/Components-ISRI/Administracion/ModalRoles.vue';
import ModalCreateRoleVue from '@/Components-ISRI/Administracion/ModalCreateRole.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
  <Head title="Administracion" />
  <AppLayoutVue nameSubModule="Administracion - Roles">
    <div class="sm:flex sm:justify-end sm:items-center mb-2">
      <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
        <GeneralButton v-if="permits.insertar == 1" @click="createRol()" color="bg-green-700  hover:bg-green-800"
          text="Agregar Rol" icon="add" />
      </div>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
      <header class="px-5 py-4">
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
            <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
              <Multiselect v-model="tableData.length" @select="getRoles()" :options="perPage" :searchable="true" 
              placeholder="Cantidad a mostrar"/>
              <LabelToInput icon="list2" />
            </div>
          </div>
          <h2 class="font-semibold text-slate-800 pt-1">Total Roles <span class="text-slate-400 font-medium">{{
            tableData.total
          }}</span></h2>
        </div>
      </header>

      <div class="overflow-x-auto">
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true" @sort="sortBy"
          @datos-enviados="handleData($event)" @execute-search="getRoles()">
          <tbody class="text-sm divide-y divide-slate-200">
            <tr v-for="rol in roles" :key="rol.id_rol">
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800 text-center">{{ rol.id_rol }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                <div class="font-medium text-slate-800 ellipsis text-center">{{ rol.nombre_sistema }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                <div class="font-medium text-slate-800 ellipsis text-center">{{ rol.nombre_rol }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800 text-center">{{ moment(rol.fecha_reg_rol).format('DD/MM/YYYY') }}
                </div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                <div class="font-medium text-slate-800 text-center">
                  <div v-if="(rol.estado_rol == 1)"
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
                      v-if="permits.actualizar == 1 && rol.estado_rol == 1" @click="getSelectsRolMenu(rol.id_rol)">
                      <div class="w-8 text-green-900">
                        <span class="text-xs">
                          <span class="text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                              stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                          </span>
                        </span>
                      </div>
                      <div class="font-semibold">Editar</div>
                    </div>
                    <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                      @click="desactiveRol(rol.id_rol, rol.nombre_rol, rol.estado_rol)" v-if="permits.eliminar == 1">
                      <div class="w-8 text-red-900"><span class="text-xs">
                          <svg :fill="rol.estado_rol == 1 ? '#991B1B' : '#166534'" version="1.1" id="Capa_1"
                            xmlns="http://www.w3.org/2000/svg" width="20px" height="20px"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 97.994 97.994" xml:space="preserve"
                            :stroke="rol.estado_rol == 1 ? '#991B1B' : '#166534'">
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
                        </span>
                      </div>
                      <div class="font-semibold">
                        {{ rol.estado_rol ? 'Desactivar' : 'Activar' }}
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
        <p class="font-semibold text-red-500 text-[16px]" style="margin: 0 auto; text-align: center;">No se encontraron registros.</p>
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
                    <a @click="getRoles(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer text-indigo-500">
                      &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                    </a>
                  </div>
                </span>
                <span v-else-if="(link.label == 'Siguiente')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                  <div class="flex-1 text-right ml-2">
                    <a @click="getRoles(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer text-indigo-500">
                      <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                    </a>
                  </div>
                </span>
                <span class="cursor-pointer" v-else
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                    class=" w-5" @click="getRoles(link.url)">{{ link.label }}</span>
                </span>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>

    <ModalRolesVue :modalVar="modalVar" :showModal="showModal" :modalData="modalData" @cerrar-modal="closeVars"
      @abrir-modal="showModal = true" @update-table="getUpdateTable()" />

    <ModalCreateRoleVue :showModalCreate="showModalCreate" :modalDataCreate="modalDataCreate"
      @update-table="getUpdateTable()" @cerrar-modal="closeModalCreate()" @abrir-modal="showModalCreate = true" />

  </AppLayoutVue>
</template>

<script>
export default {
  created() {
    this.getRoles()
    this.getPermissions(this)
  },
  data: function (data) {
    let sortOrders = {};
    let columns = [
      { width: "10%", label: "ID", name: "id_rol", type: "text" },
      { width: "20%", label: "Nombre Sistema", name: "nombre_sistema", type: "text" },
      { width: "25%", label: "Nombre Rol", name: "nombre_rol", type: "text" },
      { width: "25%", label: "Fecha Registro", name: "fecha_reg_rol", type: "date" },
      {
        width: "10%", label: "Estado", name: "estado_rol", type: "select",
        options: [
          { value: "1", label: "Activo" },
          { value: "0", label: "Inactivo" }
        ]
      },
      { width: "10%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      if (column.name === 'id_rol')
        sortOrders[column.name] = 1;
      else
        sortOrders[column.name] = -1;
    });
    return {
      empty_object: false,
      permits: [],
      modalData: {
        insertar: false,
        actualizar: false,
        eliminar: false,
        ejecutar: false,
        rolMenus: [],
        id_rol: "",
        nombre_rol: "",
        id_menus_rol: [],
        childrenMenus: [],
        id_childrenMenu: "",
        menus: "",
        id_menu: "",
      },
      modalDataCreate: {
        insertar: false,
        actualizar: false,
        eliminar: false,
        ejecutar: false,
        //The name of the new role.
        nombre_rol: '',
        //List of all registered systems
        sistemas: [],
        //Selected system Id
        id_sistema: '',
        //Select parents menus
        parentsMenu: [],
        //This is the identifier of the parent menu
        id_menu: '',
        //This is the name of the selected parent menu.
        nombre_parent_menu: '',
        //Array of children's menu
        childrenMenus: [],
        //Id of the selected child menu
        id_childrenMenu: '',
        name_childrenMenu: '',
        //List of selected child menus.
        menus: [],
        //Property to disable system select.
        select_sistema: false
      },
      showModal: false,
      showModalCreate: false,
      modalVar: false,
      roles: [],
      links: [],
      columns: columns,
      sortKey: "id_rol",
      sortOrders: sortOrders,
      perPage: ["10", "20", "30"],
      tableData: {
        currentPage: '',
        draw: 0,
        length: 5,
        search: {},
        column: 0,
        dir: "asc",
        total: ""
      },
    };
  },
  methods: {
    //Methods for creating a new role
    async createRol() {
      await axios.get("/systems-all")
        .then((response) => {
          this.modalDataCreate.sistemas = response.data.sistemas
          this.showModalCreate = true
        })
        .catch((errors) => {
          this.manageError(errors,this)
        })
    },
    closeModalCreate() {
      this.showModalCreate = false
      this.cleanModalInputsCreate()
    },
    cleanModalInputsCreate() {
      this.modalDataCreate.sistemas = []
      this.modalDataCreate.id_sistema = ''
      this.modalDataCreate.parentsMenu = []
      this.modalDataCreate.id_menu = ''
      this.modalDataCreate.nombre_parent_menu = ''
      this.modalDataCreate.childrenMenus = []
      this.modalDataCreate.nombre_rol = ''
      this.modalDataCreate.menus = []
      this.modalDataCreate.select_sistema = false
      this.modalDataCreate.insertar = false
      this.modalDataCreate.actualizar = false
      this.modalDataCreate.eliminar = false
      this.modalDataCreate.ejecutar = false
    },

    //Methods for ModalRoles
    getUpdateTable() {
      this.getRoles(this.tableData.currentPage);
    },
    closeVars() {
      this.showModal = false
      this.modalVar = false
      this.cleanModalInputs()
    },
    cleanModalInputs() {
      this.modalData.id_childrenMenu = ""
      this.modalData.childrenMenus = ""
      this.modalData.id_menu = ""
      this.modalData.insertar = 0
      this.modalData.actualizar = 0
      this.modalData.eliminar = 0
      this.modalData.ejecutar = 0
    },

    //Methods for datatable
    desactiveRol(id_rol, nombre_rol, estado_rol) {
      let msg
      estado_rol == 1 ? msg = "Desactivar" : msg = "Activar"
      this.$swal.fire({
        title: msg + ' Rol ' + nombre_rol + ' para todos los usuarios',
        text: "Estas seguro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, ' + msg
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/change-state-role-all", {
            id_rol: id_rol,
            estado_rol: estado_rol
          })
            .then((response) => {
              this.$swal.fire({
                text: response.data.mensaje,
                icon: 'success',
                timer: 2000
              })
              this.getRoles(this.tableData.currentPage);
            })
            .catch((errors) => {
              this.manageError(errors,this)
            })
        }
      })
    },
    getSelectsRolMenu(identificador) {
      this.modalVar = true;
      this.modalData.id_rol = identificador;
    },
    async getRoles(url = "/roles") {
      this.tableData.currentPage = url
      this.tableData.draw++;
      await axios.get(url, { params: this.tableData }).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          console.log(data);
          this.tableData.total = data.data.total;
          this.links[0].label = "Anterior";
          this.links[this.links.length - 1].label = "Siguiente";
          this.roles = data.data.data;
          this.roles.length > 0 ? this.empty_object = false : this.empty_object = true
        }
      }).catch((errors) => {
        this.manageError(errors,this)
        //console.log(errors);
      })
    },
    sortBy(key) {
      if (key != "Acciones") {
        this.sortKey = key;
        this.sortOrders[key] = this.sortOrders[key] * -1;
        this.tableData.column = this.getIndex(this.columns, "name", key);
        this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
        this.getRoles();
      }
    },
    getIndex(array, key, value) {
      return array.findIndex((i) => i[key] == value);
    },
    handleData(myEventData) {
      this.tableData.search = myEventData;
      const data = Object.values(myEventData);
      if (data.every(error => error === '')) {
        this.getRoles()
      }
    }
  },
};
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
