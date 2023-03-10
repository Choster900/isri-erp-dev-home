<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
</script>

<template>
  <!-- Modal Menus-->
  <ModalVue :show="showModal" @close-modal="$emit('cerrar-modal')"
    v-bind:title="'Gestion de menus : ' + modalData.nombre_rol" @close="$emit('cerrar-modal')">
    <div class="px-5 pt-4 pb-1">
      <div class="text-sm">
        <div class="mb-4">Selecciona un Menú y un Submenú:</div>
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Menú <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
              <Multiselect v-model="modalData.id_menu" :options="modalData.menus" @select="getChildrenMenus()"
                placeholder="Seleccione Menú" :searchable="true" />
              <LabelToInput icon="list" />
            </div>
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Submenú <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
              <Multiselect v-model="modalData.id_childrenMenu" :options="modalData.childrenMenus" placeholder="Seleccione Submenú"
                :searchable="true" />
              <LabelToInput icon="list" />
            </div>
          </div>
        </div>
        <div class="mb-4">Permisos para el Submenú seleccionado:</div>
        <div class="mb-4 md:flex flex-row">
          <div class="mb-4 md:mr-2 md:mb-0">
            <label for="checbox1" class="text-sm font-bold text-gray-700">Insertar
            </label>
            <checkbox class="mr-3" id="checbox1" />
            <label for="checbox2" class="text-sm font-bold text-gray-700">Actualizar
            </label>
            <checkbox class="mr-3" id="checbox2" />
            <label for="checbox3" class="text-sm font-bold text-gray-700">Eliminar
            </label>
            <checkbox class="mr-3" id="checbox3" />
            <label for="checbox4" class="text-sm font-bold text-gray-700">Ejecutar
            </label>
            <checkbox class="mr-3" id="checbox4" />
        </div>
        </div>
        <div class="mb-4 md:flex flex-row justify-center">
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton @click="saveMenu()" color="bg-green-700  hover:bg-green-800" text="Agregar" icon="add" />
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
          </div>
        </div>
        <div class="tabla-modal">
          <table class="w-full" id="tabla_modal_validacion_arranque">
            <thead class="bg-[#1F3558] text-white">
              <tr class="">
                <th class="rounded-tl-lg">#</th>
                <th>MENU</th>
                <th>SUBMENU</th>
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
                    <button @click="desactiveMenu(menu.id_menu, menu.nombre_menu)"
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
  props: ["showModal", "modalData", "modalVar"],
  watch: {
    modalVar: async function (newParam) {
      if (newParam) {
        await this.getMenus()
        this.$emit('abrir-modal')
      }
    }
  },
  created() {

  },
  data: function (data) {
    return {
      showModal2: false,
    }
  },
  methods: {
    desactiveMenu(id_menu, nombre_menu) {
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
          axios.post("/desactive-menu", {
            id_menu: id_menu,
            id_rol: this.modalData.id_rol
          }).then((response) => {
            toast.success(response.data.mensaje, {
              autoClose: 3000,
              position: "top-right",
              transition: "zoom",
              toastBackgroundColor: "white",
            });
            response.data.update ? this.$emit('update-table') : ''
            this.getMenus()
            this.modalData.id_menu = ""
            this.modalData.id_childrenMenu = ""
            this.modalData.childrenMenus = ""
          }).catch((errors) => {
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
            axios.post("/save-menu", {
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
              response.data.update ? this.$emit('update-table') : ''
              this.getMenus()
              this.modalData.id_childrenMenu = ""
              this.modalData.id_menu = ""
              this.modalData.childrenMenus = ""
            }).catch((errors) => {
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
      } else {
        this.$swal.fire({
          title: 'Información incompleta',
          text: "Debes seleccionar Menu y Submenu",
          icon: 'warning',
          timer: 5000
        })
      }
    },
    getChildrenMenus() {
      this.modalData.id_childrenMenu = ""
      axios.get('/children-menus', { params: this.modalData })
        .then((response) => {
          this.modalData.childrenMenus = response.data.childrenMenus
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
    },
    async getMenus() {
      await axios.get("/menus-edit-rol", { params: this.modalData })
        .then((response) => {
          this.modalData.rolMenus = response.data.rolMenus
          this.modalData.menus = response.data.menus
          this.modalData.id_menus_rol = response.data.id_menus_asignados
          this.modalData.nombre_rol = response.data.nombre_rol
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
    },
  }
}

</script>

<style></style>