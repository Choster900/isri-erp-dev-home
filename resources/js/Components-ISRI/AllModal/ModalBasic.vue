<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    id: {
        type: String,
        default: "",
    },
    modalOpen: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Titulo',
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
});

const emit = defineEmits(['close-modal-persona']);
const modalContent = ref(null)
// close on click outside
const clickHandler = ({ target }) => {
    /*  if (!props.modalOpen || modalContent.value.contains(target)) return
     emit('close-modal-persona') */
}

// close if the esc key is pressed
const keyHandler = ({ keyCode }) => {
    if (!props.modalOpen || keyCode !== 27) return
    emit('close-modal-persona')
}

onMounted(() => {
    document.addEventListener('click', clickHandler)
    document.addEventListener('keydown', keyHandler)
})

onUnmounted(() => {
    document.removeEventListener('click', clickHandler)
    document.removeEventListener('keydown', keyHandler)
})
const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
        '5xl': 'sm:max-w-5xl',
        '6xl': 'sm:max-w-6xl',
    }[props.maxWidth];
});
</script>

<template>
    <!-- Modal backdrop -->
    <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition ease-out duration-100" leave-from-class="opacity-100"
        leave-to-class="opacity-0">
        <div v-show="modalOpen" class="fixed inset-0 bg-slate-900 bg-opacity-30 z-50 transition-opacity" aria-hidden="true">
        </div>
    </transition>
    <!-- Modal dialog -->
    <transition enter-active-class="transition ease-in-out duration-300" enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in-out duration-300"
        leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-4">
        <div v-show="modalOpen" :id="id"
            class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6 " role="dialog"
            aria-modal="true">
            <div ref="modalContent"
                class="bg-white rounded-md shadow-lg overflow-auto max-w-lg w-full max-h-full sin-scroll"
                :class="maxWidthClass">

                <!-- Modal header -->
                <div class="sticky  block px-5 py-3 border-b border-slate-300 bg-[#0c2958]">
                    <div class="flex justify-between items-center ">
                        <div class="font-semibold text-slate-50">{{ title }}</div>
                        <button class="text-slate-400 hover:text-slate-500" @click.stop="$emit('close-modal-persona')">
                            <div class="sr-only">Close</div>
                            <svg class="w-4 h-4 fill-current">
                                <path
                                    d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- // Modal header -->

                <slot /><!-- Contenido -->
            </div>
        </div>
    </transition>
</template >
<style>
.sin-scroll {
    overflow: auto;
    scrollbar-width: none;
}

.sin-scroll::-webkit-scrollbar {
    width: 0;
}
</style>