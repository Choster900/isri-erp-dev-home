<template>
    <div class="relative inline-flex">
        <div class="bg-gray-200 text-sm text-gray-500 leading-none border-2 border-gray-200 rounded-full inline-flex"
            ref="trigger" @click.prevent="dropdownOpen = !dropdownOpen" :aria-expanded="dropdownOpen" aria-haspopup="true">
            <button
                class="inline-flex items-center transition-colors duration-300 ease-in focus:outline-none hover:text-indigo-600 focus:text-indigo-600 rounded-full px-2 py-1.5 active"
                id="grid">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-indigo-600 w-4 h-4">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                </svg>
            </button>
        </div>

        <div v-show="dropdownOpen"
            class="origin-top-right z-10 absolute top-full min-w-44  py-1.5 rounded  mt-1 right-0 transform-origin-top-right transform -translate-y-full">
            <div ref="dropdown" @focusin="dropdownOpen = true" @focusout="dropdownOpen = false"
                class="bg-white w-40 border border-gray-300 rounded-lg flex flex-col text-sm py-1 px-1 text-gray-500">
                <slot>
                    <!-- opciones del componenten a mostrar -->
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref, onMounted, onUnmounted } from 'vue'

export default {
    components: { DropdownLink },
    setup() {
        const dropdownOpen = ref(false)
        const trigger = ref(null)

        const dropdown = ref(null)

        // close on click outside
        const clickHandler = ({ target }) => {
            if (!dropdownOpen.value || dropdown.value.contains(target) || trigger.value.contains(target)) return
            dropdownOpen.value = false
        }
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
    },

}
</script>

<style></style>