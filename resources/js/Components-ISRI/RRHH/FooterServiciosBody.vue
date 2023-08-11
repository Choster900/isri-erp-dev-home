<script setup>
import TooltipVue from '../Tesoreria/Tooltip.vue';
import moment from 'moment';
import ListAcuerdosVue from './ListAcuerdos.vue';
</script>
<template>
    <div v-show="showAcuerdos">
        {{ deals }}
        <h2 class="text-slate-800 font-semibold mb-2">Work History</h2>
        <!-- Calendario de contribuciones -->
        <div class="p-2 bg-slate-50 rounded-lg border border-slate-200">
            <div class="flex  justify-center">
                <div class="grid grid-rows-1 grid-flow-col gap-10">
                    <template v-for="(month, mothIndex) in monthName" :key="mothIndex">
                        <span class="text-xs">{{ month }}</span>
                    </template>
                </div>
            </div>
            <div class="flex justify-center">
                <div class="w-1/1 ">
                    <div class="flex flex-col justify-center items-center h-full">
                        <div class="text-end space-y-3">
                            <div class="text-xs pr-1 text-black">Lun</div>
                            <div class="text-xs pr-1">Mie</div>
                            <div class="text-xs pr-1">Vie</div>
                        </div>
                    </div>
                </div>
                <div class="w-3/1 ">
                    <div class="flex justify-center items-center h-full">
                        <div class="grid grid-rows-7 grid-flow-col">
                            <template v-for="(month, mothIndex) in month" :key="mothIndex">
                                <TooltipVue bg="dark" v-for="(day, weekIndex) in dayGenerater()[mothIndex]"
                                    :key="weekIndex">
                                    <template v-slot:contenido>
                                        <div class="h-[11px] w-[11px]  m-[1.5px] rounded-sm bg-gray-500/60">
                                        </div>
                                    </template>
                                    <template v-slot:message>
                                        <div class="text-xs text-slate-200 whitespace-nowrap">{{
                                            transFormDat(day, month, year) }}</div>
                                    </template>
                                </TooltipVue>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full ">

            <div class="flex flex-col md:flex-row  md:space-y-0">
                <div class="flex-1">
                    <div class="md:py-8">
                        <div class="space-y-4">

                            <!-- Block 1 -->

                            <div class="bg-slate-50 p-4 rounded border border-slate-200">
                                <template v-for="index in 2 " :key="index">
                                    <div class="flex justify-start text-xs font-semibold text-slate-400 uppercase mb-4">
                                        <span class="mr-1">Enero 2023 </span>
                                        <hr class="h-0.5 bg-slate-200 flex-grow my-1.5" aria-hidden="true">
                                    </div>
                                    <ul>
                                        <ListAcuerdosVue v-for="index in 2 " :key="index" />
                                    </ul>
                                </template>
                                <div class="mt-4">
                                    <button
                                        class="btn-sm w-full border border-slate-300 rounded-md py-1 hover:border-slate-400 text-indigo-500 shadow-none">Show
                                        More Activity</button>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="px-4 py-2 rounded-md overflow-x-auto pt-8">
                    <div class="flex items-center justify-center pb-2" v-for="index in 3" :key="index">
                        <button
                            class="relative bg-gray-500 text-white p-3 w-20 h-8 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
                            <span class="flex items-center justify-center h-full">2023</span>
                        </button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
export default {
    props: {
        showAcuerdos: {
            type: Boolean,
            default: false,
        },
        deals: {
            type: Object,
            default: [],
        },

    },
    data() {
        return {
            month: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            monthName: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            year: '2023',
            listYears: [],

        }
    },
    methods: {
        daysOfMonth(yearin) {
            const kabisa = (yearin) => (yearin % 4 === 0 && yearin % 100 !== 0 && yearin % 400 !== 0) || (yearin % 100 === 0 && yearin % 400 === 0);
            const fevral = (yearin) => (kabisa(yearin) ? 29 : 28);
            return [31, fevral(this.year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        },

        dayGenerater() {
            const days = [];
            for (let k = 0; k < this.daysOfMonth().length; k++) {
                days.push([]);
                for (let i = 1; i <= this.daysOfMonth()[k]; i++) {
                    days[k].push(i);
                }
            }
            return days;
        },
        getCurrentYear() {
            const today = new Date()
            this.year = today.getFullYear()
        },
        transFormDat(dia, mes, año) {
            const fechaProporcionada = `${año}-${mes}-${dia}`
            return moment(fechaProporcionada).format('dddd, MMMM D, YYYY')
        },
    }
}
</script>

<style></style>