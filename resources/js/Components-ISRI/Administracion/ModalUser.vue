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
        <ModalBasicVue :modalOpen="showAdminUser" @close-modal="$emit('cerrar-modal')"
            :title="userId == '' ? 'Creación de usuario.' : 'Administracion de usuario.'"
            :maxWidth="foundUser ? '4xl' : 'xl'">
            <div class="px-2 py-4">

                <div v-if="isLoading && foundUser" class="flex items-center justify-center h-[100px]">
                    <div role="status" class="flex items-center">
                        <svg aria-hidden="true"
                            class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
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

                <div v-else class="flex" :class="!foundUser ? ' justify-center' : 'flex-col md:flex-row'">
                    <div :class="foundUser ? 'md:w-[40%] border-r border-gray-600 pr-2' : 'md:w-[75%]'">
                        <div class="flex flex-col">
                            <div v-if="userId == '' && !foundUser" class="flex flex-col items-center">
                                <input id="dui" type="text" class="p-1 mb-2 w-[75%] rounded-lg" placeholder="Ingresa DUI"
                                    v-model="dui" @keyup.enter="searchDui()" @input="typeDUI()">
                                <div class="flex justify-center">
                                    <button
                                        class="ml-1.5 btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                                        @click="searchDui()">Buscar</button>
                                </div>
                            </div>

                            <div class="flex justify-center mb-4 mx-2">
                                <div
                                    class="w-full sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm mx-auto bg-gray-200 shadow-xl rounded-lg text-gray-900">
                                    <div class="rounded-t-lg h-24 bg-[#3c4557]"></div>
                                    <div v-if="urlLastActivePhoto === false"
                                        class="mx-auto w-32 h-32 -mt-16 rounded-full overflow-hidden bg-gray-200">
                                        <img class="object-cover object-center h-32" src="../../../img/default-avatar2.jpg"
                                            alt="Default Profile Photo">
                                    </div>
                                    <div v-else class="mx-auto w-32 h-32 -mt-16 rounded-full overflow-hidden bg-gray-200">
                                        <img class="object-contain h-32 w-32" :src="urlLastActivePhoto"
                                            alt="Default Profile Photo">
                                    </div>
                                    <div v-if="allUserInfo.id_persona" class="text-center mt-2">
                                        <h2 class="font-semibold">
                                            {{ allUserInfo.pnombre_persona }}
                                            {{ allUserInfo.snombre_persona }}
                                            {{ allUserInfo.tnombre_persona }}
                                            {{ allUserInfo.papellido_persona }}
                                            {{ allUserInfo.sapellido_persona }}
                                            {{ allUserInfo.tapellido_persona }}
                                        </h2>
                                        <p class="text-gray-700">{{
                                            moment(allUserInfo.fecha_nac_persona).format('DD/MM/YYYY') }}</p>
                                        <p class="text-gray-700">
                                            {{ allUserInfo.residencias.length > 0 ?
                                                allUserInfo.residencias[0].municipio.nombre_municipio : '' }}</p>
                                        <p class="text-gray-700">{{ allUserInfo.telefono_persona }}</p>
                                    </div>
                                    <div v-else>
                                        <p class="text-center p-2 font-light">Sin registrar</p>
                                    </div>
                                    <div class="h-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="foundUser" class="md:w-[60%]">
                        <div class="mx-auto w-[95%] flex mb-4">
                            <div @click="changeSelection(1)"
                                class="cursor-pointer bg-gray-300 rounded-l-lg w-1/3 py-1.5 hover:text-blue-700 hover:bg-gray-400 text-[13px] text-center border-r border-gray-400"
                                :class="hoverSelected === 1 ? 'text-white bg-[#3c4557]' : ''">INFORMACION</div>
                            <div @click="changeSelection(2)"
                                class="cursor-pointer bg-gray-300 w-1/3 py-1.5 text-[13px] hover:text-blue-700 hover:bg-gray-400 text-center border-r border-gray-400"
                                :class="hoverSelected === 2 ? 'text-white bg-[#3c4557]' : ''">ROLES</div>
                            <div @click="changeSelection(3)"
                                class="cursor-pointer bg-gray-300 rounded-r-lg w-1/3 hover:text-blue-700 hover:bg-gray-400 py-1.5 text-[13px] text-center"
                                :class="hoverSelected === 3 ? 'text-white bg-[#3c4557]' : ''">{{ edit ? 'MODIFICAR' :
                                    'AGREGAR' }}</div>
                        </div>
                        <div v-if="hoverSelected === 1" id="information">
                            <div class="flex border border-gray-400 rounded-lg mt-1 mx-auto w-[80%] mb-4">
                                <div class="border-r border-gray-400 w-[15%] flex items-center justify-center py-2">
                                    <svg class="text-green-400" fill="currentColor" width="40px" height="40px"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <g>
                                                <path
                                                    d="M19.79,16.72,11.06,1.61A1.19,1.19,0,0,0,9,1.61L.2,16.81C-.27,17.64.12,19,1.05,19H19C19.92,19,20.26,17.55,19.79,16.72ZM11,17H9V15h2Zm0-4H9L8.76,5h2.45Z">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <div class="w-[85%] rounded-r-lg text-[13px]">
                                    <h2 class="text-green-500 text-center font-semibold">¡Aviso!</h2>
                                    <p class="mx-2 mb-1 font-semibold">El nombre de usuario es designado automaticamente por
                                        el sistema.</p>
                                </div>
                            </div>
                            <div class="mb-2 mt-2 md:flex flex-row ml-3 justify-start">
                                <div class="">
                                    <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                        Informacion del usuario
                                    </span>
                                </div>
                            </div>
                            <div v-if="!allUserInfo.usuario" class="flex justify-start mb-2 ml-2">
                                <div class="mb-4 md:ml-1.5 md:mr-2 md:mb-0 w-[50%]">
                                    <TextInput v-model="password" id="personal-information" placeholder="Digite contraseña"
                                        :type="showPassword ? 'text' : 'password'">
                                        <div class="cursor-pointer no-select absolute inset-y-0 right-0 flex items-center px-4 focus:outline-none"
                                            @click="toggleShow">
                                            <svg v-if="showPassword" class="w-6 h-6" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.28 9.27C4.69 5.94 8.2 3.97 12 3.97C13.35 3.97 14.67 4.22 15.92 4.69C16.3 4.84 16.5 5.27 16.35 5.66C16.2 6.05 15.77 6.24 15.38 6.1C14.3 5.68 13.16 5.47 12 5.47C8.74 5.47 5.67 7.15 3.5 10.15L3.5 10.15C3.16 10.62 2.97 11.29 2.97 12C2.97 12.7 3.16 13.37 3.5 13.84L3.5 13.84C3.93 14.43 4.4 14.98 4.89 15.47C5.19 15.76 5.19 16.23 4.9 16.53C4.61 16.82 4.14 16.82 3.84 16.53C3.28 15.99 2.76 15.38 2.28 14.72C1.72 13.94 1.46 12.95 1.46 12C1.46 11.04 1.72 10.05 2.28 9.27ZM18.77 7.19C19.05 6.88 19.53 6.86 19.83 7.14C20.52 7.77 21.15 8.49 21.72 9.27C22.28 10.05 22.53 11.04 22.53 12C22.53 12.95 22.28 13.94 21.72 14.72C19.31 18.05 15.8 20.02 12 20.02C10.51 20.02 9.06 19.72 7.71 19.15C7.33 18.98 7.15 18.54 7.31 18.16C7.47 17.78 7.91 17.6 8.29 17.76C9.47 18.26 10.72 18.52 12 18.52C15.26 18.52 18.33 16.84 20.5 13.84L20.5 13.84C20.84 13.37 21.03 12.7 21.03 12C21.03 11.29 20.84 10.62 20.5 10.15L20.5 10.15C19.99 9.44 19.42 8.81 18.82 8.25C18.51 7.97 18.49 7.49 18.77 7.19Z"
                                                    fill="#000000"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12 9.75C10.75 9.75 9.75 10.75 9.75 12C9.75 12.55 9.94 13.05 10.27 13.44C10.53 13.76 10.49 14.23 10.17 14.49C9.85 14.76 9.38 14.71 9.11 14.4C8.57 13.75 8.25 12.91 8.25 12C8.25 9.93 9.93 8.25 12 8.25C13.02 8.25 13.95 8.66 14.62 9.32C14.92 9.61 14.93 10.08 14.64 10.38C14.35 10.68 13.87 10.68 13.57 10.39C13.17 10 12.61 9.75 12 9.75ZM15 11.25C15.41 11.25 15.75 11.59 15.75 12C15.75 14.07 14.07 15.75 12 15.75C11.59 15.75 11.25 15.41 11.25 15C11.25 14.59 11.59 14.25 12 14.25C13.25 14.25 14.25 13.25 14.25 12C14.25 11.59 14.59 11.25 15 11.25Z"
                                                    fill="#000000"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M22.55 2.19C22.83 2.5 22.81 2.97 22.51 3.25L2.51 21.55C2.2 21.83 1.73 21.81 1.45 21.51C1.17 21.2 1.19 20.73 1.49 20.45L21.49 2.15C21.8 1.87 22.27 1.89 22.55 2.19Z"
                                                    fill="#000000"></path>
                                            </svg>
                                            <svg v-else class="w-7 h-7" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                stroke="#000000" stroke-width="0.00024000000000000003">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12 9.75C10.755 9.75 9.75 10.755 9.75 12C9.75 13.245 10.755 14.25 12 14.25C13.245 14.25 14.25 13.245 14.25 12C14.25 10.755 13.245 9.75 12 9.75ZM8.25 12C8.25 9.92657 9.92657 8.25 12 8.25C14.0734 8.25 15.75 9.92657 15.75 12C15.75 14.0734 14.0734 15.75 12 15.75C9.92657 15.75 8.25 14.0734 8.25 12Z"
                                                    fill="#000000"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.28282 9.27342C4.69299 5.94267 8.19618 3.96997 12.0001 3.96997C15.8042 3.96997 19.3075 5.94286 21.7177 9.27392C22.2793 10.0479 22.5351 11.0421 22.5351 11.995C22.5351 12.948 22.2792 13.9424 21.7174 14.7165C19.3072 18.0473 15.804 20.02 12.0001 20.02C8.19599 20.02 4.69264 18.0471 2.28246 14.716C1.7209 13.942 1.46509 12.9478 1.46509 11.995C1.46509 11.0419 1.721 10.0475 2.28282 9.27342ZM12.0001 5.46997C8.74418 5.46997 5.66753 7.15436 3.49771 10.1532L3.497 10.1542C3.15906 10.6197 2.96509 11.2866 2.96509 11.995C2.96509 12.7033 3.15906 13.3703 3.497 13.8357L3.49771 13.8367C5.66753 16.8356 8.74418 18.52 12.0001 18.52C15.256 18.52 18.3326 16.8356 20.5025 13.8367L20.5032 13.8357C20.8411 13.3703 21.0351 12.7033 21.0351 11.995C21.0351 11.2866 20.8411 10.6197 20.5032 10.1542L20.5025 10.1532C18.3326 7.15436 15.256 5.46997 12.0001 5.46997Z"
                                                    fill="#000000"></path>
                                            </svg>
                                        </div>
                                        <LabelToInput icon="password" forLabel="personal-information" />
                                    </TextInput>
                                    <InputError class="mt-2" :message="errors.password" />
                                </div>
                            </div>
                            <div v-else
                                class="mx-auto max-w-[95%] rounded-lg leading-none px-4 py-4 border hover:bg-blue-100 border-gray-400 mt-2 shadow-lg hover:shadow-xl">
                                <div class="flex flex-col space-y-1 md:flex-row">
                                    <div class="w-full md:w-[60%]">
                                        <p class="text-sm text-gray-600">Usuario</p>
                                        <p class="text-base font-medium text-navy-700 ">
                                            {{ allUserInfo.usuario.nick_usuario }}
                                        </p>
                                    </div>
                                    <div class="w-full md:w-[40%]">
                                        <p class="text-sm text-gray-600">Fecha creación</p>
                                        <p class="text-base font-medium text-navy-700 ">
                                            {{ moment(allUserInfo.usuario.fecha_reg_usuario).format('DD/MM/YYYY') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-1 md:flex-row">
                                    <div class="md:w-[20%] mt-1">
                                        <p class="text-sm text-gray-600">Estado</p>
                                        <p class="text-base font-medium"
                                            :class="allUserInfo.usuario.estado_usuario === 1 ? 'text-green-700' : 'text-red-700'">
                                            {{ allUserInfo.usuario.estado_usuario === 1 ? 'Activo' : 'Inactivo' }}
                                        </p>
                                    </div>
                                    <div class="md:w-[40%]">
                                        <p class="text-sm text-gray-600">Ultima modificación</p>
                                        <p class="text-base font-medium text-navy-700 ">
                                            {{ moment(allUserInfo.usuario.fecha_mod_usuario).format('DD/MM/YYYY') ?? 'N/A'
                                            }}
                                        </p>
                                    </div>
                                    <div class="md:w-[40%]">
                                        <p class="text-sm text-gray-600">Usuario ejecuto acción</p>
                                        <p class="text-base font-medium text-navy-700 ">
                                            {{ allUserInfo.usuario.usuario_usuario ?? 'N/A'
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="hoverSelected === 2" id="roles" class="max-w-full">
                            <div class="mb-4 mt-2 md:flex flex-row ml-6 justify-center">
                                <div class="">
                                    <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                        Roles asignados
                                    </span>
                                </div>
                            </div>
                            <table class="w-[95%] mx-auto text-[12px]" id="tabla_modal_validacion_arranque">
                                <thead class="bg-[#1F3558] text-white">
                                    <tr class="w-full">
                                        <th class="rounded-tl-lg w-[5%]">#</th>
                                        <th class="w-[35%]">SISTEM</th>
                                        <th class="w-[40%]">ROL</th>
                                        <th class="rounded-tr-lg w-[15%]">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-slate-200">
                                    <template v-for="(rol, index) in userRolesFilter" :key="index">
                                        <tr class="hover:bg-[#141414]/10">
                                            <td class="text-center">{{ rol.rolId }}</td>
                                            <td class="text-center">{{ showSystemName(rol.systemId) }}</td>
                                            <td class="text-center">{{ showRolName(rol.rolId) }}</td>
                                            <td class="text-center">
                                                <div class="space-x-1 h-7">
                                                    <button @click="editRol(rol, index)"
                                                        class="text-slate-400 hover:text-slate-500 rounded-full">
                                                        <span class="sr-only">Edit</span>
                                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 32 32">
                                                            <path
                                                                d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="deleteRol(rol.systemId, index)"
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
                                </tbody>
                            </table>
                            <div class="flex justify-center mt-5">
                                <GeneralButton class="mr-1" color="bg-green-700  hover:bg-green-800" text="Guardar cambios"
                                    icon="check" @click="saveChanges()" />
                                <GeneralButton class="ml-1" text="Cerrar" icon="delete" @click="$emit('cerrar-modal')" />
                            </div>
                        </div>
                        <div v-if="hoverSelected === 3">
                            <div class="mb-4 mt-2 md:flex flex-row ml-6 justify-center">
                                <div class="">
                                    <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                        Informacion del rol
                                    </span>
                                </div>
                            </div>
                            <div class="mb-4 md:flex flex-row justify-items-start">
                                <div class="mb-4 md:ml-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs font-light text-gray-600">
                                        Sistema <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                        <Multiselect v-bind:disabled="edit" v-model="row.id_sistema" :options="systemFilter"
                                            placeholder="Sistema" :searchable="true" @change="row.id_rol = ''" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <InputError class="mt-2" :message="errors.id_sistema" />
                                </div>
                                <div class="mb-4 md:ml-3 md:mb-0 mr-1 basis-1/2">
                                    <label class="block mb-2 text-xs font-light text-gray-600">
                                        Rol <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                        <Multiselect v-model="row.id_rol" :options="rolesFilter" placeholder="Rol"
                                            :searchable="true" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <InputError class="mt-2" :message="errors.id_rol" />
                                </div>
                            </div>
                            <div class="flex justify-center mb-4">
                                <div class="flex items-center">
                                    <GeneralButton class="mr-1" color="bg-gray-600 hover:bg-gray-700" icon="delete"
                                        text="Cancelar" @click="cancel()" />
                                    <GeneralButton class="ml-1"
                                        :color="edit ? 'bg-orange-700 hover:bg-orange-800' : 'bg-green-700 hover:bg-green-800'"
                                        :icon="!edit ? 'add' : 'update'" :text="!edit ? 'Asignar' : 'Actualizar'"
                                        @click="!edit ? saveRol() : updateRol()" />
                                </div>
                            </div>
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
        userId: {
            type: String,
            default: '',
        },
        showAdminUser: {
            type: Boolean,
            default: false,
        },
    },
    created() {
        this.setInitialInformation()
    },
    data: function (data) {
        return {
            errors: [],
            foundUser: false,
            isLoading: false,
            hoverSelected: 1,
            showPassword: false,
            edit: false,
            dui: '',
            person: [],
            allUserInfo: [],
            systems: [],
            roles: [],
            userRoles: [],
            row: {
                id_rol: '',
                id_sistema: ''
            }
        };
    },
    methods: {
        validateInputs() {
            if (this.row.id_rol && this.row.id_sistema) {
                this.errors = []
                return true
            } else {
                if (!this.row.id_rol) {
                    this.errors.id_rol = 'Debe seleccionar rol.'
                }
                if (!this.row.id_sistema) {
                    this.errors.id_sistema = 'Debe seleccionar sistema.'
                }
                return false
            }
        },
        setRolesValue(roles) {
            roles.forEach(element => {
                let array = {
                    accessId: element.pivot.id_permiso_usuario,
                    rolId: element.id_rol,
                    systemId: element.sistema.id_sistema,
                    deletedVw: element.pivot.estado_permiso_usuario === 0 ? true : false
                }
                this.userRoles.push(array)
            });
        },
        async saveChanges() {
            this.$swal
                .fire({
                    title: '¿Está seguro de guardar los cambios?',
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, guardar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        this.isLoading = true;
                        await axios.post("/save-changes-admin-user", {
                            userId: this.userId,
                            roles: this.userRoles
                        })
                            .then((response) => {
                                this.showToast(toast.success, response.data.message);
                                this.$emit('cerrar-modal')
                            })
                            .catch((errors) => {
                                if (errors.response.status === 422) {
                                    if (errors.response.data.logical_error) {
                                        this.showToast(toast.error, errors.response.data.logical_error);
                                    } else {
                                        this.showToast(toast.error, "Ocurrio un error con tus datos.");
                                    }
                                } else {
                                    this.manageError(errors, this)
                                }
                            })
                            .finally(() => {
                                this.isLoading = false;
                            });
                    }
                });
        },
        noEdit() {
            this.row.id_rol = ''
            this.row.id_sistema = ''
            this.edit = false
        },
        showRolName(rolId) {
            const rolIndex = this.roles.findIndex(rol => rol.value === rolId);
            if (rolIndex != -1) {
                return this.roles[rolIndex].label
            } else {
                return ""
            }
        },
        showSystemName(systemId) {
            const systemIndex = this.systems.findIndex(system => system.value === systemId);
            if (systemIndex != -1) {
                return this.systems[systemIndex].label
            } else {
                return ""
            }
        },
        updateRol() {
            if (this.validateInputs()) {
                this.$swal
                    .fire({
                        title: '¿Está seguro de actualizar el rol temporalmente? La acción se ejecutara al guardar cambios.',
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
                            const rolIndex = this.userRoles.findIndex(rol => rol.systemId === this.row.id_sistema);
                            this.userRoles[rolIndex].rolId = this.row.id_rol
                            this.userRoles[rolIndex].deletedVw = false
                            this.noEdit()
                            this.hoverSelected = 2
                        }
                    });
            } else {
                this.showToast(toast.warning, "Tienes errores, por favor verifica tus datos.")
            }
        },
        saveRol() {
            if (this.validateInputs()) {
                this.$swal
                    .fire({
                        title: '¿Está seguro de asignar el rol temporalmente? La acción se ejecutara al guardar cambios.',
                        icon: 'question',
                        iconHtml: '❓',
                        confirmButtonText: 'Si, Asignar.',
                        confirmButtonColor: '#141368',
                        cancelButtonText: 'Cancelar',
                        showCancelButton: true,
                        showCloseButton: true
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            const index = this.userRoles.findIndex(rol => rol.systemId === this.row.id_sistema)
                            if (index != -1) {
                                this.userRoles[index].rolId = this.row.id_rol
                                this.userRoles[index].deletedVw = false
                                this.desactiveSystemOption(this.row.id_sistema)
                                this.noEdit()
                                this.hoverSelected = 2
                            } else {
                                let array = {
                                    accessId: '',
                                    rolId: this.row.id_rol,
                                    systemId: this.row.id_sistema,
                                    deletedVw: false
                                }
                                this.userRoles.push(array)
                                this.desactiveSystemOption(this.row.id_sistema)
                                this.noEdit()
                                this.hoverSelected = 2
                            }
                        }
                    });
            } else {
                this.showToast(toast.warning, "Tienes errores, por favor verifica tus datos.")
            }

        },
        desactiveSystemOption(systemId) {
            // Buscar y actualizar el objeto en this.systems
            const systemIndex = this.systems.findIndex(system => system.value === systemId);
            if (systemIndex !== -1) {
                this.systems[systemIndex].deleted = true;
            }
        },
        activeSystemOption(systemId) {
            // Buscar y actualizar el objeto en this.systems
            const systemIndex = this.systems.findIndex(system => system.value === systemId);
            if (systemIndex !== -1) {
                this.systems[systemIndex].deleted = false;
            }
        },
        changeSelection(option) {
            this.hoverSelected = option
            if (option != 3) {
                this.desactiveSystemOption(this.row.id_sistema)
                this.noEdit()
                this.errors = []
            }
        },
        setSystemsOptions() {
            this.systems = this.systems.map(system => {
                const foundRole = this.allUserInfo.usuario.roles.find(role => {
                    return role.id_sistema === system.value;
                });

                if (foundRole && foundRole.pivot.estado_permiso_usuario === 1) {
                    return { ...system, deleted: true };
                } else {
                    return { ...system, deleted: false };
                }
            });
        },
        editRol(rol, index) {
            this.row.id_sistema = rol.systemId
            this.row.id_rol = rol.rolId
            this.hoverSelected = 3
            this.edit = true
            this.activeSystemOption(rol.systemId)
        },
        deleteRol(rolId, index) {
            this.activeSystemOption(rolId)
            this.userRoles[index].deletedVw = true
        },
        cancel() {
            this.hoverSelected = 2
            this.desactiveSystemOption(this.row.id_sistema)
            this.noEdit()
            this.errors = []
        },
        setInitialInformation() {
            this.errors = []
            if (this.userId) {
                this.foundUser = true
                this.showPassword = true
                this.getUserInfo()
            } else {
                this.foundUser = false
                this.getSelects()
            }
        },
        typeDUI() {
            var x = this.dui.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.dui = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
        },
        async getSelects() {
            this.isLoading = true;
            await axios.get("/get-selects-admin-user")
                .then((response) => {
                    this.systems = response.data.systems
                    this.roles = response.data.roles
                    this.setSystemsOptions()
                })
                .catch((errors) => {
                    this.manageError(errors, this)
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        async getUserInfo() {
            this.isLoading = true;
            await axios.get(`/get-user-info/${this.userId}`)
                .then((response) => {
                    this.allUserInfo = response.data.allUserInfo
                    this.systems = response.data.systems
                    this.roles = response.data.roles
                    console.log(this.allUserInfo);
                    this.setSystemsOptions()
                    this.setRolesValue(this.allUserInfo.usuario.roles)
                })
                .catch((errors) => {
                    this.manageError(errors, this)
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        toggleShow() {
            this.showPassword = !this.showPassword;
        },
        //To create a new user
        searchDui() {
            axios.get('/get-dui', {
                params: {
                    dui: this.dui
                }
            })
                .then((response) => {
                    this.person = response.data.persona
                    this.foundUser = true
                })
                .catch((errors) => {
                    if (errors.response.status === 422) {
                        this.errors = errors.response.data.errors;
                        this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                    } else {
                        this.manageError(errors, this)
                    }
                })
        },
    },
    watch: {
    },
    computed: {
        urlLastActivePhoto() {
            if (this.allUserInfo.id_persona && this.foundUser) {
                const filteredPhotos = this.allUserInfo.fotos.filter(foto => foto.estado_foto === 1);
                if (filteredPhotos.length > 0) {
                    return filteredPhotos[filteredPhotos.length - 1].url_foto
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        rolesFilter() {
            if (this.row.id_sistema) {
                return this.roles.filter(role => role.id_sistema === this.row.id_sistema);
            } else {
                return ''
            }
        },
        systemFilter() {
            return this.systems.filter(system => system.deleted != true)
        },
        userRolesFilter() {
            return this.userRoles.filter(rol => rol.deletedVw != true)
        }
    }
};
</script>

<style></style>