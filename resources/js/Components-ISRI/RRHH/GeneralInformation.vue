<script setup>
import moment from 'moment';
import AccordionBasicVue from '@/Components-ISRI/AccordionBasic.vue';
</script>
<template>
    <div class="flex flex-col xl:flex-row xl:space-x-16" v-show="showInformation">
        <div class="space-y-4 mb-8 xl:mb-0">

            <h2 class="text-slate-800 font-semibold">Informacion general del empleado</h2>
            <div>
                <div class="flex flex-col md:flex-row  md:space-y-0">
                    <div class="flex-1 w-full">
                        <div v-if="moreInformacionEmployee.empleado">
                            <article class="bg-white shadow-md rounded border border-slate-200 p-5 mb-1"
                                v-for="plaza in  moreInformacionEmployee.empleado.plazas_asignadas || []" :key="plaza">
                                <div class="flex flex-start space-x-4">
                                    <div class="grow">
                                        <h2 class="font-semibold text-slate-800 mb-2">
                                            <div class="">8-ADMINISTRADOR DE CENTRO</div>
                                        </h2>
                                        <div class="flex gap-2">
                                            <h1 class="text-sm">Salario base: <span class="font-semibold">$750.99</span>
                                            </h1>
                                            <h1 class="text-sm">Salario base: <span class="font-semibold">$2500.50</span>
                                            </h1>
                                        </div>
                                        <footer class="flex flex-wrap text-sm">
                                            <div class="my-1.5 mr-1">
                                                <!-- Start -->
                                                <div
                                                    class="text-sm inline-flex font-medium bg-rose-100 text-rose-600  text-center px-2.5 py-1">
                                                    En ejecucion</div>
                                                <!-- End -->
                                            </div>
                                            <div
                                                class="flex items-center after:block after:content-['·'] last:after:content-[''] after:text-sm after:text-slate-400 after:px-2">
                                                <span class="text-slate-500">Asignada en: Lunes, 17 marzo, 2021</span>
                                            </div>
                                        </footer>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div>
                            <AccordionBasicVue title="Accordion Title">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et
                                dolore magna aliqua. Ut enim ad minim veniam quis. Ut enim ad minim veniam quis. Lorem ipsum
                                dolor sit
                                amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                                magna aliqua.
                            </AccordionBasicVue>
                        </div>
                    </div>
                    <div class="px-4 py-2 rounded-md overflow-x-auto">
                        <div v-if="moreInformacionEmployee.empleado">
                        <aside class="xl:min-w-56 xl:w-60 space-y-3">
                            <div class="text-sm">
                                <h3 class="font-medium text-slate-800 uppercase">Profesion en: </h3>
                                <div class="uppercase font-semibold">{{ moreInformacionEmployee.profesion.nombre_profesion
                                }}</div>
                            </div>
                            <div class="text-sm">
                                <h3 class="font-medium uppercase text-slate-800">Genero</h3>
                                <div class="uppercase font-semibold"> {{ moreInformacionEmployee.id_genero }}</div>
                            </div>
                            <div class="text-sm">
                                <div class="flex">
                                    <div class="font-medium uppercase text-slate-800 pr-2">Dui: </div>
                                    <div class="uppercase font-semibold">{{ moreInformacionEmployee.dui_persona }}</div>
                                </div>
                            </div>
                            <div class="text-sm">
                                <h3 class="font-medium uppercase text-slate-800">Telefono</h3>
                                <div class="uppercase font-semibold">{{ moreInformacionEmployee.telefono_persona }}</div>
                            </div>
                        </aside>
                        </div>
                        
                    </div>

                </div>
            </div>
            <pre>
<!--         {{ moreInformacionEmployee.empleado.plazas_asignadas }} -->
    </pre>
        </div>


    </div>
</template>

<script>
export default {
    props: {
        showInformation: {
            type: Boolean,
            default: false,
        },
        moreInformacionEmployee: {
            type: Object,
            default: [],
        },

    },
}
</script>

<style></style>