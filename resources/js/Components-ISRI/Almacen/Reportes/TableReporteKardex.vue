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
                                <div class="font-semibold text-center w-20">FECHA</div>
                            </th>
                            <th class="px-1 w-14 last:pr-5 py-1">
                                <div class="font-semibold text-left w-[250px]">DETALLE</div>
                            </th>
                            <th class="px-1 w-14 last:pr-5 py-1">
                                <div class="font-semibold text-center w-20">TIPO DE INGRESO</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">CENTRO DE ATENCION</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">CANTIDAD DE ENTRADA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">COSTO DE ENTRADA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">MONTO DE ENTRADA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 w-20">
                                <div class="font-semibold text-center">CANTIDAD DE SALIDA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 text-center w-20">
                                <div class="font-semibold">COSTO DE SALIDA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 text-center">
                                <div class="font-semibold">MONTO  SALIDA</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 text-center">
                                <div class="font-semibold">CANTIDAD FINAL</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 text-center">
                                <div class="font-semibold">COSTO FINAL</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1 text-center">
                                <div class="font-semibold">MONTO FINAL</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- id_rpt_requisicion -->
                    <tbody>
                        <template v-for="(item, index) in dataGetKardex" :key="index">


                            <tr
                                class="border *:text-xs *:text-center *:hover:bg-slate-200 *:px-1 *:py-1.5">
                                <td style="text-align: center">{{ item.fecha }}</td>
                                <td style="text-align: left">{{ item.referencia_rpt_kardex }}</td>
                                <td style="text-align: center">{{ item.tipo_mov_rpt_kardex }}</td>
                                <td style="text-align: ">{{ item.centro_rpt_kardex }}</td>
                                <td style="text-align: ">{{ item.cant_entrada_rpt_kardex }}</td>
                                <td style="text-align: ">{{ item.costo_entrada_rpt_kardex }}</td>
                                <td style="text-align: ">{{ item.monto_entrada_rpt_kardex }}</td>
                                <td style="text-align: ">$ {{ item.cant_salida_rpt_kardex }}</td>
                                <td style="text-align: ">$ {{ item.costo_salida_rpt_kardex }}</td>
                                <td style="text-align: ">{{ item.monto_salida_rpt_kardex }}</td>
                                <td style="text-align: ">$ {{ item.cant_final_rpt_kardex }}</td>
                                <td style="text-align: ">{{ item.costo_final_rpt_kardex }}</td>
                                <td style="text-align: ">$ {{ item.monto_final_rpt_kardex }}</td>

                            </tr>

                        </template>
                    </tbody>
                    <tbody v-if="dataGetKardex == '' && isLoadinRequest == false">
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


<!-- <pre>
{{dataGetKardex}}
</pre> -->
</template>

<script>
import { toRefs } from "vue";

export default {
    name: "Test",
    props: ["dataGetKardex", "isLoadinRequest", "tipoReporte"],
    setup(props) {
        const { paramsToRequest } = toRefs(props);

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
