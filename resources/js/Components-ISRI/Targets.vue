<script setup>
import DropdownLink from "@/Components/DropdownLink.vue";
import { computed, onMounted, onUnmounted, watch } from "vue";
const props = defineProps({
    modulo: {
        type: String,
        default: "",
    },
});
</script>
<template>
    <DropdownLink :href="route('index.getMenus', this.modulo.id_rol)" method="get" as="button">
        <div
            class="relative cursor-pointer py-6 px-6 rounded-md w-64 my-4 bg-white shadow-lg hover:scale-110 transform transition-all duration-500 hover:shadow-slate-600">
            <div class=" flex items-center absolute rounded-full py-2 px-2 shadow-xl bg-[#1c1e4d] left-4 -top-6"
                ref="container">
            </div>
            <div class="mt-8">
                <p class="text-xl font-semibold my-2">{{ modulo.sistema }}</p>
                <div class="space-x-2 text-gray-400 text-sm pb-5">
                    <p class="">Modulo Independiente <br> SIGI</p>
                </div>
                <div class="border-t-2 border-[#1c1e4d]"></div>
                <div class="flex justify-center">
                    <div class="my-2">
                        <p class="font-semibold text-base mb-2">Dependencia</p>
                        <div class="text-bol text-gray-500 font-bold">
                            <p>ADMON</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DropdownLink>
</template>
<script>



export default {
    mounted() {
        this.$refs.container.innerHTML = this.modulo.icono_sistema
    }
};
</script>
