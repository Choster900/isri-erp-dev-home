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
        default: false,
    },
    rounded: {
        type: Boolean,
        default: false,
    },
    addClasses: {
        type: String,
        default: ' bg-white ',
    },
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
    return props.rounded ? ' rounded-lg' : '';
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" v-bind:class="{'flex items-center justify-center':center}">
                <transition enter-active-class="ease-out duration-500" enter-from-class="opacity-0" enter-to-class="opacity-100"
                            leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
                    <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                        <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                    </div>
                </transition>

                <transition enter-active-class="ease-out duration-500" enter-from-class="opacity-0 translate-y-[-100%]"
                            enter-to-class="opacity-100 translate-y-0" leave-active-class="ease-in duration-200"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-[-100%]">
                    <div v-show="show"
                         class="mb-6 overflow-hidden shadow-xl transform transition-all w-full max-w-full sm:mx-auto"
                         :class="[maxWidthClass, isRounded, addClasses]">
                        <slot></slot>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
