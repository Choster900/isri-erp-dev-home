<template>
    <!--  {{dataReporteInfo.map(index =>{ return index.numero_mov_rpt_consumo}) }} -->
    <!--     {{ tipoReporte }}
    {{ dataReporteInfo.some(index => index.numero_mov_rpt_consumo !== null) }} -->

 <!--    {{ isLoadinRequest }} -->
    <div class="bg-white ">
        <div class="m">
            <!-- Table -->
            <div class="overflow-x-auto w-full">
                <table class="table-auto w-full">
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
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-[100px] " v-if="tipoReporte == 'D'">
                                <div class="font-semibold text-center">NUMERO</div>
                            </th>

                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-[100px]" v-if="tipoReporte == 'D'">
                                <div class="font-semibold text-center ">FECHA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">CANTIDAD</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1" v-if="tipoReporte == 'D'">
                                <div class="font-semibold text-center">COSTO</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">MONTOS</div>
                            </th>

                        </tr>
                    </thead>
                    <tbody v-for="(consumo, i) in dataReporteInfo" :key="i" v-if="!isLoadinRequest">
                        <tr class="*:text-[12px] *:font-semibold *:py-1" v-if="consumo.id_tipo_reg_rpt_consumo == 0">
                            <!--   <td class="text-center border-y">{{ consumo.id_ccta_presupuesto_rpt_consumo }}</td> -->
                            <td class="border-y text-left px-2" :colspan="tipoReporte === 'D' ? 9 : 6">
                                <div class="text-base">
                                    {{ consumo.nombre_prod_rpt_consumo }}
                                </div>
                            </td>
                        </tr>
                        <tr class="*:text-[12px] *:font-semibold *:py-1" v-if="consumo.id_tipo_reg_rpt_consumo == 1">
                            <td class="text-center border-y">{{ consumo.id_ccta_presupuesto_rpt_consumo }}</td>
                            <td class="border-y text-left px-2" :colspan="tipoReporte === 'D' ? 8 : 6">
                                <div class="underline">
                                    {{ consumo.nombre_prod_rpt_consumo }}
                                </div>
                            </td>
                        </tr>
                        <tr class="*:border-y *:text-[12px] *:px-1 *:py-1 hover:bg-slate-200"
                            v-if="consumo.id_tipo_reg_rpt_consumo === 2">
                            <td class="text-center">{{ consumo.codigo_uplt_rpt_consumo }}</td>
                            <td class="text-left">{{ consumo.nombre_prod_rpt_consumo }}</td>
                            <td class="text-center">{{ consumo.marca_rpt_consumo || '-' }}</td>
                            <td class="text-center">{{ consumo.nombre_umedida_rpt_consumo }}</td>
                            <td class="text-center font-medium" v-if="tipoReporte == 'D'">{{
        consumo.numero_mov_rpt_consumo }}</td>
                            <td class="text-center" v-if="tipoReporte == 'D'">{{ consumo.fecha }}</td>
                            <td class="text-center">{{ consumo.cant_rpt_consumo }}</td>
                            <td class="text-center" v-if="tipoReporte == 'D'">{{ consumo.costo_rpt_consumo }}</td>
                            <td class="text-center">{{ consumo.monto_rpt_consumo }}</td>
                        </tr>
                        <tr class="*:text-[12px] *:font-semibold *:py-1" v-if="consumo.id_tipo_reg_rpt_consumo == 3">
                            <td class="text-center"></td>
                            <td class="border-y text-right px-2" :colspan="tipoReporte === 'D' ? 7 : 4">
                                {{ consumo.nombre_prod_rpt_consumo }}
                            </td>
                            <td class="text-center">{{ consumo.monto_rpt_consumo }}</td>

                        </tr>
                    </tbody>

                    <tbody v-if="isLoadinRequest">
                        <tr>
                            <td colspan="12">
                                <div  class="flex items-center justify-center h-96">
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

    <!--  <pre>
              {{ dataReporteInfo }}</pre> -->
</template>

<script>
import { toRefs } from "vue";

export default {
    name: "Test",
    props: ["dataReporteInfo", "isLoadinRequest", "tipoReporte"],
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
