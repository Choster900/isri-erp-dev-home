<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue', "placeholder","icon","type"]);

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
    <label class="block mb-2 text-sm font-bold text-gray-700" for="">
        {{ placeholder }}
    </label>
    <div class="relative flex h-10 w-full flex-row-reverse overflow-clip "
        style="border: none; background-color: transparent;">
        <input :placeholder="placeholder" :type="type" style="font-weight: bold;"
            class="peer w-full text-sm rounded-r-md border border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" />
        <label
            class="flex items-center rounded-l-md border border-slate-400 bg-slate-50 px-2 text-sm text-slate-400 transition-colors duration-300 peer-focus:border-[#001b47] peer-focus:bg-[#001b47] peer-focus:text-white"
            for="domain">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    :d="icon" />
            </svg>

        </label>
    </div>


</template>

