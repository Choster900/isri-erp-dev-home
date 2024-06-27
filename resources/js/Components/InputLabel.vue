<script setup>
defineProps(['value']);
</script>

<template>
    <!-- dark classes deleted in label: dark:text-gray-300 -->
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
