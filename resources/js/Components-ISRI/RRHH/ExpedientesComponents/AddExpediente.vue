<template>
    <div id="addSection" class="mb-4 px-4">
        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 border rounded-lg bg-slate-100 p-3">
            <!-- <div class="w-full md:w-3/4"> -->
            <div class="w-full md:w-3/5">

                <template v-if="!nameArchivoAnexo || !objectAnexoFileForUpdate">
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
                    <InputError class="mt-2" :message="errorsData.fileArchivoAnexo" />
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
                            <svg class="w-5" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M5 21V3.90002C5 3.90002 5.875 3 8.5 3C11.125 3 12.875 4.8 15.5 4.8C18.125 4.8 19 3.9 19 3.9V14.7C19 14.7 18.125 15.6 15.5 15.6C12.875 15.6 11.125 13.8 8.5 13.8C5.875 13.8 5 14.7 5 14.7"
                                    stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                            <div class="font-medium text-base ">Detalles del anexo</div>
                        </div>
                        <div class="flex items-center">
                            <button @click="createArchivoAnexo(selectedValue)" v-if="actionInProgress == 'add'"
                                class="border border-green-900 bg-green-800 rounded-lg  text-center  text-white-900 text-white text-xs font-semibold w-full py-1 px-2">Enviar
                                la información</button>

                            <button @click="updateArchivoAnexo" v-else
                                class="border border-orange-900 bg-orange-800 rounded-lg  text-center  text-white-900 text-white text-xs font-semibold w-full py-1 px-2">Modificar
                                la información</button>
                        </div>
                    </div>

                    <div class="max-w-2xl mx-auto">
                        <textarea id="description" rows="4" v-model="descripcionArchivoAnexo"
                            class="block p-2.5 w-full text-xs text-gray-900  rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                            placeholder="Descripción del archivo..."></textarea>

                        <p class="mt-5 text-xs pb-4">Puedes poner un breve resumen o descripcion del archivo que estas por agregar.</p>
                    </div>

                    <div class="mb-1 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <div class="relative flex h-8 w-full flex-row-reverse">
                                <Multiselect v-if="!opcionPersona || opcionPersona == ''" v-model="idPersona"
                                    @select="getPersonasById($event)" :filter-results="false" :resolve-on-load="false"
                                    :delay="1000" :searchable="true" :clear-on-search="true" :min-chars="5"
                                    placeholder="Busqueda de usuario..." :classes="{
                                        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                    }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                    noResultsText="<p class='text-xs'>Sin resultados de personas <p>" :options="async function (query) {
                                        return await getPeopleByName(query)
                                    }" />
                                <Multiselect v-else v-model="selectedValue" :filter-results="false" :disabled="true"
                                    :resolve-on-load="false" :delay="1000" :searchable="true" :clear-on-search="true"
                                    :min-chars="5" placeholder="Busqueda de usuario..." :classes="{
                                        containerDisabled: 'cursor-not-allowed bg-gray-500 text-white',
                                        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                    }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                    noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                    :options="opcionPersona" />
                            </div>
                            <InputError class="mt-2 px-2" :message="errorsData.idPersona" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <div class="relative flex h-8 w-full flex-row-reverse">
                                <Multiselect @deselect="idTipoArchivoAnexo = ''" v-model="idTipoArchivoAnexo"
                                    placeholder="Seleccione el tipo de anexo..." :classes="{
                                        containerDisabled: 'cursor-not-allowed bg-gray-200 ',
                                        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                    }
                                        " noOptionsText="<p class='text-xs'>sin contratos<p>"
                                    noResultsText="<p class='text-xs'>contrato no encontrados<p>"
                                    :options="opcionesTipoArchivo" :searchable="true" />

                            </div>
                            <InputError class="mt-2 px-2" :message="errorsData.idTipoArchivoAnexo" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-2/5">
                <SideInfo :fileName="nameArchivoAnexo" @delete-file="delteArchivoAnexo(idArchivoAnexo)"
                    :persona="objectPersona" :isLoading="isLoadingRequestPersona" :ableToEdit="selectedValue" />
            </div>
        </div>

    </div>
</template>
  

<script>
import SideInfo from './SideInfoPersona.vue';
import { computed, onMounted, ref, toRefs, watch, watchEffect } from 'vue';
import { usePersona } from '@/Composables/RRHH/Persona/usePersona';
import { useFileHandling } from '@/Composables/RRHH/Expediente/useFileHandling'
import { useArchivoAnexo } from '@/Composables/RRHH/Expediente/useArchivoAnexo'

export default {
    props: {
        persona: {
            type: Object,
            default: () => { },
        },
        opcionPersona: { type: Object, default: () => { } },
        actionInProgress: { type: Object, default: () => { } },
        tipoArchivoAnexo: { type: Object, default: () => { }, },
        objectAnexoFileForUpdate: { type: Object, default: () => { } },
    },
    name: 'AddExpediente',
    emits: ["refresh-information", "cerrar-modal"],
    components: { SideInfo },

    setup(props, context) {
        const { objectAnexoFileForUpdate, actionInProgress, persona } = toRefs(props);
        // Composable para manejar los eventos de los archivos
        const { file, fileInput, handleDrop, deleteFile, openFileInput, urlArchivoAnexo, handleDragOver, handleFileChange, nameArchivoAnexo, sizeArchivo, tipoMine } = useFileHandling();
        // Composable usePersona => getPeopleByName() retorna el objeto con las personas buscada en multiselect
        const { getPeopleByName } = usePersona();
        // Composable useArchivoAnexo 
        const { idPersona, idTipoMine, sizeArchivoAnexo, idArchivoAnexo, idTipoArchivoAnexo, fileArchivoAnexo, nombreArchivoAnexo, descripcionArchivoAnexo, createArchivoAnexo, updateArchivoAnexo, errorsData, delteArchivoAnexoRequest, delteArchivoAnexo, getPersonasById, dataArrayPersona, isLoadingRequestPersona } = useArchivoAnexo(context);

        const objectPersona = ref(null)

        watch(persona, () => {
            objectPersona.value = persona.value
        })

        /**
         * Propiedad computada que genera un objeto con un array para obtener el id y el nombre de la persona seleccionada 
         * Esto se usa cuando estamos editando y queremos setear el id de la persona actual
         */
        const selectedValue = computed(() => {
            if (props.persona) {
                idPersona.value = props.opcionPersona[0].value
                return props.opcionPersona != '' ? props.opcionPersona[0].value : null;
            } else {
                idPersona.value = '';
                return props.value;
            }
        });

        /**
         * Propiedad computada que retorna objeto con todos las opciones de tipo de archivo en la base de datos
         * Para mas referencias buscar en la base de datos la tabla tipo_archivo_anexo
         */
        const tipoArchivoAnexo = ref(props.tipoArchivoAnexo);
        const opcionesTipoArchivo = computed(() => {
            return tipoArchivoAnexo.value.map(item => {
                return {
                    value: item.id_tipo_archivo_anexo,
                    label: item.nombre_tipo_archivo_anexo
                }
            });
        });


        watch(file, () => {
            idTipoMine.value = tipoMine.value == 'application/pdf' ? '1' : '2';
            fileArchivoAnexo.value = file.value
            sizeArchivoAnexo.value = sizeArchivo.value
        }) // Verficia que se seleccione una archivo para asignarlo a fileArchivoAnexo

        watch(dataArrayPersona, async () => {
            objectPersona.value = dataArrayPersona.value
            console.log(objectPersona.value);
        })
        const triggetData = () => {
            const { descripcion_archivo_anexo, id_tipo_archivo_anexo, url_archivo_anexo, nombre_archivo_anexo, id_archivo_anexo } = objectAnexoFileForUpdate.value;
            descripcionArchivoAnexo.value = descripcion_archivo_anexo;
            idArchivoAnexo.value = id_archivo_anexo;
            idTipoArchivoAnexo.value = id_tipo_archivo_anexo;
            urlArchivoAnexo.value = url_archivo_anexo;
            nameArchivoAnexo.value = nombre_archivo_anexo;
        };

        const clearData = () => {
            descripcionArchivoAnexo.value = ''
            idTipoArchivoAnexo.value = ''
            urlArchivoAnexo.value = ''
            idTipoMine.value = ''
            sizeArchivoAnexo.value = ''
            nameArchivoAnexo.value = ''
        }


        watch(actionInProgress, (newValue, oldValue) => {
            if (newValue === 'edit') {
                let objectAnexoFileWatched = false; // Variable de control
                watch(objectAnexoFileForUpdate, (newObject, oldObject) => {
                    if (!objectsAreEqual(newObject, oldObject)) {
                        triggetData();
                    }
                    objectAnexoFileWatched = true; // Marcamos que el watch ya se ejecutó
                });
                if (!objectAnexoFileWatched) {
                    triggetData();
                }
            } else {
                clearData();
            }
        });

        /**
         * 
         * @param {Object} obj1 Objeto de comparacion1
         * @param {Object} obj2 Objeto de comparacion2
         */
        const objectsAreEqual = (obj1, obj2) => {
            // Implementa una lógica personalizada para comparar objetos
            // Aquí, por ejemplo, se convierten a cadenas JSON para la comparación
            return JSON.stringify(obj1) === JSON.stringify(obj2);
        }


        return {
            //persona,
            objectPersona,
            file,
            fileInput,
            dataArrayPersona,
            idPersona,
            handleDrop,
            deleteFile,
            idTipoMine,
            selectedValue,
            openFileInput,
            handleDragOver,
            delteArchivoAnexoRequest,
            delteArchivoAnexo,
            idArchivoAnexo,
            getPeopleByName,
            urlArchivoAnexo,
            nameArchivoAnexo,
            handleFileChange,
            getPersonasById,
            fileArchivoAnexo,
            nombreArchivoAnexo,
            createArchivoAnexo,
            idTipoArchivoAnexo,
            opcionesTipoArchivo,
            descripcionArchivoAnexo,
            isLoadingRequestPersona,
            updateArchivoAnexo,
            actionInProgress,
            errorsData,
        };
    }
}
</script>

<style scoped>
/* Estilos específicos del componente */
</style>
