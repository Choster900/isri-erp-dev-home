<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalAdminUserVue from '@/Components-ISRI/Administracion/ModalAdminUser.vue';
import ModalChangePasswordVue from '@/Components-ISRI/Administracion/ModalChangePassword.vue';
</script>
<template>
    <Head title="Administracion" />
    <AppLayoutVue nameSubModule="Administracion - Usuarios">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton v-if="permits.insertar == 1" @click="createNewUser()"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Usuario" icon="add" class="mr-2" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getUsers()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Total Usuarios <span class="text-slate-400 font-medium">{{
                        tableData.total
                    }}</span></h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getUsers()">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="(user, index) in users" :key="index">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{ user.id_usuario }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">
                                    {{ user.pnombre_persona }}
                                    {{ user.snombre_persona }}
                                    {{ user.tnombre_persona }}
                                    {{ user.papellido_persona }}
                                    {{ user.sapellido_persona }}
                                    {{ user.tapellido_persona }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">{{ user.dui_persona }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">{{ user.nick_usuario }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div v-if="(user.estado_usuario == 1)"
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-500">
                                        Activo
                                    </div>
                                    <div v-else
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-rose-100 text-rose-600">
                                        Inactivo
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permits.actualizar == 1 && user.estado_usuario == 1"
                                            @click="editUser(user)">
                                            <div class="w-8 text-green-900">
                                                <span class="text-xs">
                                                    <span class="text-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Editar</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="changeStateUser(user)" v-if="permits.eliminar == 1">
                                            <div class="w-8 text-red-900"><span class="text-xs">
                                                    <svg :fill="user.estado_usuario == 1 ? '#991B1B' : '#166534'"
                                                        version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                        width="20px" height="20px"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        viewBox="0 0 97.994 97.994" xml:space="preserve"
                                                        :stroke="user.estado_usuario == 1 ? '#991B1B' : '#166534'">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <g>
                                                                <g>
                                                                    <path
                                                                        d="M97.155,9.939c-0.582-0.416-1.341-0.49-1.991-0.193l-10.848,4.935C74.08,5.29,60.815,0.118,46.966,0.118 c-15.632,0-30.602,6.666-41.07,18.289c-0.359,0.399-0.543,0.926-0.51,1.461c0.033,0.536,0.28,1.036,0.686,1.388l11.301,9.801 c0.818,0.711,2.055,0.639,2.787-0.162c6.866-7.512,16.636-11.821,26.806-11.821c6.135,0,12.229,1.584,17.622,4.583l-7.826,3.561 c-0.65,0.296-1.095,0.916-1.163,1.627c-0.069,0.711,0.247,1.405,0.828,1.82l34.329,24.52c0.346,0.246,0.753,0.373,1.163,0.373 c0.281,0,0.563-0.06,0.828-0.181c0.65-0.296,1.095-0.916,1.163-1.627l4.075-41.989C98.053,11.049,97.737,10.355,97.155,9.939z">
                                                                    </path>
                                                                    <path
                                                                        d="M80.619,66.937c-0.819-0.709-2.055-0.639-2.787,0.162c-6.866,7.514-16.638,11.822-26.806,11.822 c-6.135,0-12.229-1.584-17.622-4.583l7.827-3.561c0.65-0.296,1.094-0.916,1.163-1.628c0.069-0.711-0.247-1.404-0.828-1.819 L7.237,42.811c-0.583-0.416-1.341-0.49-1.991-0.193c-0.65,0.296-1.094,0.916-1.163,1.627L0.009,86.233 c-0.069,0.712,0.247,1.406,0.828,1.822c0.583,0.416,1.341,0.488,1.991,0.192l10.848-4.935 c10.237,9.391,23.502,14.562,37.351,14.562c15.632,0,30.602-6.666,41.07-18.289c0.358-0.398,0.543-0.926,0.51-1.461 c-0.033-0.536-0.28-1.036-0.687-1.388L80.619,66.937z">
                                                                    </path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                {{ user.estado_usuario ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permits.actualizar == 1 && user.estado_usuario == 1"
                                            @click="changePasswordUser(user)">
                                            <div class="w-8 text-green-500  rounded-full">
                                                <span class="text-xs">
                                                    <svg fill="#F97316" version="1.1" id="Capa_1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="20px"
                                                        height="20px" viewBox="0 0 569.16 569.16" xml:space="preserve"
                                                        stroke="#F97316">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <g>
                                                                <g>
                                                                    <path
                                                                        d="M513.217,216.366c-18.427,0-31.318-4.568-34.492-12.218c-3.17-7.647,2.702-19.982,15.704-32.999l33.109-33.109 l6.493-6.493l-6.493-6.49l-83.474-83.44l-6.49-6.49l-6.49,6.49l-33.079,33.082c-14.422,14.419-24.076,16.573-28.547,16.573 c-3.295,0-5.915-1.083-8.24-3.415c-3.151-3.161-8.434-11.5-8.391-31.864c0-0.386-0.021-0.768-0.067-1.147V9.18V0h-9.18H225.599 h-9.18v9.18v46.931c-0.024,8.229-1.3,35.166-16.741,35.166c-4.464,0-14.104-2.154-28.519-16.576l-33.103-33.085l-6.49-6.487 l-6.49,6.49l-83.44,83.44l-6.487,6.487l6.484,6.49l33.082,33.112c13.018,13.011,18.896,25.343,15.729,32.996 c-3.17,7.65-16.046,12.222-34.446,12.222H9.18H0v9.18V343.58v9.18h9.18h46.815c18.396,0,31.273,4.568,34.443,12.219 c3.173,7.656-2.705,20.004-15.722,33.025l-33.079,33.08l-6.49,6.49l6.49,6.492l83.44,83.475l6.493,6.496l6.494-6.496 l33.097-33.113c14.407-14.4,24.049-16.551,28.51-16.551c15.45,0,16.726,26.918,16.75,35.168v46.936v9.18h9.18h117.984h9.18v-9.18 v-45.662c0.046-0.377,0.067-0.752,0.067-1.135c-0.042-20.373,5.239-28.713,8.391-31.871c2.329-2.334,4.951-3.42,8.25-3.42 c4.471,0,14.125,2.148,28.544,16.539l33.069,33.104l6.49,6.498l6.493-6.496l83.474-83.471l6.493-6.492l-6.496-6.494 l-33.112-33.082c-12.999-13.023-18.871-25.373-15.698-33.023c3.174-7.648,16.065-12.215,34.489-12.215h46.761h9.18v-9.18V225.546 v-9.18h-9.18H513.217z M413.1,284.58c0,70.867-57.653,128.52-128.52,128.52c-70.867,0-128.52-57.652-128.52-128.52 c0-70.867,57.653-128.52,128.52-128.52C355.446,156.06,413.1,213.713,413.1,284.58z">
                                                                    </path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Contraseña</div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </datatable>
            </div>
            <div v-if="empty_object" class="flex text-center py-2">
                <p class="font-semibold text-red-500 text-[16px]" style="margin: 0 auto; text-align: center;">No se
                    encontraron registros.</p>
            </div>
        </div>
        <div v-if="!empty_object" class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">

                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="(link.label == 'Anterior')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getUsers(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                          text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getUsers(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                          text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getUsers(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalChangePasswordVue :showModalChangePassword="showModalChangePassword" :modalData="modalData"
            @cerrar-modal="showModalChangePassword = !showModalChangePassword"
            @abrir-modal="showModalChangePassword = true" />

        <ModalAdminUserVue :show="show" @cerrar-modal="show = !show" :modalData="modalData"
            @update-table="getUpdateTable()" />
    </AppLayoutVue>
</template>
<script>
export default {
    created() {
        this.getUsers()
        this.getPermissions(this)
        this.getSelectsCreateUser()
    },
    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "ID", name: "id_usuario", type: "text" },
            { width: "35%", label: "Nombre Persona", name: "nombre_persona", type: "text" },
            { width: "15%", label: "Dui", name: "dui_persona", type: "text" },
            { width: "20%", label: "Nombre Usuario", name: "nick_usuario", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_usuario", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_usuario')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            show: false,
            systems: [],

            empty_object: false,
            permits: [],
            modalData: []
            ,
            modalDataCreate: {
                dui: '',
                nombre_persona: '',
                fecha_nacimiento: '',
                id_persona: '',
                nick_usuario: '',
                password: '',
                disable_submit: true,
                id_sistema: '',
                systems: [],
                id_rol: '',
                roles: []
            },
            modalDataChangePassword: {
                password: '',
                nick_usuario: '',
                id_usuario: ''
            },
            showModalCreate: false,
            modalVar: false,
            showModal: false,
            showModalChangePassword: false,
            users: [],
            links: [],
            columns: columns,
            sortKey: "id_usuario",
            sortOrders: sortOrders,
            perPage: ["10", "20", "30"],
            tableData: {
                draw: 0,
                length: 5,
                search: "",
                column: 0,
                dir: "desc",
                total: "",
                currentPage: '',
            }
        };
    },
    methods: {
        createNewUser() {
            this.show = true
            this.modalData = []
        },
        changePasswordUser(user) {
            this.modalData = user
            this.showModalChangePassword = true
        },
        cleanModalInputs() {
            this.modalData.id_rol = ""
            this.modalData.id_sistema = ""
            this.modalData.roles = ""
        },

        getSelectsCreateUser() {
            axios.get("/get-selects-create-user")
                .then((response) => {
                    this.systems = response.data.systems
                })
                .catch((errors) => {
                    this.manageError(errors, this)
                })
        },

        closeVars() {
            this.showModal = false
            this.modalVar = false
            this.cleanModalInputs()
        },
        async createUser() {
            await axios.get("/systems-all")
                .then((response) => {
                    this.modalDataCreate.systems = response.data.sistemas
                    this.showModalCreate = true
                })
                .catch((errors) => {
                    this.manageError(errors, this)
                })
        },
        getUpdateTable() {
            this.getUsers(this.tableData.currentPage);
        },
        changeStateUser(user) {
            let msg
            user.estado_usuario == 1 ? msg = "Desactivar" : msg = "Activar"
            this.$swal.fire({
                title: msg + ' usuario ' + user.nick_usuario,
                text: "Estas seguro?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, ' + msg
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post("/change-state-user", {
                        id_usuario: user.id_usuario,
                        estado_usuario: user.estado_usuario
                    })
                        .then((response) => {
                            this.$swal.fire({
                                text: response.data.mensaje,
                                icon: 'success',
                                timer: 5000
                            })
                            this.getUsers(this.tableData.currentPage);
                        })
                        .catch((errors) => {
                            this.manageError(errors, this)
                        })
                }
            })
        },
        editUser(user) {
            this.show = true;
            this.modalData = user;
        },
        async getUsers(url = "/users") {
            this.tableData.draw++;
            this.tableData.currentPage = url
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.tableData.total = data.data.total
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.users = data.data.data;
                    this.users.length > 0 ? this.empty_object = false : this.empty_object = true
                }
            }).catch((errors) => {
                this.manageError(errors, this)
            })
        },
        sortBy(key) {
            if (key != "Acciones") {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, "name", key);
                this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
                this.getUsers();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        handleData(myEventData) {
            this.tableData.search = myEventData;
            const data = Object.values(myEventData);
            if (data.every(error => error === '')) {
                this.getUsers()
            }
        }
    },
};
</script>
<style>
.td-data-table {
    max-width: 100px;
    white-space: nowrap;
    height: 50px;
}

.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
}</style>
