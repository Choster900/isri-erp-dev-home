<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";

import VueImageLightbox from 'vue-image-lightbox';
import 'vue-image-lightbox/dist/vue-image-lightbox.min.css';
</script>

<template>
    <div class="m-1.5">
        <div v-if="isFullScreenActive" ref="fullScreenContainer" @click="closeFullScreen"
            class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-90 z-50 flex justify-center items-center">
            <span ref="deleteButton" class="absolute top-2 right-8 text-white text-4xl cursor-pointer"
                @click.stop="closeFullScreen">&times;</span>
            <img class="max-w-[90%] max-h-[90%] object-contain cursor-pointer" :src="getFullScreenImageUrl()" />
        </div>

        <Modal v-else :show="showModalFlag" @close="$emit('cerrar-modal')" :modal-title="'Administracion fotografias. '"
            maxWidth="2xl" :closeOutSide="false">
            <div class="px-5 py-4">
                <!-- <div v-if="employee.persona">
                    <VueImageLightbox :media="media" />
                </div> -->

                <div >
                    <div class="card w-[90%] bg-gray-100">
                        <div class="text-center">
                            <p class="text-slate-800 font-bold">Fotografia para: {{ employee.codigo_empleado }}</p>
                        </div>
                        <div class="drag-area bg-gray-200 text-slate-800 mb-2" @dragover.prevent="onDragOver"
                            @dragleave="onDragLeave" @drop.prevent="onDrop">
                            <span v-if="!isDragging">
                                Arrastra y suelta una imagen aquí o
                                <span class="text-blue-600 ml-0 cursor-pointer transition duration-400" role="button"
                                    @click="selectFiles">
                                    Selecciona
                                </span>
                            </span>
                            <div v-else class="text-blue-600 ml-0 cursor-pointer transition duration-400">Suelta la imagen
                                aquí</div>
                            <input type="file" name="file" class="file" ref="fileInput" multiple @change="onFileSelect" />
                        </div>
                        <div class="container w-full flex flex-wrap -mx-1">
                            <div class="image-wrapper w-1/3 px-1" v-for="(image, index) in images" :key="index">
                                <div class="bg-slate-400 image border border-gray-400 rounded-md flex items-center justify-center"
                                    :class="isSelectedImage(index) ? 'border-2 border-blue-600' : ''">
                                    <span class="delete-img" @click="deleteImage(index)">&times;</span>
                                    <img :src="image.url" @click="toggleFullScreenImage(index)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <GeneralButton class="mr-1" text="Cancelar" icon="delete" @click="$emit('cerrar-modal')" />
                        <GeneralButton v-if="images.length > 0" color="bg-green-700 hover:bg-green-800" text="Guardar"
                            icon="add" class="" @click="storeEmployePhoto()" />
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    components: {
        VueImageLightbox,
    },
    props: {
        showModalFlag: {
            type: Boolean,
            default: false,
        },
        modalData: {
            type: Array,
            default: []
        }
    },
    created() { },
    data: function (data) {
        return {
            media: [
                { // For image
                    thumb: '../../../img/escudo-nacional.png',
                    src: '../../../img/escudo-nacional.png',
                },
                { // For image
                    thumb: '../../../img/isri-logo2.png',
                    src: '../../../img/isri-logo2.png',
                }
            ],
            employee: [],
            images: [],
            selectedImageIndex: -1,
            isDragging: false,
            isFullScreenActive: false,
            fullScreenImageIndex: null,
            lightboxIndex: 0,
        };
    },
    methods: {
        openLightbox(index) {
            this.lightboxIndex = index;
        },
        closeLightbox() {
            this.lightboxIndex = -1;
        },
        storeEmployePhoto() {
            this.$swal
                .fire({
                    title: '¿Está seguro de guardar los archivos?',
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
                        let url = '/upload-employee-photo'
                        this.saveEmployePhoto(url)
                    }
                });
        },
        saveEmployePhoto(url) {
            axios.post(url, {
                file: this.images,
                id_person: this.employee.persona.id_persona,
                code: this.employee.codigo_empleado
            }, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then((response) => {
                    this.handleSuccessResponse(response.data.message)
                })
                .catch((errors) => {
                    this.handleErrorResponse(errors)
                });
        },
        handleSuccessResponse(message) {
            this.showToast(toast.success, message);
            this.$emit("get-table");
            this.$emit("cerrar-modal");
        },
        handleErrorResponse(errors) {
            if (errors.response.status === 422) {
                if (errors.response.data.logical_error) {
                    this.showToast(toast.error, errors.response.data.logical_error);
                    this.$emit("get-table");
                    this.$emit("cerrar-modal");
                } else {
                    this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                }
            } else {
                this.manageError(errors, this)
                this.$emit("cerrar-modal");
            }
        },
        isSelectedImage(index) {
            return this.selectedImageIndex === index; // Returns true if the image is selected
        },
        focusLastImage() {
            this.$nextTick(() => {
                const imagesWrapper = document.querySelector(".container");
                if (imagesWrapper) {
                    const imageWrappers = imagesWrapper.querySelectorAll(".image-wrapper");
                    if (imageWrappers.length > 0) {
                        this.selectedImageIndex = imageWrappers.length - 1
                        const lastImageWrapper = imageWrappers[imageWrappers.length - 1];
                        lastImageWrapper.scrollIntoView({
                            behavior: "smooth",
                            block: "start",
                        });
                    }
                }
            });
        },
        toggleFullScreenImage(index) {
            this.selectedImageIndex = index;
            this.isFullScreenActive = true;
            this.fullScreenImageIndex = index;
            document.body.style.overflow = "hidden"; // Hide the scrollbar while in full-screen
        },
        closeFullScreen(event) {
            const fullScreenContainer = this.$refs.fullScreenContainer;
            const deleteButton = this.$refs.deleteButton;

            if (event.target === fullScreenContainer || event.target === deleteButton) {
                this.isFullScreenActive = false;
                this.fullScreenImageIndex = null;
                document.body.style.overflow = "auto"; // Restore the scrollbar after closing full-screen
            }
        },
        getFullScreenImageUrl() {
            if (this.fullScreenImageIndex !== null && this.fullScreenImageIndex >= 0 && this.fullScreenImageIndex < this.images.length) {
                return this.images[this.fullScreenImageIndex].url;
            }
            return ""; // Return an empty string if the index is invalid or null
        },
        selectFiles() {
            this.$refs.fileInput.value = '';
            this.$refs.fileInput.click();
        },
        onFileSelect(event) {
            event.preventDefault();
            const files = event.target.files;
            if (files.length === 0) return;
            if (files.length + this.images.length > 2) {
                this.showToast(toast.warning, "No puedes subir más de dos fotografias a la vez.");
            } else {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.split("/")[0] !== "image") {
                        this.showToast(toast.warning, "Solo puedes subir archivos tipo imagen.");
                    } else if (file.size > 2 * 1024 * 1024) {
                        this.showToast(toast.warning, `El archivo "${file.name}" pesa mas de 2 Mb.`);
                    } else {
                        if (!this.images.some((e) => e.name === file.name)) {
                            this.images.push({ name: file.name, url: URL.createObjectURL(file), file: file });
                            this.focusLastImage();
                        } else {
                            this.showToast(toast.warning, `El archivo "${file.name}" ya fue cargado.`);
                        }
                    }
                }
            }
        },
        deleteImage(index) {
            this.images.splice(index, 1)
        },
        onDragOver(event) {
            event.preventDefault();
            this.isDragging = true
            event.dataTransfer.dropEffect = "copy"
        },
        onDragLeave(event) {
            event.preventDefault()
            this.isDragging = false
        },
        onDrop(event) {
            event.preventDefault()
            this.isDragging = false
            const files = event.dataTransfer.files
            if (files.length + this.images.length > 2) {
                this.showToast(toast.warning, "No puedes subir más de dos fotografias a la vez.");
            } else {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.split("/")[0] !== "image") {
                        this.showToast(toast.warning, "Solo puedes subir archivos tipo imagen.");
                    } else if (file.size > 2 * 1024 * 1024) {
                        this.showToast(toast.warning, `El archivo ${file.name} pesa mas de 2 Mb.`);
                    } else {
                        if (!this.images.some((e) => e.name === file.name)) {
                            this.images.push({ name: file.name, url: URL.createObjectURL(file), file: file });
                            this.focusLastImage();
                        } else {
                            this.showToast(toast.warning, `El archivo ${file.name} ya fue cargado.`);
                        }
                    }
                }
            }
        }
    },
    watch: {
        showModalFlag: function (value, oldValue) {
            if (value) {
                this.images = []
                this.employee = JSON.parse(JSON.stringify(this.modalData));
                console.log(this.employee);
            }
        },
    },
    computed: {
        // photos : function(){
        //     if(this.employee.persona){
        //         return this.employee.persona.fotos.length
        //     }else{
        //         return 0
        //     }
        // }
    }
};
</script>

<style scoped>
.card {
    /* width:600px; */
    padding: 10px;
    margin: 0 auto;
    box-shadow: 0 0 5px #5f5454;
    border-radius: 5px;
    overflow: hidden;
}

.card button {
    outline: 0;
    border: 0;
    color: #fff;
    border-radius: 4px;
    font-weight: 400;
    padding: 8px 13px;
    width: 25%;
    /* background: #fe0000; */
    display: block;
    /* Ensure the button behaves as a block element */
    margin: 0 auto;
    /* Center the button horizontally */
}

.card .drag-area {
    height: 100px;
    border-radius: 5px;
    border: 2px;
    /* background: #f4f3f9; */
    /* color: #000000; */
    display: flex;
    justify-content: center;
    align-items: center;
    user-select: center;
    -webkit-user-select: none;
    margin-top: 10px;
    border-style: dashed;
}

.card .drag-area .visible {
    font-size: 18px;
}



.card .container {
    /* width: 400px; */
    height: auto;
    padding-top: 10px;
    justify-content: flex-start;
    align-items: flex-start;
    flex-wrap: wrap;
    position: relative;
    margin-bottom: 10px;
    overflow-x: auto;
    /* Add this line to allow horizontal scrolling */
    max-height: 200px;
    /* Set a maximum height to limit the container's height */
}

.card .container .image {
    height: 180px;
    position: relative;
    margin-bottom: 8px;
    box-sizing: border-box;

    display: flex;
    /* Use flexbox to center the content vertically */
    align-items: center;
    justify-content: center;
}

.card .container .image-wrapper {
    flex-basis: calc(33.3333% - 2px);
    /* Set each image wrapper's initial width to occupy 1/3 of the container minus the margin */
}

.card .container .image img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 5px;
    object-fit: cover;
    cursor: pointer;
}

.card .container .image span {
    position: absolute;
    top: -2px;
    right: 9px;
    font-size: 20px;
    cursor: pointer;
}

.card input,
.card .drag-area .on-drop,
.card .drag-area.dragover .visible {
    display: none;
}

.delete-img {
    z-index: 999;
    color: #fe0000;
}


/* Customize the loader's appearance using Tailwind CSS classes or your custom styles */
.loader {
    border-top-color: #3498db;
    border-left-color: #3498db;
}
</style>