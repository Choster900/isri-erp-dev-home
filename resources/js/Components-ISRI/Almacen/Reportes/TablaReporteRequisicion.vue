<template>
  <!--  {{dataReporteInfo.map(index =>{ return index.numero_mov_rpt_consumo}) }} -->
  <!--     {{ tipoReporte }}
    {{ dataReporteInfo.some(index => index.numero_mov_rpt_consumo !== null) }} -->

  <!--    {{ isLoadinRequest }} -->
  <div class="bg-white">
    <div class="">
      <!-- Table -->
      <div class="overflow-x-auto w-full">
        <table class="table-auto w-full">
          <!-- Table header -->
          <thead
            class="font-semibold uppercase text-slate-500 border-t border-b border-slate-200 *:text-[11px]"
          >
            <tr>
              <th class="px-1 w-14 last:pr-5 py-1">
                <div class="font-semibold text-center w-20">CODIGO</div>
              </th>
              <th class="px-1 w-14 last:pr-5 py-1">
                <div class="font-semibold text-left w-[500px]">PRODUCTO</div>
              </th>

              <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                <div class="font-semibold text-left">MARCA</div>
              </th>
              <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                <div class="font-semibold text-center">UNIDAD DE MEDIDA</div>
              </th>
              <th class="px-1 first:pl-5 last:pr-5 py-1 w-32">
                <div class="font-semibold text-center">FECHA DE VENCIMIENTO PROD</div>
              </th>
              <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                <div class="font-semibold text-center">CANTIDAD REQUERIDA</div>
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
            <template v-for="(item, index) in dataGetRequisicion" :key="index">
              <tr
                class="border *:text-base font-semibold *:text-center *:hover:bg-slate-200 *:px-2"
                v-show="item.id_tipo_reg_rpt_requisicion == 1"
              >
                <td style="text-align: left" colspan="6">
                  {{ item.marca_rpt_requisicion }} , {{ item.producto_rpt_requisicion }} ,
                  {{ item.umedida_rpt_requisicion }}
                </td>
              </tr>

              <tr
                v-show="item.id_tipo_reg_rpt_requisicion == 2"
                class="border *:text-xs *:text-center *:hover:bg-slate-200 *:px-2"
              >
                <td style="text-align: center">{{ item.id_prod_rpt_requisicion }}</td>
                <td style="text-align: left">{{ item.producto_rpt_requisicion }}</td>
                <td style="text-align: ">{{ item.marca_rpt_requisicion }}</td>
                <td style="text-align: ">{{ item.umedida_rpt_requisicion }}</td>
                <td style="text-align: ">{{ item.fecha_vcto_rpt_requisicion }}</td>
                <td style="text-align: ">{{ item.cant_rpt_requisicion }}</td>
                <td style="text-align: " class="w-10">
                  ${{ item.costo_rpt_requisicion }}
                </td>
                <td style="text-align: " class="w-10">
                  ${{ item.monto_rpt_requisicion }}
                </td>
              </tr>
              <tr
                class="border *:text-xs *:text-center *:hover:bg-slate-200 *:px-2"
                v-show="item.id_tipo_reg_rpt_requisicion == 3"
              >
                <td style="text-align: center" class="font-bold" colspan="7">
                  {{ item.producto_rpt_requisicion }}
                </td>
                <td style="text-align: " colspan="1">
                  ${{ item.monto_rpt_requisicion }}
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!--  <pre class="text-xs">
    {{ dataGetRequisicion }}
</pre
  > -->
</template>

<script>
import { toRefs } from "vue";

export default {
  name: "Test",
  props: ["dataGetRequisicion", "isLoadinRequest", "tipoReporte"],
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
