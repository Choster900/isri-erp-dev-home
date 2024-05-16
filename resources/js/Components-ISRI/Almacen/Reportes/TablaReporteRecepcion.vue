<template>
    <div class="bg-white">
        <div class="">
            <!-- Table -->
            <div class="overflow-x-auto w-full">
                <table class="table-auto w-full">
                    <!-- Table header -->
                    <thead
                        class="font-semibold uppercase text-slate-500 border-t border-b border-slate-200 *:text-[11px]">
                        <tr>
                            <th class="px-1 w-14 last:pr-5 py-1">
                                <div class="font-semibold text-center w-20">CODIGO</div>
                            </th>
                            <th class="px-1 w-14 last:pr-5 py-1">
                                <div class="font-semibold text-left w-[500px]">PRODUCTO</div>
                            </th>

                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">MARCA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">UNIDAD DE MEDIDA</div>
                            </th>
                            <!--   <th class="px-1 first:pl-5 last:pr-5 py-1 w-32">
                                <div class="font-semibold text-center">FECHA DE VENCIMIENTO PROD</div>
                            </th> -->

                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">CENTRO</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">CODIGO UPLT</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">CANTIDAD COMPRA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 text-center w-10">
                                <div class="font-semibold">COSTO</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 text-center">
                                <div class="font-semibold">MONTO</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- id_rpt_requisicion -->
                    <tbody>
                        <template v-for="(item, index) in dateGetRecepcion" :key="index">
                            <tr class="border  font-semibold *:text-center *:hover:bg-slate-200 *:px-2 border-y-2 border-y-black"
                                v-show="item.id_tipo_reg_rpt_compra == 1">
                                <td style="text-align: left" class="text-sm" colspan="9">
                                    {{ item.producto_rpt_compra }} -
                                    {{ item.marca_rpt_compra }} -
                                    {{ item.umedida_rpt_compra }} -
                                    {{ item.fecha_vcto_rpt_compra }}
                                </td>
                            </tr>

                            <tr v-show="item.id_tipo_reg_rpt_compra == 2"
                                class="border *:text-xs *:text-center *:hover:bg-slate-200 *:px-2">
                                <td style="text-align: center">{{ item.id_prod_rpt_compra }}</td>
                                <td style="text-align: left">{{ item.producto_rpt_compra }}</td>
                                <td style="text-align: ">{{ item.marca_rpt_compra }}</td>
                                <td style="text-align: ">{{ item.umedida_rpt_compra }}</td>
                                <td style="text-align: ">{{ item.codigo_centro_rpt_compra }}</td>
                                <td style="text-align: ">{{ item.codigo_uplt_rpt_compra }}</td>
                                <td style="text-align: ">{{ item.cant_rpt_compra }}</td>
                                <td style="text-align: " class="w-10">
                                    ${{ item.costo_rpt_compra }}
                                </td>
                                <td style="text-align: " class="w-10">
                                    ${{ item.monto_rpt_compra }}
                                </td>
                            </tr>
                            <tr class=" *:text-xs  *:hover:bg-slate-200 " v-show="item.id_tipo_reg_rpt_compra == 3">
                                <td class="font-bold px-6" colspan="8">
                                    {{ item.producto_rpt_compra }}
                                </td>
                                <td style="text-align: center" class="text-red-600" colspan="1">
                                    ${{ item.monto_rpt_compra }}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody v-if="dateGetRecepcion == '' && isLoadinRequest == false">
                        <tr>
                            <td colspan="12">
                                <div class="w-full h-[500px] px-1 text-selection-disable">
                                    <div class="flex flex-col items-center justify-center h-full">
                                        <img src="../../../../img/TableReport.svg" class="h-72 mx-auto" alt="SVG Image"
                                            draggable="false" />
                                        <h1 class="font-medium text-center">
                                            No se encontraron resultados
                                        </h1>
                                        <p class="text-[9pt] text-center">
                                            Intenta buscar en períodos válidos o revisa tus criterios de
                                            búsqueda.
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="isLoadinRequest">
                        <tr>
                            <td colspan="12">
                                <div class="flex items-center justify-center h-96">
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
                </table>
            </div>
        </div>
    </div>

    <!-- <pre class="text-xs">
    {{ dateGetRecepcion }}
</pre> -->
</template>

<script>
import { toRefs } from "vue";

export default {
    name: "Test",
    props: ["dateGetRecepcion", "isLoadinRequest", "tipoReporte"],
    setup(props) {
        const { paramsToRequest } = toRefs(props);
        /*  const exportExcel = async () => {
                           try {
                             const response = await axios.post(
                               "/get-excel-document-reporte-financiero",
                               { paramsToRequest: paramsToRequest.value },
                               { responseType: "blob" }
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
                         }; */

        return {
            /* exportExcel, */
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
