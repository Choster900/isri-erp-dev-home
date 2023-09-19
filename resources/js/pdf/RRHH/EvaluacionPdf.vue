<template>
    <div class="pt-1"></div>
    <table class="w-full">
        <tbody>
            <tr>
                <td class="border border-y border-l border-r-0 border-black" rowspan="4">
                    <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                </td>
                <td class="border border-b-0  border-black text-center font-medium uppercase" rowspan="2">
                    <div style="margin-top: -12px;" class="text-base">
                        SEGUIMIENTO DE DESEMPEÑO
                    </div>
                </td>
                <td class="border border-l-0 border-b-0 border-black text-[8pt] font-normal pr-3 px-1.5 pb-2">
                    <div class="" style="margin-top: -5px;">FECHA DE CREACIÓN: 12/12/2021</div>
                </td>
            </tr>
            <tr>
                <td class="border border-l-0 border-b-0 border-black text-[8pt] font-normal px-1.5 pb-2">
                    <div class="" style="margin-top: -5px;">ID 00000-1</div>
                </td>
            </tr>
            <tr>
                <td class="border border-x border-b  border-black text-center font-medium px-8" rowspan="2">
                    <div style="margin-top: -12px;" class="text-base">
                        DESEMPEÑO DE PERSONAL ADMINISTRATIVO
                    </div>
                </td>
                <td class="border border-l-0 border-b-0 border-black text-[8pt] font-normal px-1.5 pb-2">
                    <div class="" style="margin-top: -5px;">DEPENDENCIA: ADMON</div>
                </td>
            </tr>
            <tr>
                <td class="border border-l-0 border-black text-[8pt] font-normal  px-1.5 pb-2">
                    <div class="" style="margin-top: -5px;">CODIGO: VERSION 1.0</div>
                </td>
            </tr>
            <tr>
                <td class="border border-l-0 border-black bg-black h-5 text-white text-center text-[10pt] pb-2" colspan="3">
                    <div class="" style="margin-top: -5px;">ESPECIFICACIONES</div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="flex items-center justify-center pt-4 gap-28">
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">EMPLEADO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">{{ empleado }}</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">PUESTO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">{{ puesto }}</div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center pt-2 gap-2 pb-7">
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">JEFE INME::</div>
            </label>
            <div
                class="text-left text-[8pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">MIGUEL JOSUE TOBIAS RIVAS</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">PUESTO :</div>
            </label>
            <div
                class="text-left text-[8pt] w-64 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">COORDINADOR DEL AREA DE SISTEMAS DE...</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">PUNTAJE TOTAL:
                    :</div>
            </label>
            <div
                class=" text-[8pt] w-10 text-sm text-center font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">{{ promedio }}</div>
            </div>
        </div>
    </div>

    <table class="pb-5" v-for="(data, i) in contenidoEvaluacionRendimiento.categorias_rendimiento" :key="i">
        <tbody>
            <tr>
                <td class="border border-black bg-black h-10 text-[10pt] text-white text-center" colspan="2">
                    <div class="flex justify-between items-center">
                        <span class="mx-auto">
                            <div class="" style="margin-top: -12px;">{{ data.nombre_cat_rendimiento }}</div>
                        </span>
                        <span class="text-end pr-8">
                            <div class="" style="margin-top: -12px;">PUNTOS {{ data.rubricas_rendimiento.find(element =>
                                element.id_rubrica_rendimiento ===
                                registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal[i].id_rubrica_rendimiento).puntaje_rubrica_rendimiento
                            }}</div>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="border border-r-0 border-black bg-slate-300/60" rowspan="2">
                    <div class="w-52 p-1.5 text-[9pt] text-justify text-[#4f4f4f] font-medium">
                        {{ data.descripcion_cat_rendimiento }}</div>
                </td>
            </tr>
            <tr>
                <td class="border border-black">

                    <table class="" id="rubrica_rendimiento">
                        <tr v-for="(rubrica, j) in data.rubricas_rendimiento" :key="j">

                            <!--    {{ rubrica.puntaje_rubrica_rendimiento }}
                            {{ rubrica.id_rubrica_rendimiento }}
                            {{ registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal[i].id_rubrica_rendimiento }}
 -->
                            <td :class="data.rubricas_rendimiento.length != j + 1 ? 'border-b' : ''"
                                class=" border-l-0 border-r border-t-0  border-black  w-[500px] text-[8pt] text-justify px-4">
                                <div class="" style="margin-top: -12px;"> {{ rubrica.descripcion_rubrica_rendimiento }}
                                </div>
                            </td>
                            <td :class="data.rubricas_rendimiento.length != j + 1 ? 'border-b' : ''"
                                class="border-x-0  border-t-0  border-black justify-center text-center px-14 py-4">
                                <div :class="{ 'container-ok': registroEvaluacionRendimientoPersonal.detalle_evaluaciones_personal[i].id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento }"
                                    class="container">
                                    <label>
                                        <input type="radio" :name="data.nombre_cat_rendimiento">
                                        <span class="text-xs justify-center text-center ">
                                            {{ rubrica.opcion_rubrica_rendimiento }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                        </tr>


                    </table>
                </td>

            </tr>

        </tbody>
    </table>
</template>

<script>
export default {
    props: ["contenidoEvaluacionRendimiento", "registroEvaluacionRendimientoPersonal", "infoEmployee", "empleado", "puesto", "promedio"],

}
</script>

<style scoped>
.container form {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
}

.container label {
    display: flex;
    cursor: pointer;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    margin-bottom: 0.375em;
}

.container label input {
    position: absolute;
    left: -9999px;
}

.container label input:checked+span {
    background-color: #ffffff;
    color: rgb(0, 0, 0);
}

.container label input:checked+span:before {
    box-shadow: inset 0 0 0 0.4375em #000000;
}

.container label span {
    margin-top: -5px;
    display: flex;
    align-items: center;
    padding: 0.375em 0.75em 0.375em 0.375em;
    /*  border-radius: 99em; */
    transition: 0.25s ease;
    color: #000000;
}

.container label span:hover {
    background-color: #d6d6e5;
}

.container label span:before {
    display: flex;
    flex-shrink: 0;
    content: "";
    background-color: #ffffff;
    width: 1.2em;
    height: 1.2em;
    /* border-radius: 50%; */
    margin-right: 0.375em;
    transition: 0.25s ease;
    box-shadow: inset 0 0 0 0.125em #000000;
}

.container-ok label span:before {
    display: flex;
    flex-shrink: 0;
    content: "";
    background-color: #000000;
    width: 1.2em;
    height: 1.2em;
    /* border-radius: 50%; */
    margin-right: 0.375em;
    transition: 0.25s ease;
    box-shadow: inset 0 0 0 0.125em #000000;
}</style>