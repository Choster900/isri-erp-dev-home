<template>

    <!--  <div class="relative cursor-pointer" @mouseenter="tooltipOpen = true" @mouseleave="tooltipOpen = false"
        @focusin="tooltipOpen = true" @focusout="tooltipOpen = false"> -->
    <div class="relative cursor-pointer ">
        <svg v-show="tooltipOpen"
            class="absolute text-slate-800 border-slate-800 h-6 left-0 ml-1 -translate-x-4 -translate-y-1 transform rotate-90"
            x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve">
            <polygon class="fill-current" points="0,0 127.5,127.5 255,0" />
        </svg>
        <button class="block" aria-haspopup="true" aria-expanded="tooltipOpen" @click.prevent>
            <slot name="contenido"></slot>
        </button>

        <div class=" absolute -translate-y-1" :class="positionOuterClasses(position)">
            <button v-show="tooltipOpen" @click="tooltipOpen = false"
                class="absolute top-0 right-0 mt-1 mr-1 text-slate-200 hover:text-slate-400">
                &times;
            </button>

            <div v-show="tooltipOpen" class="rounded overflow-hidden " :class="[
            bg === 'dark' ? 'bg-slate-800 ' : 'bg-white border border-slate-200 shadow-lg text-black',
            sizeClasses(size),
            positionInnerClasses(position)
        ]">
                <slot name="message" />
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'

export default {
    name: 'TooltipAlwaysOpen',
    props: ['bg', 'size', 'position'],
    setup() {

        const tooltipOpen = ref(true)

        const positionOuterClasses = (position) => {
            switch (position) {
                case 'right':
                    return 'left-full top-1/2 -translate-y-1/2';
                case 'left':
                    return 'right-full top-1/2 -translate-y-1/2';
                case 'bottom':
                    return 'top-full left-1/2 -translate-x-1/2 ';
                default:
                    return 'bottom-full left-1/2 -translate-x-1/2';
            }
        }

        const sizeClasses = (size) => {
            switch (size) {
                case 'lg':
                    return 'min-w-72  p-3';
                case 'md':
                    return 'min-w-56 p-3';
                case 'sm':
                    return 'min-w-44 p-2';
                default:
                    return 'p-2';
            }
        }

        const positionInnerClasses = (position) => {
            switch (position) {
                case 'right':
                    return 'ml-2';
                case 'left':
                    return 'mr-2';
                case 'bottom':
                    return 'mt-2';
                default:
                    return 'mb';
            }
        }

        return {
            tooltipOpen,
            positionOuterClasses,
            sizeClasses,
            positionInnerClasses,
        }
    }
}
</script>
<style scoped>
.z-per {
    z-index: 9999;
    /* Puedes ajustar este valor si es necesario */
}
</style>
