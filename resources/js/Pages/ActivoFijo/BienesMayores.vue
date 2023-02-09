
<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from '@/Layouts/AppLayout.vue';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import TextInput from "@/Components-ISRI/ComponentsToForms/TextInput.vue";
import GeneralButton from "@/Components-ISRI/ComponentsToForms/GeneralButton.vue";
</script>



<template>

  <Head title="Activo Fijo" />

  <AppLayoutVue>

    <h3 class="pt-4 text-2xl text-center pb-10">Create an Account!</h3>

    <div id="formulario">
      <div class="mb-4 md:flex flex-row ">
        <div class="mb-4 md:mr-2 md:mb-0  basis-1/3">
          <TextInput id="primer-nombre" type="text" text="Primer Nombre" placeholder="Primer Nombre" />
        </div>
        <div class="mb-4 md:mr-2 md:mb-0  basis-1/3">
          <TextInput id="segundo-nombre" type="text" text="Segundo Nombre" placeholder="Segundo Nombre"
            icon="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/6">
          <TextInput id="edad" type="number" text="Edad" placeholder="Edad"
            icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
        </div>
      </div>
      <div class="mb-4 md:flex flex-row ">
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
          <TextInput id="correo-electronico" type="email" text="Correo Electronico" placeholder="Correo Electronico"
            icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 ">
          <label class="block mb-2 text-sm font-bold text-gray-700" for="select">
            Seleccionable
          </label>
          <div class="relative flex h-10 w-full flex-row-reverse overflow-clip div-select2">
            <Select2 id="select" name="domain" class="text-sm " v-model="myValue" :options="myOptions" />
            <label
              class="flex items-center rounded-l-md border border-slate-400 bg-slate-50 px-2 text-sm text-slate-400 transition-colors duration-300 peer-focus:border-[#001b47] peer-focus:bg-[#001b47] peer-focus:text-white"
              for="domain">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
              </svg>
            </label>
          </div>
        </div>
      </div>
      <div class="mb-4 md:flex flex-row ">
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="password" type="password" text="Contraseña" placeholder="Contraseña"
            icon="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
            <TextInput id="password" type="password" text="Confirmar Contraseña" placeholder="Confirmar Contraseña"
              icon="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
          </div>
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
          <label class="block mb-2 text-sm font-bold text-gray-700" for="message">
            Mensaje
          </label>
          <textarea id="message" rows="4"
            class="peer w-full rounded-md border h-10 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
            placeholder="Your message..."></textarea>
        </div>
      </div>
    </div>

    <!-- BUTTONS -->
    <div class="mb-4 md:flex flex-row ">
      <div class="mb-2 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-green-600  hover:bg-greeb-700" text="Agregar Elemento" icon="add" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-red-600  hover:bg-red-700" text="Eliminar" icon="delete" @click="deletAlert" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-orange-600  hover:bg-orange-700" text="Actualizar" icon="update" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-blue-600  hover:bg-blue-700" text="Informacion" icon="info" @click="notify" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton />
      </div>
    </div>
    <!-- //BUTTONS -->

  </AppLayoutVue>



</template>

<script>

export default {
  data: function () {
    return {
      changeState: false,
      showModal: false,
      myOptions: ['Registro', 'Usuario de prueba', 'Opcion de prueba 3'],
      primerNombre: '',

    };
  },
  components: { Modal },
  methods: {
    changeStateFromModal() {
      this.showModal = !this.showModal;
    },
    notify() {
      toast.info("Te informamos que", {
        autoClose: 1000,
        position: "top-right",
        transition: "zoom",
        toastBackgroundColor: "white",
      });
    },
    deletAlert() {
      this.$swal.fire({
        title: 'Esta seguro de eliminar este registro',
        icon: 'question',
        iconHtml: '❓',
        confirmButtonText: 'Si, Eliminarlo',
        confirmButtonColor: '#FF8300',
        cancelButtonColor: '#D20000',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        showCloseButton: true
      }).then((result) => {
        if (result.isConfirmed) {
          this.$swal.fire(
            'Deleted!',
            'Your file has been deleted.',
            'success'
          )
        }
      })
    }

  },
  created() {

  },
};
</script>

<style >
.div-select2 span {
  height: 40px !important;
  width: 200px !important;
}

.div-select2 .select2-container--default .select2-selection--single {
  background-color: #fff;
  border: 1px solid #aaa;
  border-radius: 0px 7px 7px 0px;
}

.div-select2 .select2-container--default .select2-selection--single .select2-selection__arrow b {
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 6px 4px 0 4px;
  height: 0;
  left: 50%;
  margin-left: 82px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}

.div-select2 .select2-container--default .select2-selection--single .select2-selection__rendered {
  color: #444;
  line-height: 38px;
  font-weight: bold;
}
</style>