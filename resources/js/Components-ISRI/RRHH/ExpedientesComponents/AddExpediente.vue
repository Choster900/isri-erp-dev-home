<template>
    <div id="addSection" class="mb-4 px-4">
        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 border rounded-lg bg-slate-100 p-3">
            <!-- <div class="w-full md:w-3/4"> -->
            <div class="w-full md:w-3/5">
                <template v-if="!nameArchivoAnexo">
                    <div class="flex py-4 gap-2">
                        <span class="text-xs font-medium block text-slate-500/70">Adjunta un archivo</span>
                        <span class="text-xs font-medium block pb-3">Selecciona el archivo o arrástralo aquí</span>
                    </div>
                    <div @dragover.prevent="handleDragOver" @drop="handleDrop" @click="openFileInput"
                        class="h-52 w-full border-[3px] cursor-pointer border-dashed border-slate-400 rounded-lg flex flex-col items-center justify-center text-center bg-slate-200 hover:bg-slate-300">
                        <img src="../../../../img/Upload-pana.svg" class="w-36 h-24 md:w-64 md:h-64 -mt-10"
                            alt="Icono de cargar archivo">
                        <input type="file" ref="fileInput" accept=".pdf,.jpeg,.jpg,.png" style="display: none;"
                            @change="handleFileChange">
                    </div>
                </template>
                <template v-if="nameArchivoAnexo">
                    <img v-if="/.(jpg|jpeg|png)$/.test(nameArchivoAnexo)" :src="urlArchivoAnexo" alt=""
                        class="rounded-lg border shadow-md shadow-black">
                    <embed v-else :src="urlArchivoAnexo" type="application/pdf" width="550" height="400"
                        class="rounded-lg border border-gray-300 shadow-md shadow-black">

                </template>
                <div class="p-4 bg-slate-200 rounded-lg mt-4">
                    <div class="flex  justify-between py-2 ">
                        <div class="flex items-center gap-2">
                            <svg class="w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M5 21V3.90002C5 3.90002 5.875 3 8.5 3C11.125 3 12.875 4.8 15.5 4.8C18.125 4.8 19 3.9 19 3.9V14.7C19 14.7 18.125 15.6 15.5 15.6C12.875 15.6 11.125 13.8 8.5 13.8C5.875 13.8 5 14.7 5 14.7"
                                        stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </g>
                            </svg>
                            <div class="font-medium text-base ">Detalles del anexo</div>
                        </div>
                        <div class="flex items-center">
                            <button
                                class="border border-green-900 bg-green-800 rounded-lg  text-center  text-white-900 text-white text-xs font-semibold w-full py-1 px-2">Enviar
                                la información</button>
                        </div>
                    </div>

                    <div class="max-w-2xl mx-auto">
                        <textarea id="description" rows="4"
                            class="block p-2.5 w-full text-xs text-gray-900  rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                            placeholder="Descripción del archivo..."></textarea>
                        <p class="mt-5 text-xs pb-4">This textarea bar component is part of a larger, open-source library of
                            Tailwind CSS components. Learn more by going to the official .</p>
                    </div>

                    <div class="flex gap-4">
                        <div class="relative flex h-8 w-full flex-row-reverse">
                            <Multiselect v-if="opcionPersona == ''" v-model="selectedValue" :filter-results="false"
                                :resolve-on-load="false" :delay="1000" :searchable="true" :clear-on-search="true"
                                :min-chars="5" placeholder="Busqueda de usuario..." :classes="{
                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                }
                                    " noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                noResultsText="<p class='text-xs'>Sin resultados de personas <p>" :options="async function (query) {
                                    return await getPeopleByName(query)
                                }" />

                            <Multiselect v-if="opcionPersona != ''" v-model="selectedValue" :filter-results="false"
                                :disabled="true" :resolve-on-load="false" :delay="1000" :searchable="true"
                                :clear-on-search="true" :min-chars="5" placeholder="Busqueda de usuario..." :classes="{
                                    containerDisabled: 'cursor-not-allowed bg-gray-500 text-white',
                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                }
                                    " noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                :options="opcionPersona" />
                        </div>
                        <div class="relative flex h-8 w-full flex-row-reverse">
                            <Multiselect @deselect="null" @clear="null" @select="null"
                                placeholder="Seleccione el tipo de anexo..." :classes="{
                                    containerDisabled: 'cursor-not-allowed bg-gray-200 ',
                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                }
                                    " noOptionsText="<p class='text-xs'>sin contratos<p>"
                                noResultsText="<p class='text-xs'>contrato no encontrados<p>" :options="opcionesTipoArchivo"
                                :searchable="true" />
                        </div>
                    </div>

                </div>
            </div>
            {{ nombreDeLaImagen }}
            <div class="w-full md:w-2/5">
                <SideInfo :fileName="nombreDeLaImagen" @delete-file="deleteFile" />
            </div>
        </div>

    </div>
</template>
  

<script>
import { computed, onMounted, ref, watch } from 'vue';
import SideInfo from './SideInfoPersona.vue';
import { usePersona } from '@/Composables/RRHH/Persona/usePersona';
import { useFileHandling } from '@/Composables/RRHH/Expediente/useFileHandling'
export default {
    name: 'AddExpediente',
    components: { SideInfo },
    props: {
        tipoArchivoAnexo: {
            type: Object,
            default: () => { },
        },
        persona: {
            type: Object,
            default: () => { },
        },
        opcionPersona: {
            type: Object,
            default: () => { }
        }
    },
    setup(props) {

        const selectedValue = computed(() => {
            if (props.persona) {
                return props.opcionPersona != '' ? props.opcionPersona[0].value : null;
            } else {
                return props.value;
            }
        });

        // Ref para el tipoArchivoAnexo => enviamos la data al select de tipo de archivo anexo
        const tipoArchivoAnexo = ref(props.tipoArchivoAnexo);
        // Propiedad computada en opcionesTipoArchivo para opciones de tipoArchivoAnexo
        const opcionesTipoArchivo = computed(() => {
            return tipoArchivoAnexo.value.map(item => {
                return {
                    value: item.id_tipo_archivo_anexo,
                    label: item.nombre_tipo_archivo_anexo
                }
            });
        });


        const {
            fileInput,
            handleDrop,
            urlArchivoAnexo,
            openFileInput,
            handleDragOver,
            handleFileChange,
            nameArchivoAnexo,
            deleteFile,
        } = useFileHandling();

        const isImage = ref(null)
        const nombreDeLaImagen = ref(null)
        watch(nameArchivoAnexo,() => nombreDeLaImagen.value = nameArchivoAnexo.value)

        const { getPeopleByName } = usePersona();


        return {
            opcionesTipoArchivo,
            getPeopleByName,
            selectedValue,
            nameArchivoAnexo,
            fileInput,
            handleDrop,
            urlArchivoAnexo,
            openFileInput,
            handleDragOver,
            handleFileChange,
            isImage,
            deleteFile,
            nombreDeLaImagen,
        };
    }
}
</script>

<style scoped>
/* Estilos específicos del componente */
</style>
