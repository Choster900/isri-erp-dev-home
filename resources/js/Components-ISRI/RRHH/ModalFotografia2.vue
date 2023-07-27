<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModalFlag" @close="$emit('cerrar-modal')" :modal-title="'Administracion plazas. '" 
        maxWidth="3xl" :closeOutSide="false">
            <div class="px-5 py-4">
                <div class="card w-[75%]">
                    <div class="top">
                        <p class="text-slate-800">Carga de imagen</p>
                    </div>
                    <div class="drag-area bg-gray-200 text-slate-800" @dragover.prevent="onDragOver"
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
                    <div class="container w-full flex">
                        <div class="image w-1/3 px-1" v-for="(image, index) in images " :key="index">
                            <span class="delete" @click="deleteImage(index)">&times;</span>
                            <img :src="image.url" @click="toggleFullScreenImage(index)"/>
                        </div>
                        <!-- Full-screen image display -->
                        <div v-if="isFullScreenActive" class="full-screen-container" @click="closeFullScreen">
                            <span class="delete" @click="closeFullScreen">&times;</span>
                            <img :src="getFullScreenImageUrl()"/>
                        </div>
                    </div>
                    <button type="button" class="bg-green-600">Subir</button>
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
    },
    created() { },
    data: function (data) {
        return {
            images: [],
            isDragging: false,
            isFullScreenActive: false,
            fullScreenImageIndex: null,
        };
    },
    methods: {
        toggleFullScreenImage(index) {
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
            this.$refs.fileInput.click();
        },
        onFileSelect(event) {
            const files = event.target.files;
            if (files.length === 0) return;
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.split("/")[0] !== "image") {
                    this.showToast(toast.warning, "Solo puedes subir archivos jpg, jpeg y png.");
                } else if (!this.images.some((e) => e.name === files[i].name)) {
                    this.images.push({ name: files[i].name, url: URL.createObjectURL(files[i]) });
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
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.split("/")[0] !== "image") {
                    this.showToast(toast.warning, "Solo puedes subir archivos jpg, jpeg y png.");
                } else if (!this.images.some((e) => e.name === files[i].name)) {
                    this.images.push({ name: files[i].name, url: URL.createObjectURL(files[i]) });
                }
            }
        }
    },
    watch: {
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
    box-shadow: 0 0 5px #ffdfdf;
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
    margin-bottom: 8px;
    overflow-x: auto;
    /* Add this line to allow horizontal scrolling */
    max-height: 200px;
    /* Set a maximum height to limit the container's height */
}

.card .container .image {
    height: 100px;
    position: relative;
    margin-bottom: 8px;
    box-sizing: border-box;
}

.card .container .image img {
    width: 100%;
    height: 100%;
    border-radius: 5px;
    object-fit: cover;
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
}</style>