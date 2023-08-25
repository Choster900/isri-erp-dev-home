<script setup>
import TooltipVue from '../Tooltip.vue';
import moment from 'moment';
import ListAcuerdosVue from './ListAcuerdos.vue';
</script>
<template>
    <div v-show="showAcuerdos">
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
                                <template v-for="(acuerdosArray, mesAñoKey) in objectDeals" :key="mesAñoKey">
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
                                <div class="mt-4"><!-- TODO: PONER LOS ESTILOS PARA BOTON DESHABILITADO CUANDO ESTE FILTRANDO POR FECHA -->
                                    <button @click="showMoreActivity()"
                                        :class=" currentDealIndex <= arrDeals.length - 1 ? 'hover:border-slate-300 text-blue-900 hover:bg-slate-200' : 'text-slate-600 bg-gray-200 cursor-not-allowed'"
                                        class="btn-sm w-full border border-slate-300 rounded-md py-1   shadow-none">Show
                                        More Activity</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-2 rounded-md overflow-x-auto pt-8">
                    <div class="flex items-center justify-center pb-2" v-for="index in listYears" :key="index">
                        <button @click="year = index;  showMoreActivity(true)"
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
            year: '',
            listYears: [],
            newDataAcuerdos: [],
            currentDealIndex: null,
            objectDeals: [],
            isFiltering: false,
            filterFromHereDate: null,
            
            

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
        arrDeals() {//No podia directamente recorrer este objeto en un v-for asi que mejor lo hice aqui
            this.ObjectOrderedByMonths.forEach(acuerdo => {
                // Obtener la fecha del acuerdo laboral en formato de fecha
                const fechaAcuerdo = new Date(acuerdo.fecha_acuerdo_laboral);

                // Construir una clave para agrupar por mes y año
                const mes = fechaAcuerdo.getMonth() ; // Sumamos 1 para ajustar el índice del mes
                const año = fechaAcuerdo.getFullYear();
          //      const mesAñoKey = `${mes < 10 ? '0' : ''}${mes}-${año}`;
                const mesAñoKey = moment(acuerdo.fecha_acuerdo_laboral).format('MM-YYYY'); // Formato "MM-YYYY"
                console.log({mesAñoKey});

                if (!this.newDataAcuerdos[mesAñoKey]) {
                    this.newDataAcuerdos[mesAñoKey] = [];
                }
                // Agregar el acuerdo laboral al arreglo correspondiente al mes y año
                this.newDataAcuerdos[mesAñoKey].push(acuerdo);

            });

            const acuerdosData = [];

            for (const mesAñoKey in this.newDataAcuerdos) {
                const mesAñoObj = {
                    mesAño: mesAñoKey,
                    acuerdos: []
                };

                const acuerdosArray = this.newDataAcuerdos[mesAñoKey];
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
            this.isFiltering = true
            const targetMonthYear = moment(filterFromHere, "DD/MM/YYYY").format("MM-YYYY");
            const matchingArrayIndex = this.arrDeals.findIndex(item => item.mesAño === targetMonthYear);

            if (matchingArrayIndex !== -1) {
                const matchingArray = this.arrDeals[matchingArrayIndex];

                const targetCompleteDate = moment(filterFromHere, "DD/MM/YYYY").format("YYYY-MM-DD");
                const filteredAcuerdos = matchingArray.acuerdos.filter(deal => deal.fecha_acuerdo_laboral <= targetCompleteDate);

                // Crear un nuevo objeto con los acuerdos actualizados
                const updatedMatchingArray = {
                    ...matchingArray,
                    acuerdos: filteredAcuerdos
                };

                // Reemplazar el objeto antiguo con el nuevo en el arreglo
                this.arrDeals[matchingArrayIndex] = updatedMatchingArray;

                return this.arrDeals.filter(item => item.mesAño <= targetMonthYear);

            } else {
                //console.log("No se encontró ningún arreglo con ese mes y año.");
            }

        },
        showMoreActivity(reset = false, filterFromHere = '') {

            if (filterFromHere) {
                this.filterFromHereDate = filterFromHere
                this.isFiltering = true;
            }
            console.log(this.arrDeals);

            if (reset) {
                this.objectDeals = []
                this.newDataAcuerdos = []
                this.currentDealIndex = 0;
            }
            if (this.currentDealIndex === null) {
                this.currentDealIndex = 0;
            }

            if (this.currentDealIndex <= this.arrDeals.length - 1 && filterFromHere == '' && !this.isFiltering) {

                this.objectDeals.push(this.arrDeals[this.currentDealIndex]);
                this.currentDealIndex++;

            } else {
                this.objectDeals.push(this.newFilter(this.filterFromHereDate)[this.currentDealIndex]);
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
            this.objectDeals = []
            this.currentDealIndex = 0;
            this.filterAllYearsInDeals()

            this.showMoreActivity()

        },
        deals() {
            this.objectDeals = []
            this.currentDealIndex = 0;
            this.year = '';
            this.objectDeals = []
            this.newDataAcuerdos = []
            this.filterAllYearsInDeals()
            this.showMoreActivity()

        }
    }
}
</script>

<style></style>