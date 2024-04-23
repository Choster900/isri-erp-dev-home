<template>
    <div class="bg-white">
        <div>
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <!-- Table header -->
                    <thead
                        class="font-semibold uppercase text-slate-500 border-t border-b border-slate-200 *:text-xs">
                        <tr>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-left">Dependencia</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-left">cod</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center w-96">Concepto</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">Existencia Al Inicio del Periodo</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">Rotacion Ideal (0.80 Exist.Ini.)</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">Salida del periodo</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">Diferencia</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">Existencia al final del periodo</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">Costo Unita. Aproxima</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">Monto Aproxima</div>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataReporteInfo" :key="index"
                            v-show="item.id_tipo_reg_rpt_rotacion == 1">
                            <td colspan="10" class="px-4 font-bold">
                               {{ item.id_ccta_presupuesto_rpt_rotacion }} - {{ item.nombre_prod_rpt_rotacion }}
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr v-for="(item, index) in dataReporteInfo" :key="index" class="border *:text-xs *:text-center *:hover:bg-slate-200"
                            v-show="item.id_tipo_reg_rpt_rotacion == 2">
                            <td>{{ item.sigla_centro_rpt_rotacion }}</td>
                            <td>{{ item.id_prod_rpt_rotacion }}</td>
                            <td class="text-left" style="text-align: left;">{{ item.nombre_prod_rpt_rotacion }}</td>
                            <td>{{ item.existencia_inicial_rtp_rotacion }}</td>
                            <td>{{ item.rotacion_ideal_rtp_rotacion }}</td>
                            <td>{{ item.rotacion_real_rtp_rotacion }}</td>
                            <td>{{ item.diferencia_rtp_rotacion || '-' }}</td>
                            <td>{{ item.existencia_final_rtp_rotacion }}</td>
                            <td>{{ item.costo_aprox_rtp_rotacion || '-'}}</td>
                            <td>${{ item.saldo_aprox_rtp_rotacion }}</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr v-for="(item, index) in dataReporteInfo" :key="index"
                            v-show="item.id_tipo_reg_rpt_rotacion == 3">
                            <td colspan="10" class="px-4 font-bold">
                                54101-PRODUCTOS ALIMENTICIOS PARA PERSONAS
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="dataReporteInfo == '' && !isLoadinRequest">
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
           <!--  <pre>
            {{ dataReporteInfo }}
            </pre> -->
        </div>
    </div>
</template>

<script>
import { toRefs } from 'vue';

export default {
    name: "Test",
    props: ["dataReporteInfo", "isLoadinRequest", "paramsToRequest"],
    setup() {


        return {

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
