<script setup>
import { Head } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
</script>

<template>
  <Head title="Activo fijo" />

  <AppLayoutVue>
    <h3 class="pt-4 text-2xl text-center pb-10">Create an Account!</h3>

    <!-- <Multiselect v-model="valor" mode="tags" :close-on-select="false" :searchable="true" :create-option="false"
                    :options="[
                      { value: '1', label: 'Batman' },
                      { value: '2', label: 'Robin' },
                      { value: '3', label: 'Joker' },
                    ]" class="" /> -->

    <div class="mb-4 md:flex flex-row justify-items-start">
      <div class="mb-4 md:mr-2 md:mb-0">
        <div class="relative flex h-8 w-full flex-row-reverse basis-1">
          <DatepickerTest v-model="picked" id="domain">
          </DatepickerTest>
          <LabelToInput icon="date" />
        </div>
      </div>
      <!--       {{ valor }}
   -->

      <div class="mb-4 md:mr-2 md:mb-0">
        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
          <Multiselect class="" v-model="valor" :options="opcionesSelect2" :searchable="true" />
          <LabelToInput icon="date" />
        </div>
      </div>

      <div class="mb-4 md:mr-2 md:mb-0">
        <div class="relative flex h-8 w-full flex-row-reverse">
          <Multiselect placeholder="Seleccione" v-model="valor" mode="tags" :close-on-select="false" :searchable="true"
            :create-option="false" :options="opcionesSelect2" class="cursor-pointer text-xs" />
          <LabelToInput icon="date" />
        </div>
      </div>
    </div>

    <div class="mb-4 md:flex flex-row">
      <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
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
      <div class="mb-4 md:flex flex-row justify-items-start">
        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
          <TextInput id="correo-electronico" type="email" placeholder="Correo Electronico">
            <LabelToInput icon="email" forLabel="correo-electronico" />
          </TextInput>
        </div>
        <div class="mb-4 md:mr-2 md:mb-0">
          <!--  <label class="block mb-2 text-sm font-bold text-gray-700" for="select">
                          Seleccionable
                        </label> -->
          <div class="relative flex h-8 w-full flex-row-reverse div-select2">
            <Select2 id="select" name="domain" class="text-xs" v-model="prueba" :options="opcionesSelect2" />
            <LabelToInput icon="list" for-label="select" />
            {{ prueba }}
          </div>
        </div>

        <div class="mb-4 md:mr-2 md:mb-0">
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
      <div class="mb-4 md:flex flex-row justify-items-start">
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

    <div class="mb-4 md:flex flex-row">
      <div class="mb-4 md:mr-2 md:mb-0">
        <label for="checbox1" class="text-sm font-bold text-gray-700">Remember me
        </label>
        <checkbox class="mr-3" id="checbox1" />
        <label for="checbox2" class="text-sm font-bold text-gray-700">Remember my password to
        </label>
        <checkbox class="mr-3" id="checbox2" />
      </div>
    </div>

    <!-- <Multiselect id="mechanic_id" placeholder="Search mechanic" :options="mechanics" label="name" track-by="id"
                    @search-change="onSearchMechanisChange" @input="onSelectdMechanic">

                  </Multiselect> -->
    <!-- BUTTONS -->
    <div class="mb-4 md:flex flex-row">
      <div class="mb-2 md:mr-2 md:mb-0 basis-1/">
        <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add"
          @click="changeStateFromModal" />
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

    <ModalVue :show="showModal" @close="changeStateFromModal" title="Basic Modal" @close-modal="changeStateFromModal">
      <div class="px-5 pt-4 pb-1">
        <div class="text-sm">
          <div class="mb-4">Selecciona un rol y un sistema:</div>
          <div class="mb-4 md:flex flex-row justify-items-start">
            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="myValue" :options="myOptions" placeholder="Sistema" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
              <div class="relative flex h-8 w-full flex-row-reverse div-select2">
                <Select2 class="text-xs" v-model="myValue" :options="myOptions" placeholder="Rol" />
                <LabelToInput icon="list" for-label="select" />
              </div>
            </div>
          </div>

          <div class="mb-4 md:flex flex-row justify-center">
            <div class="mb-4 md:mr-2 md:mb-0 px-1">
              <GeneralButton color="bg-orange-700  hover:bg-orange-800" text="Agregar" icon="add" @click="deletAlert()" />
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
              <tbody class="text-sm divide-y divide-slate-200">
                <tr class="hover:bg-[#141414]/10">
                  <td class="text-center">1</td>
                  <td class="text-center">Sistema 1</td>
                  <td class="text-center">Rol 1</td>
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
                      <button class="text-rose-500 hover:text-rose-600 rounded-full">
                        <span class="sr-only">Delete</span><svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                          <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                          <path
                            d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                          </path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-[#141414]/10">
                  <td class="text-center">1</td>
                  <td class="text-center">Sistema 1</td>
                  <td class="text-center">Rol 1</td>
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
                      <button class="text-rose-500 hover:text-rose-600 rounded-full">
                        <span class="sr-only">Delete</span><svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                          <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                          <path
                            d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                          </path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-[#141414]/10">
                  <td class="text-center">1</td>
                  <td class="text-center">Sistema 1</td>
                  <td class="text-center">Rol 1</td>
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
                      <button class="text-rose-500 hover:text-rose-600 rounded-full">
                        <span class="sr-only">Delete</span><svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                          <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                          <path
                            d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                          </path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-[#141414]/10">
                  <td class="text-center">1</td>
                  <td class="text-center">Sistema 1</td>
                  <td class="text-center">Rol 1</td>
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
                      <button class="text-rose-500 hover:text-rose-600 rounded-full">
                        <span class="sr-only">Delete</span><svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                          <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                          <path
                            d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                          </path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-[#141414]/10">
                  <td class="text-center">1</td>
                  <td class="text-center">Sistema 1</td>
                  <td class="text-center">Rol 1</td>
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
                      <button class="text-rose-500 hover:text-rose-600 rounded-full">
                        <span class="sr-only">Delete</span><svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                          <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
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
          <div class="text-xs text-slate-500">
            Your workspace’s Mosaic Light Plan is set to $39 per
            month and will renew on August 9, 2021.
          </div>
        </div>
      </div>

      <div class="px-5 py-4">
        <div class="flex flex-wrap justify-end space-x-2">
          <!-- <GeneralButton color="bg-orange-700  hover:bg-orange-800" text="Agregar" icon="add" @click="deletAlert()" />
                        <GeneralButton text="Cancelar" icon="add" @click="changeStateFromModal" /> -->
        </div>
      </div>
    </ModalVue>
  </AppLayoutVue>
</template>

<script>
export default {
  data: function () {
    return {
      changeState: false,
      showModal: false, //COPIAR ESTO PARA FUNCIONAMIENTO DE MODAL
      opcionesSelect2: [
        { value: 'batman', label: 'Batman' },
        { value: 'robin', label: 'Robin', disabled: true },
        { value: 'joker', label: 'Joker' },
      ],
      primerNombre: "",
      picked: "",
      mechanics: [],
      prueba: "",
      valor: ["batman"],
      textDate: "",
    };
  },
  methods: {
    changeStateFromModal() {
      //COPIAR ESTE PARA FUNCIONAMIENTO DE MODAL
      this.showModal = !this.showModal;
    },
    notify() {
      toast.info("Te informamos que", {
        autoClose: 5000,
        position: "top-right",
        transition: "bounce",
        toastBackgroundColor: "white",
        icon: "🚀",
      });
    },
    deletAlert() {
      this.$swal
        .fire({
          title: "Esta seguro de eliminar este registro",
          icon: "question",
          iconHtml: "❓",
          confirmButtonText: "Si, Eliminarlo",
          confirmButtonColor: "#f8312f",
          cancelButtonText: "Cancelar",
          showCancelButton: true,
          showCloseButton: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            toast.success("Alerta de success", {
              autoClose: 5000,
              position: "top-right",
              transition: "zoom",
              toastBackgroundColor: "white",
            });
          }
        });
    },
  },
  created() { },
};
</script>

<style>
/* ESTILOS SELECT2 */
/* altura del select 2 y ancho */
.div-select2 span {
  height: 32px !important;
  width: 215px !important;
}

/* Distancia el icono del select 2 de display */
.div-select2 .select2-container--default .select2-selection--single .select2-selection__arrow b {
  margin-left: 91px;
}

/* //ESTILOS SELECT2 */

div.tabla-modal table thead {
  font-size: 12px;
}

/* ESTILOS MULTISELECT */
.multiselect {
  min-width: 200px;
  min-height: 10px;
}



/* //ESTILOS MULTISELECT */
</style>
