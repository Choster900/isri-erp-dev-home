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
            :maxWidth="found_user ? '4xl' : 'xl'">
            <div class="px-2 py-4">

                <div class="flex" :class="!found_user ? ' justify-center' : 'flex-col md:flex-row'">
                    <div :class="found_user ? 'md:w-[40%]' : 'md:w-[75%]'">
                        <div class="flex flex-col">
                            <div v-if="modalData == '' && !found_user" class="flex flex-col items-center">
                                <input id="dui" type="text" class="p-1 mb-2 w-[75%] rounded-lg" placeholder="Ingresa DUI"
                                    v-model="user_modal.dui" @keyup.enter="searchDui()" @input="typeDUI()">
                                <div class="flex justify-center">
                                    <button
                                        class="ml-1.5 btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                                        @click="searchDui()">Buscar</button>
                                </div>
                            </div>
                            <div v-if="found_user && modalData == ''">
                                <div class="flex justify-center mb-2 ml-2">
                                    <div class="mb-4 md:mr-2 md:mb-0 w-[90%]">
                                        <TextInput v-model="user_modal.password" id="personal-information"
                                            placeholder="Digite contraseña" :type="show_password ? 'text' : 'password'">

                                            <div class="cursor-pointer no-select absolute inset-y-0 right-0 flex items-center px-4 focus:outline-none"
                                                @click="toggleShow">
                                                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                                    v-if="show_password" class="mr-1" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <g id="style=doutone">
                                                            <g id="eye-close">
                                                                <path id="vector (Stroke)" fill-rule="evenodd"
                                                                    clip-rule="evenodd"
                                                                    d="M2.2827 9.27342C4.69287 5.94267 8.19606 3.96997 12 3.96997C13.3515 3.96997 14.6695 4.21968 15.9154 4.69366C16.3026 4.84093 16.497 5.27417 16.3497 5.66131C16.2025 6.04846 15.7692 6.24292 15.3821 6.09564C14.3024 5.68491 13.1649 5.46997 12 5.46997C8.74406 5.46997 5.66741 7.15436 3.49759 10.1532L3.49687 10.1542C3.15894 10.6197 2.96497 11.2866 2.96497 11.995C2.96497 12.7033 3.15894 13.3703 3.49687 13.8357L3.49759 13.8367C3.92863 14.4325 4.3957 14.9764 4.89269 15.4654C5.18793 15.7559 5.19175 16.2308 4.90122 16.526C4.61069 16.8213 4.13584 16.8251 3.8406 16.5346C3.28219 15.9851 2.76085 15.3774 2.28234 14.716C1.72077 13.942 1.46497 12.9478 1.46497 11.995C1.46497 11.0419 1.72088 10.0475 2.2827 9.27342ZM18.7729 7.18838C19.0534 6.88361 19.5279 6.86393 19.8327 7.14444C20.5154 7.77278 21.1469 8.48525 21.7176 9.27392C22.2792 10.0479 22.535 11.0421 22.535 11.995C22.535 12.948 22.279 13.9424 21.7172 14.7165C19.3071 18.0473 15.8039 20.02 12 20.02C10.5114 20.02 9.06377 19.717 7.70845 19.1455C7.32679 18.9846 7.14786 18.5447 7.30881 18.163C7.46976 17.7813 7.90964 17.6024 8.2913 17.7634C9.46746 18.2594 10.7172 18.52 12 18.52C15.2559 18.52 18.3325 16.8356 20.5023 13.8367L20.5031 13.8357C20.841 13.3703 21.035 12.7033 21.035 11.995C21.035 11.2866 20.841 10.6197 20.5031 10.1542L20.5023 10.1532C19.9883 9.44277 19.423 8.80602 18.8169 8.24813C18.5121 7.96762 18.4924 7.49316 18.7729 7.18838Z"
                                                                    fill="#000000"></path>
                                                                <path id="vector (Stroke)_2" fill-rule="evenodd"
                                                                    clip-rule="evenodd"
                                                                    d="M12 9.75C10.755 9.75 9.75 10.755 9.75 12C9.75 12.5484 9.94405 13.0481 10.2674 13.4375C10.532 13.7562 10.4881 14.229 10.1694 14.4936C9.85076 14.7582 9.37792 14.7144 9.11332 14.3957C8.57407 13.7462 8.25 12.9102 8.25 12C8.25 9.92657 9.92657 8.25 12 8.25C13.0215 8.25 13.9486 8.658 14.6243 9.31951C14.9203 9.60929 14.9253 10.0841 14.6355 10.3801C14.3457 10.6761 13.8709 10.6811 13.5749 10.3913C13.1691 9.99399 12.6147 9.75 12 9.75ZM15 11.25C15.4142 11.25 15.75 11.5858 15.75 12C15.75 14.0734 14.0734 15.75 12 15.75C11.5858 15.75 11.25 15.4142 11.25 15C11.25 14.5858 11.5858 14.25 12 14.25C13.245 14.25 14.25 13.245 14.25 12C14.25 11.5858 14.5858 11.25 15 11.25Z"
                                                                    fill="#000000"></path>
                                                                <path id="vector (Stroke)_3" fill-rule="evenodd"
                                                                    clip-rule="evenodd"
                                                                    d="M22.5533 2.19366C22.8329 2.49926 22.8119 2.97366 22.5063 3.25328L2.5063 21.5533C2.2007 21.8329 1.7263 21.8118 1.44668 21.5062C1.16706 21.2006 1.18812 20.7262 1.49371 20.4466L21.4937 2.14663C21.7993 1.86701 22.2737 1.88807 22.5533 2.19366Z"
                                                                    fill="#000000"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <svg width="28px" height="28px" viewBox="-2.4 -2.4 28.80 28.80" fill="none"
                                                    v-else xmlns="http://www.w3.org/2000/svg" stroke="#000000"
                                                    stroke-width="0.00024000000000000003">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.624"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <g id="style=doutone">
                                                            <g id="eye-open">
                                                                <path id="vector (Stroke)" fill-rule="evenodd"
                                                                    clip-rule="evenodd"
                                                                    d="M12 9.75C10.755 9.75 9.75 10.755 9.75 12C9.75 13.245 10.755 14.25 12 14.25C13.245 14.25 14.25 13.245 14.25 12C14.25 10.755 13.245 9.75 12 9.75ZM8.25 12C8.25 9.92657 9.92657 8.25 12 8.25C14.0734 8.25 15.75 9.92657 15.75 12C15.75 14.0734 14.0734 15.75 12 15.75C9.92657 15.75 8.25 14.0734 8.25 12Z"
                                                                    fill="#000000"></path>
                                                                <path id="vector (Stroke)_2" fill-rule="evenodd"
                                                                    clip-rule="evenodd"
                                                                    d="M2.28282 9.27342C4.69299 5.94267 8.19618 3.96997 12.0001 3.96997C15.8042 3.96997 19.3075 5.94286 21.7177 9.27392C22.2793 10.0479 22.5351 11.0421 22.5351 11.995C22.5351 12.948 22.2792 13.9424 21.7174 14.7165C19.3072 18.0473 15.804 20.02 12.0001 20.02C8.19599 20.02 4.69264 18.0471 2.28246 14.716C1.7209 13.942 1.46509 12.9478 1.46509 11.995C1.46509 11.0419 1.721 10.0475 2.28282 9.27342ZM12.0001 5.46997C8.74418 5.46997 5.66753 7.15436 3.49771 10.1532L3.497 10.1542C3.15906 10.6197 2.96509 11.2866 2.96509 11.995C2.96509 12.7033 3.15906 13.3703 3.497 13.8357L3.49771 13.8367C5.66753 16.8356 8.74418 18.52 12.0001 18.52C15.256 18.52 18.3326 16.8356 20.5025 13.8367L20.5032 13.8357C20.8411 13.3703 21.0351 12.7033 21.0351 11.995C21.0351 11.2866 20.8411 10.6197 20.5032 10.1542L20.5025 10.1532C18.3326 7.15436 15.256 5.46997 12.0001 5.46997Z"
                                                                    fill="#000000"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <LabelToInput icon="password" forLabel="personal-information" />
                                        </TextInput>
                                        <InputError class="mt-2" :message="errors.password" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center mt-2 mb-4 mx-2">
                                <div
                                    class="w-full sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm mx-auto bg-gray-200 shadow-xl rounded-lg text-gray-900">
                                    <div class="rounded-t-lg h-24 bg-[#3c4557]"></div>
                                    <div class="mx-auto w-32 h-32 -mt-16 rounded-full overflow-hidden bg-gray-200">
                                        <img class="object-cover object-center h-32" src="../../../img/default-avatar2.jpg"
                                            alt="Default Profile Photo">
                                    </div>
                                    <div v-if="user_modal.person_id !== ''" class="text-center mt-2">
                                        <h2 class="font-semibold">{{ user_modal.name }}</h2>
                                        <p class="text-gray-700">{{ user_modal.nick_usuario }}</p>
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
                    <div v-if="found_user" class="w-full md:w-[60%]">
                        <div>
                            <span class="font-semibold text-slate-800 mb-4 text-lg">Selecciona un sistema y un rol:</span>
                        </div>
                        <!-- <div class="mb-2">Selecciona un sistema y un rol:</div> -->
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:ml-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Sistema <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect v-bind:disabled="!new_item" v-model="row.id_sistema"
                                        :options="new_item ? filteredSystemOptions : new_option"
                                        @select="getRolesxSistema($event)" placeholder="Sistema" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                            </div>
                            <div class="mb-4 md:ml-3 md:mb-0 mr-1 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Rol <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect :disabled="is_loadig_roles" v-model="row.id_rol" :options="roles"
                                        placeholder="Rol" :searchable="true" @select="selectRol($event)" />
                                    <LabelToInput icon="list" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center mb-4">
                            <div class="flex items-center">
                                <!-- <button
                                    class="mr-4 btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                                    @:disabled="is_loadig_roles" @click="new_item ? saveRol() : updateRol()"
                                    :class="new_item ? 'text-green-600' : 'text-orange-600'">
                                    {{ new_item ? 'AGREGAR' : 'ACTUALIZAR' }}
                                </button>

                                <button
                                    class="ml-0 btn-sm border-slate-200 hover:border-slate-300 underline underline-offset-1"
                                    @:disabled="is_loadig_roles" @click="new_item ? clean() : cancel()"
                                    :class="new_item ? 'text-gray-600' : 'text-red-600'">
                                    {{ new_item ? 'LIMPIAR' : 'CANCELAR' }}
                                </button> -->

                                <GeneralButton class="mr-1 py-1" color="bg-blue-500 hover:bg-blue-700"
                                    :icon="new_item ? 'add' : 'update'" :text="new_item ? 'Agregar' : 'Actualizar'"
                                    @click="new_item ? saveRol() : updateRol()" :disabled="is_loadig_roles" />
                                <GeneralButton v-if="itemSelected" class="ml-1 py-1"
                                    color="bg-red-500 hover:bg-red-700" icon="delete"
                                    text="Cancelar" @click="cancel()"
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
                                                <div class="space-x-1 h-7">
                                                    <button v-if="!rol.selected" @click="editRol(rol, index)"
                                                        class="text-slate-400 hover:text-slate-500 rounded-full">
                                                        <span class="sr-only">Edit</span>
                                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                                                            <path
                                                                d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button v-if="!rol.selected"
                                                        @click="deleteRol(rol.id_rol, rol.nombre_rol, rol.sistema.id_sistema)"
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
                        <div class="flex justify-center mt-5">
                            <!-- <GeneralButton class="mr-1" v-if="modalData != '' && !itemSelected && !allDeleted"
                                color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update"
                                @click="updateUser()" /> -->
                            <GeneralButton class="mr-1" v-if="modalData == '' && !itemSelected"
                                color="bg-green-700  hover:bg-green-800" text="Guardar" icon="add" @click="saveNewUser()" />
                            <GeneralButton class="ml-1" text="Cerrar" icon="delete" @click="$emit('cerrar-modal')" />
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
            errors: [],
            show_password: false,
            new_item: true,
            found_user: false,
            user_modal: {
                id: '',
                dui: '',
                name: '',
                person_id: '',
                password: '',
                birth_date: '',
                address: '',
                phone_number: '',
                nick_usuario: ''
            },
            row: {
                id_sistema: '',
                id_rol: '',
                nombre_sistema: '',
                nombre_rol: ''
            },
            roles: [],
            sistemas: [],
            new_option: [],
            id_permiso_usuario: '',
            is_loadig_roles: false,
        };
    },
    methods: {
        toggleShow() {
            this.show_password = !this.show_password;
        },
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
            //Assign the vars depending the previous selection
            this.row.id_sistema = rol.sistema.id_sistema
            this.row.id_rol = rol.id_rol
            this.row.nombre_sistema = rol.sistema.nombre_sistema
            this.row.nombre_rol = rol.nombre_rol
            //This is not a new item, set this var to take control of the confirm button action
            this.new_item = false
            //new_option is a temp array, this is only to show the system name
            this.new_option = []
            var array = { value: rol.sistema.id_sistema, label: rol.sistema.nombre_sistema, selected: true }
            this.new_option.push(array)
            //Clean the selected role
            const itemToClean = this.user_modal.roles.find(item => item.selected);
            if (itemToClean) {
                itemToClean.selected = false;
            }
            //Set the intermediate table id, this is useful to change the state for the permiso_usuario selected
            this.id_permiso_usuario = this.user_modal.roles[index].pivot.id_permiso_usuario
            this.user_modal.roles[index].selected = true
            //Method to update the role options
            this.getRolesxSistema(rol.sistema.id_sistema)
        },
        async getRolesxSistema(id_sistema) {
            try {
                const item = this.sistemas.find((item) => item.value === id_sistema);
                item ? this.row.nombre_sistema = item.label : this.row.nombre_sistema = '';
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
            if (this.row.id_rol != "") {
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
                } else {
                    const item = this.user_modal.roles.find((item) => item.sistema.id_sistema === this.row.id_sistema);
                    if (item) {
                        item.id_rol = this.row.id_rol
                        item.nombre_rol = this.row.nombre_rol
                        item.selected = false
                    }
                    this.row.id_rol = ''
                    this.row.id_sistema = ''
                    this.roles = []
                    this.new_item = true
                }
            } else {
                toast.warning("Debes seleccionar rol", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }

        },
        saveRol() {
            if (this.row.id_rol != "" && this.row.id_sistema != "") {
                if (this.modalData != '') {
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
                    //This code is executed in case of a new user
                    //Define the array to insert into table
                    var array = {
                        id_rol: this.row.id_rol, nombre_rol: this.row.nombre_rol, selected: false,
                        sistema: { nombre_sistema: this.row.nombre_sistema, id_sistema: this.row.id_sistema },
                        pivot: { estado_permiso_usuario: 1 }
                    }
                    this.user_modal.roles.push(array)
                    //Filter the select system options
                    const item = this.sistemas.find((item) => item.value === this.row.id_sistema);
                    item ? item.assigned = true : '';
                    //Clean the v-model for the two selects
                    this.row.id_rol = ''
                    this.row.id_sistema = ''
                    this.roles = []
                }
            } else {
                let msg
                this.filteredSystemOptions.length != 0 ? msg = 'Debes seleccionar sistema y rol.' : msg = 'Este usuario ya posee un rol en cada sistema.'
                toast.warning(msg, {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }
        },
        deleteRol(id_rol, nombre_rol, id_sistema) {
            if (this.modalData != '') {
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
            } else {
                const item = this.sistemas.find((item) => item.value === id_sistema);
                item ? item.assigned = false : '';
                const index = this.user_modal.roles.findIndex(item => item.id_rol === id_rol);
                if (index !== -1) {
                    this.user_modal.roles.splice(index, 1);
                }
            }
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
            this.roles = []
            this.modalData != '' ? this.getRoles(this.modalData.id_usuario) : ''
        },
        //Methods to save a new user
        searchDui() {
            if (this.user_modal.dui == "") {
                this.$swal.fire({
                    title: 'Información incompleta',
                    text: 'Digite numero de DUI',
                    icon: 'warning',
                    timer: 5000
                })
            } else {
                axios.get('/get-dui', {
                    params: {
                        dui: this.user_modal.dui
                    }
                })
                    .then((response) => {
                        let persona = response.data.persona
                        let usuario = response.data.usuario
                        if (persona === "") {
                            this.found_user = false
                            this.user_modal.person_id = ''
                            this.$swal.fire({
                                title: 'Persona no encontrada',
                                text: 'Este DUI no se encuentra en nuestros registros.',
                                icon: 'warning',
                                timer: 5000
                            })
                        } else {
                            if (usuario === "") {
                                this.found_user = true
                                this.user_modal.name = persona.nombre_persona
                                this.user_modal.person_id = persona.id_persona
                                this.user_modal.birth_date = persona.fecha_nac_persona
                                this.user_modal.address = persona.nombre_municipio
                                this.user_modal.phone_number = persona.telefono_persona
                            } else {
                                this.found_user = false
                                this.user_modal.person_id = ''
                                let status;
                                usuario.estado_usuario == 1 ? status = 'Activo' : status = 'Inactivo'
                                this.$swal.fire({
                                    title: 'Persona ya posee usuario.',
                                    html: '<br>Nombre: ' + persona.nombre_persona + '<br>Usuario : ' + usuario.nick_usuario + ' <br> Estado : ' + status,
                                    icon: 'warning',
                                    timer: 5000
                                })
                            }
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
            }
        },
        getSystems() {
            axios.get("/get-selects-create-user")
                .then((response) => {
                    this.sistemas = response.data.systems
                    for (let i = 0; i < this.sistemas.length; i++) {
                        this.sistemas[i].assigned = false;
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
        selectRol(id_rol) {
            const item = this.roles.find((item) => item.value === id_rol);
            item ? this.row.nombre_rol = item.label : this.row.nombre_rol = '';
        },
        typeDUI() {
            var x = this.user_modal.dui.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.user_modal.dui = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
        },
        saveNewUser() {
            if (this.user_modal.password == '') {
                this.errors.password = 'La contraseña es obligatoria.'
                toast.warning(
                    "Tienes algunos errores por favor verifica tus datos.",
                    {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    }
                );
                setTimeout(() => {
                    this.errors.password = ''; // Clear the error message after 5 seconds
                }, 5000); // 5000 milliseconds = 5 seconds
            } else {
                const roles = this.user_modal.roles.length;
                if (roles > 0) {
                    this.$swal.fire({
                        title: '¿Está seguro de guardar el usuario?',
                        icon: 'question',
                        iconHtml: '✅',
                        confirmButtonText: 'Si, Guardar',
                        confirmButtonColor: '#15803D',
                        cancelButtonText: 'Cancelar',
                        showCancelButton: true,
                        showCloseButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post("/save-user", this.user_modal).then((response) => {
                                toast.success(response.data.mensaje, {
                                    autoClose: 3000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                                this.$emit("update-table")
                                this.$emit("cerrar-modal")
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
                                } else {
                                    let msg = this.manageError(errors)
                                    this.$swal.fire({
                                        title: 'Operación cancelada',
                                        text: msg,
                                        icon: 'warning',
                                        timer: 5000
                                    })
                                }
                            })
                        }
                    })
                } else {
                    toast.warning(
                        "Debes agregar al menos un rol al usuario.",
                        {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        }
                    );
                }
            }
        },
        updateUser() {
            if (!this.allDeleted) {
                this.$swal.fire({
                    title: '¿Está seguro de actualizar el usuario?',
                    icon: 'question',
                    iconHtml: '✅',
                    confirmButtonText: 'Si, Guardar',
                    confirmButtonColor: '#15803D',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        toast.success("Usuario actualizado con éxito.", {
                            autoClose: 3000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                        this.$emit("update-table")
                        this.$emit("cerrar-modal")
                    }
                })
            } else {
                toast.warning(
                    "Debes agregar al menos un rol al usuario.",
                    {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    }
                );
            }
        }
    },
    watch: {
        show: function (value, oldValue) {
            if (value) {
                this.errors = []
                this.show_password = false
                this.found_user = false
                this.sistemas = []
                this.roles = []
                this.new_option = []
                this.user_modal.id = this.modalData.id_usuario ?? ''
                this.user_modal.dui = ''
                this.user_modal.person_id = this.modalData.id_persona ?? ''
                this.user_modal.name = this.modalData.pnombre_persona + ' '
                    + this.modalData.snombre_persona + ' '
                    + (this.modalData.tnombre_persona ?? ' ')
                    + this.modalData.papellido_persona + ' '
                    + (this.modalData.sapellido_persona ?? ' ')
                    + (this.modalData.tapellido_persona ?? '')
                this.user_modal.birth_date = this.modalData.fecha_nac_persona ?? ''
                this.user_modal.address = this.modalData.nombre_municipio ?? ''
                this.user_modal.phone_number = this.modalData.telefono_persona ?? ''
                this.user_modal.nick_usuario = this.modalData.nick_usuario ?? ''
                this.user_modal.roles = []
                if (this.modalData != '') {
                    this.found_user = true
                    this.getRoles(this.modalData.id_usuario)
                } else {
                    this.getSystems()
                }
            }
        },
    },
    computed: {
        formatBirthDate() {
            return moment(this.user_modal.birth_date).format('DD/MM/YYYY');
        },
        filteredSystemOptions() {
            return this.sistemas.filter(item => !item.assigned);
        },
        itemSelected() {
            const item = this.user_modal.roles.find(item => item.selected);
            if (item) {
                return true;
            } else {
                return false;
            }
        },
        allDeleted() {
            const all_deleted = this.user_modal.roles.every(item => item.pivot.estado_permiso_usuario == 0);
            if (all_deleted) {
                return true
            } else {
                return false
            }
        }
    }
};
</script>

<style></style>