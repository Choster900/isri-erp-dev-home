<template>
    <div class="relative inline-flex">
        <button class="inline-flex justify-center items-center group" ref="trigger"
            @click.prevent="dropdownOpen = !dropdownOpen" :aria-expanded="dropdownOpen" aria-haspopup="true">

            <div class="size-8 rounded-full text-lg flex items-center justify-center bg-[#001c48] text-blue-300">
                <span class="uppercase text- text-white ">{{ dynamicUsername }}</span>
                <!--  <lord-icon
                    src="https://cdn.lordicon.com/bhfjfgqz.json"
                    trigger="hover"
                    colors="primary:white"
                    style="width:250px;height:250px">
                </lord-icon> -->
            </div>

            <div class="flex items-center truncate gap-1">
                <span class="truncate ml-2 text-sm font-medium group-hover:text-slate-800">
                    <span class="font-bold">{{ $page.props.auth.user.nick_usuario }}</span>
                </span>
                <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" viewBox="0 0 12 12">
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"></path>
                </svg>
                <!-- <lord-icon src="https://cdn.lordicon.com/hwuyodym.json" trigger="hover" colors="primary:#121331"
                    style="width:24px;height:24px">
                </lord-icon> -->
            </div>
        </button>

        <transition enter-active-class="transition ease-out duration-200 transform"
            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-out duration-200" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-show="dropdownOpen"
                class="origin-top-right z-10 absolute top-full min-w-44 w-52 bg-white border border-slate-200 py-1.5 rounded shadow-lg  mt-1 right-0">
                <div class="pt-0.5 pb-1 px-3 mb-1 border-slate-200">
                    <div class="font-bold text-slate-800">{{ $page.props.auth.user.name }}</div>
                    <div class="text-xs text-slate-500"><span class="font-bold pr-1"></span>{{
                $page.props.menu ? $page.props.menu.rol : 'Bienvenido al sistema ISRI'
            }}</div>


                </div>
                <ul ref="dropdown" @focusin="dropdownOpen = true" @focusout="dropdownOpen = false">
                    <li>
                        <DropdownLink :href="route('index.createCambiarContraseña')" method="get" as="button"
                            class="font-semibold text-sm text-indigo-500 flex items-center py-0.5 px-3 gap-2 hover:text-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="size-5">
                                <path fill-rule="evenodd"
                                    d="M8 7a5 5 0 1 1 3.61 4.804l-1.903 1.903A1 1 0 0 1 9 14H8v1a1 1 0 0 1-1 1H6v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-2a1 1 0 0 1 .293-.707L8.196 8.39A5.002 5.002 0 0 1 8 7Zm5-3a.75.75 0 0 0 0 1.5A1.5 1.5 0 0 1 14.5 7 .75.75 0 0 0 16 7a3 3 0 0 0-3-3Z"
                                    clip-rule="evenodd" />
                            </svg>
                            Cambiar Contraseña

                        </DropdownLink>
                    </li>
                    <li>
                        <DropdownLink :href="route('logout')" method="post" as="button"
                            class="font-semibold text-sm text-indigo-500 flex items-center py-1 px-3 gap-2 hover:text-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="size-5">
                                <path fill-rule="evenodd"
                                    d="M17 4.25A2.25 2.25 0 0 0 14.75 2h-5.5A2.25 2.25 0 0 0 7 4.25v2a.75.75 0 0 0 1.5 0v-2a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 .75.75v11.5a.75.75 0 0 1-.75.75h-5.5a.75.75 0 0 1-.75-.75v-2a.75.75 0 0 0-1.5 0v2A2.25 2.25 0 0 0 9.25 18h5.5A2.25 2.25 0 0 0 17 15.75V4.25Z"
                                    clip-rule="evenodd" />
                                <path fill-rule="evenodd"
                                    d="M1 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H1.75A.75.75 0 0 1 1 10Z"
                                    clip-rule="evenodd" />
                            </svg>

                            Cerrar Sesión
                        </DropdownLink>
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
    components: { DropdownLink },
    data() {
        return {
            dynamicUsername: "", // Aquí puedes asignar el valor dinámico que desees
        };
    },
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
    created() {
        let name = (this.$page.props.auth.user.nick_usuario).split(".");

        let firstTwoLetters;

        if (name.length > 1) {
            // Si hay un punto en el nombre, concatena las primeras letras después del punto
            const primerasLetras = name.map(nombre => nombre.charAt(0));
            firstTwoLetters = primerasLetras.join('');
        } else {
            // Si no hay un punto en el nombre, toma las dos primeras letras directamente
            firstTwoLetters = name[0].substr(0, 2);
        }

        this.dynamicUsername = firstTwoLetters;

    },

}
</script>

<style></style>
