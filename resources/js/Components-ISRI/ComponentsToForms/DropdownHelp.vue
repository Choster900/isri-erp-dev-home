<template>
    <div class="relative inline-flex">
        <button ref="trigger"
            class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 transition duration-150 rounded-full"
            :class="{ 'bg-slate-200': dropdownOpen }" aria-haspopup="true" @click.prevent="dropdownOpen = !dropdownOpen"
            :aria-expanded="dropdownOpen">
            <span class="sr-only">Info</span>
            <svg class="w-4 h-4" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                <path class="fill-current text-slate-500"
                    d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z" />
            </svg>
        </button>
        <transition enter-active-class="transition ease-out duration-200 transform"
            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-out duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="dropdownOpen"
                class="origin-top-right z-10 absolute top-full min-w-44 w-40 bg-white border border-slate-200 py-1.5 rounded shadow-lg overflow-hidden mt-1"
                :class="align === 'right' ? 'right-0' : 'left-0'">
                <div class="text-xs text-start font-semibold text-slate-400 uppercase pt-1.5 pb-2 px-3">Mas opciones</div>
                <ul ref="dropdown" @focusin="dropdownOpen = true" @focusout="dropdownOpen = false">
                    <slot><!-- BODY´S COMPONENT --></slot>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'

export default {
    name: 'DropdownHelp',
    props: ['align'],
    setup() {

        const dropdownOpen = ref(false)
        const trigger = ref(null)
        const dropdown = ref(null)

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
        }
    }
}
</script>