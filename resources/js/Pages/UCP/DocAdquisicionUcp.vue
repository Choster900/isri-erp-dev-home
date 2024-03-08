<template>
    <Header title="Proceso - Documentos de adquisiciones" />
    <AppLayoutVue nameSubModule="Compras - Documentos de adquisiciones" :colorSide="' bg-[#343a40] '">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton v-if="permits.insertar == 1" color="bg-green-700  hover:bg-green-800"
                    text="Generar documento" icon="add" />

            </div>
        </div>

        <header class="px-5 py-4">
            <div class="mb-4 md:flex flex-row justify-items-start">
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                    <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                        <Multiselect v-model="tableData.length" @select="getDataToShow()"
                            @deselect=" tableData.length = 5; getDataToShow()"
                            @clear="tableData.length = 5; getDataToShow()" :options="perPage" :searchable="true"
                            placeholder="Cantidad a mostrar" />
                        <LabelToInput icon="list2" />
                    </div>
                </div>
                <h2 class="font-semibold text-slate-800 pt-1">Documentos Adquisicion: <span
                        class="text-slate-400 font-medium">{{
        tableData.total
    }}</span></h2>
            </div>
        </header>
        <!--    <pre>
            {{ dataToShow }}
        </pre> -->
    </AppLayoutVue>
</template>

<script>
import { usePermissions } from '@/Composables/General/usePermissions';
import { useToDataTable } from '@/Composables/General/useToDataTable';
import { ref, toRefs } from 'vue';


export default {
    props: {
        menu: {
            type: Object,
            default: () => { },
        },
    },
    setup(props) {
        const { menu } = toRefs(props);

        const permits = usePermissions(menu.value, window.location.pathname);

        const docAdquisicionId = ref(0)
        const show_modal_acq_doc = ref(false)

        const columns = [
            { width: "9%", label: "ID", name: "id_doc_adquisicion", type: "text" },
            { width: "9%", label: "Tipo", name: "nombre_tipo_doc_adquisicion", type: "text" },
            { width: "15%", label: "Numero", name: "numero_doc_adquisicion", type: "text" },
            { width: "22%", label: "Proveedor", name: "razon_social_proveedor", type: "text" },
            { width: "12%", label: "Monto", name: "monto_doc_adquisicion", type: "text" },
            { width: "18%", label: "Compromisos", name: "compromiso_ppto_det_doc_adquisicion", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_doc_adquisicion", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "5%", label: "Acciones", name: "Acciones" },

        ];
        const requestUrl = "/doc-adquisicion"
        const columntToSort = "id_doc_adquisicion"
        const dir = 'desc'
        const {
            dataToShow,
            tableData, perPage,
            links, sortKey,
            sortOrders,
            isLoadinRequest,
            isLoadingTop,
            emptyObject,
            getDataToShow, handleData, sortBy, changeStatusElement
        } = useToDataTable(columns, requestUrl, columntToSort, dir)

        const changeStatus = async (id, status) => {
            await changeStatusElement(id, status, "/change-state-acq-doc")
        }
        return {
            permits, dataToShow, tableData,
            perPage, links, sortKey, isLoadingTop,
            sortOrders, sortBy, docAdquisicionId,
            handleData, isLoadinRequest,
            getDataToShow, changeStatus, show_modal_acq_doc,
            emptyObject, columns,
        }
    }
}
</script>

<style></style>
