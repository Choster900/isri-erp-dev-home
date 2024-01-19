<template>
    <label :for="id" class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">{{ label }}
        <span v-if="required" class="text-red-600 font-extrabold">*</span></label>
    <vue-date-picker :config="config" v-model="modelValue" time-picker :placeholder="placeholder" :teleport="teleport"
        :disabled="disabled" @update:model-value="$emit('update:modelValue', $event)"
        :style="hasError ? '--dp-border-color: #F87171;' : ''">
        <template #input-icon>
            <svg class="ml-[10px] w-auto h-[17px] relative text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path
                        d="M12 7V12H15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
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
        modelValue: {
            type: [String, Date],
            default: '',
        },
        teleport: {
            type: Boolean,
            default: true,
        },
        hasError: {
            type: Boolean,
            default: false,
        },
        height: {
            type: Number,
            default: 0,
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
    },
    emits: ["update:modelValue"],
    setup(props, { emit }) {
        const localeConfig = {
            ...es,
        };

        const { modelValue, height } = toRefs(props)

        const config = {
            modeHeight: height.value,
        }

        return {
            modelValue,
            localeConfig,
            config
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
    --dp-font-size: 0.8rem;
    
}

.dp__theme_dark {
    /* I've edited this */
    --dp-primary-color: #10B981;
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}</style>