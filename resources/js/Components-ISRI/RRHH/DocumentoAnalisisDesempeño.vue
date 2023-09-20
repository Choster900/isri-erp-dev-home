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
                <tr v-for="i in incidentesEvaluaciones" :key="i">
                    <td class="border border-black text-center px-4 text-xs">99</td>
                    <td class="border border-black text-center text-xs px-4">9/18/2023</td>
                    <td class="border border-black text-center w-52">
                        <div class="relative flex h-8 w-full flex-row-reverse ">
                            <Multiselect placeholder="seleccione" :classes="{
                                placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-black rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ',
                                noOptions: 'py-2 px-3 text-gray-600 bg-white text-left text-[8pt]',
                                optionsContainer: 'custom-options-container' // Añade una clase personalizada para el contenedor de opciones
                            }" :options="[
                                    { value: '1', label: 'Calidad de trabajo' },
                                    { value: '2', label: 'Productividad' },
                                    { value: '3', label: 'Responsabilidad' },
                                    { value: '4', label: 'Iniciativa y Creatividad' },
                                    { value: '5', label: 'Cumplimiento de normas e instruccciones' },
                                    { value: '6', label: 'Relaciones Laborales' },
                                    { value: '7', label: 'Discrecion' },
                                ]" :searchable="true" noOptionsText="sin opciones" noResultsText="sin resultado" />
                                                        </div>
                    </td>
                    <td class="border border-black text-center px-4" contenteditable="true" style="outline: none;">D</td>
                    <td class="border border-black text-center w-96 max-w-96 text-xs px-4" contenteditable="true"
                        style="outline: none;">
                        TEXTO
                    </td>
                </tr>
            </tbody>
        </table>
        <button @click="pushToIncidentes">add</button>


    </div>
</template>

<script>
export default {
    props: [],
    data() {
        return {
            incidentesEvaluaciones: [],
        }
    },
    methods: {
        pushToIncidentes() {
            this.incidentesEvaluaciones.push({
                nFactor: '',
                dOF: '',
                descripcionEvento: ''
            })
        }
    }

}
</script>

<style>


.custom-options-container {
    width: 200px; /* Ajusta este valor según sea necesario */
}

</style>