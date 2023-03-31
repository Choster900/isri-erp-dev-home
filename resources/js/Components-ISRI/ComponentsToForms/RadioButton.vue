<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

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
    <input type="radio" :name="name" class="border-gray-700 text-[#001c48] rounded-xl shadow-sm" :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)" ref="input" />
</template>
