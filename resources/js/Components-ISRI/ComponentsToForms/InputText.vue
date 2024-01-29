<template>
    <div>
        <label :for="id" class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">{{ label }}
            <span v-if="required" class="text-red-600 font-extrabold">*</span></label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex rounded-l-md items-center px-2 pointer-events-none border-r border-gray-300"
                :class="addClasses">
                <template v-if="iconName === 'email'">
                    <svg class="w-[22px] h-[22px] p-0.5 dark:text-gray-500" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                        <path
                            d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
                        <path
                            d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
                    </svg>
                </template>
                <template v-else-if="iconName === 'address'">
                    <svg class="w-5 h-5 dark:text-gray-600" viewBox="0 0 24 24" fill="none"
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
                <template v-else-if="iconName === 'oldPhone'">
                    <svg class="w-5 h-5 p-0.5 dark:text-gray-600" version="1.1" id="_x32_"
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
                <template v-else-if="iconName === 'code'">
                    <svg class="w-5 h-5 dark:text-gray-700" viewBox="0 0 24 24" fill="none"
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
                <template v-else-if="iconName === 'date'">
                    <svg class="w-5 h-5 dark:text-gray-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M20 10V7C20 5.89543 19.1046 5 18 5H6C4.89543 5 4 5.89543 4 7V10M20 10V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V10M20 10H4M8 3V7M16 3V7"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            <rect x="6" y="12" width="3" height="3" rx="0.5" fill="currentColor"></rect>
                            <rect x="10.5" y="12" width="3" height="3" rx="0.5" fill="currentColor"></rect>
                            <rect x="15" y="12" width="3" height="3" rx="0.5" fill="currentColor"></rect>
                        </g>
                    </svg>
                </template>
                <template v-else-if="iconName === 'number'">
                    <svg class="w-4 h-4 dark:text-gray-600" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11 1v4h4v1h-4v4h4v1h-4v4h-1v-4H6v4H5v-4H1v-1h4V6H1V5h4V1h1v4h4V1h1zM6 6v4h4V6H6z">
                            </path>
                        </g>
                    </svg>
                </template>
                <template v-else-if="iconName === 'money'">
                    <svg class="w-5 h-5 dark:text-gray-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            </path>
                            <path
                                d="M15 8H12M9 16H12M12 16H13C14.1046 16 15 15.1046 15 14V14C15 12.8954 14.1046 12 13 12H11C9.89543 12 9 11.1046 9 10V10C9 8.89543 9.89543 8 11 8H12M12 16V18M12 8V6"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            </path>
                        </g>
                    </svg>
                </template>
                <template v-else-if="iconName === 'personalInfo'">
                    <svg class="w-5 h-5 dark:text-gray-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <rect width="24" height="24" fill="white"></rect>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9999 6C9.79077 6 7.99991 7.79086 7.99991 10C7.99991 12.2091 9.79077 14 11.9999 14C14.209 14 15.9999 12.2091 15.9999 10C15.9999 7.79086 14.209 6 11.9999 6ZM17.1115 15.9974C17.8693 16.4854 17.8323 17.5491 17.1422 18.1288C15.7517 19.2966 13.9581 20 12.0001 20C10.0551 20 8.27215 19.3059 6.88556 18.1518C6.18931 17.5723 6.15242 16.5032 6.91351 16.012C7.15044 15.8591 7.40846 15.7251 7.68849 15.6097C8.81516 15.1452 10.2542 15 12 15C13.7546 15 15.2018 15.1359 16.3314 15.5954C16.6136 15.7102 16.8734 15.8441 17.1115 15.9974Z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                </template>
                <template v-else>
                    <svg class="w-5 h-5 p-0.5 dark:text-gray-600" fill="currentColor" version="1.1" id="Icons"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32"
                        xml:space="preserve">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <path d="M20,5h9c0.6,0,1-0.4,1-1s-0.4-1-1-1h-9c-0.6,0-1,0.4-1,1S19.4,5,20,5z"></path>
                                <path d="M29,9h-9c-0.6,0-1,0.4-1,1s0.4,1,1,1h9c0.6,0,1-0.4,1-1S29.6,9,29,9z"></path>
                                <path d="M29,15h-9c-0.6,0-1,0.4-1,1s0.4,1,1,1h9c0.6,0,1-0.4,1-1S29.6,15,29,15z"></path>
                                <path d="M29,21H3c-0.6,0-1,0.4-1,1s0.4,1,1,1h26c0.6,0,1-0.4,1-1S29.6,21,29,21z"></path>
                                <path d="M29,27H3c-0.6,0-1,0.4-1,1s0.4,1,1,1h26c0.6,0,1-0.4,1-1S29.6,27,29,27z"></path>
                                <path
                                    d="M3,11h9.6l-2.3,2.3c-0.4,0.4-0.4,1,0,1.4c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3l4-4c0.1-0.1,0.2-0.2,0.2-0.3 c0.1-0.2,0.1-0.5,0-0.8c-0.1-0.1-0.1-0.2-0.2-0.3l-4-4c-0.4-0.4-1-0.4-1.4,0s-0.4,1,0,1.4L12.6,9H3c-0.6,0-1,0.4-1,1S2.4,11,3,11z">
                                </path>
                            </g>
                        </g>
                    </svg>
                </template>
            </div>
            <input :type="type" :id="id" :required="required" :value="modelValue" :placeholder="placeholder"
                :class="hasError ? 'border border-red-400' : 'bg-gray-50 border border-gray-300'" :validation="validation" @input="updateValue($event)"
                class="text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 font-semibold text-[12px] placeholder-gray-400
                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 hover:border-gray-400 transition duration-300 ease-in-out" />
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
        hasError: {
            type: Boolean,
            default: false
        },
        required: {
            type: Boolean,
            default: false
        },
        addClasses: {
            type: String,
            default: 'text-[#001c48]'
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
            'bg-red-50 border border-red-400 text-gray-900 rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 font-semibold text-[12px] placeholder-gray-400',
            'dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
        ];

        return {
            inputClasses,
            updateValue
        };
    }
};
</script>
  