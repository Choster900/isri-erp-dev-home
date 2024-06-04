<template>
    <label v-if="label" :for="id" class="block mb-2 text-[13px] font-medium text-gray-600">{{ label }}
        <span v-if="required" class="text-red-600 font-extrabold">*</span></label>
    <vue-date-picker v-model="modelValue" :enable-time-picker="enableTimePicker" :format="format" :no-today="noToday" :hide-input-icon="!showIcon"
        :placeholder="placeholder" :disabled="disabled" :teleport="teleport" auto-apply :locale="localeConfig" :day-names="dayNames" :required="required"
        @update:model-value="$emit('update:modelValue', $event)" :style="hasError ? '--dp-border-color: #F87171;' : ''"
        class="py-0">
        <template #input-icon>
            <svg :class="iconColor" fill="currentColor" class="ml-[10px] w-auto h-[16px] relative" viewBox="0 0 35 35"
                data-name="Layer 2" id="a866a81f-2948-4418-8bd5-1a5193c5f74e" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path
                        d="M29.545,34.75H5.455a5.211,5.211,0,0,1-5.2-5.2V8.56a5.21,5.21,0,0,1,5.205-5.2h24.09a5.21,5.21,0,0,1,5.2,5.205V29.545A5.211,5.211,0,0,1,29.545,34.75ZM5.455,5.855A2.708,2.708,0,0,0,2.75,8.56V29.545a2.709,2.709,0,0,0,2.705,2.7h24.09a2.708,2.708,0,0,0,2.7-2.7V8.56a2.707,2.707,0,0,0-2.7-2.7Z">
                    </path>
                    <path d="M33.5,17.331H1.541a1.25,1.25,0,0,1,0-2.5H33.5a1.25,1.25,0,0,1,0,2.5Z"></path>
                    <path
                        d="M9.459,9.155a1.249,1.249,0,0,1-1.25-1.25V1.5a1.25,1.25,0,0,1,2.5,0V7.905A1.25,1.25,0,0,1,9.459,9.155Z">
                    </path>
                    <path
                        d="M25.542,9.155a1.249,1.249,0,0,1-1.25-1.25V1.5a1.25,1.25,0,0,1,2.5,0V7.905A1.25,1.25,0,0,1,25.542,9.155Z">
                    </path>
                </g>
            </svg>
        </template>
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
        iconColor: {
            type: String,
            default: 'text-gray-500',
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
