<template>
    <template>
        <div class="m-1.5">
            <!-- Componente del modal ProcessModal -->
            <ProcessModal maxWidth="3xl" :show="showModal" @close="$emit('cerrar-modal')">
                <div class=" flex  justify-center pt-2 content-between">
                    <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                        @click="$emit('cerrar-modal')">
                        <path fill="currentColor"
                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                    </svg>

                </div>
                <div class="mx-6 mt-2"></div>
                <div class="mx-4 overflow-y-auto h-[650px] py-3 pl-2 pr-4 mt-4">
                    <table class="w-[710px] sheet0 " border="0" cellpadding="0" cellspacing="0">
                        <col class="col0">
                        <col class="col1">
                        <col class="col2">
                        <col class="col3">
                        <col class="col4">
                        <col class="col5">
                        <col class="col6">
                        <col class="col7">
                        <tbody>
                            <tr>
                                <td class="border border-black w-40" rowspan="2">
                                    <div class="w-40 p-1.5">
                                        <img src="../../../../img/isri-gob.png" alt="logotipo-isri" draggable="false">
                                    </div>
                                </td>
                                <td class="border text-sm border-black text-center font-medium uppercase" colspan="3">

                                    <div class="flex items-center justify-center">
                                        <div class="relative text-center">
                                            UCP DEL INSITUTO SALVADOREÑO DE REHABILITACION INTEGRAL
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-5 h-5 ml-2 stroke-slate-400 hover:stroke-slate-500 cursor-pointer">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                        </svg>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td class="border text-sm border-black text-center font-medium px-8" colspan="3">
                                    ORDEN DE COMPRA DE BIENES Y SERVICIOS
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-black  bg-black h-5 border-r-white text-white text-center text-[9pt] "
                                    colspan="3">
                                    RAZON SOCIAL DEL SUMINISTRANTE
                                </td>
                                <td class="border border-black bg-black h-5 text-white text-center  text-[9pt] ">
                                    NIT
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-black  h-5  " colspan="3">
                                    <!--  <Multiselect :filter-results="false" :searchable="true" :clear-on-search="true"
                                        :min-chars="1" placeholder="Busca proveedores por nombre o razon social" :classes="{
                                            placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',

                                        }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                        :options="[{ value: 1, label: 'CIA DE ALUMBRADO ELECTRICO DE SAN SALVADOR S.A DE C.V' }]" /> -->
                                    <h1 class="text-xs font-medium text-center ">CIA DE ALUMBRADO ELECTRICO DE SAN SALVADOR
                                        S.A DE C.V</h1>
                                </td>
                                <td class="border border-black  h-5  text-center text-[9pt] ">
                                    0614-071017-102-3
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <div id="especificaciones" class="mb-4">
                        <div class="flex items-center justify-between pt-4 ">
                            <div class="flex items-center">
                                <label for="" class="text-[7pt] font-semibold mr-2">LUGAR Y FECHA: </label>
                                <input type="text" value="SAN SALVADOR 20 DE NOVIEMBRE DEL 2023"
                                    class="w-96 text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </div>
                            <div class="flex items-center">
                                <label for="" class="text-[7pt] font-semibold mr-2">N°.ORDEN:</label>

                                <input type="text" value="187/2023"
                                    class="w-40 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </div>
                        </div>

                    </div>

                    <table class="w-[710px]" border="0" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr class="*:text-[8pt] *:bg-black *:text-white *:px-2 *:py-0.5 *:font-normal *:border--white">
                                <th class="border border-black border-r-white border-b-white">CANTIDAD</th>
                                <th class="border border-black border-r-white border-b-white" colspan="2">U/MEDIDAS</th>
                                <th class="border border-black border-r-white border-b-white">ESPECIFICO</th>
                                <th class="border border-black border-r-white border-b-white">DEPENDENCIA</th>
                                <th class="border border-black border-r-white border-b-white">PRODUCTO
                                </th>
                                <th class="border border-black border-r-white w-40 border-b-white" colspan="2">DESCRIPCION
                                </th>
                                <th class="border border-black border-r-white">PRECIO UNITARIO</th>
                                <th class="border border-black ">VALOR TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="*:border-black">
                                <td colspan="2"
                                    class="uppercase border bg-black text-[8pt] text-white border-black border-t-white text-center">
                                    Linea de trabajo:</td>
                                <td colspan="3" class="border border-t-black">
                                    <Multiselect :filter-results="false" :searchable="true" :clear-on-search="true"
                                        :min-chars="1" placeholder="-" :classes="{
                                            placeholder: ' flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
                                            container: 'relative mx-auto w-full h-6  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                            singleLabel: 'pr-14 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                            optionSelected: 'text-white bg-[#001c48]',
                                            optionPointed: 'text-gray-800 bg-gray-100',
                                        }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                        :options="[{ value: 1, label: 'CIA DE ALUMBRADO ELECTRICO DE SAN SALVADOR S.A DE C.V' }]" />
                                </td>
                                <td colspan="2"
                                    class="uppercase border bg-black text-[8pt] text-white border-black text-center">
                                    Doc. Adquisición:</td>
                                <td colspan="4" class="border border-black">
                                    <Multiselect :filter-results="false" :searchable="true" :clear-on-search="true"
                                        placeholder="-" :min-chars="1" :classes="{
                                            placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
                                            container: 'relative mx-auto w-full h-6  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                            singleLabel: 'pr-14 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                            optionSelected: 'text-white bg-[#001c48]',
                                            optionPointed: 'text-gray-800 bg-gray-100',
                                        }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                        :options="[{ value: 1, label: 'CIA DE ALUMBRADO ELECTRICO DE SAN SALVADOR S.A DE C.V' }]" />
                                </td>
                            </tr>
                            <tr class="*:text-[8pt]  *:px-2 *:py-0.5 *:font-normal *:border *:border-black hover:bg-slate-200"
                                v-for="i in 2" :key="i">
                                <td class=" w-12 relative ">
                                    <input type="number"
                                        class="absolute top-0 left-0 w-full bg-transparent border-none pl-2 text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                                        placeholder="0" min="0" max="1000" />
                                </td>



                                <td class=" relative h-28" style="padding-left: 0 !important; padding-right: 0 !important; "
                                    colspan="2">
                                    <div class="absolute top-0 w-full flex flex-col items-center">
                                        <Multiselect :filter-results="false" :searchable="false" :canClear="false"
                                            :clear-on-search="true" :min-chars="1" :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
                                                container: 'relative mx-auto w-full h-6  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                singleLabel: ' text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                optionSelected: 'text-white bg-[#001c48]',
                                                optionPointed: 'text-gray-800 bg-gray-100',
                                            }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                            :options="[{ value: 1, label: 'KG' }, { value: 2, label: 'T' }, { value: 3, label: 'ONZ' }]" />
                                        <span class="text-center">
                                            -
                                        </span>
                                    </div>
                                </td>
                                <!-- Para el span -->
                                <td class=" relative text-center">
                                    <span class="absolute top-1 left-0 w-full flex flex-col items-center">4111</span>
                                </td>
                                <td class=" relative h-20 w-24"
                                    style="padding-left: 0 !important; padding-right: 0 !important; ">
                                    <div class="absolute top-0 w-full flex flex-col items-center">
                                        <Multiselect :filter-results="false" :searchable="false" :canClear="false"
                                            :clear-on-search="true" :min-chars="1" :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
                                                container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                singleLabel: 'pr-6 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                optionSelected: 'text-white bg-[#001c48]',
                                                optionPointed: 'text-gray-800 bg-gray-100',
                                            }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                            :options="[{ value: 1, label: 'ADMON' }, { value: 2, label: 'CAA' }, { value: 3, label: 'CAL' }]" />
                                        <span class="text-center mx-10 px-10">-</span>
                                    </div>
                                </td>
                                <td class=" relative h-20 w-28"
                                    style="padding-left: 0 !important; padding-right: 0 !important; ">
                                    <div class="absolute top-0 w-full flex flex-col items-center">

                                        <Multiselect :filter-results="false" :searchable="false" :canClear="false"
                                            :clear-on-search="true" :min-chars="1" :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
                                                container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                singleLabel: 'pr-1 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                singleLabelText: 'text-center overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full',
                                                option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                optionSelected: 'text-white bg-[#001c48]',
                                                optionPointed: 'text-gray-800 bg-gray-100',
                                            }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                            :options="[{ value: 1, label: '61102' }, { value: 2, label: '62201' }, { value: 3, label: '9999999' }]" />

                                        <span class="text-center ">-</span>
                                    </div>
                                </td>
                                <td class=" w-72 p-0 uppercase" colspan="2" contenteditable="true">
                                </td>
                                <td class=" relative text-center">
                                    <div class="flex justify-center space-x-1 absolute top-0 left-0 ">
                                        <!--  <div class="flex items-center">
                                            <span>$</span>
                                        </div> -->
                                        <input type="text"
                                            class="w-full bg-transparent border-none  text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                                            placeholder="$00.00" min="0" max="1000" />
                                    </div>
                                </td>
                                <td class=" relative text-center w-20">
                                    <span class="absolute top-1 left-0 w-full flex flex-col items-center">$1287.32</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <!--  </div> -->
            </ProcessModal>
        </div>
    </template>
</template>

<!-- Este es un componente Vue.js que importa el componente ProcessModal -->
<script>
// Importa el componente ProcessModal desde la ruta relativa '@/Components-ISRI/AllModal/ProcessModal.vue'
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import { ref } from 'vue';

// Define el componente Vue.js
export default {
    // Indica que este componente utiliza el componente ProcessModal
    components: { ProcessModal },

    // Define un array de eventos personalizados que puede emitir este componente
    emit: ["cerrar-modal"],

    // Define las propiedades que este componente puede recibir
    props: {
        // La propiedad showModal es de tipo Booleano utilizado para manejar estado del modal y su valor predeterminado es falso
        showModal: {
            type: Boolean,
            default: false,
        },
    },
    // Configuración del componente utilizando el nuevo sistema de configuración de Vue 3
    setup() {
        const arrayProductoAdquisicion = ref([
            {
                idLt: 0,
                idDetDocAdquisicion: 1,

            }
        ])

        /**
         * Obtener todas las lineas de trabajos
         *
         * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
         */
        const getAllLineaTrabajo = async () => {
            try {
                const resp = await axios.post("/get-all-linea-trabajo");
                console.log(resp);
            } catch (error) {
                reject(error);
                console.error("Error en la creación de la evaluación personal:", error);
                throw new Error("Error en la creación de la evaluación personal");
            }

        };
        getAllLineaTrabajo()
        return {
            arrayProductoAdquisicion,
        };
    },
}
</script>

<style>
table.sheet0 col.col0 {
    width: 116.37777658pt
}

table.sheet0 col.col1 {
    width: 281.06666592pt
}

/* Define las clases de animación */
@keyframes slideOutRight {
    0% {
        opacity: 1;
        transform: translateX(0);
    }

    100% {
        opacity: 0;
        transform: translateX(100%);
    }
}

@keyframes slideInLeft {
    0% {
        opacity: 0;
        transform: translateX(-100%);
    }

    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Agrega estas clases a tu elemento */
.animate-slide-out-right {
    animation: slideOutRight 0.5s ease both;
}

.animate-slide-in-left {
    animation: slideInLeft 0.5s ease both;
}

/* Estilos para la barra de desplazamiento en Webkit (Chrome, Safari) */
::-webkit-scrollbar {
    width: 10px;
    /* Ancho de la barra de desplazamiento */
}

::-webkit-scrollbar-thumb {
    background-color: #c1c2c5;
    /* Color de la manija de desplazamiento */
    border-radius: 5px;
    /* Radio de la manija de desplazamiento */
}

::-webkit-scrollbar-track {
    background-color: #f3f4f6;
    /* Color del fondo de la barra de desplazamiento */
}

td {
    outline: none;
    /* Desactiva el marco de enfoque */
}

#tabla_imprentas td {
    padding: 0 !important;
    margin: 0 !important;
}
</style>
