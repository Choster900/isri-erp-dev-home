<template>
    <div class="relative">
        <button ref="trigger"
            class="btn justify-between min-w-44 bg-white border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-600"
            aria-label="Select date range" aria-haspopup="true" @click.prevent="dropdownOpen = !dropdownOpen"
            :aria-expanded="dropdownOpen">
            <span class="flex items-center">
                <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mr-2" viewBox="0 0 16 16">
                    <path
                        d="M15 2h-2V0h-2v2H9V0H7v2H5V0H3v2H1a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V3a1 1 0 00-1-1zm-1 12H2V6h12v8z" />
                </svg>
                <span class="text-xs">{{ options[selected].period }}</span>
            </span>
            <svg class="shrink-0 ml-1 fill-current text-slate-400" width="11" height="7" viewBox="0 0 11 7">
                <path d="M5.4 6.8L0 1.4 1.4 0l4 4 4-4 1.4 1.4z" />
            </svg>
        </button>
        <transition enter-active-class="transition ease-out duration-100 transform"
            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-out duration-100" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-show="dropdownOpen"
                class="z-10 absolute top-full right-0 w-full bg-white border border-slate-200 py-1.5 rounded shadow-lg overflow-hidden mt-1">
                <div ref="dropdown" class="font-medium text-sm text-slate-600" @focusin="dropdownOpen = true"
                    @focusout="dropdownOpen = false">

                    <button v-for="option in options" :key="option.id"
                        class="flex text-xs items-center w-full hover:bg-slate-50 py-1 px-3 cursor-pointer"
                        :class="option.id === selected && 'text-indigo-500'"
                        @click="selected = option.id; dropdownOpen = false; $emit('optionId',option.id)">
                        <svg class="shrink-0 mr-2 fill-current text-indigo-500"
                            :class="option.id !== selected && 'invisible'" width="12" height="9" viewBox="0 0 12 9">
                            <path
                                d="M10.28.28L3.989 6.575 1.695 4.28A1 1 0 00.28 5.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28.28z" />
                        </svg>
                        <span>{{ option.period }}</span>
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'
import moment from 'moment';

export default {
    name: 'DateSelect',
    setup() {

        moment.locale('es');
        const dropdownOpen = ref(false)
        const trigger = ref(null)
        const dropdown = ref(null)
        const selected = ref(0)

        const options = ref([
            {
                id: 0,
                period: `Mes actual ${moment().format("MMMM")}`,
            },
            {
                id: 1,
                period: 'Mes anterior'
            },
            {
                id: 2,
                period: 'Año Actual'
            },
            {
                id: 3,
                period: 'Ultimos 6 meses'
            },
           /*  {
                id: 4,
                period: 'Todos'
            } */
        ])

        // close on click outside
        const clickHandler = ({ target }) => {
            if (!dropdownOpen.value || dropdown.value.contains(target) || trigger.value.contains(target)) return
            dropdownOpen.value = false
        }

        // close if the esc key is pressed
        const keyHandler = ({ keyCode }) => {
            if (!dropdownOpen.value || keyCode !== 27) return
            dropdownOpen.value = false
        }

        onMounted(() => {
            document.addEventListener('click', clickHandler)
            document.addEventListener('keydown', keyHandler)
        })

        onUnmounted(() => {
            document.removeEventListener('click', clickHandler)
            document.removeEventListener('keydown', keyHandler)
        })

        return {
            dropdownOpen,
            trigger,
            dropdown,
            selected,
            options,
        }
    }
}
</script>
