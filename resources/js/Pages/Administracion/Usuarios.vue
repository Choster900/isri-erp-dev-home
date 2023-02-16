<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import GeneralButton from '@/Components-ISRI/ComponentsToForms/GeneralButton.vue';
import TextInput from '@/Components-ISRI/ComponentsToForms/TextInput.vue';
import LabelToInput from '@/Components-ISRI/ComponentsToForms/LabelToInput.vue';
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";

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
            <Select2 id="select" name="domain" class="text-xs" v-model="tableData.length" @select="getUsers()"
              :options="perPage" />
            <LabelToInput icon="list" for-label="select" />
          </div>
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="search-user" type="text" v-model="tableData.search" placeholder="Search Table"
            @input="getUsers()">
            <LabelToInput icon="search" forLabel="search-user" />
          </TextInput>
        </div>
        <h2 class="font-semibold text-slate-800 pt-1">All Users<span class="text-slate-400 font-medium">{{
          pagination.total
        }}</span></h2>
      </header>
      <div class="overflow-x-auto">
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
          <tbody class="text-sm divide-y divide-slate-200">
            <tr v-for="user in users" :key="user.id_usuario">
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ user.id_usuario }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ user.nick_usuario }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">
                  <div v-if="(user.estado_usuario == 1)"
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
                  <button @click="getSelectsRolSistema(user.id_usuario)" class="text-slate-400 hover:text-slate-500 rounded-full">
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
                  <button class="text-rose-500 hover:text-rose-600 rounded-full">
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
                    <a @click="getUsers(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                      text-indigo-500">
                      &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                    </a>
                  </div>
                </span>
                <span v-else-if="(link.label == 'Siguiente')"
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                  <div class="flex-1 text-right ml-2">
                    <a @click="getUsers(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                      text-indigo-500">
                      <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                    </a>
                  </div>
                </span>
                <span class="cursor-pointer" v-else
                  :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                    class=" w-5" @click="getUsers(link.url)">{{ link.label }}</span>
                </span>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>

    <!-- Modal -->
    <ModalVue :show="showModal" @close="changeStateFromModal" v-bind:title="'Gestion de roles usuario: '+modalData.nombre_usuario" @close-modal="changeStateFromModal">
      <div class="px-5 pt-4 pb-1">
        <div class="text-sm">
          <div class="mb-4">Selecciona un rol y un sistema:</div>
          <div class="mb-4 md:flex flex-row justify-items-start">
            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="modalData.id_sistema" @select="getRolesxSistema()" :options="modalData.sistemas" placeholder="Sistema" />

                <!-- <Select2 class="text-xs"  :options="test" placeholder="Sistema" /> -->

                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="modalData.id_rol" :options="modalData.roles" placeholder="Rol" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
          </div>

          <div class="mb-4 md:flex flex-row justify-center">

            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton @click="saveRol()" color="bg-orange-700  hover:bg-orange-800" text="Agregar" icon="add" />

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
                  <th>SISTEM</th>
                  <th>ROL</th>
                  <th class="rounded-tr-lg">ACCIONES</th>
                </tr>
              </thead>
              <tbody v-for="rol in modalData.userRoles" :key="rol.id_rol" class="text-sm divide-y divide-slate-200">
                <tr v-if="rol.pivot.estado_permiso_usuario==1" class="hover:bg-[#141414]/10">
                  <td class="text-center">{{ rol.id_rol }}</td>
                  <td class="text-center">{{ rol.sistema.nombre_sistema }}</td>
                  <td class="text-center">{{ rol.nombre_rol }}</td>
                  <td class="text-center">
                    <div class="space-x-1">
                      <button @click="editRol(rol.sistema.id_sistema,rol.sistema.nombre_sistema,rol.id_rol)" class="text-slate-400 hover:text-slate-500 rounded-full">
                        <span class="sr-only">Edit</span>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                          <path
                            d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                          </path>
                        </svg>
                      </button>
                      <button @click="deleteRol(rol.id_rol,rol.nombre_rol)" class="text-rose-500 hover:text-rose-600 rounded-full">
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

      <!-- <div class="px-5 py-4">
        <div class="flex flex-wrap justify-end space-x-2"> -->
          <!-- <GeneralButton color="bg-orange-700  hover:bg-orange-800" text="Agregar" icon="add" @click="deletAlert()" />
          <GeneralButton text="Cancelar" icon="add" @click="changeStateFromModal" /> -->
        <!-- </div>
      </div> -->
    </ModalVue>

    <!-- Modal cambio de rol -->
    <ModalVue :show="showModal2" @close="changeStateFromModal2" title="Cambio de rol" @close-modal="changeStateFromModal2">
      <div class="px-5 pt-4 pb-1">
        <div class="text-sm">
          <div class="mb-4">Sistema : {{ modalData.sistema_edit }}</div>
          <div class="mb-4 md:flex flex-row justify-items-start">
            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="modalData.id_rol_edit" :options="modalData.roles_edit" placeholder="Rol" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
          </div>
          <div class="mb-4 md:flex flex-row justify-center">
            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton @click="updateRol()" color="bg-orange-700  hover:bg-orange-800" text="Aceptar" icon="add" />
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton text="Cancelar" icon="add" @click="changeStateFromModal2" />
            </div>
          </div>
          <div class="text-xs text-slate-500">ISRI2023</div>
        </div>
      </div>
    </ModalVue>
    <!-- Fin modal cambio de rol -->

  </AppLayoutVue>
</template>

<script>
export default {
  created() {
    this.getUsers();
  },
  data: function (data) {
    let sortOrders = {};
    let columns = [
      { width: "25%", label: "ID", name: "id_usuario" },
      { width: "25%", label: "User Name", name: "nick_usuario" },
      { width: "25%", label: "Estado", name: "estado_usuario" },
      { width: "25%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      if(column.name==='id_usuario')
      sortOrders[column.name] = 1;
      else
      sortOrders[column.name] = -1;
    });
    return {
      modalData:{
        userRoles:[],
        id_usuario:"",
        nombre_usuario:"",
        sistemas:"",
        roles:"",
        id_sistema:"",
        id_rol:"",

        sistema_edit:"",
        id_rol_edit:"",
        roles_edit:"",
        id_sistema_edit:"",
        permiso_usuario:""
      },
      showModal: false,
      showModal2: false,
      users: [],
      links: [],
      columns: columns,
      sortKey: "id_usuario",
      sortOrders: sortOrders,
      perPage: ["10", "20", "30"],
      tableData: {
        draw: 0,
        length: 5,
        search: "",
        column: 0,
        dir: "asc",
      },
      pagination: {
        lastPage: "",
        currentPage: "",
        total: "",
        lastPageUrl: "",
        nextPageUrl: "",
        prevPageUrl: "",
        from: "",
        to: "",
      },
    };
  },
  methods: {

    cleanModalInputs(){
      this.modalData.id_rol=""
      this.modalData.id_sistema=""
    },
    getSelectsRolSistema(identificador){
      this.modalData.id_usuario=identificador
      this.getSistemas()
    },
    async getSistemas(){
      await axios.get("/sistemas",{params: this.modalData})
        .then((response) => {
          this.modalData.userRoles=response.data.userRoles
          this.modalData.sistemas=response.data.sistemas
          this.modalData.nombre_usuario=response.data.nombre_usuario
          if(this.showModal==false){
            this.changeStateFromModal()
          }
        })
        .catch((errors) => console.log(errors))
    },
    saveRol(){
      if(this.modalData.id_rol!="" && this.modalData.id_sistema!=""){
        this.$swal.fire({
        title: 'Esta seguro de guardar el rol',
        icon: 'question',
        iconHtml: '✅',
        confirmButtonText: 'Si, Guardar',
        confirmButtonColor: '#15803D',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        showCloseButton: true
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/save/rol",{
                id_rol: this.modalData.id_rol,
                id_usuario: this.modalData.id_usuario
              })
            .then((response) => {
              this.$swal.fire({
                text:response.data.mensaje,
                icon: 'success',
                timer:2000
              })
              this.cleanModalInputs()
              this.getSistemas()
            })
            .catch((errors) => console.log(errors))
        }
      })
      }else{
        this.$swal.fire({
          text:'Debes seleccionar sistema y rol',
          icon: 'warning',
          timer:2000
        })
      }
    },
    async getRolesxSistema(){
      await axios.get("/rolesxsistemas",{params: this.modalData})
        .then((response) => {
          this.modalData.roles=response.data.roles
        })
        .catch((errors) => console.log(errors))
    },
    changeStateFromModal() {
      this.showModal = !this.showModal;
    },
    changeStateFromModal2() {
      this.showModal2 = !this.showModal2;
    },
    deleteRol(id_rol,nombre_rol){
      this.$swal.fire({
        title: 'Desactivar Rol '+nombre_rol,
        text: "Estas seguro?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText:'Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, desactivar!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/desactive/rol",{
                id_rol: id_rol,
                id_usuario: this.modalData.id_usuario
              })
            .then((response) => {
              this.$swal.fire({
                text:response.data.mensaje,
                icon: 'success',
                timer:2000
              })
              this.cleanModalInputs()
              this.getSistemas()
            })
            .catch((errors) => console.log(errors))
        }
      })
    },
     editRol(id_sistema,sistema,id_rol){
      //console.log(id_sistemax);
      this.modalData.sistema_edit=sistema
      this.modalData.id_sistema_edit=id_sistema
      this.modalData.id_rol_edit=id_rol
       axios.get("/rolesxsistema",{params: this.modalData})
        .then((response) => {
          this.modalData.roles_edit=response.data.roles
          this.modalData.permiso_usuario=response.data.permiso_usuario
          this.changeStateFromModal2()
        })
        .catch((errors) => console.log(errors))
    },
    updateRol(){
        this.$swal.fire({
        title: 'Esta seguro de editar el rol',
        icon: 'question',
        confirmButtonText: 'Si, Guardar',
        confirmButtonColor: '#15803D',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        showCloseButton: true
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/edit/rol",{
                id_rol: this.modalData.id_rol_edit,
                permiso_usuario: this.modalData.permiso_usuario
              })
            .then((response) => {
              this.$swal.fire({
                text:response.data.mensaje,
                icon: 'success',
                timer:2000
              })
              this.changeStateFromModal2()
              this.modalData.permiso_usuario=""
              this.getSistemas()
            })
            .catch((errors) => console.log(errors))
        }
      }) 
    },


    async getUsers(url = "/users") {
      this.tableData.draw++;
      await axios.get(url, { params: this.tableData }).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          this.links[0].label = "Anterior";
          this.links[this.links.length - 1].label = "Siguiente";
          this.users = data.data.data;
        }
      }).catch((errors) => {
        console.log(errors);
      });
    },
    sortBy(key) {
      if (key != "Acciones") {
        this.sortKey = key;
        this.sortOrders[key] = this.sortOrders[key] * -1;
        this.tableData.column = this.getIndex(this.columns, "name", key);
        this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
        this.getUsers();
      }
    },
    getIndex(array, key, value) {
      return array.findIndex((i) => i[key] == value);
    },
  },
};
</script>
