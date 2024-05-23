<template>
    <div class="h-32 w-full">
        <div class="flex *:border-slate-300 *:border hover:bg-slate-200">
            <div class="w-1/6 text-center">Enero</div>
            <div class="w-1/6 text-center">Marzo</div>
            <div class="w-1/6 text-center">Mayo</div>
            <div class="w-1/6 text-center">Julio</div>
            <div class="w-1/6 text-center">Sepmtbre</div>
            <div class="w-1/6 text-center">Novmbre</div>
        </div>
        <div class="flex *:border-slate-300 *:border *:h-[46.5px] hover:bg-slate-200">
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.January"
                    @input="handleInput(); dataCalendar.January = dataCalendar.January.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.March"
                    @input="handleInput(); dataCalendar.March = dataCalendar.March.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.May"
                    @input="handleInput(); dataCalendar.May = dataCalendar.May.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.July"
                    @input="handleInput(); dataCalendar.July = dataCalendar.July.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.September"
                    @input="handleInput(); dataCalendar.September = dataCalendar.September.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.November"
                    @input="handleInput(); dataCalendar.November = dataCalendar.November.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
        </div>
        <div class="flex *:border-slate-300 *:border hover:bg-slate-200">
            <div class="w-1/6 text-center">Febrero</div>
            <div class="w-1/6 text-center">Abril</div>
            <div class="w-1/6 text-center">Junio</div>
            <div class="w-1/6 text-center">Agosto</div>
            <div class="w-1/6 text-center">Octubre</div>
            <div class="w-1/6 text-center">Dicmbre</div>
        </div>
        <div class="flex *:border-slate-300 *:border *:h-[46.5px] hover:bg-slate-200">
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.February"
                    @input="handleInput(); dataCalendar.February = dataCalendar.February.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.April"
                    @input="handleInput(); dataCalendar.April = dataCalendar.April.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.June"
                    @input="handleInput(); dataCalendar.June = dataCalendar.June.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.August"
                    @input="handleInput(); dataCalendar.August = dataCalendar.August.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.October"
                    @input="handleInput(); dataCalendar.October = dataCalendar.October.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text" v-model="dataCalendar.December"
                    @input="handleInput(); dataCalendar.December = dataCalendar.December.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
        </div>
    </div>
</template>

<script>
import { toRefs, onMounted, onBeforeUnmount } from 'vue'; // Importa funciones y objetos de Vue para la reactividad
import { reactive } from 'vue'; // Importa la función reactive de Vue para crear objetos reactivos

export default {
    props: {
        // Propiedad dataInserted para mostrar los datos actuales en el calendario
        dataInserted: {
            type: Object,
            default: () => null, // Valor por defecto es nulo
        },
    },
    setup(props, { emit }) {
        // Desestructura la propiedad dataInserted de las props
        const { dataInserted } = toRefs(props);

        // Objeto reactivo para almacenar los valores de cada mes del calendario
        const dataCalendar = reactive({
            January: 0,
            February: 0,
            March: 0,
            April: 0,
            May: 0,
            June: 0,
            July: 0,
            August: 0,
            September: 0,
            October: 0,
            November: 0,
            December: 0,
        });

        // Función para manejar la entrada en el calendario
        const handleInput = (month) => {
            emit('update:dataCalendar', dataCalendar); // Emite el evento 'update:dataCalendar' con los datos del calendario
        };

        // Función que se ejecuta después de que el componente ha sido montado en el DOM
        onMounted(() => {
            // Asigna los valores de dataInserted a dataCalendar
            Object.keys(dataCalendar).forEach(month => {
                dataCalendar[month] = dataInserted.value[month] || 0; // Asigna el valor del mes o 0 si no hay valor
            });
        });

        // Función para realizar limpieza antes de destruir el componente
        const destroyComponent = () => {
            // Realiza cualquier limpieza necesaria antes de destruir el componente
        };

        // Registra la función de destrucción del componente antes de que se desmonte
        onBeforeUnmount(destroyComponent);

        // Retorna las funciones y datos necesarios para el componente
        return {
            handleInput,
            dataCalendar
        };
    },
};
</script>


<style lang="scss" scoped></style>
