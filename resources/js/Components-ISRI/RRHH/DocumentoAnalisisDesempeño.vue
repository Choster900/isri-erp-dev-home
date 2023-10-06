<script setup>
import moment from 'moment';

</script>
<template>
    <div class="mx-4 overflow-y-auto max-h-[calc(100vh-100px)] p-3 mb-4">
        <table class="w-full">
            <tbody>
                <tr>
                    <td class="border border-black" rowspan="4">
                        <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                    </td>
                    <td class="border border-black text-center font-medium uppercase" rowspan="2">Seguimiento de Desempeño
                    </td>
                    <td class="border border-black text-[8pt] font-semibold pr-3 px-1.5">FECHA : HOY</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold   px-1.5">ID 00000-1</td>
                </tr>
                <tr>
                    <td class="border border-black text-center font-medium px-8" rowspan="2">DESEMPEÑO DE PERSONAL
                        ADMINISTRATIVO</td>
                    <td class="border border-black text-[8pt] font-semibold py-0.5 px-1.5">DEPENDENCIA:ADMON</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold  py-0.5 px-1.5">CODIGO: VERSION 1.0</td>
                </tr>
                <tr>
                    <td class="border border-black bg-black h-5 text-white text-center text-[10pt] " colspan="3">
                        ESPECIFICACIONES</td>
                </tr>
            </tbody>
        </table>
        <div class="flex items-center justify-center pt-4 gap-40">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">EMPLEADO: </label>
                <input type="text"
                    class="text-left text-[9pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO: </label>
                <input type="text"
                    class="text-left text-[9pt] w-52 text-sm font-medium capitalize h-5 border-x-0 border-t-0">
            </div>
        </div>
        <div class="flex items-center justify-center pt-2 gap-20 pb-7">
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
        </div>

        <div class="max-h-72 overflow-y-auto">

            <table class="w-full border border-black border-collapse">

                <thead>
                    <tr>
                        <td class="bg-black text-white text-center py-2 text-[10pt]" colspan="5">
                            REGISTRO DE INCIDENTES CRITICOS DEN DESEMPEÑO PARA PERSONAL ADMINISTRATIVO
                        </td>
                    </tr>
                    <tr class="bg-gray-200 text-gray-700 text-xs">
                        <th class="border border-black font-semibold text-center py-1" rowspan="2" style="min-width: 20px;">
                            #
                        </th>
                        <th class="border border-black font-semibold text-center" rowspan="2" style="min-width: 80px;">
                            Fecha
                        </th>
                        <th class="border border-black font-semibold text-center" rowspan="2" style="min-width: 80px;">
                            N° Factor
                        </th>
                        <th class="border border-black font-semibold text-center py-1" style="min-width: 40px;">
                            F / D
                        </th>
                        <th class="border border-black font-semibold text-center" rowspan="2" style="min-width: 200px;">
                            Descripción del evento
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(data, i) in incidentesEvaluaciones" :key="i">
                        <tr v-if="!data.isDelete">
                            <td class="border border-black text-center px-1 text-xs">
                                <svg class="h-7 w-7 cursor-pointer" viewBox="0 0 1024.00 1024.00" fill="#ff0000"
                                    stroke="#ff0000" @click="delteIncident(data.id)" stroke-width="23.552">
                                    <path
                                        d="M512 897.6c-108 0-209.6-42.4-285.6-118.4-76-76-118.4-177.6-118.4-285.6 0-108 42.4-209.6 118.4-285.6 76-76 177.6-118.4 285.6-118.4 108 0 209.6 42.4 285.6 118.4 157.6 157.6 157.6 413.6 0 571.2-76 76-177.6 118.4-285.6 118.4z m0-760c-95.2 0-184.8 36.8-252 104-67.2 67.2-104 156.8-104 252s36.8 184.8 104 252c67.2 67.2 156.8 104 252 104 95.2 0 184.8-36.8 252-104 139.2-139.2 139.2-364.8 0-504-67.2-67.2-156.8-104-252-104z"
                                        fill=""></path>
                                    <path d="M707.872 329.392L348.096 689.16l-31.68-31.68 359.776-359.768z" fill=""></path>
                                    <path d="M328 340.8l32-31.2 348 348-32 32z" fill=""></path>

                                </svg>
                            </td>
                            <td class="border border-black text-center text-xs px-4">{{ moment().format('L') }}</td>
                            <td class="border border-black text-center w-52">
                                <div class="relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="seleccione"
                                        v-model="incidentesEvaluaciones[i].id_cat_rendimiento" :classes="{
                                            placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-black rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ',
                                            noOptions: 'py-2 px-3 text-gray-600 bg-white text-left text-[8pt]',
                                            optionsContainer: 'custom-options-container absolute z-50'// Añade una clase personalizada para el contenedor de opciones
                                        }" :options="optionsFactor" :searchable="true" noOptionsText="sin opciones"
                                        noResultsText="sin resultado" />
                                </div>
                            </td>
                            <td class="border border-black text-center " style="outline: none;">
                                <select v-model="incidentesEvaluaciones[i].resultado_incidente_evaluacion"
                                    class="text-[9pt] py-0.5">
                                    <option value="-"> - </option>
                                    <option value="">F</option>
                                    <option value="">D</option>
                                </select>
                            </td>
                            <td class="border border-black text-center w-96 max-w-96 text-xs px-4"
                                @input="incidentesEvaluaciones[i].comentario_incidente_evaluacion = $event.target.innerText"
                                contenteditable="true" style="outline: none;">
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class=" flex  justify-start  content-between my-3" @click="pushToIncidentes">
            <div>
                <GeneralButton :color="['bg-slate-700 hover:bg-slate-800']" text="Agregar incidente" icon="add" />
            </div>
            <div class="px-2">
                <GeneralButton :color="['bg-orange-700 hover:bg-orange-800']" text="Enviar documento" icon="update" />
            </div>

        </div>

    </div>
</template>

<script>
import { v4 as uuid, v4 } from "uuid";
import momentAlias from 'moment';
export default {
    props: [],
    data() {
        return {
            incidentesEvaluaciones: [],
            optionsFactor: [
                { value: '1', label: '1. Calidad de trabajo' },
                { value: '2', label: '2. Productividad' },
                { value: '3', label: '3. Responsabilidad' },
                { value: '4', label: '4. Iniciativa y Creatividad' },
                { value: '5', label: '5. Cumplimiento de normas e instruccciones' },
                { value: '6', label: '6. Relaciones Laborales' },
                { value: '7', label: '7. Discrecion' },
            ],
        }
    },
    methods: {
        pushToIncidentes() {
            this.incidentesEvaluaciones.push({
                id: v4(),
                id_cat_rendimiento: '',
                resultado_incidente_evaluacion: '',
                comentario_incidente_evaluacion: '',
                isDelete: false,
            })
        },
        delteIncident(row) {
            const index = this.incidentesEvaluaciones.findIndex(i => i.id == row)
            this.incidentesEvaluaciones[index].isDelete = true
        }
    }

}
</script>

<style>
.custom-options-container {
    width: 200px;
    /* Ajusta este valor según sea necesario */
}
</style>