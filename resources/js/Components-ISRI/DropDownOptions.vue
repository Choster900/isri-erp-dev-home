
<template>
    <div class="relative inline-flex">
        <div class="bg-gray-200 text-sm text-gray-500 leading-none border-2 border-gray-200 rounded-full inline-flex"
            ref="trigger" @click.prevent="dropdownOpen = !dropdownOpen" :aria-expanded="dropdownOpen" aria-haspopup="true">
            <button
                class="inline-flex items-center transition-colors duration-300 ease-in focus:outline-none hover:text-indigo-600 focus:text-indigo-600 rounded-l-full px-4 py-2 active"
                id="grid">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="fill-current w-4 h-4 mr-2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Opciones</span>
            </button>
        </div>


        <transition enter-active-class="transition ease-out duration-200 transform"
            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-out duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="dropdownOpen"
                class="origin-top-right z-10 absolute top-full min-w-44  py-1.5 rounded  mt-1 right-0">
                <div ref="dropdown" @focusin="dropdownOpen = true" @focusout="dropdownOpen = false"
                    class="bg-white w-40 border border-gray-300 rounded-lg flex flex-col text-sm py-2 px-2 text-gray-500">
                    <slot>
                        <!-- opciones del componenten a mostrar -->
                    </slot>
                </div>
            </div>

        </transition>

    </div>
</template>

<script>
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref, onMounted, onUnmounted } from 'vue'

export default {
    components: { DropdownLink },
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
    created() {
        let name = this.$page.props.auth.user.nick_usuario
        this.imgPrfile = "https://ui-avatars.com/api/?name=" + name + "&background=001b47&color=fff&size=100"
    }

}
</script>

<style></style>