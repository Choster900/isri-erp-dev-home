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
                            <th class="px-2   py-1">
                                <div class="font-semibold text-left">PROYECTO FINANCIADO</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-left  w-96">PRODUCTO</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">ESPECIFICO</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">MARCA</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">UNIDAD DE MEDIDA</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">CENTRO ATENCION</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">LINEA DE TRABAJO</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">CANTIDAD EXISTENCIA</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">COSTO</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">FECHA VENCIMIENTO</div>
                            </th>
                            <th class="px-1  py-1">
                                <div class="font-semibold text-center">SUB ALMACEN</div>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataReporteExistencia" :key="index" class="border *:text-xs *:text-center *:hover:bg-slate-200 *:py-1">
                            <td>{{ item.codigo_proy_rpt_existencia }}</td>
                            <td style="text-align: left;">{{ item.nombre_prod_rpt_existencia }}</td>
                            <td>{{ item.id_ccta_presupuesto_rpt_existencia }}</td>
                            <td>{{ item.marca_rpt_existencia }}</td>
                            <td>{{ item.nombre_umedida_rpt_existencia }}</td>
                            <td>{{ item.codigo_centro_rpt_existencia }}</td>
                            <td>{{ item.codigo_lt_rpt_existencia }}</td>
                            <td>{{ item.cant_rpt_existencia }}</td>
                            <td>{{ item.costo_rpt_existencia }}</td>
                            <td>{{ item.fecha_vcto_rpt_existencia }}</td>
                            <td>{{ item.id_prod_rpt_existencia }}</td>
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
    props: ["dataReporteExistencia"],
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
