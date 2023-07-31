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
    showPlaceholder: {
        type: Boolean,
        default: true,
    },
    icon: {
        type: String,
        default: 'defaultBruh',
    },
    type: {
        type: String,
        default: '',
    },
    min: {
        type: String,
        default: '',
    },
    max: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: '',
    },
    labelInput: {
        type: Boolean,
        default: true,
    },
    readOnly: {
        type: Boolean,
        default: false
    },
    required: {
        type: Boolean,
        default: true
    },
    disabled: {
        type: Boolean,
        default: false
    }
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
    <label v-if="labelInput" class="block mb-2 text-xs font-light text-gray-600" :for="id">
        {{ placeholder }} <span class="text-red-600 font-extrabold" v-if="required">*</span>
    </label>
    <div class="relative flex h-8 w-full flex-row-reverse overflow-clip "
        style="border: none; background-color: transparent;">
        <input :id="id" :min="min" :max="max" :placeholder="showPlaceholder ? placeholder : ''" :type="type" :readOnly="readOnly" :disabled="disabled"
            class="peer w-full text-xs font-medium  rounded-r-md border border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300  focus:outline-none"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" />
        <slot />
    </div>
</template>

