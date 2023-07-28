<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModalFlag" @close="$emit('cerrar-modal')" :modal-title="'Administracion fotografias. '"
            maxWidth="2xl" :closeOutSide="false">
            <div class="px-5 py-4">
                <div class="card w-[90%] bg-gray-100">
                    <div class="top">
                        <p class="text-slate-800">Carga de imagen</p>
                    </div>
                    <div class="drag-area bg-gray-200 text-slate-800 mb-2" @dragover.prevent="onDragOver"
                        @dragleave="onDragLeave" @drop.prevent="onDrop">
                        <span v-if="!isDragging">
                            Arrastra y suelta una imagen aquí o
                            <span class="select" role="button" @click="selectFiles">
                                Selecciona
                            </span>
                        </span>
                        <div v-else class="select">Suelta la imagen aquí</div>
                        <input type="file" name="file" class="file" ref="fileInput" multiple @change="onFileSelect" />
                    </div>
                    <div class="container w-full flex flex-wrap -mx-1">
                        <div class="image-wrapper w-1/3 px-1" v-for="(image, index) in images" :key="index">
                            <div class="bg-slate-400 image border border-gray-400 rounded-md flex items-center justify-center"
                                :class="isSelectedImage(index) ? 'border-2 border-blue-600' : ''">
                                <span class="delete" @click="deleteImage(index)">&times;</span>
                                <img :src="image.url" @click="toggleFullScreenImage(index)" />
                            </div>
                        </div>
                    </div>
                    <!-- Full-screen image display -->
                    <div v-if="isFullScreenActive" class="full-screen-container" @click="closeFullScreen">
                        <span class="delete" @click="closeFullScreen">&times;</span>
                        <img :src="getFullScreenImageUrl()" />
                    </div>
                    <!-- <button type="button" class="bg-green-600">SUBIR</button> -->
                </div>
                <div class="flex justify-center mt-4">
                    <GeneralButton class="mr-1" text="Cancelar" icon="delete" @click="$emit('cerrar-modal')" />
                    <GeneralButton v-if="images.length > 0" color="bg-green-700 hover:bg-green-800" text="Guardar"
                        icon="add" class="" @click="storeEmployePhoto()" />
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    props: {
        showModalFlag: {
            type: Boolean,
            default: false,
        },
        person: {
            type: Array,
            default: []
        }
    },
    created() { },
    data: function (data) {
        return {
            persona: [],
            images: [],
            selectedImageIndex: -1,
            isDragging: false,
            isFullScreenActive: false,
            fullScreenImageIndex: null,
        };
    },
    methods: {
        storeEmployePhoto() {
            //console.log(this.images);
            //const formData = new FormData();
            // this.images.forEach((image, index) => {
            //     if (image.file) {
            //         formData.append(`file_${index}`, image.file[0]);
            //     }
            // });
            // formData.forEach((value, key) => {
            //     console.log(`Clave: ${key}, Nombre del archivo: ${value.name}, Tipo MIME: ${value.type}`);
            // });
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

                        this.images.forEach((image, index) => {
                            axios.post(url, {file:image.file}, {
                                headers: {
                                    'Content-Type': 'multipart/form-data', // Asegurar que se establezca el encabezado correcto para el envío de archivos
                                },
                            })
                                .then((response) => {
                                    console.log(response);
                                    this.handleSuccessResponse(response.data.mensaje)
                                })
                                .catch((errors) => {
                                    this.handleErrorResponse(errors)
                                });
                        });

                        //this.saveEmployePhoto(url,formData)
                        // axios.post(url, formData, {
                        //     headers: {
                        //         'Content-Type': 'multipart/form-data', // Asegurar que se establezca el encabezado correcto para el envío de archivos
                        //     },
                        // })
                        //     .then((response) => {
                        //         console.log(response);
                        //         this.handleSuccessResponse(response.data.mensaje)
                        //     })
                        //     .catch((errors) => {
                        //         this.handleErrorResponse(errors)
                        //     });
                    }
                });
        },
        saveEmployePhoto(url, formData) {
            axios.post(url, formData)
                .then((response) => {
                    console.log(response);
                    this.handleSuccessResponse(response.data.mensaje)
                })
                .catch((errors) => {
                    this.handleErrorResponse(errors)
                });
        },
        handleSuccessResponse(message) {
            this.showToast(toast.success, message);
            // this.$emit("get-table");
            // this.$emit("cerrar-modal");
        },
        handleErrorResponse(errors) {
            console.log(errors);
            // if (errors.response.status === 422) {
            //     if (errors.response.data.logical_error) {
            //         this.showToast(toast.error, errors.response.data.logical_error);
            //         this.$emit("get-table");
            //         this.$emit("cerrar-modal");
            //     } else {
            //         this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
            //     }
            // } else {
            //     this.manageError(errors,this)
            //     this.$emit("cerrar-modal");
            // }
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
            if (event.target.classList.contains("full-screen-container") ||
                event.target.classList.contains("delete")) {
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
                this.showToast(toast.warning, "No puedes subir más de dos fotografias.");
            } else {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.split("/")[0] !== "image") {
                        this.showToast(toast.warning, "Solo puedes subir archivos jpg, jpeg y png.");
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
                this.showToast(toast.warning, "No puedes subir más de dos fotografias.");
            } else {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.split("/")[0] !== "image") {
                        this.showToast(toast.warning, "Solo puedes subir archivos jpg, jpeg y png.");
                    } else if (file.size > 1 * 1024 * 1024) {
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
                this.persona = JSON.parse(JSON.stringify(this.person));
                //console.log(this.persona);
            }
        },
    },
    computed: {
    }
};
</script>

<style scoped>
/* Styles for the full-screen image container */
.full-screen-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    /* Semi-transparent black background */
    z-index: 9999;
    /* Ensure the full-screen container is on top of other elements */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Styles for the full-screen image */
.full-screen-container img {
    max-width: 90%;
    /* Limit the image width to 90% of the screen width */
    max-height: 90%;
    /* Limit the image height to 90% of the screen height */
    object-fit: contain;
    /* Maintain aspect ratio and fit the image within the container */
    cursor: pointer;
    /* Show the pointer cursor when hovering over the image */
}

/* Styles for the close button */
.full-screen-container .delete {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 36px;
    cursor: pointer;
    color: #fff;
}

.card {
    /* width:600px; */
    padding: 10px;
    margin: 0 auto;
    box-shadow: 0 0 5px #5f5454;
    border-radius: 5px;
    overflow: hidden;
}

.card .top {
    text-align: center;
}

.card p {
    font-weight: bold;
    /* color: #000000; */
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

.card .select {
    color: #5256ad;
    margin-left: 5px;
    cursor: pointer;
    transition: 0.4s;

}

.card .select:hover {
    opacity: 0.6;
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

.delete {
    z-index: 999;
    color: #fe0000;
}


/* Customize the loader's appearance using Tailwind CSS classes or your custom styles */
.loader {
    border-top-color: #3498db;
    border-left-color: #3498db;
}
</style>