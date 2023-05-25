<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
  <ModalVue :show="showModal" @close-modal="$emit('cerrar-modal')" :title="'Administración de modelo '"
    @close="$emit('cerrar-modal')">
    <div class="px-3 pt-1 pb-1">
      <div class="text-sm">
        <div class="mb-4 md:flex flex-row justify-center">
          Seleccione marca y escriba el modelo:
        </div>
        <div class="mb-4 md:flex flex-row justify-center">
          <div class="mb-4 md:mr-0 md:mb-0 w-2/3">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Marca <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
              <Multiselect v-model="model.id_brand" :options="brands" placeholder="Marca" :searchable="true" />
              <LabelToInput icon="list" />
            </div>
            <InputError v-for="(item, index) in errors.id_brand" :key="index" class="mt-2" :message="item" />
          </div>
        </div>
        <div class="mb-4 md:flex flex-row justify-center">
          <div class="mb-4 md:mx-0 md:mb-0 w-2/3">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Modelo <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
              <TextInput v-model="model.name_model" :label-input="false" id="personal-information" type="text"
                placeholder="Nombre Modelo" @update:modelValue="validateInput('name_model', limit = 55)">
                <LabelToInput icon="personalInformation" forLabel="personal-information" />
              </TextInput>
            </div>
            <InputError v-for="(item, index) in errors.name_model" :key="index" class="mt-2" :message="item" />
          </div>
        </div>

        <div class="mb-4 md:flex flex-row justify-center">
          <GeneralButton v-if="modalData != ''" @click="updateModel()" color="bg-orange-700  hover:bg-orange-800"
            text="Actualizar" icon="add" />
          <GeneralButton v-else @click="saveNewModel()" color="bg-green-700  hover:bg-green-800" text="Agregar"
            icon="add" />
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
  props: {
    modalData: {
      type: Array,
      default: [],
    },
    showModal: {
      type: Boolean,
      default: false,
    },
  },
  created() {
    this.getBrands();
  },
  data: function (data) {
    return {
      model: {
        name_model: "",
        id_model: "",
        id_brand: "",
      },
      brands: [],
      errors: [],
    };
  },
  methods: {
    validateInput(field, limit) {
      if (this.model[field] && this.model[field].length > limit) {
        this.model[field] = this.model[field].substring(0, limit);
      }
    },
    async getBrands() {
      await axios
        .get("/get-brands")
        .then((response) => {
          this.brands = response.data.brands;
        })
        .catch((errors) => {
          let msg = this.manageError(errors);
          this.$swal.fire({
            title: "Operación cancelada",
            text: msg,
            icon: "warning",
            timer: 5000,
          });
          this.$emit("cerrar-modal");
        });
    },
    saveNewModel() {
      this.$swal
        .fire({
          title: '¿Está seguro de guardar el nuevo modelo?',
          icon: 'question',
          iconHtml: '❓',
          confirmButtonText: 'Si, Guardar',
          confirmButtonColor: '#141368',
          cancelButtonText: 'Cancelar',
          showCancelButton: true,
          showCloseButton: true
        })
        .then((result) => {
          if (result.isConfirmed) {
            axios
              .post("/save-model", this.model)
              .then((response) => {
                toast.success(response.data.mensaje, {
                  autoClose: 3000,
                  position: "top-right",
                  transition: "zoom",
                  toastBackgroundColor: "white",
                });
                this.$emit("get-table");
                this.$emit("cerrar-modal");
              })
              .catch((errors) => {
                if (errors.response.status === 422) {
                  toast.warning(
                    "Tienes algunos errores por favor verifica tus datos.",
                    {
                      autoClose: 5000,
                      position: "top-right",
                      transition: "zoom",
                      toastBackgroundColor: "white",
                    }
                  );
                  this.errors = errors.response.data.errors;
                } else {
                  let msg = this.manageError(errors);
                  this.$swal.fire({
                    title: "Operación cancelada",
                    text: msg,
                    icon: "warning",
                    timer: 5000,
                  });
                  this.$emit("cerrar-modal");
                }
              });
          }
        });
    },
    updateModel() {
      this.$swal
        .fire({
          title: '¿Está seguro de actualizar el modelo?',
          icon: 'question',
          iconHtml: '❓',
          confirmButtonText: 'Si, Actualizar',
          confirmButtonColor: '#141368',
          cancelButtonText: 'Cancelar',
          showCancelButton: true,
          showCloseButton: true
        })
        .then((result) => {
          if (result.isConfirmed) {
            axios
              .post("/update-model", this.model)
              .then((response) => {
                toast.success(response.data.mensaje, {
                  autoClose: 3000,
                  position: "top-right",
                  transition: "zoom",
                  toastBackgroundColor: "white",
                });
                this.$emit("get-table");
                this.$emit("cerrar-modal");
              })
              .catch((errors) => {
                if (errors.response.status === 422) {
                  if (errors.response.data.logical_error) {
                    toast.error(
                      errors.response.data.logical_error,
                      {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                      }
                    );
                    this.$emit("get-table");
                    this.$emit("cerrar-modal");
                  } else {
                    toast.warning(
                      "Tienes algunos errores por favor verifica tus datos.",
                      {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                      }
                    );
                    this.errors = errors.response.data.errors;
                  }
                } else {
                  let msg = this.manageError(errors);
                  this.$swal.fire({
                    title: "Operación cancelada",
                    text: msg,
                    icon: "warning",
                    timer: 5000,
                  });
                  this.$emit("cerrar-modal");
                }
              });
          }
        });
    },
  },
  watch: {
    showModal: function (value, oldValue) {
      if (value) {
        this.errors = [];
        this.model.id_model = this.modalData.id_modelo;
        this.model.name_model = this.modalData.nombre_modelo;
        this.model.id_brand = this.modalData.id_marca;
      }
    },
  },
};
</script>