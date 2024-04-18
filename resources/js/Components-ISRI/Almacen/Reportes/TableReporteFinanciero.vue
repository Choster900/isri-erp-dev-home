<template>
    <div class="bg-white">
        <div>
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <!-- Table header -->
                    <thead class="font-semibold uppercase text-slate-500 border-t border-b border-slate-200 *:text-[11px]">
                        <tr>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-left">Especifico</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-left">Descripcion</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Saldo Anterior</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Cuenta Contable</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Compras</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Transferencia Ingreso</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">ajustes ingresos</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Cuenta Contable</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Consumo</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Transferencia Egreso</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Ajustes Salidas</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Nuevo saldo</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <template v-if="!isLoadingExport">
                            <template v-if="dataReporteInfo != ''">
                                <tbody class="divide-y divide-slate-200 border-b border-slate-200 *:text-[11.5px]">
                                    <tr class="cursor-pointer hover:bg-slate-200 *:py-1.5"
                                        v-for="(existencia, i) in dataReporteInfo" :key="i"
                                        v-show="dataReporteInfo.length - 1 != i">
                                        <td class="px-1 first:pl-5 last:pr-5 py-1">
                                            <div class="flex items-left">
                                                <div class="font-medium text-slate-800">
                                                    {{ existencia.codigo_cta_presupuesto_rpt_finaciero }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-[300px]">
                                            <div class="text-left">
                                                {{ existencia.nombre_cta_presupuesto_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="first:pl-5 last:pr-5 py-1">
                                            <div class="text-center">
                                                <div class="text-center font-medium text-slate-700">
                                                    $
                                                    {{
                            existencia.saldo_inicial_rpt_finaciero
                                ? existencia.saldo_inicial_rpt_finaciero.toLocaleString(
                                    "en-US",
                                    {
                                        style: "currency",
                                        currency: "USD",
                                    }
                                )
                                : ""
                        }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1">
                                            <div class="text-center">
                                                {{ existencia.codigo_cta_gasto_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-medium text-slate-700">
                                                ${{ existencia.compra_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-medium text-slate-700">
                                                ${{ existencia.transf_ingreso_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-medium text-slate-700">
                                                ${{ existencia.ajuste_ingreso_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1">
                                            <div class="text-center">
                                                {{ existencia.codigo_cta_inversion_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-medium text-slate-700">
                                                ${{ existencia.consumo_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-medium text-slate-700">
                                                ${{ existencia.transf_egreso_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-medium text-slate-700">
                                                ${{ existencia.ajuste_egreso_rpt_finaciero }}
                                            </div>
                                        </td>
                                        <td class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-medium text-slate-700">
                                                ${{ existencia.saldo_actual_rpt_finaciero }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="*:text-[12px]">
                                        <th class="px-1 last:pr-5 py-1" colspan="2">
                                            <div class="flex items-center justify-end">
                                                <div class="font-bold text-slate-800">
                                                    {{
                            dataReporteInfo.length > 0
                                ? dataReporteInfo[dataReporteInfo.length - 1]
                                    .nombre_cta_presupuesto_rpt_finaciero
                                : ""
                        }}
                                                </div>
                                            </div>
                                        </th>
                                        <th class="first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                $
                                                {{
                                dataReporteInfo.length > 0
                                    ? dataReporteInfo[dataReporteInfo.length - 1]
                                        .saldo_inicial_rpt_finaciero
                                    : ""
                            }}
                                            </div>
                                        </th>
                                        <!-- <th>$404,857.63</th> -->
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">-</div>
                                        </th>
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                $
                                                {{
                                dataReporteInfo.length > 0
                                    ? dataReporteInfo[dataReporteInfo.length - 1]
                                        .compra_rpt_finaciero
                                    : ""
                            }}
                                            </div>
                                        </th>
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                $
                                                {{
                                dataReporteInfo.length > 0
                                    ? dataReporteInfo[dataReporteInfo.length - 1]
                                        .transf_ingreso_rpt_finaciero
                                    : ""
                            }}
                                            </div>
                                        </th>
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                $
                                                {{
                                dataReporteInfo.length > 0
                                    ? dataReporteInfo[dataReporteInfo.length - 1]
                                        .ajuste_ingreso_rpt_finaciero
                                    : ""
                            }}
                                            </div>
                                        </th>
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">-</div>
                                        </th>
                                        <th class="first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                $
                                                {{
                                dataReporteInfo.length > 0
                                    ? dataReporteInfo[dataReporteInfo.length - 1]
                                        .consumo_rpt_finaciero
                                    : ""
                            }}
                                            </div>
                                        </th>
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                $
                                                {{
                                dataReporteInfo.length > 0
                                    ? dataReporteInfo[dataReporteInfo.length - 1]
                                        .transf_egreso_rpt_finaciero
                                    : ""
                            }}
                                            </div>
                                        </th>
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                $
                                                {{
                                                dataReporteInfo.length > 0
                                                ? dataReporteInfo[dataReporteInfo.length - 1]
                                                .ajuste_egreso_rpt_finaciero
                                                : ""
                                                }}
                                            </div>
                                        </th>
                                        <th class="px-1 first:pl-5 last:pr-5 py-1 w-px">
                                            <div class="text-center font-bold text-slate-700">
                                                ${{
                                                dataReporteInfo.length > 0
                                                ? dataReporteInfo[dataReporteInfo.length - 1]
                                                .saldo_actual_rpt_finaciero
                                                : ""
                                                }}
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
</template>

<template v-else>
    <tbody>
        <tr>
            <td colspan="12">
                <div class="w-full h-[500px] px-1 text-selection-disable">
                    <div class="flex flex-col items-center justify-center h-full">
                        <img src="../../../../img/TableReport.svg" class="h-72 mx-auto" alt="SVG Image" draggable="false" />
                        <h1 class="font-medium text-center">
                            No se encontraron resultados
                        </h1>
                        <p class="text-[9pt] text-center">
                            Intenta buscar en períodos válidos o revisa tus criterios de búsqueda.
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</template>
                    </template>

<template v-else>
    <tbody>
        <tr>
            <td colspan="12">
                <div v-if="isLoadingExport" class="flex items-center justify-center h-96">
                    <div aria-label="Loading..." role="status" class="loader">
                        <svg class="icon" viewBox="0 0 256 256">
                                                    <line x1="128" y1="32" x2="128" y2="64" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                    <line x1="195.9" y1="60.1" x2="173.3" y2="82.7" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                    <line x1="224" y1="128" x2="192" y2="128" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                    <line x1="195.9" y1="195.9" x2="173.3" y2="173.3" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                    <line x1="128" y1="224" x2="128" y2="192" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                    <line x1="60.1" y1="195.9" x2="82.7" y2="173.3" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                    <line x1="32" y1="128" x2="64" y2="128" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                    <line x1="60.1" y1="60.1" x2="82.7" y2="82.7" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="24"></line>
                                                </svg>
                        <span class="loading-text">Cargando...</span>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</template>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { toRefs } from 'vue';

export default {
    name: "Test",
    props: ["dataReporteInfo", "isLoadingExport", "paramsToRequest"],
    setup(props) {
        const { paramsToRequest } = toRefs(props);
        const exportExcel = async () => {
            try {
                const response = await axios.post(
                    "/get-excel-document-reporte-financiero", { paramsToRequest: paramsToRequest.value }, { responseType: "blob" }
                );

                // Crear una URL para el blob
                const url = window.URL.createObjectURL(new Blob([response.data]));

                // Crear un enlace temporal y simular un clic para descargar el archivo
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "nombre_del_archivo.xlsx"); // Nombre del archivo deseado
                document.body.appendChild(link);
                link.click();

                // Liberar la URL del blob después de la descarga
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error("Error al descargar el archivo:", error);
            }
        };

        return {
            exportExcel,
        };
    },
};
</script>

<style lang="scss" scoped>
.loader {
    display: flex;
    align-items: center;
}

.icon {
    height: 1.5rem;
    width: 1.5rem;
    animation: spin 1s linear infinite;
    stroke: rgba(107, 114, 128, 1);
}

.loading-text {
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 500;
    color: rgba(107, 114, 128, 1);
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
