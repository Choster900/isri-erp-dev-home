<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    color: {
        type: String,
        default: 'bg-gray-600  hover:bg-gray-700'
    },
    text: {
        type: String,
        default: 'default'
    },
    icon: {
        type: String,
        default: 'defaultBruh'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    styleDisabled: {
        type: String,
        default: 'bg-gray-600  hover:bg-gray-700'
    }
});
const iconSelected = computed(() => {
    return {
        add: 'M12 4.5v15m7.5-7.5h-15',
        delete: 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        update: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10',
        info: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
        pdf: 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        search: 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
        check: 'M4.5 12.75l6 6 9-13.5',
        'defaultBruh': 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
    }[props.icon];
});



</script>

<template>
    <div class="flex rounded-md overflow-hidden mb-2 cursor-pointer" :class="[color, disabled ? styleDisabled : '']">
        <div class=" shadow-border py-1.5 px-2 border-r-2 border-white">
            <div class="w-5 h-5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="iconSelected" />
                </svg>
            </div>
        </div>
        <button :disabled="disabled"
            class="block text-white shadow-border hover:bg-blue-dark text-[12px] px-2 font-sans tracking-wide uppercase font-bold rounded-r-md">
            {{ text }}
        </button>

    </div>
</template>
