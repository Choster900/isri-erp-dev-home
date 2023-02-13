<script setup>
import { onMounted, ref, computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    icon: {
        type: String,
        default: 'defaultBruh',
    },
    type: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: '',
    },
});

defineEmits(['update:modelValue']);



const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <!-- <label class="block mb-2 text-sm font-bold text-gray-700" :for="id">
        {{ placeholder }}
    </label> -->
    <div class="relative flex h-8 w-full flex-row-reverse overflow-clip "
        style="border: none; background-color: transparent;">
        <input :id="id" :placeholder="placeholder" :type="type"
            class="peer w-full text-xs   rounded-r-md border border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" />

        <!-- <input :id="id" :placeholder="placeholder" :type="type"
            class="peer w-full text-xs border-r-0 border-t-0  bg-transparent border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" /> -->
        <slot />
        <!--  <label
            class="flex items-center rounded-l-md border border-slate-400 bg-slate-50 px-2 text-sm text-slate-400 transition-colors duration-300 peer-focus:border-[#001b47] peer-focus:bg-[#001b47] peer-focus:text-white"
            :for="id">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" :d="iconSelected" />
            </svg>

        </label> -->

        <!--  <label
            class="flex items-center rounded-l-md border  px-2 text-sm  border-[#001b47] bg-[#152c50] text-white"
            :for="id">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" :d="iconSelected" />
            </svg>

        </label> -->
    </div>


</template>

