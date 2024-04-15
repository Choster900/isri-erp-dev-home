<template>
   <!--  {{dataReporteInfo.map(index =>{ return index.numero_mov_rpt_consumo}) }} -->

    {{ dataReporteInfo.some(index => index.numero_mov_rpt_consumo !== null) }}
    <div class="bg-white">
        <div class="m">
            <!-- Table -->
            <div class="overflow-x-auto w-full">
                <table class="table-auto">
                    <!-- Table header -->
                    <thead
                        class="font-semibold uppercase text-slate-500 border-t border-b border-slate-200 *:text-[11px]">
                        <tr>
                            <th class="px-1 w-14 last:pr-5 py-1">
                                <div class="font-semibold text-center">CODIGO</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-left">DESCRIPCIÓN</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">MARCA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">UNIDAD</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-[100px]">
                                <div class="font-semibold text-center">NUMERO</div>
                            </th>

                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-[100px]">
                                <div class="font-semibold text-center ">FECHA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">CANTIDAD</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">COSTO</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">MONTOS</div>
                            </th>

                        </tr>
                    </thead>
                    <tbody v-for="(consumo, i) in dataReporteInfo" :key="i">
                        <tr class="*:text-[12px] *:font-semibold *:py-1" v-if="consumo.id_tipo_reg_rpt_consumo == 0">
                          <!--   <td class="text-center border-y">{{ consumo.id_ccta_presupuesto_rpt_consumo }}</td> -->
                            <td class="border-y text-left px-2" colspan="9">
                                <div class="text-base">
                                    {{ consumo.nombre_prod_rpt_consumo }}
                                </div>
                            </td>
                        </tr>
                        <tr class="*:text-[12px] *:font-semibold *:py-1" v-if="consumo.id_tipo_reg_rpt_consumo == 1">
                            <td class="text-center border-y">{{ consumo.id_ccta_presupuesto_rpt_consumo }}</td>
                            <td class="border-y text-left px-2" colspan="8">
                                <div class="underline">
                                    {{ consumo.nombre_prod_rpt_consumo }}
                                </div>
                            </td>
                        </tr>
                        <tr class="*:border-y *:text-[12px] *:px-1 *:py-1 hover:bg-slate-200" v-if="consumo.id_tipo_reg_rpt_consumo === 2">
                            <td class="text-center">{{ consumo.codigo_uplt_rpt_consumo }}</td>
                            <td class="text-left">{{ consumo.nombre_prod_rpt_consumo }}</td>
                            <td class="text-center">{{ consumo.marca_rpt_consumo || '-' }}</td>
                            <td class="text-center">{{ consumo.nombre_umedida_rpt_consumo }}</td>
                            <td class="text-center font-medium">{{ consumo.numero_mov_rpt_consumo }}</td>
                            <td class="text-center">{{ consumo.fecha }}</td>
                            <td class="text-center">{{ consumo.cant_rpt_consumo }}</td>
                            <td class="text-center">{{ consumo.costo_rpt_consumo }}</td>
                            <td class="text-center">{{ consumo.monto_rpt_consumo }}</td>
                        </tr>
                        <tr class="*:text-[12px] *:font-semibold *:py-1" v-if="consumo.id_tipo_reg_rpt_consumo == 3">
                              <td class="text-center"></td>
                            <td class="border-y text-right px-2" colspan="7">
                                {{ consumo.nombre_prod_rpt_consumo }}
                            </td>
                            <td class="text-center">{{ consumo.monto_rpt_consumo }}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <!--  <pre>
              {{ dataReporteInfo }}</pre> -->
</template>

<script>
import { toRefs } from "vue";

export default {
    name: "Test",
    props: ["dataReporteInfo", "isLoadingExport", "tipoReporte"],
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
