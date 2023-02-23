<script setup>
    import Modal from "@/Components/Modal.vue";
    import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import axios from 'axios';
</script>

<template>
    <!-- Modal Menus-->
    <ModalVue :show="showModal" @close-modal="$emit('cerrar-modal')" v-bind:title="'Gestion de menus : '+modalData.nombre_rol" @close="$emit('cerrar-modal')">
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
              <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
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
</template>

<script>
export default {
    props: ["showModal", "modalData","modalVar"],
    watch: {
        modalVar: async function(newParam){
            if(newParam){
                await this.getMenus()
                this.$emit('abrir-modal')
            }else{
                this.$emit('cerrar-modal')
            }
        }
    },
    created(){
        
    },
    data: function (data) {
        return {
            showModal2: false,
        }
    },
    methods:{
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
          //this.$emit('abrir-modal')
        })
        .catch((errors) => console.log(errors))
    },
    }
}

</script>

<style>

</style>