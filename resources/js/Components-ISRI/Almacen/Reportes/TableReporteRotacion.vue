<template>
    <div class="bg-white">
        <div>
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <!-- Table header -->
                    <thead
                        class="font-semibold uppercase text-slate-500 border-t border-b border-slate-200 *:text-[11px]">
                        <tr>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-left">Dependencia</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-left">Codigo</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Concepto</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Existencia Al Inicio del Periodo</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Rotacion Ideal (0.80 Exist.Ini.)</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Salida del periodo</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Diferencia</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Existencia al final del periodo</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Costo Unita. Aproxima</div>
                            </th>
                            <th class="px-1 first:pl-5 last:pr-5 py-1">
                                <div class="font-semibold text-center">Monto Aproxima</div>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10">
                                54101-PRODUCTOS ALIMENTICIOS PARA PERSONAS
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td>CRINA</td>
                            <td>1</td>
                            <td>ACEITE  COMESTIBLE DE OLIVA, PRESENTACION: 500 ML,  VENCIMIENTO,MINIMO 1 AÑO</td>
                            <td>60</td>
                            <td>6</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { toRefs } from 'vue';

export default {
    name: "Test",
    // props: ["dataReporteInfo", "isLoadingExport", "paramsToRequest"],
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
