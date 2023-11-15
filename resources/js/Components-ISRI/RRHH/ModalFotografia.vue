<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";

</script>

<template>
    <div v-if="isLoading"
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
    </div>
    <div v-else class="m-1.5">

        <div v-if="isFullScreenActive" ref="fullScreenContainer" @click="closeFullScreen"
            class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-90 z-50 flex flex-col items-center justify-center">
            <span ref="deleteButton" class="absolute top-2 right-8 text-white text-4xl cursor-pointer"
                @click.stop="closeFullScreen">&times;</span>
            <div ref="imageWrapper" :class="activeImages.length > 1 ? 'justify-between' : 'justify-center'"
                class="flex items-center h-[90%] w-full px-4">
                <div v-if="activeImages.length > 1" class="cursor-pointer" @click.stop="prev">
                    <svg class="pointer-events-none" fill="#fff" height="48" viewBox="0 0 24 24" width="48">
                        <path d="M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z" />
                        <path d="M0-.5h24v24H0z" fill="none" />
                    </svg>
                </div>
                <img class="max-w-[81%] max-h-[90%] object-contain cursor-pointer"
                    :src="activeImages[fullScreenImageIndex].url" />
                <div v-if="activeImages.length > 1" class="cursor-pointer" @click.stop="next">
                    <svg class="pointer-events-none" fill="#fff" height="48" viewBox="0 0 24 24" width="48">
                        <path d="M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z" />
                        <path d="M0-.25h24v24H0z" fill="none" />
                    </svg>
                </div>
            </div>
            <div class="text-white text-sm h-[10%]">
                {{ fullScreenImageIndex + 1 }}/{{ activeImages.length }}
            </div>
        </div>



        <Modal v-else :show="showModalFlag" @close="$emit('cerrar-modal')" :modal-title="'Administracion fotografias. '"
            maxWidth="2xl" :closeOutSide="false">
            <div class="px-5 py-4">
                <div>
                    <div class="card overflow-hidden p-4 mx-auto w-[90%] bg-gray-100">
                        <div class="text-center">
                            <p class="text-slate-800 font-bold">Fotografia para: {{ employee.codigo_empleado }}</p>
                        </div>
                        <div class="h-24 rounded-md border-black border-2 border-dashed flex justify-center items-center mt-4 bg-gray-200 text-slate-800 mb-2"
                            @dragover.prevent="onDragOver" @dragleave="onDragLeave" @drop.prevent="onDrop">
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
                        <div ref="imagesWrapper"
                            class="h-auto pt-2 justify-start items-start relative mb-2 overflow-x-auto max-h-[200px] w-full flex flex-wrap -mx-1">
                            <div ref="imageWrappers" class="flex-basis-[calc(33.3333%-2px)] w-1/3 px-1"
                                v-for="(image, index) in activeImages" :key="index">
                                <div class="bg-slate-400 h-[180px] relative mb-2 border border-gray-400 rounded-md flex items-center justify-center"
                                    :class="isSelectedImage(index) ? 'border-2 border-blue-600' : ''">
                                    <span
                                        class="absolute top-[-10px] right-[-5px] mt-2 mr-2 text-2xl cursor-pointer z-10 text-red-600"
                                        @click="deleteImage(index)">&times;</span>
                                    <img class="max-w-full max-h-full rounded-lg object-cover cursor-pointer"
                                        :src="image.url" @click="toggleFullScreenImage(index)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <GeneralButton class="mr-1" text="Cancelar" icon="delete" @click="$emit('cerrar-modal')" />
                        <GeneralButton v-if="images.length > 0" color="bg-green-700 hover:bg-green-800"
                            text="Guardar cambios" icon="add" class="" @click="storeEmployePhoto()" />
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    mounted() {
        window.addEventListener('keydown', this.onKeydown)
    },
    destroyed() {
        window.removeEventListener('keydown', this.onKeydown)
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
            isLoading: false,
            employee: [],
            images: [],
            selectedImageIndex: -1,
            isDragging: false,
            isFullScreenActive: false,
            fullScreenImageIndex: null,
        };
    },
    methods: {
        onKeydown(e) {
            if (this.isFullScreenActive) {
                switch (e.key) {
                    case 'ArrowRight':
                        this.next();
                        break;
                    case 'ArrowLeft':
                        this.prev();
                        break;
                    case 'ArrowDown':
                    case 'ArrowUp':
                    case ' ':
                        e.preventDefault();
                        break;
                    case 'Escape':
                        this.isFullScreenActive = false;
                        break;
                }
            }
        },
        hasNext() {
            return this.fullScreenImageIndex + 1 < this.images.length;
        },
        hasPrev() {
            return this.fullScreenImageIndex - 1 >= 0;
        },
        prev() {
            if (this.hasPrev()) {
                this.fullScreenImageIndex -= 1;
            } else {
                this.fullScreenImageIndex = this.images.length - 1
            }
        },
        next() {
            if (this.hasNext()) {
                this.fullScreenImageIndex += 1;
            } else {
                this.fullScreenImageIndex = 0
            }
        },
        async storeEmployePhoto() {
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
                .then(async (result) => {
                    if (result.isConfirmed) {
                        let url = '/upload-employee-photo'
                        this.saveEmployePhoto(url)
                    }
                });
        },
        async saveEmployePhoto(url) {
            this.isLoading = true
            await axios.post(url, {
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
                })
                .finally(() => {
                    this.isLoading = false
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
                const imagesWrapper = this.$refs.imagesWrapper;
                if (imagesWrapper) {
                    const imageWrappers = this.$refs.imageWrappers;
                    if (imageWrappers.length > 0) {
                        this.selectedImageIndex = imageWrappers.length - 1;
                        const lastImageWrapper = imageWrappers[imageWrappers.length - 1];
                        lastImageWrapper.scrollIntoView({
                            behavior: "smooth",
                            block: "start"
                        });
                    }
                }
            });
        },
        focusSelectedImage() {
            this.$nextTick(() => {
                const imagesWrapper = this.$refs.imagesWrapper;
                if (imagesWrapper) {
                    const imageWrappers = this.$refs.imageWrappers;
                    if (imageWrappers.length > 0) {
                        //this.selectedImageIndex = imageWrappers.length - 1;
                        const lastImageWrapper = imageWrappers[this.selectedImageIndex];
                        lastImageWrapper.scrollIntoView({
                            behavior: "smooth",
                            block: "start"
                        });
                    }
                }
            });
        },
        toggleFullScreenImage(index) {
            // this.index = index
            // this.visible = true

            this.selectedImageIndex = index;
            this.isFullScreenActive = true;
            this.fullScreenImageIndex = index;
            document.body.style.overflow = "hidden"; // Hide the scrollbar while in full-screen
        },
        closeFullScreen(event) {
            const fullScreenContainer = this.$refs.fullScreenContainer;
            const deleteButton = this.$refs.deleteButton;
            const imageWrapper = this.$refs.imageWrapper

            if (event.target === fullScreenContainer || event.target === deleteButton || event.target === imageWrapper) {
                this.isFullScreenActive = false;
                this.selectedImageIndex = this.fullScreenImageIndex
                this.fullScreenImageIndex = null;
                document.body.style.overflow = "auto"; // Restore the scrollbar after closing full-screen
                this.focusSelectedImage()
            }
        },
        selectFiles() {
            this.$refs.fileInput.value = '';
            this.$refs.fileInput.click();
        },
        onFileSelect(event) {
            event.preventDefault();
            const files = event.target.files;
            if (files.length === 0) return;
            if (files.length > 2) {
                this.showToast(toast.warning, "No puedes cargar más de dos fotografias a la vez.");
            } else {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.split("/")[0] !== "image") {
                        this.showToast(toast.warning, "Solo puedes subir archivos tipo imagen.");
                    } else if (file.size > 2 * 1024 * 1024) {
                        this.showToast(toast.warning, `El archivo "${file.name}" pesa mas de 2 Mb.`);
                    } else {
                        if (!this.images.some((e) => e.name === file.name)) {
                            this.images.push({ id: '', name: file.name, url: URL.createObjectURL(file), file: file, deleted: false });
                            this.focusLastImage();
                        } else {
                            this.showToast(toast.warning, `El archivo "${file.name}" ya fue cargado.`);
                        }
                    }
                }
            }
        },
        deleteImage(index) {
            this.$swal.fire({
                title: "Eliminar fotografia",
                text: "¿Estas seguro?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: 'Si, eliminar.',
                confirmButtonColor: "#DC2626",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (this.images[index].id != '') {
                        this.images[index].deleted = true
                    } else {
                        this.images.splice(index, 1)
                    }
                }
            })
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
            if (files.length > 2) {
                this.showToast(toast.warning, "No puedes cargar más de dos fotografias a la vez.");
            } else {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.split("/")[0] !== "image") {
                        this.showToast(toast.warning, "Solo puedes subir archivos tipo imagen.");
                    } else if (file.size > 2 * 1024 * 1024) {
                        this.showToast(toast.warning, `El archivo ${file.name} pesa mas de 2 Mb.`);
                    } else {
                        if (!this.images.some((e) => e.name === file.name)) {
                            this.images.push({ id: '', name: file.name, url: URL.createObjectURL(file), file: file, deleted: false });
                            this.focusLastImage();
                        } else {
                            this.showToast(toast.warning, `El archivo ${file.name} ya fue cargado.`);
                        }
                    }
                }
            }
        },
    },
    watch: {
        showModalFlag: function (value, oldValue) {
            if (value) {
                this.images = []
                this.selectedImageIndex = -1
                this.employee = JSON.parse(JSON.stringify(this.modalData));
                if (this.employee.persona.fotos.length > 0) {
                    const activeFiles = this.employee.persona.fotos.filter(foto => foto.estado_foto !== 0);
                    this.images = activeFiles.map(({ id_foto, url_foto }) => {
                        const fileName = url_foto.split('/').pop();
                        return { id: id_foto, name: fileName, url: url_foto, deleted: false };
                    });
                }
            }
        },
    },
    computed: {
        activeImages: function () {
            return this.images.filter(photo => !photo.deleted)
        }
    },
};
</script>

<style scoped>
.card {
    box-shadow: 0 0 5px #5f5454;
    border-radius: 5px;
}

.card .drag-area .visible {
    font-size: 18px;
}

.card input,
.card .drag-area .on-drop,
.card .drag-area.dragover .visible {
    display: none;
}

/* Customize the loader's appearance using Tailwind CSS classes or your custom styles */
.loader {
    border-top-color: #3498db;
    border-left-color: #3498db;
}
</style>