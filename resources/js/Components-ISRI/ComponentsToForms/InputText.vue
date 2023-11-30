<template>
    <div>
        <label :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center pl-3.5 pointer-events-none">
                <template v-if="iconName === 'email'">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                        <path
                            d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
                        <path
                            d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
                    </svg>
                </template>
                <template v-if="iconName === 'address'">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M9 20L3 17V4L9 7M9 20L15 17M9 20V7M15 17L21 20V7L15 4M15 17V4M9 7L15 4"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </g>
                    </svg>
                </template>
                <template v-if="iconName === 'oldPhone'">
                    <svg class="w-4 h-4 text-gray-700 dark:text-gray-600" version="1.1" id="_x32_"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                        xml:space="preserve" fill="currentColor">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill="currentColor"
                                d="M500.177,55.798c0,0-21.735-7.434-39.551-11.967C411.686,31.369,308.824,24.727,256,24.727 S100.314,31.369,51.374,43.831c-17.816,4.534-39.551,11.967-39.551,11.967c-7.542,2.28-12.444,9.524-11.76,17.374l8.507,97.835 c0.757,8.596,7.957,15.201,16.581,15.201h84.787c8.506,0,15.643-6.416,16.553-14.878l4.28-39.973 c0.847-7.93,7.2-14.138,15.148-14.815c0,0,68.484-6.182,110.081-6.182c41.586,0,110.08,6.182,110.08,6.182 c7.949,0.676,14.302,6.885,15.148,14.815l4.29,39.973c0.9,8.462,8.038,14.878,16.545,14.878h84.777 c8.632,0,15.832-6.605,16.589-15.201l8.507-97.835C512.621,65.322,507.72,58.078,500.177,55.798z">
                            </path>
                            <path fill="currentColor"
                                d="M357.503,136.629h-55.365v46.137h-92.275v-46.137h-55.365c0,0-9.228,119.957-119.957,207.618 c0,32.296,0,129.95,0,129.95c0,7.218,5.857,13.076,13.075,13.076h416.768c7.218,0,13.076-5.858,13.076-13.076 c0,0,0-97.654,0-129.95C366.73,256.586,357.503,136.629,357.503,136.629z M338.768,391.42v37.406h-37.396V391.42H338.768z M338.768,332.27v37.406h-37.396V332.27H338.768z M301.372,310.518v-37.396h37.396v37.396H301.372z M274.698,391.42v37.406h-37.396 V391.42H274.698z M274.698,332.27v37.406h-37.396V332.27H274.698z M274.698,273.122v37.396h-37.396v-37.396H274.698z M210.629,391.42v37.406h-37.397V391.42H210.629z M210.629,332.27v37.406h-37.397V332.27H210.629z M210.629,273.122v37.396h-37.397 v-37.396H210.629z">
                            </path>
                        </g>
                    </svg>
                </template>
                <template v-if="iconName === 'code'">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.1987 2.58728C11.6971 2.31042 12.303 2.31043 12.8013 2.58728L20.0013 6.58728C20.5252 6.87829 20.85 7.43041 20.85 8.02964V15.9705C20.85 16.5697 20.5252 17.1219 20.0013 17.4129L12.8013 21.4129C12.303 21.6897 11.6971 21.6897 11.1987 21.4129L3.99871 17.4129C3.47489 17.1219 3.15002 16.5697 3.15002 15.9705V8.02964C3.15002 7.43041 3.4749 6.87829 3.99871 6.58728L11.1987 2.58728ZM12.0729 3.89852C12.0276 3.87335 11.9725 3.87335 11.9272 3.89851L4.72718 7.89852C4.67956 7.92497 4.65002 7.97516 4.65002 8.02964V15.9705C4.65002 16.025 4.67956 16.0752 4.72718 16.1016L11.9272 20.1016C11.9725 20.1268 12.0276 20.1268 12.0729 20.1016L19.2729 16.1016C19.3205 16.0752 19.35 16.025 19.35 15.9705V8.02964C19.35 7.97516 19.3205 7.92497 19.2729 7.89852L12.0729 3.89852Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.25002 12.0001C7.25002 9.37672 9.37667 7.25008 12 7.25008C14.6234 7.25008 16.75 9.37672 16.75 12.0001C16.75 14.6234 14.6234 16.7501 12 16.7501C9.37667 16.7501 7.25002 14.6234 7.25002 12.0001ZM12 8.75008C10.2051 8.75008 8.75002 10.2051 8.75002 12.0001C8.75002 13.795 10.2051 15.2501 12 15.2501C13.795 15.2501 15.25 13.795 15.25 12.0001C15.25 10.2051 13.795 8.75008 12 8.75008Z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                </template>
                <!-- Puedes agregar más bloques <template> para otros íconos -->
            </div>
            <input :type="type" :id="id" :value="modelValue" :placeholder="placeholder" :class="inputClasses"
                :validation="validation" @input="updateValue($event)" />
        </div>
    </div>
</template>
  
<script>
import { ref, toRefs, onMounted, } from 'vue';
export default {
    props: {
        label: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'text'
        },
        placeholder: {
            type: String,
            default: ''
        },
        modelValue: {
            type: String,
            default: '',
        },
        id: {
            type: String,
            default: '',
            required: true
        },
        iconName: {
            type: String,
            default: ''
        }, // Nueva propiedad para el nombre del ícono SVG
        validation: {
            type: Object,
            default: {},
        },
    },
    emits: ["update:modelValue"],
    setup(props, { emit }) {
        const { validation } = toRefs(props);

        const updateValue = ($event) => {

            if (validation.value.upper) {
                $event.target.value = $event.target.value.toUpperCase();
            }

            if (validation.value.limit) {
                const limit2 = validation.value.limit;
                if ($event.target.value.length > limit2) {
                    $event.target.value = $event.target.value.substring(0, limit2);
                }
            }

            if (validation.value.number) {
                $event.target.value = $event.target.value.replace(/[^0-9]/g, '');
            }

            if (validation.value.amount) {
                let x = $event.target.value.replace(/^\./, '').replace(/[^0-9.]/g, '');
                $event.target.value = x;
                const regex = /^(\d+)?([.]?\d{0,2})?$/;
                if (!regex.test($event.target.value)) {
                    const matched = $event.target.value.match(regex);
                    $event.target.value = matched ? matched[0] : x.substring(0, x.length - 1);
                }
            }

            if (validation.value.phoneNumber) {
                const phoneNumber = $event.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})/);
                $event.target.value = !phoneNumber[2] ? phoneNumber[1] : phoneNumber[1] + '-' + phoneNumber[2] + (phoneNumber[4] ? '-' + phoneNumber[4] : '');
            }

            emit('update:modelValue', $event.target.value);
        };
        // Dynamic classes for the input
        const inputClasses = [
            'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 pl-10',
            'dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
        ];

        return {
            inputClasses,
            updateValue
        };
    }
};
</script>
  