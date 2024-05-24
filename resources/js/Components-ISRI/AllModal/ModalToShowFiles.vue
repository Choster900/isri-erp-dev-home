<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    center: {
        type: Boolean,
        default:false
    },
    rounded: {
        type: Boolean,
        default: false
    },
    addClases: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

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
        '7xl': 'sm:max-w-7xl',
        '8xl': 'max-w-8xl',
    }[props.maxWidth];
});

const isRounded = computed(() => {
    const res = props.rounded ? ' rounded-lg' : ''
    return res
})
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto z-50">
                <transition enter-active-class="ease-out duration-500" enter-from-class="opacity-0"
                    enter-to-class="opacity-100" leave-active-class="ease-in duration-200" leave-from-class="opacity-100"
                    leave-to-class="opacity-0">
                    <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                        <div class="absolute inset-0 bg-gray-900 opacity-75" />
                    </div>
                </transition>

                <div class="fixed top-0 left-0 right-0 bg-white shadow-lg p-4 flex justify-between items-center z-50">
                    <h2 class="text-lg font-medium text-gray-900">Modal Title</h2>
                    <div class="flex items-center space-x-4">
                        <button @click="close" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16c0 1.1046.8954 2 2 2h12c1.1046 0 2-.8954 2-2V4M4 4h16v16M4 4L20 20M12 4v16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <transition enter-active-class="ease-out duration-500" enter-from-class="opacity-0 translate-y-[-100%]"
                    enter-to-class="opacity-100 translate-y-0" leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-[-100%]">
                    <div v-show="show"
                        class="mt-16 mx-auto p-4 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass+isRounded+addClases">
                        <slot />
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
