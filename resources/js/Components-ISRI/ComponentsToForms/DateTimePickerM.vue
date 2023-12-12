<template>
    <label :for="id" class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">{{ label }}
        <span v-if="required" class="text-red-600 font-extrabold">*</span></label>
    <vue-date-picker v-model="modelValue" :enable-time-picker="enableTimePicker" :format="format" :placeholder="placeholder"
        :locale="localeConfig" :day-names="dayNames" :teleport="teleport" :disabled="disabled" @update:model-value="$emit('update:modelValue', $event)">
    </vue-date-picker>
</template>

<script>
import { ref, toRefs, onMounted, watch } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { es } from 'date-fns/locale';

export default {
    components: {
        VueDatePicker,
    },
    props: {
        modelValue: {
            type: [String, Date],
            default: '',
        },
        enableTimePicker: {
            type: Boolean,
            default: false,
        },
        teleport: {
            type: Boolean,
            default: true,
        },
        format: {
            type: String,
            default: 'dd/MM/yyyy',
        },
        placeholder: {
            type: String,
            default: 'Seleccione',
        },
        label: {
            type: String,
            default: 'Seleccione',
        },
        required: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        dayNames: {
            type: Array,
            default: () => ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
        },
    },
    emits: ["update:modelValue"],
    setup(props, { emit }) {
        const localeConfig = {
            ...es,
        };

        const { modelValue } = toRefs(props)

        return {
            modelValue,
            localeConfig,
        };
    },
};
</script>

<style>
.dp__input::placeholder {
    font-weight: 600;
    font-family: 'poppins';
    font-size: 13px;
}

.dp__input_wrap {
    height: 40px;
}

.dp__input {
    height: 100%;
    font-weight: 600;
}

.dp__theme_light {
    /* I've edited this */
    --dp-primary-color: #059669;
    --dp-cell-size: 25px;
    --dp-font-size: 0.9rem;
}

.dp__theme_dark {
    /* I've edited this */
    --dp-primary-color: #10B981;
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}
</style>