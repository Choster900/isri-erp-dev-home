<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import InputError from "@/Components/InputError.vue";
import axios from 'axios';
</script>

<template>
  <ModalVue :show="showModal" @close-modal="$emit('cerrar-modal')" :title="'Administración de marca '"
    @close="$emit('cerrar-modal')">
    <div class="px-5 pt-4 pb-1">

      <div class="text-sm">
        <div class="mb-4 md:flex flex-row justify-center">Nombre marca:</div>
        <div class="mb-4 md:flex flex-row justify-center">
          <div class="md:mx-2 md:mb-0 basis-2/3">
            <TextInput id="nombre-marca" :label-input="false" v-model="brand.name_brand" :value="brand.name_brand"
              type="text" placeholder="Nombre Marca"
              @update:modelValue="validateInput('name_brand', limit = 55, upper = true)">
              <LabelToInput icon="personalInformation" forLabel="nombre-marca" />
            </TextInput>
            <InputError v-for="(item, index) in errors.name_brand" :key="index" class="mt-2" :message="item" />
          </div>
        </div>
        <div class="mb-4 md:flex flex-row justify-center">
          <GeneralButton v-if="modalData != ''" @click="updateMarca()" color="bg-orange-700  hover:bg-orange-800"
            text="Actualizar" icon="add" />
          <GeneralButton v-else @click="saveNewMarca()" color="bg-green-700  hover:bg-green-800" text="Agregar"
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
      default: false
    }
  },
  data: function (data) {
    return {
      brand: {
        name_brand: '',
        id_brand: ''
      },
      errors: [],
    }
  },
  methods: {
    //Function to validate data entry
    validateInput(field, limit, upper_case) {
      if (this.brand[field] && this.brand[field].length > limit) {
        this.brand[field] = this.brand[field].substring(0, limit);
      }
      if (upper_case) {
        this.toUpperCase(field)
      }
    },
    toUpperCase(field) {
      //Converts field to uppercase
      this.brand[field] = this.brand[field].toUpperCase()
    },
    saveNewMarca() {
      this.$swal.fire({
        title: '¿Está seguro de guardar la nueva marca?',
        icon: 'question',
        iconHtml: '❓',
        confirmButtonText: 'Si, Guardar',
        confirmButtonColor: '#141368',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        showCloseButton: true
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/save-brand", {
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
          })
        }
      })

    },
    updateMarca() {
      this.$swal.fire({
        title: '¿Está seguro de actualizar la nueva marca?',
        icon: 'question',
        iconHtml: '❓',
        confirmButtonText: 'Si, Actualizar',
        confirmButtonColor: '#141368',
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
          })
        }
      })
    }
  },
  watch: {
    showModal: function (value, oldvalue) {
      if (value) {
        this.errors = []
        this.brand.name_brand = this.modalData.nombre_marca
        this.brand.id_brand = this.modalData.id_marca
      }
    }
  }
};

</script>