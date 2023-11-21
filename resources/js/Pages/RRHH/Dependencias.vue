
<template>
    <Head title="Proceso - Empleados" />
    <AppLayoutVue nameSubModule="RRHH - Empleados">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <!-- <GeneralButton @click="addEmployee()" v-if="permits.insertar == 1" color="bg-green-700  hover:bg-green-800"
                    text="Agregar Empleado" icon="add" /> -->
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">

            <div class="overflow-x-auto">

                <p class="text-center text-[14px]"> AQUI INICIA</p>
                <pre>{{ dependencies }}</pre>
            </div>


        </div>



    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import moment from 'moment';
import axios from 'axios';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';
import { ref, onMounted, toRefs } from 'vue';


export default {
    components: { Head, AppLayoutVue, Datatable },
    props: {
        menu: {
            type: Array,
            default: []
        },
    },
    setup(props) {
        const { menu } = toRefs(props);
        //Datatable options
        const dependencies = []
        const columns = [
            { width: "10%", label: "ID", name: "id_dependencia", type: "text" },
            { width: "30%", label: "Nombre", name: "nombre_dependencia", type: "text" },
            { width: "10%", label: "Codigo", name: "codigo_dependencia", type: "text" },
            { width: "30%", label: "Jefatura", name: "jefatura", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_dependencia", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        const requestUrl = "/dependencias"
        const columntToSort = "id_dependencia"
        const dir = 'desc'

        const {
            dataToShow,
            tableData, perPage,
            links, sortKey,
            sortOrders, 
            isLoadinRequest,
            emptyObject,
            getDataToShow, handleData, sortBy,
        } = useToDataTable(columns,requestUrl,columntToSort,dir)

        const permits = usePermissions(menu.value, window.location.pathname);

        return {
            permits, dependencies,
            dataToShow, tableData, 
            perPage, links, sortKey,
            sortOrders, sortBy,
            handleData, isLoadinRequest,
            emptyObject 
        };
    }
}


</script>