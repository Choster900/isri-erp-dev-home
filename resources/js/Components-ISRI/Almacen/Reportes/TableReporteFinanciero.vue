<template>
  <div class="flex px-2" v-if="dataReporteInfo != ''">
    <button class="bg-red-700 flex justify-center items-center m-1 p-0.5 rounded">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        width="25px"
        height="25px"
        color="#ffffff"
        fill="none"
      >
        <path
          d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z"
          stroke="currentColor"
          stroke-width="1.5"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
        <path
          d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10"
          stroke="currentColor"
          stroke-width="1.5"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
        <path
          d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2"
          stroke="currentColor"
          stroke-width="1.5"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>

      <span class="text-white text-xs font-medium"> Imprimir </span>
    </button>

    <button
      class="bg-green-700 flex justify-center items-center m-1 p-0.5 rounded"
      @click="exportExcel"
    >
      <svg
        width="23px"
        height="23px"
        viewBox="0 0 24.00 24.00"
        xmlns="http://www.w3.org/2000/svg"
        fill="#ffffff"
        stroke="#000000"
        stroke-width="0.00024000000000000003"
      >
        <path fill="none" d="M0 0h24v24H0z"></path>
        <path
          d="M2.859 2.877l12.57-1.795a.5.5 0 0 1 .571.495v20.846a.5.5 0 0 1-.57.495L2.858 21.123a1 1 0 0 1-.859-.99V3.867a1 1 0 0 1 .859-.99zM4 4.735v14.53l10 1.429V3.306L4 4.735zM17 19h3V5h-3V3h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-4v-2zm-6.8-7l2.8 4h-2.4L9 13.714 7.4 16H5l2.8-4L5 8h2.4L9 10.286 10.6 8H13l-2.8 4z"
        ></path>
      </svg>
      <span class="text-white text-xs font-medium"> Imprimir </span>
    </button>
  </div>

  <div class="bg-white">
    <div>
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="table-auto w-full">
          <!-- Table header -->
          <thead
            class="font-semibold uppercase text-slate-500 border-t border-b border-slate-200 *:text-[11px]"
          >
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
              <tbody
                class="divide-y divide-slate-200 border-b border-slate-200 *:text-[11.5px]"
              >
                <tr
                  class="cursor-pointer hover:bg-slate-200 *:py-1.5"
                  v-for="(existencia, i) in dataReporteInfo"
                  :key="i"
                  v-show="dataReporteInfo.length - 1 != i"
                >
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
                        <img
                          src="../../../../img/TableReport.svg"
                          class="h-72 mx-auto"
                          alt="SVG Image"
                          draggable="false"
                        />
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
            </template>
          </template>

          <template v-else>
            <tbody>
              <tr>
                <td colspan="12">
                  <div
                    v-if="isLoadingExport"
                    class="flex items-center justify-center h-96"
                  >
                    <div aria-label="Loading..." role="status" class="loader">
                      <svg class="icon" viewBox="0 0 256 256">
                        <line
                          x1="128"
                          y1="32"
                          x2="128"
                          y2="64"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
                        <line
                          x1="195.9"
                          y1="60.1"
                          x2="173.3"
                          y2="82.7"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
                        <line
                          x1="224"
                          y1="128"
                          x2="192"
                          y2="128"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
                        <line
                          x1="195.9"
                          y1="195.9"
                          x2="173.3"
                          y2="173.3"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
                        <line
                          x1="128"
                          y1="224"
                          x2="128"
                          y2="192"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
                        <line
                          x1="60.1"
                          y1="195.9"
                          x2="82.7"
                          y2="173.3"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
                        <line
                          x1="32"
                          y1="128"
                          x2="64"
                          y2="128"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
                        <line
                          x1="60.1"
                          y1="60.1"
                          x2="82.7"
                          y2="82.7"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="24"
                        ></line>
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
