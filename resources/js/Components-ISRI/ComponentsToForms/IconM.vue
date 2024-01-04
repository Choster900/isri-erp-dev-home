<template>
    <svg v-if="iconName === 'editM'" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path
                d="M15.49 7.3h-1.16v6.35H1.67V3.28H8V2H1.67A1.21 1.21 0 0 0 .5 3.28v10.37a1.21 1.21 0 0 0 1.17 1.25h12.66a1.21 1.21 0 0 0 1.17-1.25z">
            </path>
            <path
                d="M10.56 2.87 6.22 7.22l-.44.44-.08.08-1.52 3.16a1.08 1.08 0 0 0 1.45 1.45l3.14-1.53.53-.53.43-.43 4.34-4.36.45-.44.25-.25a2.18 2.18 0 0 0 0-3.08 2.17 2.17 0 0 0-1.53-.63 2.19 2.19 0 0 0-1.54.63l-.7.69-.45.44zM5.51 11l1.18-2.43 1.25 1.26zm2-3.36 3.9-3.91 1.3 1.31L8.85 9zm5.68-5.31a.91.91 0 0 1 .65.27.93.93 0 0 1 0 1.31l-.25.24-1.3-1.3.25-.25a.88.88 0 0 1 .69-.25z">
            </path>
        </g>
    </svg>
    <svg v-else-if="iconName === 'personInfoMulti'" class="w-5 h-5 text-[#001c48] dark:text-gray-600" viewBox="0 0 24 24" fill="none"
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
<script>
export default {
    props: {
        iconName: {
            type: String,
            default: ''
        },
    }
}
</script>