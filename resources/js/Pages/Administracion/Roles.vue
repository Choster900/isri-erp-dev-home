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

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>

  <Head title="Administracion" />
  <AppLayoutVue>
    <div class="sm:flex sm:justify-end sm:items-center mb-2">
      <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
        <GeneralButton @click="createRol()" color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add" />
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

    <ModalVue :show="showModalCreate" @close-modal="cleanModalCreateInputs()" v-bind:title="'Creacion de Rol '" @close="cleanModalCreateInputs()">
      <div class="px-5 pt-4 pb-1">
        <div class="text-sm">
          <div class="mb-4">Selecciona un Sistema y escriba nombre del rol</div>
          <div class="mb-4 md:flex flex-row justify-items-start">
            <div class="mb-4 md:mr-2 md:mb-0 w-64">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 v-bind:disabled="modalDataCreate.select_sistema" class="text-xs" v-model="modalDataCreate.id_sistema" :options="modalDataCreate.sistemas" @select="getParentMenu()" placeholder="Seleccione Sistema" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
            <div class="mb-4 md:ml-1 md:mb-0 w-64">
              <TextInput v-model="modalDataCreate.nombre_rol" id="personal-information" type="text" placeholder="Nombre Rol">
                <LabelToInput icon="standard" forLabel="personal-information" />
              </TextInput>
            </div>
          </div>
          <div class="mb-4">Selecciona un Menu y un Submenu:</div>
          <div class="mb-4 md:flex flex-row justify-items-start">
            <div class="mb-4 md:mr-2 md:mb-0 w-64">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="modalDataCreate.id_menu" :options="modalDataCreate.parentsMenu" @select="getChildrenMenus($event)" placeholder="Seleccione Menu" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 w-64">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="modalDataCreate.id_childrenMenu" :options="modalDataCreate.childrenMenus" @select="getChildren($event)"  placeholder="Seleccione Sub Menu" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
          </div>
          <div class="mb-4 md:flex flex-row justify-center">
            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton @click="saveRol()" color="bg-green-700  hover:bg-green-800" text="Guardar" icon="add" />
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton text="Cancelar" icon="add" @click="showModalCreate=false" />
            </div>
          </div>

          <!-- Begin Table -->
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
              <tbody v-for="menu in modalDataCreate.menus" :key="menu.id" class="text-sm divide-y divide-slate-200">
                <tr class="hover:bg-[#141414]/10">
                  <td class="text-center">{{ menu.id }}</td>
                  <td class="text-center">{{ menu.menu_padre }}</td>
                  <td class="text-center">{{ menu.menu }}</td>
                  <td class="text-center">
                    <div class="space-x-1">
                      <button @click="deleteMenu(menu.id,menu.id_menu_padre,menu.menu_padre)" class="text-rose-500 hover:text-rose-600 rounded-full">
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

    <ModalRolesVue 
    :modalVar="modalVar" :showModal="showModal" :modalData="modalData" 
    @cerrar-modal="closeVars" @abrir-modal="showModal = true"
    @update-table="getUpdateTable()"/>

  </AppLayoutVue>
</template>

<script>
export default {
  created() {
    this.getRoles()
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
      },
      modalDataCreate:{
        //List of all registered systems
        sistemas:[],
        //Selected system Id
        id_sistema:"",
        //Select parents menus
        parentsMenu:[],
        //This is the identifier of the parent menu
        id_menu:"",
        //This is the name of the selected parent menu.
        nombre_parent_menu:'',
        //Array of children's menu
        childrenMenus:[],
        //Id of the selected child menu
        id_childrenMenu:"",
        //List of selected child menus.
        menus:[],
        //Property to disable system select.
        select_sistema:false
      },
      showModal: false,
      showModalCreate: false,
      modalVar:false,
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
    //Methods for creating a new role
    cleanModalCreateInputs(){
      this.showModalCreate=false
      this.modalDataCreate.sistemas=[]
      this.modalDataCreate.id_sistema=''
      this.modalDataCreate.parentsMenu=[]
      this.modalDataCreate.id_menu=''
      this.modalDataCreate.nombre_parent_menu=''
      this.modalDataCreate.childrenMenus=[]
      this.modalDataCreate.nombre_rol=''
      this.modalDataCreate.menus=[]
      this.modalDataCreate.select_sistema=false
    },
    async createRol(){
      await axios.get("/sistemas/all")
        .then((response)=> {
          this.modalDataCreate.sistemas=response.data.sistemas
          console.log(response.data.sistemas);
          this.showModalCreate=true
        })
        .catch((errors)=> console.log(errors))
    },
    getParentMenu(){
      this.modalDataCreate.select_sistema=true
      axios.get('/parentsMenu/all',{params: this.modalDataCreate})
        .then((response) => {
          this.modalDataCreate.parentsMenu=response.data.parentsMenu
        })
        .catch((errors) => console.log(errors))
    },
    getChildrenMenus({id,text}){
      this.modalDataCreate.id_childrenMenu=""
      this.modalDataCreate.nombre_parent_menu=text
      axios.get('/menus/childrenMenus',{params: this.modalDataCreate})
        .then((response) => {
          this.modalDataCreate.childrenMenus=response.data.childrenMenus
          if(this.modalDataCreate.menus!=""){
            this.modalDataCreate.menus.forEach((value1, index1) => {
              this.modalDataCreate.childrenMenus.forEach((value2,index2)=>{
                if(value2.id==value1.id){
                  this.modalDataCreate.childrenMenus.splice(index2,1)
                }
              })
          });
          }
        })
        .catch((errors) => console.log(errors))
    },
    getChildren({id,text}){
      //Insertando nuevo array en tabla de menus
      var array = {id:this.modalDataCreate.id_childrenMenu,id_menu_padre:this.modalDataCreate.id_menu, menu_padre:this.modalDataCreate.nombre_parent_menu,menu:text}
      this.modalDataCreate.menus.push(array)
      this.modalDataCreate.id_childrenMenu=""
      this.modalDataCreate.childrenMenus.forEach((value, index) => {
        if(value.id==array.id){
          this.modalDataCreate.childrenMenus.splice(index,1)
        }
      })
      //Verificando si el select de menus hijos esta vacio para borrar el respectivo menu padre
      if(this.modalDataCreate.childrenMenus==''){
        this.modalDataCreate.parentsMenu.forEach((value, index) => {
          if(value.id==this.modalDataCreate.id_menu){
            this.modalDataCreate.parentsMenu.splice(index,1)
            this.modalDataCreate.id_menu=""
          }
        })
        this.modalDataCreate.id_menu=''
      }
      
    },
    deleteMenu(id_menu,id_padre,nombre_padre){
      let arraySelectParents = {id:id_padre,text:nombre_padre}
      this.modalDataCreate.menus.forEach((value, index) => {
        if(value.id==id_menu){
        this.modalDataCreate.menus.splice(index,1)
        }
      })
      if(this.modalDataCreate.parentsMenu==''){
        this.modalDataCreate.parentsMenu.push(arraySelectParents)
      }else{
        let isInArray=false
        this.modalDataCreate.parentsMenu.forEach((value2, index2) => {
          if(value2.id==id_padre){
            isInArray=true
          }
        })
        if(!isInArray){
          this.modalDataCreate.parentsMenu.push(arraySelectParents)
        }
      }
      this.modalDataCreate.childrenMenus=''
      this.modalDataCreate.id_childrenMenu=''
      this.modalDataCreate.id_menu=''
      
    },
    saveRol(){
      if(this.modalDataCreate.nombre_rol!="" && this.modalDataCreate.menus!=""){
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
              axios.post("/create/rol", {
                id_sistema : this.modalDataCreate.id_sistema,
                nombre_rol : this.modalDataCreate.nombre_rol,
                menus : this.modalDataCreate.menus
              }).then((response) => {
                this.$swal.fire({
                text:response.data.mensaje,
                icon: 'success',
                timer:2000
                })
                this.cleanModalCreateInputs()
                this.getRoles();
                }).catch((errors) => console.log(errors))
              }
            })
      }else{
        let msg="Debes "
        if(this.modalDataCreate.nombre_rol==""){
            msg=msg+"escribir el nombre para el rol "
          if(this.modalDataCreate.menus==""){
            msg=msg+"y seleccionar menus"
          }
        }else{
          msg=msg+"seleccionar menus"
        }
        this.$swal.fire({
          title: 'Información incompleta',
          text: msg,
          icon: 'warning',
          timer:5000
          })
      }
    },


    //Methods for ModalRoles
    getUpdateTable(){
      this.getRoles('http://127.0.0.1:8000/roles?page='+this.tableData.currentPage);
    },
    closeVars(){
      this.showModal=false
      this.modalVar=false
      this.cleanModalInputs()
    },
    cleanModalInputs(){
      this.modalData.id_childrenMenu=""
      this.modalData.childrenMenus=""
      this.modalData.id_menu=""
    },

    //Methods for datatable
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
    getSelectsRolMenu(identificador){
      this.modalVar = true;
      this.modalData.id_rol = identificador;
    },
    async getRoles(url = "/roles") {
      this.tableData.draw++;
      await axios.get(url, { params: this.tableData }).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          this.tableData.total=data.data.total;
          this.tableData.currentPage=data.data.current_page;
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
