
<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from '@/Layouts/AppLayout.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import TextInput from "@/Components-ISRI/ComponentsToForms/TextInput.vue";
import LabelToInput from "@/Components-ISRI/ComponentsToForms/LabelToInput.vue";
import GeneralButton from "@/Components-ISRI/ComponentsToForms/GeneralButton.vue";
import Checkbox from '@/Components-ISRI/ComponentsToForms/Checkbox.vue';

import Datepicker from 'vue3-datepicker'
import Multiselect from 'vue-multiselect';
import RadioButton from '@/Components-ISRI/ComponentsToForms/RadioButton.vue';

</script>

<template>

  <Head title="Activo fijo" />

  <AppLayoutVue>

    <h3 class="pt-4 text-2xl text-center pb-10">Create an Account!</h3>

    <div class="mb-4 md:flex flex-row ">
      <div class="mb-4 md:mr-2 md:mb-0  basis-1/2">
        <RadioButton modelValue="1" v-model="prueba" />
        <RadioButton modelValue="2" v-model="prueba" />
        <RadioButton modelValue="3" v-model="prueba" />
        <RadioButton modelValue="4" v-model="prueba" />
        <RadioButton modelValue="5" v-model="prueba" />
        <RadioButton modelValue="6" v-model="prueba" />
      </div>

      {{ prueba }}

    </div>



    <div id="formulario">

      <div class="mb-4 md:flex flex-row justify-items-start">
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="personal-information" type="text" placeholder="Informacion Personal">
            <LabelToInput icon="personalInformation" forLabel="personal-information" />
          </TextInput>

        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="general-information" type="text" placeholder="Información General">
            <LabelToInput icon="generalInformation" forLabel="general-information" />
          </TextInput>
        </div>

        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="money" type="text" placeholder="Money">
            <LabelToInput icon="money" forLabel="money" />
          </TextInput>
        </div>
      </div>
      <div class="mb-4 md:flex flex-row justify-items-start ">
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="correo-electronico" type="email" placeholder="Correo Electronico">
            <LabelToInput icon="email" forLabel="correo-electronico" />

          </TextInput>
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 ">
          <!--  <label class="block mb-2 text-sm font-bold text-gray-700" for="select">
            Seleccionable
          </label> -->
          <div class="relative flex h-8 w-full flex-row-reverse div-select2">
            <Select2 id="select" name="domain" class="text-xs" v-model="myValue" :options="myOptions" />
            <LabelToInput icon="list" for-label="select" />
          </div>
        </div>

        <div class="mb-4 md:mr-2 md:mb-0 ">
          <!-- <label class="block mb-2 text-sm font-bold text-gray-700" for="select">
            DatePicker
          </label> -->
          <div class="relative flex h-8 w-full flex-row-reverse">
            <Datepicker v-model="picked" id="domain"
              class="peer w-full text-sm rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none" />
            <LabelToInput icon="date" />
          </div>
        </div>
      </div>
      <div class="mb-4 md:flex flex-row justify-items-start ">
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="password" type="password" placeholder="Contraseña">
            <LabelToInput icon="password" for-label="password" />

          </TextInput>
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="distance" type="text" placeholder="Distancia">
            <LabelToInput icon="distance" for-label="distance" />
          </TextInput>
        </div>
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <!--      <label class="block mb-2 text-sm font-bold text-gray-700" for="message">
            Mensaje
          </label> -->
          <textarea id="message" rows="4"
            class="peer w-full rounded-md text-sm border h-10 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
            placeholder="Your message..."></textarea>
        </div>
      </div>
    </div>

    <div class="mb-4 md:flex flex-row ">
      <div class="mb-4 md:mr-2 md:mb-0 ">
        <label for="checbox1" class="text-sm font-bold text-gray-700">Remember me </label>
        <checkbox class="mr-3" id="checbox1" />
        <label for="checbox2" class="text-sm font-bold text-gray-700">Remember my password to </label>
        <checkbox class="mr-3" id="checbox2" />
      </div>
    </div>

    <!-- <Multiselect id="mechanic_id" placeholder="Search mechanic" :options="mechanics" label="name" track-by="id"
      @search-change="onSearchMechanisChange" @input="onSelectdMechanic">

    </Multiselect> -->
    <!-- BUTTONS -->
    <div class="mb-4 md:flex flex-row ">
      <div class="mb-2 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-red-700  hover:bg-red-800" text="Eliminar" icon="delete" @click="deletAlert" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-blue-700  hover:bg-blue-800" text="Informacion" icon="info" @click="notify" />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton />
      </div>
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/">
        <a href="https://kamona-wd.github.io/kwd-dashboard/" target="_blank"
          class="inline-flex items-center justify-center px-4 py-1 space-x-1 bg-red-500 text-white rounded-md shadow animate-bounce hover:bg-red-600">
          <span>See Dark & Light version</span>
        </a>
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
      picked: '',
      mechanics: [],
      prueba: ''

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
        confirmButtonColor: '#f8312f',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        showCloseButton: true
      }).then((result) => {
        if (result.isConfirmed) {
          toast.success("Alerta de success", {
            autoClose: 1000,
            position: "top-right",
            transition: "zoom",
            toastBackgroundColor: "white",
          });
        }
      })
    }

  },
  created() {

  },
};
</script>

<style>
.div-select2 span {
  height: 32px !important;
  width: 215px !important;
}

.div-select2 .select2-container--default .select2-selection--single .select2-selection__arrow b {
  margin-left: 91px;
}
</style>