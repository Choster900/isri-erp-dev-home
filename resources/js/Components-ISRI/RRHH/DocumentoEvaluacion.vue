<template>
    <div class=" mx-4 overflow-y-auto max-h-[calc(100vh-200px)] p-3 mb-4" v-if="evaluacionData != ''">

        <table>
            <tbody>
                <tr>
                    <td class="border border-black" rowspan="4">
                        <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                    </td>
                    <td class="border border-black text-center font-medium uppercase" rowspan="2">
                        Seguimiento de Desempeño</td>
                    <td class="border border-black text-[8pt] font-semibold pr-3 px-1.5">FECHA DE CREACIÓN:
                        12/12/2021</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold   px-1.5">{{
                        dataPersonalEvaluacion[0].id_evaluacion_personal }}</td>
                </tr>
                <tr>
                    <td class="border border-black text-center font-medium px-8" rowspan="2">DESEMPEÑO DE
                        PERSONAL ADMINISTRATIVO</td>
                    <td class="border border-black text-[8pt] font-semibold py-0.5 px-1.5">DEPENDENCIA:
                        ADMON</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold  py-0.5 px-1.5">CODIGO: VERSION
                        1.0</td>
                </tr>
                <tr>
                    <td class="border border-black bg-black h-5 text-white text-center text-[10pt] " colspan="3">
                        ESPECIFICACIONES</td>
                </tr>
            </tbody>
        </table>
        <div class="flex items-center justify-center pt-4 gap-28">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">EMPLEADO: </label>
                <input type="text" value="SERGIO ADONAY LOPEZ MEJIA"
                    class=" text-left text-[9pt] w-56 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO: </label>
                <input type="text" value="TECNICO EN SOPORTE"
                    class=" text-left text-[9pt] w-52 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
        </div>
        <div class="flex items-center justify-center pt-2 gap-10 pb-7">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">JEFE INME: </label>
                <input type="text" value="MIGUEL JOSUE TOBIAS RIVAS"
                    class=" text-left text-[9pt]  text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO : </label>
                <input type="text" value="COORDINADOR DEL AREA DE SISTEMAS DE INFORMACION"
                    class=" text-left text-[9pt] w-56 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUNTAJE TOTAL:


                </label>
                <input type="text"
                    :value="coleccionCountScore.reduce((countingScore, obj) => countingScore + parseFloat(obj.puntaje_rubrica_rendimiento), 0)"
                    class=" text-left text-[9pt] w-20 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
        </div>
        <table class="pb-5" v-for="(data, i) in evaluacionData.categorias_rendimiento" :key="i">
            <tbody>
                <tr>
                    <td class="border border-black bg-black h-10 text-[10pt] text-white text-center" colspan="2">
                        <div class="flex justify-between items-center">
                            <span class="mx-auto">{{ data.nombre_cat_rendimiento }}</span>
                            <span class="text-end pr-8">
                                PUNTOS: {{
                                    (coleccionCountScore.find(obj => obj.nombre_cat_rendimiento === data.nombre_cat_rendimiento)
                                        || { puntaje_rubrica_rendimiento: 0 }).puntaje_rubrica_rendimiento
                                }}

                            </span>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black bg-slate-300/60" rowspan="2">
                        <div class="w-40 p-1.5 text-[9pt] text-justify text-[#4f4f4f] font-medium">
                            {{ data.descripcion_cat_rendimiento }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black">

                        <table class="" id="rubrica_rendimiento">
                            <tr v-for="(rubrica, j) in data.rubricas_rendimiento" :key="j">
                                <!-- FIXME: Arreglar el clase de border botton -->
                                <td :class="{ ' border-t-0 border-b': j == data.rubricas_rendimiento.lenght }"
                                    class="text-[9pt] border-l-0 border-r border-t-0 border-b border-black text-center font-medium">
                                    {{ rubrica.descripcion_rubrica_rendimiento }}
                                </td>
                                <td
                                    class="border-x-0  border-t-0 border-b  border-black justify-center text-center px-10 py-4">
                                    <div class="container">
                                        <label>
                                            <!-- {{
                                                dataPersonalEvaluacion[0].detalle_evaluaciones_personal[i].id_rubrica_rendimiento
                                                == rubrica.id_rubrica_rendimiento ? countingScore({
                                                    nombre_cat_rendimiento: data.nombre_cat_rendimiento,
                                                    id_rubrica_rendimiento:
                                                        dataPersonalEvaluacion[0].detalle_evaluaciones_personal[i].id_rubrica_rendimiento,
                                                    id_cat_rendimiento: data.id_cat_rendimiento,
                                                    opcion_rubrica_rendimiento: rubrica.opcion_rubrica_rendimiento,
                                                    puntaje_rubrica_rendimiento: rubrica.puntaje_rubrica_rendimiento,
                                                }) : '' }} -->
                                            <input
                                                :checked="dataPersonalEvaluacion[0].detalle_evaluaciones_personal[i].id_rubrica_rendimiento == rubrica.id_rubrica_rendimiento"
                                                type="radio" :name="data.descripcion_cat_rendimiento" @click="saveResponseOfQuestion({
                                                    nombre_cat_rendimiento: data.nombre_cat_rendimiento,
                                                    id_rubrica_rendimiento: rubrica.id_rubrica_rendimiento,
                                                    id_cat_rendimiento: data.id_cat_rendimiento,
                                                    opcion_rubrica_rendimiento: rubrica.opcion_rubrica_rendimiento,
                                                    puntaje_rubrica_rendimiento: rubrica.puntaje_rubrica_rendimiento,
                                                })">
                                            <span class="text-xs justify-center text-center"
                                                :title="`${rubrica.puntaje_rubrica_rendimiento} Puntos`">{{
                                                    rubrica.opcion_rubrica_rendimiento }}</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>


                        </table>
                    </td>

                </tr>

            </tbody>
        </table>


    </div>
</template>

<script>
export default {
    props: ["dataEvaluacion", "dataPersonalEvaluacion"],
    data() {
        return {
            evaluacionData: [],
            coleccionCountScore: [], // Contabiliza el puntaje de la evaluacion
        }
    },
    methods: {
        countingScore(data) {
            const catRendimientoIndex = this.coleccionCountScore.findIndex(obj => obj.nombre_cat_rendimiento === data.nombre_cat_rendimiento);
            if (catRendimientoIndex !== -1) {
                //console.log(catRendimientoIndex);
                this.coleccionCountScore.splice(catRendimientoIndex, 1); // Eliminar un elemento en el índice encontrado
            }

            this.coleccionCountScore.push(data);
        },
        async saveResponseOfQuestion(data) {
            this.countingScore(data)

            try {
                this.isLoading = true
                const response = await axios.post('/save-response', {
                    id_evaluacion_personal: this.dataPersonalEvaluacion[0].id_evaluacion_personal,
                    id_cat_rendimiento: data.id_cat_rendimiento,
                    id_rubrica_rendimiento: data.id_rubrica_rendimiento,
                });
                console.log(response.data);
                //this.dataEvaluacionPersonal = response.data
            } catch (error) {
                console.log('Error en la búsqueda:', error)
            } finally {
                this.isLoading = false
            }
        },
        mapperResponse() {
            const detalleEvaluacionPersonal = this.dataPersonalEvaluacion[0].detalle_evaluaciones_personal;
            const categoriasRendimiento = this.evaluacionData.categorias_rendimiento;

            if (!detalleEvaluacionPersonal || !categoriasRendimiento) {
                // Maneja el caso en el que alguna de las variables sea undefined
                return;
            }

            detalleEvaluacionPersonal.forEach(obj => {
                const categoria = categoriasRendimiento.find(cat => cat.id_cat_rendimiento === obj.id_cat_rendimiento);
                if (!categoria) return; // No se encontró la categoría

                const rubrica = categoria.rubricas_rendimiento.find(rub => rub.id_rubrica_rendimiento === obj.id_rubrica_rendimiento);
                if (!rubrica) return; // No se encontró la rubrica

                const data = {
                    id_rubrica_rendimiento: obj.id_rubrica_rendimiento,
                    nombre_cat_rendimiento: categoria.nombre_cat_rendimiento,
                    id_cat_rendimiento: obj.id_cat_rendimiento,
                    opcion_rubrica_rendimiento: rubrica.opcion_rubrica_rendimiento,
                    puntaje_rubrica_rendimiento: rubrica.puntaje_rubrica_rendimiento,
                };

                this.countingScore(data);
            });
        }


    },
    watch: {
        dataEvaluacion() {
            this.evaluacionData = this.dataEvaluacion
            //  console.log(this.evaluacionData.categorias_rendimiento);
            this.coleccionCountScore = []
            this.mapperResponse()
        },

    }
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
    background-color: #fff;
    width: 1.5em;
    height: 1.5em;
    /* border-radius: 50%; */
    margin-right: 0.375em;
    transition: 0.25s ease;
    box-shadow: inset 0 0 0 0.125em #000000;
}
</style>