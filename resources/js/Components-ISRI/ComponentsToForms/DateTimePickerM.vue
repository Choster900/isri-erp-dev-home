<template>
    <label v-if="label" :for="id" class="block mb-2 text-[13px] font-medium text-gray-600">{{ label }}
        <span v-if="required" class="text-red-600 font-extrabold">*</span></label>
    <vue-date-picker v-model="modelValue" :enable-time-picker="enableTimePicker" :format="format" :no-today="noToday"
        :hide-input-icon="!showIcon" :placeholder="placeholder" :disabled="disabled" :teleport="teleport" auto-apply
        :locale="localeConfig" :day-names="dayNames" :required="required" :style="hasError ? '--dp-border-color: #F87171;' : ''"
        @update:model-value="$emit('update:modelValue', $event)"  class="py-0">
        <template #input-icon>
            <svg :class="iconColor" fill="currentColor" class="ml-[10px] w-auto h-[16px] relative" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M19 4h-1V2a1 1 0 10-2 0v2H8V2a1 1 0 10-2 0v2H5a3 3 0 00-3 3v12a3 3 0 003 3h14a3 3 0 003-3V7a3 3 0 00-3-3zm1 15a1 1 0 01-1 1H5a1 1 0 01-1-1V10h16v9zm0-11H4V7a1 1 0 011-1h1v1a1 1 0 102 0V6h8v1a1 1 0 102 0V6h1a1 1 0 011 1v1z" />
            </svg>
        </template>
    </vue-date-picker>
</template>

<script>
import { toRefs } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { es } from 'date-fns/locale';

export default {
    components: {
        VueDatePicker,
    },
    props: {
        iconColor: {
            type: String,
            default: 'text-gray-500',
        },
        inputWrapHeight: {
            type: String,
            default: '30px' // Valor predeterminado
        },
        modelValue: {
            type: [String, Date],
            default: '',
        },
        enableTimePicker: {
            type: Boolean,
            default: false,
        },
        hasError: {
            type: Boolean,
            default: false
        },
        noToday: {
            type: Boolean,
            default: false,
        },
        showIcon: {
            type: Boolean,
            default: true,
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
            default: '',
        },
        required: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        required: {
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

        const { modelValue, inputWrapHeight  } = toRefs(props)

        return {
            modelValue,
            localeConfig,
            inputWrapHeight
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

.dp__input {
    height: v-bind(inputWrapHeight) !important;
    font-weight: 600;
}

.dp__input_wrap {
    height: v-bind(inputWrapHeight) !important;
}

.dp__theme_light {
    /* I've edited this */
    /* --dp-primary-color: #059669; */
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}

.dp__theme_dark {
    /* I've edited this */
    --dp-primary-color: #10B981;
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}
</style>
