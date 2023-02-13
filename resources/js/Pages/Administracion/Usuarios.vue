<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
</script>

<template>
  <Head title="Administracion" />

  <AppLayoutVue>
    <div>
      <div>
        <input
          class="input"
          type="text"
          v-model="tableData.search"
          placeholder="Search Table"
          @input="getUsers()"
        />

        <div class="control">
          <div class="select">
            <select v-model="tableData.length" @change="getUsers()">
              <option
                v-for="(records, index) in perPage"
                :key="index"
                :value="records"
              >
                {{ records }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <datatable
        :columns="columns"
        :sortKey="sortKey"
        :sortOrders="sortOrders"
        @sort="sortBy"
      >
        <tbody>
          <tr v-for="user in users" :key="user.id_usuario">
            <td>{{ user.id_usuario }}</td>
            <td>{{ user.nick_usuario }}</td>
            <td>{{ user.estado_usuario }}</td>
            <td>
              <button
                type="button"
                class="btn btn-blue"
                @click="btnEdit(user.id_usuario)"
              >
                Edit
              </button>

              <button
                type="button"
                class="btn btn-red"
                @click="btnDelete(user.id_usuario)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
        <div v-for="link in links" :key="link.label">
          <a @click="getUsers(link.url)">{{ link.label }}</a>
        </div>
        <p>Registros: {{ pagination.total }}</p>
      </datatable>
    </div>
  </AppLayoutVue>
</template>

<script>
export default {
  components: { datatable: Datatable },
  created() {
    this.getUsers();
  },
  data() {
    let sortOrders = {};
    let columns = [
      { width: "25%", label: "ID", name: "id_usuario" },
      { width: "25%", label: "User Name", name: "nick_usuario" },
      { width: "25%", label: "Estado", name: "estado_usuario" },
      { width: "25%", label: "Acciones", name: "Acciones" },
    ];
    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      users: [],
      links: [],
      columns: columns,
      sortKey: "id_usuario",
      sortOrders: sortOrders,
      perPage: ["10", "20", "30"],
      tableData: {
        draw: 0,
        length: 10,
        search: "",
        column: 0,
        dir: "desc",
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
    btnEdit(id) {
      alert(`Edit user #: ${id}`);
    },
    btnDelete(id) {
      alert(`Delete user #: ${id}`);
    },

    getUsers(url = "/users") {
      this.tableData.draw++;
      axios
        .get(url, { params: this.tableData })
        .then((response) => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.links = data.data.links;
            this.links[0].label = "Anterior";
            this.links[this.links.length - 1].label = "Siguiente";
            this.users = data.data.data;
            this.configPagination(data.data);
          }
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    configPagination(data) {
      this.pagination.lastPage = data.last_page;
      this.pagination.currentPage = data.current_page;
      this.pagination.total = data.total;
      this.pagination.lastPageUrl = data.last_page_url;
      this.pagination.nextPageUrl = data.next_page_url;
      this.pagination.prevPageUrl = data.prev_page_url;
      this.pagination.from = data.from;
      this.pagination.to = data.to;
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

<style>
.btn {
  @apply font-bold py-2 px-4 rounded;
}
.btn-blue {
  @apply bg-blue-500 text-white;
}
.btn-blue:hover {
  @apply bg-blue-700;
}
.btn-red {
  @apply bg-red-500 text-white;
}
.btn-red:hover {
  @apply bg-red-700;
}

div.columTable {
  max-width: 100px;
  width: 100px;
  white-space: break-word;
}

table td {
  /*     text-align: center;
 */
  font-size: 10pt;
  /*     border: 1px solid black;
 */
}

#DataTables_Table_0_filter,
#DataTables_Table_0_length {
  padding: 1em;
}

.dataTables_wrapper .dataTables_length select {
  padding-left: 20px;
  padding-right: 20px;
}

/* div.dataTables_wrapper {
  width: 900px;
  margin: 0 auto;
} */

#tabla-central::-webkit-scrollbar {
  width: 12px;
  height: 10px;
  /* width of the entire scrollbar */
}

#tabla-central::-webkit-scrollbar-track {
  background: yellow;
  /* color of the tracking area */
}

#tabla-central::-webkit-scrollbar-thumb {
  background-color: red;
  /* color of the scroll thumb */
  border-radius: 20px;
  /* roundness of the scroll thumb */
  border: 3px solid transparent;
  /* creates padding around scroll thumb */
}

.dt-button {
  padding: 0;
  border: none;
}

table.tablas_sistema thead:nth-child(1) th {
  background-color: #1f3558;
  color: white;
  text-align: center;
  position: sticky;
  top: 0;
  z-index: 10;
}

table.tablas_sistema thead:nth-child(2) th {
  text-align: center;
  position: sticky;
  top: 0;
  z-index: 10;
}

input.tabla[type="text"] {
  width: 100px;
  height: 28px;
  line-height: 28px;
  border-radius: 30px;
  padding: 0 8px;
  border: none;
  background-color: #1f355833;
  text-align: center;
}

input.tabla[type="date"] {
  width: 125px;
  height: 28px;
  line-height: 28px;
  border-radius: 30px;
  padding: 0 8px;
  border: none;
  background-color: #1f355833;
  text-align: center;
}
</style>
