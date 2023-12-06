<template>
    <ProcessModal maxWidth="3xl" :show="showPlazasModal" :center="true">
        <div class="grow">
            <div class="p-6 space-y-6">

                <section>
                    <div class="mb-8">
                        <h2 class="text-2xl text-slate-800 font-bold mb-4">Evaluaciones Disponibles</h2>
                        <div class="text-sm">
                            En la dependencia seleccionada, existen {{ Object.keys(objectEvaluaciones).length }} tipos de
                            plazas únicas.
                            Por favor, elija una evaluación correspondiente a las plazas que desea evaluar.
                        </div>
                    </div>

                    <div class="grid gap-6"
                        :class="Object.keys(objectEvaluaciones).length == 2 ? ' grid-cols-8' : ' grid-cols-12'">
                        <div v-for="(evaluacion_rendimiento, i) in objectEvaluaciones  " :key="i"
                            class="relative col-span-full xl:col-span-4 bg-white shadow-md rounded-sm border border-slate-200 flex-grow hover:scale-105 transform transition-all duration-500 cursor-pointer">
                            <div class="absolute top-0 left-0 right-0 h-0.5 "
                                :class="{ 'bg-emerald-500': evaluacion_rendimiento.id_evaluacion_rendimiento == 3, 'bg-sky-500': evaluacion_rendimiento.id_evaluacion_rendimiento == 2, 'bg-indigo-500': evaluacion_rendimiento.id_evaluacion_rendimiento == 1 }"
                                aria-hidden="true"></div>
                            <div class="px-5 pt-5 pb-6 border-b border-slate-200">
                                <header class="flex items-center mb-2">
                                    <div :class="{ 'from-emerald-500 to-emerald-300': evaluacion_rendimiento.id_evaluacion_rendimiento == 3, 'from-sky-500 to-sky-300': evaluacion_rendimiento.id_evaluacion_rendimiento == 2, 'from-indigo-500 to-indigo-300': evaluacion_rendimiento.id_evaluacion_rendimiento == 1 }"
                                        class="w-6 h-6 rounded-full shrink-0 bg-gradient-to-tr  mr-3">
                                        <svg class="w-6 h-6 fill-current text-white" viewBox="0 0 24 24">
                                            <path
                                                d="M12 17a.833.833 0 01-.833-.833 3.333 3.333 0 00-3.334-3.334.833.833 0 110-1.666 3.333 3.333 0 003.334-3.334.833.833 0 111.666 0 3.333 3.333 0 003.334 3.334.833.833 0 110 1.666 3.333 3.333 0 00-3.334 3.334c0 .46-.373.833-.833.833z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg text-slate-800 font-semibold">{{
                                        evaluacion_rendimiento.codigo_evaluacion_rendimiento }}</h3>
                                </header>
                                <div class="text-sm mb-2 capitalize">
                                    {{ evaluacion_rendimiento.nombre_evaluacion_rendimiento.toLowerCase() }}
                                </div>
                            </div>
                            <div class="px-5 pt-4 pb-5">
                                <div class="text-xs text-slate-800 font-semibold uppercase mb-4">Plazas</div>
                                <ul>
                                    <template v-for="(plaza, j) in objectPlazas" :key="j">
                                        <li class="flex items-center py-1 "
                                            v-if="plaza.detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos[0].id_evaluacion_rendimiento == evaluacion_rendimiento.id_evaluacion_rendimiento">
                                            <svg class="w-3 h-3 shrink-0 fill-current text-emerald-500 mr-2"
                                                viewBox="0 0 12 12">
                                                <path
                                                    d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z">
                                                </path>
                                            </svg>
                                            <div class="text-sm">{{ plaza.detalle_plaza.plaza.nombre_plaza }}</div>
                                            <div class="text-sm">
                                                <pre>{{ plaza.detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos[0].id_evaluacion_rendimiento }}</pre>
                                            </div>
                                        </li>
                                    </template>

                                </ul>
                            </div>
                        </div>

                    </div>

                </section>
                <section>
                    <div
                        class="px-5 py-3 bg-indigo-50 border border-indigo-100 rounded-sm text-center xl:text-left xl:flex xl:flex-wrap xl:justify-between xl:items-center">
                        <div class="text-slate-800 font-semibold mb-2 xl:mb-0">¿La plaza no se encuentra?</div>
                        <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white"
                            @click="$emit('choose-another')">Escoger otra dependencia</button>
                    </div>
                </section>
            </div>
        </div>
    </ProcessModal>
</template>

<script>
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
export default {
    props: {
        showPlazasModal: { type: Boolean, default: false, },
        objectPlazas: { type: Object, default: () => { }, },
        objectEvaluaciones: { type: Object, default: () => { }, },
    },
    emit: ["choose-another"],
    components: { ProcessModal },
    setup() {
        return {
            originalText: "FORMULARIO DE EVALUACIÓN DEL DESEMPEÑO PARA PERSONAL DE JEFATURAS.",
        };
    },
}
</script>

<style></style>