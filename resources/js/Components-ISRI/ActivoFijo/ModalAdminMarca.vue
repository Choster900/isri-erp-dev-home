<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
</script>

<template>
    <ModalVue :show="showModal" @close-modal="this.$emit('cerrar-modal')" :title="'Administración de marca '" @close="this.$emit('cerrar-modal')">
        <div class="px-5 pt-4 pb-1">

            <div class="text-sm">
                <div class="mb-4 md:flex flex-row justify-center">Nombre marca:</div>
                <div class="mb-4 md:flex flex-row justify-center">
                    <div class="md:mr-2 md:mb-0 basis-1/2">

                        <TextInput id="nombre-marca" :label-input="false" v-model="brand.name_brand" :value="brand.name_brand"
                            type="text" placeholder="Nombre Marca">
                            <LabelToInput icon="personalInformation" forLabel="nombre-marca" />
                        </TextInput>

                    </div>
                </div>
                <div class="mb-4 md:flex flex-row justify-center">
                    <GeneralButton v-if="modalData!=''" @click="updateMarca()" color="bg-orange-700  hover:bg-orange-800"
                        text="Actualizar" icon="add" />
                    <GeneralButton v-else @click="saveNewMarca()"
                        color="bg-green-700  hover:bg-green-800" text="Agregar" icon="add" />
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton text="Cancelar" icon="add" @click="this.$emit('cerrar-modal')" />
                    </div>
                </div>
                <div class="text-xs text-slate-500">ISRI2023</div>
            </div>
        </div>
    </ModalVue>
</template>

<script>

export default {
  props: {
    modalData: {
            type: Array,
            default: [],
        },
    showModal:{
            type: Boolean,
            default:false
    }
  },
  data: function (data) {
    return {
      brand :{
        name_brand:'',
        id_brand:''
      }
    }
  },
  methods: {
    saveNewMarca(){
        if(!this.brand.name_brand){
          this.$swal.fire({
            title: 'Información incompleta.',
            text: "Debes escribir el nombre de la marca.",
            icon: 'warning',
            timer: 5000
          })
        }else{
          this.$swal.fire({
          title: 'Esta seguro de guardar la nueva marca',
          icon: 'question',
          iconHtml: '✅',
          confirmButtonText: 'Si, Guardar',
          confirmButtonColor: '#15803D',
          cancelButtonText: 'Cancelar',
          showCancelButton: true,
          showCloseButton: true
        }).then((result) => {
          if (result.isConfirmed) {
            axios.post("/save-brand", {
              brand: this.brand.name_brand,
            }).then((response) => {
              toast.success(response.data.mensaje, {
                autoClose: 3000,
                position: "top-right",
                transition: "zoom",
                toastBackgroundColor: "white",
              });
              this.$emit('get-table')
              this.$emit('cerrar-modal')
            }).catch((errors) => {
              if(errors.response.status===422){
                this.$swal.fire({
                  title: 'Error en la petición',
                  html: errors.response.data,
                  icon: 'warning',
                  timer:5000
                })
              }else{
                let msg = this.manageError(errors)
                this.$swal.fire({
                  title: 'Operación cancelada',
                  text: msg,
                  icon: 'warning',
                  timer:5000
                })
                this.$emit('cerrar-modal')
              }
            })
          }
        })
      }
    },
    updateMarca(){
      if(!this.brand.name_brand){
          this.$swal.fire({
            title: 'Información incompleta.',
            text: "Debes escribir el nombre de la marca.",
            icon: 'warning',
            timer: 5000
          })
        }else{
          this.$swal.fire({
          title: 'Esta seguro de actualizar marca',
          icon: 'question',
          iconHtml: '✅',
          confirmButtonText: 'Si, Guardar',
          confirmButtonColor: '#15803D',
          cancelButtonText: 'Cancelar',
          showCancelButton: true,
          showCloseButton: true
        }).then((result) => {
          if (result.isConfirmed) {
            axios.post("/update-brand", {
              id_brand: this.brand.id_brand,
              name_brand: this.brand.name_brand,
            }).then((response) => {
              toast.success(response.data.mensaje, {
                autoClose: 3000,
                position: "top-right",
                transition: "zoom",
                toastBackgroundColor: "white",
              });
              this.$emit('get-table')
              this.$emit('cerrar-modal')
            }).catch((errors) => {
              if(errors.response.status===422){
                this.$swal.fire({
                  title: 'Error en la petición',
                  html: errors.response.data,
                  icon: 'warning',
                  timer:5000
                })
                this.brand.name_brand=this.modalData.nombre_marca
              }else{
                let msg = this.manageError(errors)
                this.$swal.fire({
                  title: 'Operación cancelada',
                  text: msg,
                  icon: 'warning',
                  timer:5000
                })
                this.$emit('cerrar-modal')
              }
            })
          }
        })
      }
    }
  },
    watch: {
        modalData: function (value, oldvalue) {
            this.brand.name_brand = this.modalData.nombre_marca
            this.brand.id_brand = this.modalData.id_marca
        }
    }
};

</script>