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
    }
});
const iconSelected = computed(() => {
    return {
        add: 'M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z',
        delete: 'M12 9.75L14.25 12m0 0l2.25 2.25M14.25 12l2.25-2.25M14.25 12L12 14.25m-2.58 4.92l-6.375-6.375a1.125 1.125 0 010-1.59L9.42 4.83c.211-.211.498-.33.796-.33H19.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-9.284c-.298 0-.585-.119-.796-.33z',
        update: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10',
        info: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
        'defaultBruh': 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
    }[props.icon];
});
</script>

<template>
    <button :type="type" @click="deletAlert" :class="color"
        class="inline-flex text-white  rounded-md text-sm px-3 py-2 text-center mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="mr-2 -ml-1 w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" :d="iconSelected" />
        </svg>
        <span class="px-2 py-2 text-base text-gray-100 border-l-2 border-gray-100"></span>
        <div class='text-sm font-bold '>
            {{ text }}
        </div>
    </button>
</template>
