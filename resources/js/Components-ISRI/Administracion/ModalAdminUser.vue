<script setup>
import ModalBasicVue from "@/Components-ISRI/AllModal/ModalBasic.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import moment from 'moment';
</script>

<template>
    <div class="m-1.5">
        <ModalBasicVue :modalOpen="show" @close-modal="$emit('cerrar-modal')" 
        :title="modalData == '' ? 'Creación de usuario.' : 'Administracion de usuario.'"
            maxWidth="4xl">
            <div class="px-2 py-4">

                <div class="flex flex-col md:flex-row ">
                    <div class="w-full md:w-[40%] ">
                        <div class="flex flex-col">
                            <div v-if="modalData == ''" class="flex flex-col items-center">
                                <input id="dui" type="text" class="p-1 mb-2 w-[75%] rounded-lg"
                                    placeholder="Ingresa DUI">
                                <div class="flex justify-center">
                                    <button class="ml-1.5 btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                                        @click="$emit('cerrar-modal')">Buscar</button>
                                </div>
                            </div>

                            <div class="flex justify-center mt-2 mb-4 mx-2">
                                <div
                                    class="w-full sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm sm:mx-auto md:mx-auto lg:mx-auto xl:mx-auto bg-gray-200 shadow-xl rounded-lg text-gray-900">
                                    <div class="rounded-t-lg h-24 overflow-hidden bg-[#3c4557]"></div>
                                    <div class="mx-auto w-32 h-32 relative -mt-16 rounded-full overflow-hidden bg-gray-200">
                                        <img class="object-cover object-center h-32" src='../../../img/default-avatar2.jpg'
                                            alt='Default Profile Photo'>
                                    </div>
                                    <div v-if="user_modal.id != ''" class="text-center mt-2">
                                        <h2 class="font-semibold">{{ user_modal.name }}</h2>
                                        <p class="text-gray-700">{{ formatBirthDate }}</p>
                                        <p class="text-gray-700">{{ user_modal.address }}</p>
                                        <p class="text-gray-700">{{ user_modal.phone_number }}</p>
                                    </div>
                                    <div v-else>
                                        <p class="text-center p-2 font-light">Sin registrar</p>
                                    </div>
                                    <div class="h-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-[60%]">
                        <div>
                            <span class="font-semibold text-slate-800 mb-4 text-lg">Selecciona un sistema y un rol:</span>
                        </div>
                        <!-- <div class="mb-2">Selecciona un sistema y un rol:</div> -->
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Sistema <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect v-bind:disabled="!new_item" v-model="row.id_sistema"
                                        :options="new_item ? sistemas : new_option" @select="getRolesxSistema($event)"
                                        placeholder="Sistema" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Rol <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect v-bind:disabled="selected" v-model="row.id_rol" :options="roles"
                                        placeholder="Rol" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center mb-2 mt-1">
                            <div class="flex items-center">
                                <GeneralButton class="mr-1 py-1" color="bg-red-600 hover:bg-red-700" icon="delete"
                                    :text="new_item ? 'Limpiar' : 'Cancelar'" @click="new_item ? clean() : cancel()"
                                    :disabled="is_loadig_roles" />
                                <GeneralButton class="ml-1 py-1" color="bg-blue-600 hover:bg-blue-700" icon="add"
                                    :text="new_item ? 'Agregar' : 'Actualizar'" @click="new_item ? saveRol() : updateRol()"
                                    :disabled="is_loadig_roles" />
                            </div>
                        </div>
                        <div class="tabla-modal">
                            <table class="w-full" id="tabla_modal_validacion_arranque">
                                <thead class="bg-[#1F3558] text-white">
                                    <tr class="w-full">
                                        <th class="rounded-tl-lg w-[5%]">#</th>
                                        <th class="w-[35%]">SISTEM</th>
                                        <th class="w-[40%]">ROL</th>
                                        <th class="rounded-tr-lg w-[15%]">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-slate-200">
                                    <template v-for="(rol, index) in user_modal.roles" :key="index">
                                        <tr v-if="rol.pivot.estado_permiso_usuario == 1"
                                            :class="rol.selected ? 'bg-orange-300 hover:bg-orange-400' : 'hover:bg-[#141414]/10'">
                                            <td class="text-center">{{ rol.id_rol }}</td>
                                            <td class="text-center">{{ rol.sistema.nombre_sistema }}</td>
                                            <td class="text-center">{{ rol.nombre_rol }}</td>
                                            <td class="text-center">
                                                <div class="space-x-1">
                                                    <button @click="editRol(rol, index)"
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
                                    <!-- you'll have to validate this if -->
                                    <tr v-if="user_modal.roles == ''">
                                        <td colspan="4" class="text-center p-2 font-light">
                                            {{ modalData == '' ? 'Sin roles asignados' : 'Cargando...' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </ModalBasicVue>
    </div>
</template>

<script>
export default {
    props: {
        modalData: {
            type: Array,
            default: [],
        },
        show: {
            type: Boolean,
            default: false,
        },
    },
    created() { },
    data: function (data) {
        return {
            new_item: true,
            user_modal: {
                id: '',
                name: '',
                birth_date: '',
                address: '',
                phone_number: ''
            },
            row: {
                id_sistema: '',
                id_rol: ''
            },
            roles: [],
            sistemas: [],
            new_option: [],
            id_permiso_usuario: '',
            is_loadig_roles: false,
        };
    },
    methods: {
        getRoles(id) {
            this.sistemas = []
            this.roles = []
            this.row.id_rol = ''
            this.row.id_sistema = ''
            this.new_item = true
            axios.post("/systems", { id_usuario: id })
                .then((response) => {
                    this.user_modal.roles = response.data.userRoles
                    this.sistemas = response.data.sistemas
                    for (let i = 0; i < this.user_modal.roles.length; i++) {
                        this.user_modal.roles[i].selected = false;
                    }
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
        editRol(rol, index) {
            this.row.id_sistema = rol.sistema.id_sistema
            this.row.id_rol = rol.id_rol
            this.new_item = false
            this.new_option = []
            var array = { value: rol.sistema.id_sistema, label: rol.sistema.nombre_sistema, selected: true }
            this.new_option.push(array)

            const itemToClean = this.user_modal.roles.find(item => item.selected);
            if (itemToClean) {
                itemToClean.selected = false;
            }
            this.id_permiso_usuario = this.user_modal.roles[index].pivot.id_permiso_usuario
            this.user_modal.roles[index].selected = true
            this.getRolesxSistema(rol.sistema.id_sistema)
        },
        async getRolesxSistema(id_sistema) {
            try {
                this.is_loadig_roles = true;  // Activar el estado de carga
                const response = await axios.post("/roles-per-system", { id_sistema: id_sistema });
                this.roles = response.data.roles;
            } catch (errors) {
                let msg = this.manageError(errors);
                this.$swal.fire({
                    title: 'Operación cancelada',
                    text: msg,
                    icon: 'warning',
                    timer: 5000
                });
            } finally {
                this.is_loadig_roles = false;  // Desactivar el estado de carga
            }
        },
        updateRol() {
            if (this.modalData != '') {
                this.$swal.fire({
                    title: 'Esta seguro de actualizar el rol',
                    icon: 'question',
                    confirmButtonText: 'Si, Actualizar',
                    confirmButtonColor: '#15803D',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post("/update-rol", {
                            id_rol: this.row.id_rol,
                            permiso_usuario: this.id_permiso_usuario
                        }).then((response) => {
                            toast.success("Rol editado con exito", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            //this.changeStateFromModal2()
                            this.id_permiso_usuario = ""
                            this.getRoles(this.modalData.id_usuario)
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
            }
        },
        saveRol() {
            if (this.row.id_rol != "" && this.row.id_sistema != "") {
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
                            id_rol: this.row.id_rol,
                            id_usuario: this.modalData.id_usuario
                        }).then((response) => {
                            toast.success("Rol agregado con exito", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            //this.cleanModalInputs()
                            this.getRoles(this.modalData.id_usuario)
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
                        //this.cleanModalInputs()
                        this.getRoles(this.modalData.id_usuario)
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
        clean() {
            this.row.id_rol = ''
            this.row.id_sistema = ''
            this.roles = []
        },
        cancel() {
            const itemToClean = this.user_modal.roles.find(item => item.selected);
            if (itemToClean) {
                itemToClean.selected = false;
            }
            this.new_item = true
            this.row.id_rol = ''
            this.row.id_sistema = ''
            this.getRoles(this.modalData.id_usuario)
        }
    },
    watch: {
        show: function (value, oldValue) {
            if (value) {
                console.log(this.modalData);
                this.sistemas= []
                this.roles = []
                this.new_option = []
                this.user_modal.id = this.modalData.id_usuario ?? ''
                this.user_modal.name = this.modalData.pnombre_persona + ' ' + this.modalData.snombre_persona + ' ' + this.modalData.papellido_persona
                this.user_modal.birth_date = this.modalData.fecha_nac_persona ?? ''
                this.user_modal.address = this.modalData.nombre_municipio ?? ''
                this.user_modal.phone_number = this.modalData.telefono_persona ?? ''
                this.user_modal.roles = []
                if (this.modalData != '') {
                    this.getRoles(this.modalData.id_usuario)
                }
            }
        },
    },
    computed: {
        formatBirthDate() {
            return moment(this.user_modal.birth_date).format('DD/MM/YYYY');
        },
    }
};
</script>

<style></style>