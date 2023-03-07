<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import Targets from '@/Components-ISRI/Targets.vue';
</script>

<template>
    <ModalVue :show="showModalChangePassword" @close-modal="$emit('cerrar-modal')" 
    v-bind:title="'Cambio de contraseña para : ' + modalDataChangePassword.nick_usuario" @close="$emit('cerrar-modal')"> 
        <div class="px-5 pt-4 pb-1">

    <div class="mb-2 justify-center">Ingresa nueva contraseña</div>
    <div class="mb-2 md:flex flex-row justify-center">
        <div class="md:mr-2 md:mb-0 basis-1/2">       
          <TextInput v-model="modalDataChangePassword.password" :label-input="false" id="personal-information" type="password"
            placeholder="Nueva contraseña">
            <LabelToInput icon="password" forLabel="personal-information" />
          </TextInput>
        </div>
      </div>

    <div class="text-sm">
        <div class="mb-4 md:flex flex-row justify-center">
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton @click="saveNewPassword()" color="bg-green-700  hover:bg-green-800" text="Guardar" icon="add" />
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
          </div>
        </div>
        <div class="text-xs text-slate-500">ISRI2023</div>
      </div>
    </div>
    </ModalVue>
</template>

<script>
export default {
  props: ["showModalChangePassword",'modalDataChangePassword'],
  data: function (data) {
    return {

    }
  },
  methods: {
    saveNewPassword(){
        if(this.modalDataChangePassword.password==""){
          this.$swal.fire({
            title: 'Información incompleta',
            text: "Debes escribir la nueva contraseña",
            icon: 'warning',
            timer: 5000
          })
        }else{
            this.$swal.fire({
          title: 'Esta seguro de guardar la nueva contraseña',
          icon: 'question',
          iconHtml: '✅',
          confirmButtonText: 'Si, Guardar',
          confirmButtonColor: '#15803D',
          cancelButtonText: 'Cancelar',
          showCancelButton: true,
          showCloseButton: true
        }).then((result) => {
          if (result.isConfirmed) {
            axios.post("/change-password-user", {
              id_usuario: this.modalDataChangePassword.id_usuario,
              password: this.modalDataChangePassword.password,
            }).then((response) => {
              toast.success(response.data.mensaje, {
                autoClose: 3000,
                position: "top-right",
                transition: "zoom",
                toastBackgroundColor: "white",
              });
              this.$emit('cerrar-modal')
              this.modalDataChangePassword.password = ""
            }).catch((errors) => console.log(errors))
          }
        })
        }
    }
  },
};
</script>