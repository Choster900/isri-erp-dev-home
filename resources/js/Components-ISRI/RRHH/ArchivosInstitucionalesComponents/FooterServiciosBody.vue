<script setup>
import TooltipVue from '../../Tooltip.vue';
import moment from 'moment';
import ListAcuerdosVue from './ListAcuerdos.vue';
</script>
<template>
    <div >
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
                                        <div @click="isDateInDataDeals(moment(`${year}-${month}-${day}`).format('L')) ? showMoreActivity(true, moment(`${year}-${month}-${day}`).format('L')) : ''"
                                            :class="isDateInDataDeals(moment(`${year}-${month}-${day}`).format('L')) ? 'bg-green-900/90' : 'bg-gray-500/60'"
                                            class="h-[11px] w-[11px]  m-[1.5px] rounded-sm ">
                                            <!--  <span class="text-[8px]">{{ isDateInDataDeals(moment(`${year}-${month}-${day}`).format('L')) }}</span> -->
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
                            <div class="bg-slate-50 p-4 rounded border border-slate-200">
                                <template v-for="(acuerdosArray, mesAñoKey) in objectToShowDeals" :key="mesAñoKey">
                                    <div class="flex justify-start text-xs font-semibold text-slate-400  mb-4">
                                        <span class="mr-1">{{ moment(acuerdosArray.mesAño, 'MM-YYYY').format('MMMM YYYY') }}
                                        </span>
                                        <hr class="h-0.5 bg-slate-200 flex-grow my-1.5" aria-hidden="true">
                                    </div>
                                    <ul>
                                        <ListAcuerdosVue v-for="acuerdos, i in acuerdosArray['acuerdos']" :key="i"
                                            :deal="acuerdos" />
                                    </ul>
                                </template>
                                <div class="mt-4">
                                    <!-- TODO: PONER LOS ESTILOS PARA BOTON DESHABILITADO CUANDO ESTE FILTRANDO POR FECHA -->
                                    <button @click="showMoreActivity()"
                                        :class="currentDealIndex <= dataFiltered.length - 1 ? 'hover:border-slate-300 text-blue-900 hover:bg-slate-200' : 'text-slate-600 bg-gray-200 cursor-not-allowed'"
                                        class="btn-sm w-full border border-slate-300 rounded-md py-1   shadow-none">Show
                                        More Activity</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-2 rounded-md overflow-x-auto pt-8">
                    <div class="flex items-center justify-center pb-2" v-for="index in listYears" :key="index">
                        <button @click="year = index; showMoreActivity(true)"
                            :class="year == index ? 'bg-blue-900' : 'bg-gray-500 hover:bg-slate-600'"
                            class="relative  text-white p-3 w-20 h-8 rounded-lg text-sm uppercase font-semibold tracking-tight overflow-visible">
                            <span class="flex items-center justify-center h-full">{{ index }}</span>
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

        deals: {
            type: Object,
            default: [],
        },

    },
    data() {
        return {
            month: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            monthName: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            year: '',
            listYears: [],
            objectToShowDeals: [],//data para imprimr
            currentDealIndex: null,
            dataFiltered: [],
            isFiltering: false,
            filterDate: null,

        }
    },
    computed: {
        ObjectOrderedByMonths() {
            // Filtra y ordena los objetos por mes de forma descendente
            const filteredByYears = this.deals.filter(objeto =>
                moment(objeto.fecha_acuerdo_laboral).year() == this.year
            );
            return filteredByYears.slice().sort((a, b) => {
                const dateA = new Date(a.fecha_acuerdo_laboral);
                const dateB = new Date(b.fecha_acuerdo_laboral);
                return dateB - dateA;
            });

        },
        arrDeals() {// Filtra y ordena la data por fecha
            const dataAcuerdos = []
            this.ObjectOrderedByMonths.forEach(acuerdo => {
                // Obtener la fecha del acuerdo laboral en formato de fecha
                const mesAñoKey = moment(acuerdo.fecha_acuerdo_laboral).format('MM-YYYY'); // Formato "MM-YYYY"
                if (!dataAcuerdos[mesAñoKey]) {
                    dataAcuerdos[mesAñoKey] = [];
                }
                // Agregar el acuerdo laboral al arreglo correspondiente al mes y año
                dataAcuerdos[mesAñoKey].push(acuerdo);

            });

            const acuerdosData = [];

            for (const mesAñoKey in dataAcuerdos) {
                const mesAñoObj = {
                    mesAño: mesAñoKey,
                    acuerdos: []
                };

                const acuerdosArray = dataAcuerdos[mesAñoKey];
                for (const acuerdo of acuerdosArray) {
                    mesAñoObj.acuerdos.push(acuerdo);
                }

                acuerdosData.push(mesAñoObj);
            }
            return acuerdosData
        },


    },
    methods: {
        newFilter(filterFromHere) {
            // Pasamos esta data a true
            this.isFiltering = true;
            // Almacenamos la fecha de filtrado
            this.filterDate = filterFromHere

            // Hacer una copia de la data actual
            const copiedData = JSON.parse(JSON.stringify(this.arrDeals));
            const targetMonthYear = moment(this.filterDate, "DD/MM/YYYY").format("MM-YYYY");
            const matchingArrayIndex = copiedData.findIndex(item => item.mesAño === targetMonthYear);

            console.log(matchingArrayIndex);
            console.log(copiedData);
            const matchingArray = copiedData[matchingArrayIndex];

            const targetCompleteDate = moment(this.filterDate, "DD/MM/YYYY").format("YYYY-MM-DD");

            const filteredAcuerdos = matchingArray.acuerdos.filter(deal => deal.fecha_acuerdo_laboral <= targetCompleteDate);

            const updatedMatchingArray = {
                ...matchingArray,
                acuerdos: filteredAcuerdos
            };

            copiedData[matchingArrayIndex] = updatedMatchingArray;
            return copiedData.filter(deal => deal.mesAño <= targetMonthYear);
        },
        showMoreActivity(reset = false, filterFromHere = '') {
            // Dejar el codigo asi (NO TOCAR 🚫)
            // Si se solicita un reinicio y también se proporciona un filtro
            if (reset && filterFromHere) {
                this.dataFiltered = []
                this.objectToShowDeals = []
                this.currentDealIndex = 0;
            }
            // Si se solicita un reinicio (sin filtro)
            if (reset) {
                this.dataFiltered = []
                this.objectToShowDeals = []
                this.currentDealIndex = 0;
                this.dataFiltered = filterFromHere ? this.newFilter(filterFromHere) : this.arrDeals;
            }
            // Si el índice actual es menor que la longitud de dataFiltered
            if (this.currentDealIndex < this.dataFiltered.length) {
                // Agregar el siguiente objeto al arreglo de objetos para mostrar
                this.objectToShowDeals.push(this.dataFiltered[this.currentDealIndex]);
                this.currentDealIndex++;
            }
        },


        filterAllYearsInDeals() {

            const uniqueYearsSet = new Set();
            // Iteramos sobre el arreglo dataDeals y agregamos los años al conjunto
            this.deals.forEach((obj, index) => {
                // Extraemos el año de la fecha_acuerdo_laboral y lo agregamos al conjunto
                if (obj.fecha_acuerdo_laboral) {
                    const year = moment(obj.fecha_acuerdo_laboral).year();
                    uniqueYearsSet.add(year);
                }
            });
            // Convertimos el conjunto a un nuevo arreglo y lo ordenamos
            const uniqueYearsArray = Array.from(uniqueYearsSet).sort();
            this.listYears = uniqueYearsArray
            this.year = this.listYears[this.listYears.length - 1]
        },


        isDateInDataDeals(date) {
            return this.deals.some((deal) => moment(deal.fecha_acuerdo_laboral).format('L') == date);
        },
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
    },
    watch: {
        showAcuerdos() {
            this.objectToShowDeals = []
            this.currentDealIndex = 0;
            this.filterAllYearsInDeals()

            this.showMoreActivity(true)

        },
        deals() {
            this.objectToShowDeals = []
            this.currentDealIndex = 0;
            this.year = '';
            this.filterAllYearsInDeals()
            this.showMoreActivity(true)

        }
    }
}
</script>

<style></style>
