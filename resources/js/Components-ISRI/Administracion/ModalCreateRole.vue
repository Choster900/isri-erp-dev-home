<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import Targets from '@/Components-ISRI/Targets.vue';
</script>

<template>
  <ModalVue :show="showModalCreate" @close-modal="$emit('cerrar-modal')" title="Creacion de Rol "
  @close="$emit('cerrar-modal')">
  <div class="px-5 pt-4 pb-1">
    <div class="text-sm">
      <div class="mb-4">Selecciona un Sistema y escriba nombre del rol</div>
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Sistema <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
              <Multiselect v-bind:disabled="modalDataCreate.select_sistema" class="text-xs"
                v-model="modalDataCreate.id_sistema" :options="modalDataCreate.sistemas" @select="getParentMenu()"
                placeholder="Seleccione Sistema" :searchable="true" />
              <LabelToInput icon="list" />
            </div>
          </div>

          <div class="mb-4 md:ml-0 md:mb-0 w-64">
          <TextInput v-model="modalDataCreate.nombre_rol" id="personal-information" type="text"
            placeholder="Nombre Rol">
            <LabelToInput icon="standard" forLabel="personal-information" />
          </TextInput>
        </div>
      </div>
      <div class="mb-4">Selecciona un Menu y un Submenu:</div>
      <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Menu <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
              <Multiselect v-model="modalDataCreate.id_menu" :options="modalDataCreate.parentsMenu"
                @select="getChildrenMenus(modalDataCreate.parentsMenu, $event)" placeholder="Seleccione Menu"
                :searchable="true" />

              <LabelToInput icon="list" />
            </div>
          </div>

          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Sub menu <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">

              <Multiselect v-model="modalDataCreate.id_childrenMenu" :options="modalDataCreate.childrenMenus"
                @select="getChildren(modalDataCreate.childrenMenus, $event)" placeholder="Seleccione Sub Menu"
                :searchable="true" />

              <LabelToInput icon="list" />
            </div>
          </div>
        </div>
        <div class="mb-4 md:flex flex-row justify-center">
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton @click="saveRol()" color="bg-green-700  hover:bg-green-800" text="Guardar" icon="add" />
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
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
                    <button @click="deleteMenu(menu.id, menu.id_menu_padre, menu.menu_padre)"
                      class="text-rose-500 hover:text-rose-600 rounded-full">
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
</template>

<script>
export default {
  props: ["showModalCreate", "modalDataCreate"],
  data: function (data) {
    return {

    }
  },
  methods: {
    //Methods for creating a new role
    cleanModalCreateInputs() {
      this.$emit('cerrar-modal')
      this.modalDataCreate.sistemas = []
      this.modalDataCreate.id_sistema = ''
      this.modalDataCreate.parentsMenu = []
      this.modalDataCreate.id_menu = ''
      this.modalDataCreate.nombre_parent_menu = ''
      this.modalDataCreate.childrenMenus = []
      this.modalDataCreate.nombre_rol = ''
      this.modalDataCreate.menus = []
      this.modalDataCreate.select_sistema = false
    },
    getParentMenu() {
      this.modalDataCreate.select_sistema = true
      axios.get('/parents-menu-all', { params: this.modalDataCreate })
        .then((response) => {
          this.modalDataCreate.parentsMenu = response.data.parentsMenu
        })
        .catch((errors) => console.log(errors))
    },
    getChildrenMenus: function (data, event) {

      let finalLabel
      data.forEach((value, index) => {
        if (value.value == event) {
          finalLabel = value.label
        }
      })
      this.modalDataCreate.id_childrenMenu = null
      this.modalDataCreate.nombre_parent_menu = finalLabel
      axios.get('/children-menus', { params: this.modalDataCreate })
        .then((response) => {
          this.modalDataCreate.childrenMenus = response.data.childrenMenus
          if (this.modalDataCreate.menus != "") {
            this.modalDataCreate.menus.forEach((value1, index1) => {
              this.modalDataCreate.childrenMenus.forEach((value2, index2) => {
                if (value2.value == value1.id) {
                  this.modalDataCreate.childrenMenus.splice(index2, 1)
                }
              })
            });
          }
        })
        .catch((errors) => console.log(errors))
    },
    getChildren(data, event) {

      let finalLabel
      data.forEach((value, index) => {
        if (value.value == event) {
          finalLabel = value.label
        }
      })
      //Insertando nuevo array en tabla de menus
      var array = {
        id: this.modalDataCreate.id_childrenMenu,
        id_menu_padre: this.modalDataCreate.id_menu,
        menu_padre: this.modalDataCreate.nombre_parent_menu, menu: finalLabel
      }
      this.modalDataCreate.menus.push(array)
      this.modalDataCreate.id_childrenMenu = ""
      this.modalDataCreate.childrenMenus.forEach((value, index) => {
        if (value.value == array.id) {
          this.modalDataCreate.childrenMenus.splice(index, 1)
        }
      })
      //Verificando si el select de menus hijos esta vacio para borrar el respectivo menu padre
      if (this.modalDataCreate.childrenMenus == '') {
        this.modalDataCreate.parentsMenu.forEach((value, index) => {
          if (value.value == this.modalDataCreate.id_menu) {
            this.modalDataCreate.parentsMenu.splice(index, 1)
            this.modalDataCreate.id_menu = ""
            this.modalDataCreate.id_childrenMenu = ""
          }
        })
        this.modalDataCreate.id_menu = ''
      }

    },
    deleteMenu(id_menu, id_padre, nombre_padre) {
      console.log(id_menu, id_padre, nombre_padre);
      let arraySelectParents = { value: id_padre, label: nombre_padre }
      this.modalDataCreate.menus.forEach((value, index) => {
        if (value.id == id_menu) {
          this.modalDataCreate.menus.splice(index, 1)
        }
      })
      if (this.modalDataCreate.parentsMenu == '') {
        this.modalDataCreate.parentsMenu.push(arraySelectParents)
      } else {
        let isInArray = false
        this.modalDataCreate.parentsMenu.forEach((value2, index2) => {
          if (value2.value == id_padre) {
            isInArray = true
          }
        })
        if (!isInArray) {
          this.modalDataCreate.parentsMenu.push(arraySelectParents)
        }
      }
      this.modalDataCreate.childrenMenus = ''
      this.modalDataCreate.id_childrenMenu = ''
      this.modalDataCreate.id_menu = ''

    },
    saveRol() {
      if (this.modalDataCreate.nombre_rol != "" && this.modalDataCreate.menus != "") {
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
            axios.post("/create-rol", {
              id_sistema: this.modalDataCreate.id_sistema,
              nombre_rol: this.modalDataCreate.nombre_rol,
              menus: this.modalDataCreate.menus
            }).then((response) => {
              this.$swal.fire({
                text: response.data.mensaje,
                icon: 'success',
                timer: 3000
              })
              this.cleanModalCreateInputs()
              this.$emit("update-table")
            }).catch((errors) => console.log(errors))
          }
        })
      } else {
        let msg = "Debes "
        if (this.modalDataCreate.nombre_rol == "") {
          msg = msg + "escribir el nombre para el rol "
          if (this.modalDataCreate.menus == "") {
            msg = msg + "y seleccionar menus"
          }
        } else {
          msg = msg + "seleccionar menus"
        }
        this.$swal.fire({
          title: 'Información incompleta',
          text: msg,
          icon: 'warning',
          timer: 5000
        })
      }
    },
  },
};
</script>