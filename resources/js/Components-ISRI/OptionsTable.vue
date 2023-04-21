
<template>
    <div class="relative inline-flex">
        <button type="button"
            class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
            ref="trigger" @click.prevent="dropdownOpen = !dropdownOpen" :aria-expanded="dropdownOpen" aria-haspopup="true">
            Acciones
        </button>
        <transition enter-active-class="transition ease-out duration-200 transform"
            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-out duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="dropdownOpen"
                class="origin-top-right z-10 absolute top-full min-w-44 bg-white border border-slate-200 py-1.5 rounded shadow-lg  mt-1 right-0">


                <ul ref="dropdown" @focusin="dropdownOpen = true" @focusout="dropdownOpen = false">
                    <li>
                        <button class="font-bold text-sm text-indigo-500 flex items-center py-1 px-3">
                            Eliminar elemento
                        </button>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>
<script>
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref, onMounted, onUnmounted } from 'vue'

export default {
    setup() {
        const imgPrfile = ""

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
