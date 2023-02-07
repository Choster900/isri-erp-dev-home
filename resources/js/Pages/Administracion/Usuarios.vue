<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
</script>

<template>
    <Head title="Administracion" />

    <AppLayoutVue>
        <table
            id="tabla-central"
            class="table table-striped hover display responsive nowrap text-center p-0 mt-2 tablas_sistema"
        >
            <thead class="bg-[#082a60] text-white">
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Estado Usuario</th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th>
                        <input
                            type="text"
                            style="width: 110px; font-size: 8pt"
                            class="form-control tabla"
                        />
                    </th>
                    <th>
                        <input
                            type="text"
                            style="width: 250px; font-size: 8pt"
                            class="form-control tabla"
                        />
                    </th>
                    <th>
                        <input
                            type="text"
                            style="width: 110px; font-size: 8pt"
                            class="form-control tabla"
                        />
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </AppLayoutVue>
</template>

<script>
export default {
    created() {
        $(document).ready(function () {
            //$.noConflict();
            var table = $("#tabla-central").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/user/list",
                columns: [
                    { data: "id_usuario" },
                    { data: "nick_usuario" },
                    { data: "estado_usuario" },
                ],
                sDom: '<"top"fli>t<"bottom"p><"clear">f',
                bProcessing: true,
                //order: [[0, "asc"]],
                bAutoWidth: false,
                bLengthChange: false,
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"],
                ],
                language: {
                    decimal: "",
                    emptyTable: "No hay registros",
                    info: "Mostrando _TOTAL_ registros",
                    infoEmpty:
                        "Mostrando desde el 0 al 0 del total de  0 registros",
                    infoFiltered: "(Filtrados del total de _MAX_ registros)",
                    infoPostFix: "",
                    thousands: ",",
                    lengthMenu: "",

                    loadingRecords: "Cargando...",
                    search: "Filtrar:",
                    zeroRecords:
                        "No se ha encontrado nada  atraves de ese filtrado.",
                    aria: {
                        sortAscending: ": activate to sort column ascending",
                        sortDescending: ": activate to sort column descending",
                    },
                },
                columnDefs: [
                    {
                        targets: "_all",
                        sortable: false,
                        ordering: false,
                        searching: true,
                    },
                ],
            });

            let filas = $("#tabla-central thead tr:eq(1) th");
            filas.each(function (i) {
                $("input", this).on("keyup change", function () {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
                $("select", this).on("change", function () {
                    table.column(i).search(this.value).draw();
                });
            });
            $("#tabla-central_filter").remove(); //eliminando los filtros que pone datatable por defecto
            $(".dataTables_filter").remove();
        });
    },
};
</script>

<style>
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
