<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import GeneralButton from '@/Components-ISRI/ComponentsToForms/GeneralButton.vue';
import TextInput from '@/Components-ISRI/ComponentsToForms/TextInput.vue';
import LabelToInput from '@/Components-ISRI/ComponentsToForms/LabelToInput.vue';
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

//import { toast } from 'vue3-toastify';
//import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>

  <Head title="Administracion" />
  <AppLayoutVue>
    <div class="sm:flex sm:justify-end sm:items-center mb-2">
      <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
        <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add" />
      </div>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
      <header class="px-5 py-4 flex">
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/6">
          <div class="relative flex h-8 w-full flex-row-reverse div-select2">
            <Select2 id="select" name="domain" class="text-xs" v-model="tableData.length" @change="getRoles()"
              :options="perPage" />
            <LabelToInput icon="list" for-label="select" />
          </div>
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="search-menu" type="text" v-model="tableData.search" placeholder="Search Table"
            @input="getRoles()">
            <LabelToInput icon="search" forLabel="search-menu" />
          </TextInput>
        </div>
        <h2 class="font-semibold text-slate-800 pt-1">Total Roles <span class="text-slate-400 font-medium">{{
          tableData.total
        }}</span></h2>
      </header>
      <div class="overflow-x-auto">
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
          <tbody class="text-sm divide-y divide-slate-200">
            <tr v-for="rol in roles" :key="rol.id_rol">
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ rol.id_rol }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ rol.nombre_sistema }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ rol.nombre_rol }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ rol.fecha_reg_rol }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">
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
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="space-x-1">
                  <button @click="getSelectsRolMenu(rol.id_rol)" class="text-slate-400 hover:text-slate-500 rounded-full">
                    <span class="sr-only">Edit</span>
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                      <path
                        d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                      </path>
                    </svg>
                  </button>
                  <button class="text-slate-400 hover:text-slate-500 rounded-full">
                    <span class="sr-only">Download</span><svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                      <path
                        d="M16 20c.3 0 .5-.1.7-.3l5.7-5.7-1.4-1.4-4 4V8h-2v8.6l-4-4L9.6 14l5.7 5.7c.2.2.4.3.7.3zM9 22h14v2H9z">
                      </path>
                    </svg>
                  </button>
                  <button @click="desactiveRol(rol.id_rol,rol.nombre_rol,rol.estado_rol)" class="text-rose-500 hover:text-rose-600 rounded-full">
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

    <div class="px-6 py-8 bg-white shadow-lg
     rounded-sm border-slate-200 relative">
      <div>
        <nav class="flex justify-between" role="navigation" aria-label="Navigation">

          <div class="grow text-center">
            <ul class="inline-flex text-sm font-medium -space-x-px">
              <li v-for="link in links" :key="link.label">
                <span v-if="(link.label == 'Anterior')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                  <div class="flex-1 text-right ml-2">
                    <a @click="getRoles(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                      text-indigo-500">
                      &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                    </a>
                  </div>
                </span>
                <span v-else-if="(link.label == 'Siguiente')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                  <div class="flex-1 text-right ml-2">
                    <a @click="getRoles(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                      text-indigo-500">
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

    <!-- Modal Menus-->
    <ModalVue :show="showModal" @close="changeStateFromModal" v-bind:title="'Gestion de menus : '+modalData.nombre_rol" @close-modal="changeStateFromModal">
      <div class="px-5 pt-4 pb-1">
        <div class="text-sm">
          <div class="mb-4">Selecciona un menu y un submenu:</div>
          <div class="mb-4 md:flex flex-row justify-items-start">
            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="modalData.id_menu" :options="modalData.menus" @select="getChildrenMenus()" placeholder="Menu" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="modalData.id_childrenMenu" :options="modalData.childrenMenus"  placeholder="Sub Menu" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
          </div>
          <div class="mb-4 md:flex flex-row justify-center">
            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton @click="saveMenu()" color="bg-orange-700  hover:bg-orange-800" text="Agregar" icon="add" />
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton text="Cancelar" icon="add" @click="changeStateFromModal" />
            </div>
          </div>
          <div class="tabla-modal">
            <table class="w-full" id="tabla_modal_validacion_arranque">
              <thead class="bg-[#1F3558] text-white">
                <tr class="">
                  <th class="rounded-tl-lg">#</th>
                  <th>Menu</th>
                  <th>Submenu</th>
                  <th class="rounded-tr-lg">ACCIONES</th>
                </tr>
              </thead>
              <tbody v-for="menu in modalData.rolMenus" :key="menu.id_menu" class="text-sm divide-y divide-slate-200">
                <tr class="hover:bg-[#141414]/10">
                  <td class="text-center">{{ menu.id_menu }}</td>
                  <td class="text-center">{{ menu.parent_menu.nombre_menu }}</td>
                  <td class="text-center">{{ menu.nombre_menu }}</td>
                  <td class="text-center">
                    <div class="space-x-1">
                      <button class="text-slate-400 hover:text-slate-500 rounded-full">
                        <span class="sr-only">Edit</span>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                          <path
                            d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                          </path>
                        </svg>
                      </button>
                      <button @click="desactiveMenu(menu.id_menu, menu.nombre_menu)" class="text-rose-500 hover:text-rose-600 rounded-full">
                        <span class="sr-only">Delete</span><svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
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
            </table>
          </div>
          <div class="text-xs text-slate-500">ISRI2023</div>
        </div>
      </div>
    </ModalVue>
    <!-- Fin Modal Menus-->

  </AppLayoutVue>
</template>

<script>
export default {
  created() {
    this.getRoles();
  },
  data: function (data) {
    let sortOrders = {};
    let columns = [
      { width: "10%", label: "ID", name: "id_rol" },
      { width: "20%", label: "Nombre Sistema", name: "nombre_sistema" },
      { width: "20%", label: "Nombre Rol", name: "nombre_rol" },
      { width: "20%", label: "Fecha Registro", name: "fecha_reg_rol" },
      { width: "15%", label: "Estado", name: "estado_rol" },
      { width: "15%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      if(column.name==='id_rol')
      sortOrders[column.name] = 1;
      else
      sortOrders[column.name] = -1;
    });
    return {
      modalData:{
        rolMenus:[],
        id_rol:"",
        nombre_rol:"",
        id_menus_rol:[],
        childrenMenus:[],
        id_childrenMenu:"",
        menus:"",
        id_menu:"",

        // sistema_edit:"",
        // id_rol_edit:"",
        // roles_edit:"",
        // id_sistema_edit:"",
        // permiso_usuario:""
      },
      showModal: false,
      //showModal2: false,
      roles: [],
      links: [],
      columns: columns,
      sortKey: "id_rol",
      sortOrders: sortOrders,
      perPage: ["10", "20", "30"],
      tableData: {
        currentPage:'',
        draw: 0,
        length: 5,
        search: "",
        column: 0,
        dir: "asc",
        total: ""
      },
    };
  },
  methods: {
    desactiveMenu(id_menu,nombre_menu){
      this.$swal.fire({
        title: 'Desactivar Menu ' + nombre_menu,
        text: "Estas seguro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, desactivar!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/desactive/menu", {
            id_menu: id_menu,
            id_rol: this.modalData.id_rol
          }).then((response) => {
            toast.success(response.data.mensaje, {
              autoClose: 3000,
              position: "top-right",
              transition: "zoom",
              toastBackgroundColor: "white",
            });
            this.cleanModalInputs()
            this.getMenus()
            }).catch((errors) => console.log(errors))
          }
        })
    },
    cleanModalInputs(){
      this.modalData.id_childrenMenu=""
      this.modalData.childrenMenus=""
      this.modalData.id_menu=""
    },
    saveMenu() {
      if (this.modalData.id_menu != "" && this.modalData.id_childrenMenu != "") {
          this.$swal.fire({
            title: 'Esta seguro de guardar el menu',
            icon: 'question',
            iconHtml: '✅',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#15803D',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
          }).then((result) => {
            if (result.isConfirmed) {
              axios.post("/save/menu", {
                id_menu: this.modalData.id_menu,
                id_childrenMenu: this.modalData.id_childrenMenu,
                id_rol: this.modalData.id_rol
              }).then((response) => {
                toast.success(response.data.mensaje, {
                  autoClose: 3000,
                  position: "top-right",
                  transition: "zoom",
                  toastBackgroundColor: "white",
                });
                this.cleanModalInputs()
                this.getMenus()
                }).catch((errors) => console.log(errors))
              }
            })
      } else {
        this.$swal.fire({
          title: 'Información incompleta',
          text: "Debes seleccionar Menu y Submenu",
          icon: 'warning',
          timer:5000
          })
      }
    },
    getSelectsRolMenu(identificador){
      this.modalData.id_rol=identificador
      this.getMenus()
    },
    getChildrenMenus(){
      this.modalData.id_childrenMenu=""
      axios.get('/menus/childrenMenus',{params: this.modalData})
        .then((response) => {
          this.modalData.childrenMenus=response.data.childrenMenus
          //console.log(response.data.childrenMenus);
        })
        .catch((errors) => console.log(errors))
    },
    async getMenus(){
      await axios.get("/menus",{params: this.modalData})
        .then((response) => {
          this.modalData.rolMenus=response.data.rolMenus
          this.modalData.menus=response.data.menus
          this.modalData.id_menus_rol=response.data.id_menus_asignados
          this.modalData.nombre_rol=response.data.nombre_rol
          console.log(response.data.menus); 
          if(this.showModal==false){
            this.changeStateFromModal()
            this.cleanModalInputs()
          }
        })
        .catch((errors) => console.log(errors))
    },
    changeStateFromModal() {
      this.showModal = !this.showModal;
    },
    desactiveRol(id_rol,nombre_rol,estado_rol){
      let msg
      estado_rol==1 ? msg="Desactivar":msg="Activar"
      this.$swal.fire({
        title: msg+' Rol '+nombre_rol+' para todos los usuarios',
        text: "Estas seguro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText:'Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, '+msg
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/change/rol/all",{
                id_rol: id_rol,
                estado_rol:estado_rol
              })
            .then((response) => {
              this.$swal.fire({
                text:response.data.mensaje,
                icon: 'success',
                timer:2000
              })
              this.getRoles('http://127.0.0.1:8000/roles?page='+this.tableData.currentPage);
            })
            .catch((errors) => console.log(errors))
        }
      })
    },
    async getRoles(url = "/roles") {
      this.tableData.draw++;
      await axios.get(url, { params: this.tableData }).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          this.tableData.total=data.data.total;
          this.tableData.currentPage=data.data.current_page;
          console.log(data.data);
          this.links[0].label = "Anterior";
          this.links[this.links.length - 1].label = "Siguiente";
          this.roles = data.data.data;
        }
      }).catch((errors) => {
        console.log(errors.response.data);
      });
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
  },
};
</script>
