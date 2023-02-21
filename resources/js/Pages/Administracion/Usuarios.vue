<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalAdministracionVue from '@/Components-ISRI/Administracion/ModalAdministracion.vue';
</script>
<template>
  <Head title="Administracion" />
  <AppLayoutVue>
    <div class="sm:flex sm:justify-end sm:items-center mb-2">
      <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
        <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add" />
      </div>
    </div>
    <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
      <header class="px-5 py-4 flex">
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
              <Multiselect v-model="tableData.length" @select="getUsers()" :options="perPage" :searchable="true" />
              <LabelToInput icon="date" />
            </div>
          </div>

          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <TextInput id="search-user" type="text" v-model="tableData.search" placeholder="Search Table"
              @input="getUsers()">
              <LabelToInput icon="search" forLabel="search-user" />
            </TextInput>
          </div>
        </div>
        <h2 class="font-semibold text-slate-800 pt-1">All Users <span class="text-slate-400 font-medium">{{
          pagination.total
        }}</span></h2>
      </header>
      <div class="overflow-x-auto">
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
          <tbody class="text-sm divide-y divide-slate-200">
            <tr v-for="user in users" :key="user.id_usuario">
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ user.id_usuario }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">{{ user.nick_usuario }}</div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-medium text-slate-800">
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
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="space-x-1">
                  <button @click="changeStateFromModal(user.id_usuario)"
                    class="text-slate-400 hover:text-slate-500 rounded-full">
                    <span class="sr-only">Edit</span>
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                      <path
                        d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                      </path>
                    </svg>
                  </button>
                  <button class="text-rose-500 hover:text-rose-600 rounded-full">
                    <span class="sr-only">Delete</span><svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
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
          </tbody>
        </datatable>
      </div>
    </div>
    <div class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
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
    <ModalAdministracionVue :showModal="showModal" :modalData="modalData" @cerrar-modal="showModal = !showModal" />
  </AppLayoutVue>
</template>
<script>
export default {
  created() {
    this.getUsers();
  },
  data: function (data) {
    let sortOrders = {};
    let columns = [
      { width: "25%", label: "ID", name: "id_usuario" },
      { width: "25%", label: "User Name", name: "nick_usuario" },
      { width: "25%", label: "Estado", name: "estado_usuario" },
      { width: "25%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      if (column.name === 'id_usuario')
        sortOrders[column.name] = 1;
      else
        sortOrders[column.name] = -1;
    });
    return {
      modalData: {
        userRoles: [],
        id_usuario: "",
        nombre_usuario: "",
        sistemas: "",
        roles: "",
        id_sistema: "",
        id_rol: "",
        sistema_edit: "",
        id_rol_edit: "",
        roles_edit: "",
        id_sistema_edit: "",
        permiso_usuario: ""
      },
      showModal: false,
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
        dir: "asc",
      },
      pagination: {
        lastPage: "",
        currentPage: "",
        total: "",
        lastPageUrl: "",
        nextPageUrl: "",
        prevPageUrl: "",
        from: "",
        to: "",
      },
    };
  },
  methods: {
    changeStateFromModal(identificador = "") {
      this.showModal = !this.showModal;
      this.modalData.id_usuario = identificador;
    },
    async getUsers(url = "/users") {
      this.tableData.draw++;
      await axios.get(url, { params: this.tableData }).then((response) => {
        let data = response.data;
        if (this.tableData.draw == data.draw) {
          this.links = data.data.links;
          this.pagination.total=data.data.total
          this.links[0].label = "Anterior";
          this.links[this.links.length - 1].label = "Siguiente";
          this.users = data.data.data;
        }
      }).catch((errors) => {
        console.log(errors);
      });
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
  },
};
</script>
