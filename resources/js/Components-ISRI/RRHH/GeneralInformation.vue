<script setup>
import moment from 'moment';
import AccordionBasicVue from '@/Components-ISRI/AccordionBasic.vue';
</script>
<template>
    <div class="flex flex-col xl:flex-row xl:space-x-16" v-show="showInformation">
        <div class="space-y-4 mb-8 xl:mb-0">
            <h2 class="text-slate-800 font-semibold">Work History</h2>
            <div>
                <div class="flex flex-col md:flex-row  md:space-y-0">
                    <div class="flex-1 w-full">
                        <div v-if="moreInformacionEmployee.empleado">
                            <div>
                                <div class="bg-white p-4 border border-slate-200 rounded-sm shadow-sm">
                                    <ul class="space-y-3">
                                        <!-- Item -->
                                        <li class="sm:flex sm:items-center sm:justify-between" v-for="job,i in moreInformacionEmployee.empleado.plazas_asignadas" :key="i"> 
                                            <div class="sm:grow flex items-center text-sm">
                                                <!-- Icon -->
                                                <lord-icon src="https://cdn.lordicon.com/twprnhsj.json" trigger="hover"
                                                    colors="primary:#000000,secondary:#4030e8"
                                                    style="width:50px;height:50px">
                                                </lord-icon>
                                                <!-- Position -->
                                                <div>
                                                    <div class="font-medium text-slate-800">
                                                        {{
                                                        `${job.id_plaza_asignada} - ${job.detalle_plaza.plaza.nombre_plaza}`
                                                        }}
                                                        </div>
                                                    <div class="flex flex-nowrap items-center space-x-2 whitespace-nowrap">
                                                        <div>{{ job.estado_plaza_asignada==1?'Actual':'Ejecutada' }}</div>
                                                        <div class="text-slate-400">·</div>
                                                        <div>{{ moment(job.fecha_plaza_asignada, 'YYYY-MM-DD').format('MMMM D, YYYY') }} - {{  job.fecha_mod_plaza_asignada ? moment(job.fecha_mod_plaza_asignada, 'YYYY-MM-DD').format('MMMM D, YYYY') : 'Ahora'}}</div>
                                                    </div>
                                                </div>
                                            </div>  
                                            <!-- Tags -->
                                            <div class="sm:ml-2 mt-2 sm:mt-0">
                                                <ul class="flex flex-wrap sm:justify-end -m-1">
                                                    <li class="m-1">
                                                        <button
                                                            class="inline-flex items-center justify-center text-xs font-medium leading-5 rounded-full px-2.5 py-0.5 border border-slate-200 hover:border-slate-300 shadow-sm bg-white text-slate-500 duration-150 ease-in-out">
                                                        {{ job.dependencia.codigo_dependencia }}
                                                        </button>
                                                    </li>
                                                    <li class="m-1">
                                                        <DropDownOptions position="right-up">
                                                            <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                                                <div class="font-semibold text-xs">
                                                                    
                                                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Molestias perspiciatis dolores consequatur aliquam distinctio. Vitae, magni dicta! Nostrum fuga aut, libero, alias architecto commodi nam iusto sunt perspiciatis qui ex.
                                                                </div>
                                                            </div>
                                                        </DropDownOptions>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-2 rounded-md overflow-x-auto">
                        <div v-if="moreInformacionEmployee.empleado">
                            <aside class="xl:min-w-56 xl:w-60 space-y-3">
                                <div class="text-sm">
                                    <h3 class="font-medium text-slate-800 uppercase">Profesion en: </h3>
                                    <div class="uppercase font-semibold">{{
                                        moreInformacionEmployee.profesion.nombre_profesion
                                    }}</div>
                                </div>
                                <div class="text-sm">
                                    <h3 class="font-medium uppercase text-slate-800">Genero</h3>
                                    <div class="uppercase font-semibold"> {{
                                        moreInformacionEmployee.id_genero == 1 ? 'FEMENINO' : 'MASCULINO' }}</div>
                                </div>
                                <div class="text-sm">
                                    <h3 class="font-medium uppercase text-slate-800">Genero</h3>
                                    <div class="uppercase font-semibold">{{ moreInformacionEmployee.dui_persona }}</div>
                                </div>
                                <div class="text-sm">
                                    <h3 class="font-medium uppercase text-slate-800">Telefono</h3>
                                    <div class="uppercase font-semibold">
                                        {{ moreInformacionEmployee.telefono_persona || 'SINDATOS' }}
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
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