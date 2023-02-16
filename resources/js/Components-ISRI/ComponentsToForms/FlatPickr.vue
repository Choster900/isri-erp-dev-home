<template>
    <flat-pickr
        class="peer w-full text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
        :config="config" v-model="date" />
    <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
        <!--  <svg class="w-4 h-4 fill-current text-slate-500 ml-3" viewBox="0 0 16 16">
                <path
                    d="M15 2h-2V0h-2v2H9V0H7v2H5V0H3v2H1a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V3a1 1 0 00-1-1zm-1 12H2V6h12v8z" />
            </svg> -->
    </div>
</template>

<script>
import flatPickr from 'vue-flatpickr-component';

export default {
    name: 'Datepicker',
    props: ['align'],
    data(props) {
        return {
            date: null, // refer to https://github.com/ankurk91/vue-flatpickr-component
            config: {
                mode: 'range',
                static: true,
                monthSelectorType: 'static',
                /*dateFormat: 'M j, Y',*/
                altInput: true,
                altFormat: "M j, Y",
                dateFormat: "Y-m-d",
                defaultDate: [new Date().setDate(new Date().getDate() + 7), new Date()],
                prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
                nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
                onReady: (selectedDates, dateStr, instance) => {
                    let replaceStr = dateStr.replace('to', '-');
                    instance.element.value = replaceStr
                    console.log(instance.element.value);
                    const customClass = (props.align) ? props.align : '';
                    instance.calendarContainer.classList.add(`flatpickr-${customClass}`);
                },
                onChange: (selectedDates, dateStr, instance) => {
                    instance.element.value = dateStr.replace('to', '-');
                    let dateSplit = dateStr.split("to");
                    var fecha1 = moment(dateSplit[0]);
                    var fecha2 = moment(dateSplit[1]);
                    console.log(fecha2.diff(fecha1, 'days'), ' dias de diferencia');
                },
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    },
                    months: {
                        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                        longhand: ['Enero', 'Febrero', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    },
                },
            },
        }
    },
    components: {
        flatPickr
    },
}
</script>