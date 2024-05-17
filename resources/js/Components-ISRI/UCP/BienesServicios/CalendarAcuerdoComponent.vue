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
                <input type="text"  v-model="dataCalendar.January"
                @input="dataCalendar.January = dataCalendar.January.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.March"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.May"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.July"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.September"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.November"
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
                <input type="text"  v-model.number="dataCalendar.February"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.April"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.June"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.August"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.October"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
            <div class="w-1/6 text-center">
                <input type="text"  v-model.number="dataCalendar.December"
                    class="w-full h-full bg-transparent border-none text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                    placeholder="0" min="0" />
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    setup() {

        const dataCalendar = ref(
            {
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
            }
        )
        return {
            dataCalendar
        };
    },
};
</script>

<style lang="scss" scoped></style>
