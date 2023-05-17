<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
</script>
<template>
    <ModalVue :show="showModal" @close="$emit('cerrar-modal')"
        v-bind:title="'⚙️ Gestion de roles usuario: ' + modalData.nombre_usuario + '.'"
        @close-modal="$emit('cerrar-modal')">
        <div class="px-5 pt-4 pb-1">
            <div class="text-sm">
                <div class="mb-4">Selecciona un rol y un sistema:</div>
                <div class="mb-4 md:flex flex-row justify-between">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="modalData.id_sistema" @select="getRolesxSistema()"
                                :options="modalData.sistemas" placeholder="Sistema" :searchable="true" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>

                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="modalData.id_rol" :options="modalData.roles" placeholder="Rol"
                                :searchable="true" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>
                </div>
                <div class="mb-4 md:flex flex-row justify-center">
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton @click="saveRol()" color="bg-green-700  hover:bg-green-800" text="Agregar"
                            icon="add" />
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
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

                            <template v-for="rol in modalData.userRoles" :key="rol.id_rol">
                                <tr v-if="rol.pivot.estado_permiso_usuario == 1" class="hover:bg-[#141414]/10">
                                    <td class="text-center">{{ rol.id_rol }}</td>
                                    <td class="text-center">{{ rol.sistema.nombre_sistema }}</td>
                                    <td class="text-center">{{ rol.nombre_rol }}</td>
                                    <td class="text-center">
                                        <div class="space-x-1">
                                            <button
                                                @click="editRol(rol.sistema.id_sistema, rol.sistema.nombre_sistema, rol.id_rol)"
                                                class="text-slate-400 hover:text-slate-500 rounded-full">
                                                <span class="sr-only">Edit</span>
                                                <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                                                    <path
                                                        d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button @click="deleteRol(rol.id_rol, rol.nombre_rol)"
                                                class="text-rose-500 hover:text-rose-600 rounded-full">
                                                <span class="sr-only">Delete</span><svg class="w-6 h-6 fill-current"
                                                    viewBox="0 0 32 32">
                                                    <path d="M13 15h2v6h-2zM17 15h2v6h-2z">
                                                    </path>
                                                    <path
                                                        d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr v-if="modalData.userRoles == ''">
                                <td colspan="4" class="text-center p-2 font-light">Sin resultados en la busqueda</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-xs text-slate-500 p-5"></div>
            </div>
        </div>
    </ModalVue>

    <ModalVue :show="showModal2" @close="changeStateFromModal2" title="Cambio de rol" @close-modal="changeStateFromModal2">
        <div class="px-5 pt-4 pb-1">
            <div class="text-sm">
                <div class="mb-4">Sistema : {{ modalData.sistema_edit }}</div>
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="modalData.id_rol_edit" :options="modalData.roles_edit" placeholder="Rol"
                                :searchable="true" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>
                </div>
                <div class="mb-4 md:flex flex-row justify-center">
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton @click="updateRol()" color="bg-orange-700  hover:bg-orange-800" text="Aceptar"
                            icon="add" />
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton text="Cancelar" icon="add" @click="changeStateFromModal2" />
                    </div>
                </div>
                <div class="text-xs text-slate-500">ISRI2023</div>
            </div>
        </div>
    </ModalVue>
</template>
<script>
export default {
    props: ["showModal", "modalData", "modalVar"],
    watch: {
        modalVar: async function (newParam) {
            if (newParam) {
                await this.getSistemas()
                this.$emit('abrir-modal')
            }
        }
    },
    data: function (data) {
        return {
            showModal2: false,
        }
    },
    methods: {
        cleanModalInputs() {
            this.modalData.id_rol = ""
            this.modalData.id_sistema = ""
            this.modalData.roles = ""
        },
        async getSistemas() {
            await axios.post("/systems",{id_usuario:this.modalData.id_usuario})
                .then((response) => {
                    this.modalData.userRoles = response.data.userRoles
                    this.modalData.sistemas = response.data.sistemas
                    this.modalData.nombre_usuario = response.data.nombre_usuario
                })
                .catch((errors) => {
                    let msg = this.manageError(errors)
                    this.$swal.fire({
                        title: 'Operación cancelada',
                        text: msg,
                        icon: 'warning',
                        timer: 5000
                    })
                })
        },
        async getRolesxSistema() {
            await axios.post("/roles-per-system", {id_sistema: this.modalData.id_sistema} )
                .then((response) => {
                    this.modalData.roles = response.data.roles
                })
                .catch((errors) => {
                    let msg = this.manageError(errors)
                    this.$swal.fire({
                        title: 'Operación cancelada',
                        text: msg,
                        icon: 'warning',
                        timer: 5000
                    })
                })
        },
        changeStateFromModal2() {
            this.showModal2 = !this.showModal2;
        },
        saveRol() {
            if (this.modalData.id_rol != "" && this.modalData.id_sistema != "") {
                this.$swal.fire({
                    title: 'Esta seguro de guardar el rol',
                    icon: 'question',
                    iconHtml: '✅',
                    confirmButtonText: 'Si, Guardar',
                    confirmButtonColor: '#15803D',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post("/save-rol", {
                            id_rol: this.modalData.id_rol,
                            id_usuario: this.modalData.id_usuario
                        }).then((response) => {
                            toast.success("Rol agregado con exito", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            this.cleanModalInputs()
                            this.getSistemas()
                        }).catch((errors) => {
                            let msg = this.manageError(errors)
                            this.$swal.fire({
                                title: 'Operación cancelada',
                                text: msg,
                                icon: 'warning',
                                timer: 5000
                            })
                        })
                    }
                })
            } else {
                toast.warning("Debes seleccionar sistema y rol", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }
        },
        deleteRol(id_rol, nombre_rol) {
            this.$swal.fire({
                title: 'Desactivar Rol ' + nombre_rol,
                text: "Estas seguro?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, desactivar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post("/desactive-rol", {
                        id_rol: id_rol,
                        id_usuario: this.modalData.id_usuario
                    }).then((response) => {
                        toast.success("Rol eliminado con exito", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                        this.cleanModalInputs()
                        this.getSistemas()
                    }).catch((errors) => {
                            let msg = this.manageError(errors)
                            this.$swal.fire({
                                title: 'Operación cancelada',
                                text: msg,
                                icon: 'warning',
                                timer: 5000
                            })
                        })
                }
            })
        },
        editRol(id_sistema, sistema, id_rol) {
            this.modalData.sistema_edit = sistema
            this.modalData.id_sistema_edit = id_sistema
            this.modalData.id_rol_edit = id_rol
            axios.post("/roles-per-system-edit", this.modalData )
                .then((response) => {
                    this.modalData.roles_edit = response.data.roles
                    this.modalData.permiso_usuario = response.data.permiso_usuario
                    this.changeStateFromModal2()
                }).catch((errors) => {
                    let msg = this.manageError(errors)
                    this.$swal.fire({
                        title: 'Operación cancelada',
                        text: msg,
                        icon: 'warning',
                        timer: 5000
                    })
                })
        },
        updateRol() {
            this.$swal.fire({
                title: 'Esta seguro de editar el rol',
                icon: 'question',
                confirmButtonText: 'Si, Guardar',
                confirmButtonColor: '#15803D',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post("/update-rol", {
                        id_rol: this.modalData.id_rol_edit,
                        permiso_usuario: this.modalData.permiso_usuario
                    }).then((response) => {
                        toast.success("Rol editado con exito", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                        this.changeStateFromModal2()
                        this.modalData.permiso_usuario = ""
                        this.getSistemas()
                    }).catch((errors) => {
                        let msg = this.manageError(errors)
                        this.$swal.fire({
                            title: 'Operación cancelada',
                            text: msg,
                            icon: 'warning',
                            timer: 5000
                        })
                    })
                }
            })
        },
    },
}
</script>

<style>

</style>