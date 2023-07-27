<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";

import 'dropzone/dist/dropzone.css';
//import 'dropzone/dist/basic.css';
import Dropzone from 'dropzone';

</script>

<template>
    <div class="m-1.5">
        <!-- <div v-if="isLoading"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class="bg-gray-200 rounded-lg p-1 font-semibold">
                    <span class="text-blue-800">CARGANDO...</span>
                </div>
            </div>
        </div> -->
        <Modal :show="showModalFlag" @close="$emit('cerrar-modal')" :modal-title="'Administracion plazas. '" maxWidth="3xl">
            <div class="px-5 py-4">
                <div class="dropzone" ref="myDropzone">
                    <div class="dz-message">
                        Click para subir foto o arrastra la imagen.
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    mounted() {
        // Initialize Dropzone when the component is mounted
        this.initDropzone();
    },
    props: {
        modalData: {
            type: Array,
            default: [],
        },
        showModalFlag: {
            type: Boolean,
            default: false,
        },
    },
    created() { },
    data: function (data) {
        return {
            errors: [],
            isLoading: false,
            selectOptions: {
                uplt: [],
                financingSources: [],
                jobPositions: [],
                contractTypes: [],
                jobPositionStatus: [],
                allActivities: [],
                activities: []
            },
            jobPositionDet: {
                actividad_institucional: {
                    linea_trabajo: {
                        id_lt: ''
                    }
                },
                id_actividad_institucional: '',
                id_det_plaza: '',
                id_estado_plaza: '',
                id_plaza: '',
                id_proy_financiado: '',
                id_tipo_contrato: '',
                plaza_asignada_activa: null,
                codigo_det_plaza: ''
            },
        };
    },
    methods: {
        initDropzone() {
            const vm = this;
            // Add your Dropzone configuration here
            const dropzoneConfig = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                url: '/upload-employee-photo', // Replace with your Laravel route URL for handling uploads
                paramName: 'file',
                maxFilesize: 5, // Max file size in MB
                acceptedFiles: '.jpg, .jpeg, .png', // Allowed file types
                addRemoveLinks: true
                //previewTemplate: document.querySelector("#my-template").innerHTML
        };


        // Initialize Dropzone
        this.dropzone = new Dropzone(this.$refs.myDropzone, dropzoneConfig);

        this.$refs.myDropzone.addEventListener('click', this.handleDropzoneClick);

        this.dropzone.on('success', this.onUploadSuccess);
        this.dropzone.on('error', this.onUploadError);
        console.log(this.dropzone);
    },
    deleteFileOnServer(file) {
        console.log("llega al metodo vue");
        alert("hola")
    },
    handleDropzoneClick(event) {
        console.log(event);
        // Check if the clicked element has the class "dz-remove"
        if (event.target.classList.contains('dz-remove')) {
            // Call your Vue method here
            alert("are you trying to delete")
        }
    },




    onUploadSuccess(file, response) {
        // Handle successful upload
        console.log(this.dropzone);
    },
    onUploadError(file, errorMessage, xhr) {
        // Handle upload error
        console.log(errorMessage);
    },
    storeJobPositionDet() {
        this.$swal
            .fire({
                title: '¿Está seguro de guardar el nuevo concepto de ingreso?',
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
                    let url = '/store-job-position-det'
                    this.saveJobPositionDet(url)
                }
            });
    },
    saveJobPositionDet(url) {
        axios.post(url, this.jobPositionDet)
            .then((response) => {
                this.handleSuccessResponse(response.data.mensaje)
            })
            .catch((errors) => {
                this.handleErrorResponse(errors)
            });
    },
    handleSuccessResponse(message) {
        toast.success(message, {
            autoClose: 3000,
            position: "top-right",
            transition: "zoom",
            toastBackgroundColor: "white",
        });
        this.$emit("get-table");
        this.$emit("cerrar-modal");
    },
    handleErrorResponse(errors) {
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
            this.manageError(errors, this)
            this.$emit("cerrar-modal");
        }
    },
    updateJobPositionDet() {
        this.$swal
            .fire({
                title: '¿Está seguro de actualizar la plaza?',
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
                    let url = '/update-job-position-det'
                    this.saveJobPositionDet(url)
                }
            });
    },
    loadOptions() {
        this.selectOptions.uplt = []
        this.selectOptions.financingSources = []
        this.selectOptions.jobPositionStatus = []
        this.selectOptions.jobPositions = []
        this.selectOptions.activities = []
        this.selectOptions.allActivities = []
        this.selectOptions.contractTypes = []
        this.getSelectsJobPositionDet()
    },
    async getSelectsJobPositionDet() {
        try {
            this.isLoading = true;  // Activar el estado de carga
            const response = await axios.get("/get-selects-job-position-det");
            this.selectOptions.uplt = response.data.uplt
            this.selectOptions.jobPositionStatus = response.data.jobPositionStatus
            this.selectOptions.financingSources = response.data.financingSources
            this.selectOptions.contractTypes = response.data.contractTypes
            this.selectOptions.jobPositions = response.data.jobPositions
            this.selectOptions.allActivities = response.data.activities
            if (this.modalData != '') {
                this.filterActivities(this.jobPositionDet.actividad_institucional.linea_trabajo.id_lt, false)
            } else {
                this.jobPositionDet.id_estado_plaza = 1
            }
        } catch (errors) {
            this.manageError(errors, this)
        } finally {
            this.isLoading = false;  // Desactivar el estado de carga
        }
    },
    filterActivities(uplt_id, load = true) {
        if (load) {
            this.jobPositionDet.id_actividad_institucional = ''
        }
        this.selectOptions.activities = []
        this.selectOptions.activities = this.selectOptions.allActivities.filter(activity => activity.id_lt === uplt_id);
    },
    clearSelectUplt() {
        this.selectOptions.activities = []
        this.jobPositionDet.id_actividad_institucional = ''
    },
    cleanInputs() {
        this.jobPositionDet.actividad_institucional.linea_trabajo.id_lt = ''
        this.jobPositionDet.id_det_plaza = ''
        this.jobPositionDet.id_estado_plaza = ''
        this.jobPositionDet.id_plaza = ''
        this.jobPositionDet.id_proy_financiado = ''
        this.jobPositionDet.id_tipo_contrato = ''
        this.jobPositionDet.id_actividad_institucional = ''
        this.jobPositionDet.plaza_asignada_activa = null
        this.jobPositionDet.codigo_det_plaza = ''
    },

},
watch: {
    showModalFlag: function (value, oldValue) {
        if (value) {
            this.errors = [];
            if (this.modalData != '') {
                this.jobPositionDet = JSON.parse(JSON.stringify(this.modalData));
            } else {
                this.cleanInputs()
            }
            this.loadOptions()
        }
    },
},
computed: {
    statusOptions() {
        if (this.jobPositionDet.plaza_asignada_activa == null) {
            // Filter the array and return a new array without modifying the original one
            const filteredStatus = this.selectOptions.jobPositionStatus.filter((value) => {
                return (value.value != 3);
            });
            return filteredStatus
        } else {
            return this.selectOptions.jobPositionStatus
        }
    }
}
};
</script>

<style>
/* Customize the loader's appearance using Tailwind CSS classes or your custom styles */
.loader {
    border-top-color: #3498db;
    border-left-color: #3498db;
}



.my-custom-file-preview {
    border: 2px solid #3490dc;
    background: #f0f8ff;
    color: #3490dc;
    padding: 10px;
    margin: 10px;
}

.dz-remove-btn {
    display: inline-block;
    background: #ff5050;
    color: #fff;
    border: none;
    padding: 5px 10px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.dz-remove-btn:hover {
    background: #cc0000;
}
</style>
