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
    <svg v-else-if="iconName === 'personInfoMulti'" class="w-5 h-5 text-[#001c48] dark:text-gray-600" viewBox="0 0 24 24"
        fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <rect width="24" height="24" fill="white"></rect>
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9999 6C9.79077 6 7.99991 7.79086 7.99991 10C7.99991 12.2091 9.79077 14 11.9999 14C14.209 14 15.9999 12.2091 15.9999 10C15.9999 7.79086 14.209 6 11.9999 6ZM17.1115 15.9974C17.8693 16.4854 17.8323 17.5491 17.1422 18.1288C15.7517 19.2966 13.9581 20 12.0001 20C10.0551 20 8.27215 19.3059 6.88556 18.1518C6.18931 17.5723 6.15242 16.5032 6.91351 16.012C7.15044 15.8591 7.40846 15.7251 7.68849 15.6097C8.81516 15.1452 10.2542 15 12 15C13.7546 15 15.2018 15.1359 16.3314 15.5954C16.6136 15.7102 16.8734 15.8441 17.1115 15.9974Z"
                fill="currentColor"></path>
        </g>
    </svg>
    <svg v-if="iconName === 'activate'" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path
                d="M16.3788 6.20698C15.1885 5.25459 13.7434 4.5 12 4.5C7.85787 4.5 4.5 7.85786 4.5 12C4.5 16.1421 7.85787 19.5 12 19.5C15.2549 19.5 18.028 17.4254 19.0646 14.5256C19.2505 14.0055 19.775 13.6568 20.3153 13.7713L21.2935 13.9787C21.8338 14.0932 22.1836 14.6262 22.0179 15.1531C20.6787 19.4112 16.7016 22.5 12 22.5C6.20101 22.5 1.5 17.799 1.5 12C1.5 6.20101 6.20101 1.5 12 1.5C14.7835 1.5 16.9516 2.76847 18.5112 4.0746L20.2929 2.29289C20.5789 2.00689 21.009 1.92134 21.3827 2.07612C21.7564 2.2309 22 2.59554 22 3V8.5C22 9.05228 21.5523 9.5 21 9.5H15.5C15.0956 9.5 14.7309 9.25636 14.5761 8.88268C14.4214 8.50901 14.5069 8.07889 14.7929 7.79289L16.3788 6.20698Z"
                fill="currentColor"></path>
        </g>
    </svg>
    <svg v-if="iconName === 'desactivate'" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path
                d="M7.62095 6.20695C8.81127 5.25458 10.2564 4.5 11.9998 4.5C16.1419 4.5 19.4998 7.85786 19.4998 12C19.4998 16.1421 16.1419 19.5 11.9998 19.5C8.74488 19.5 5.97175 17.4254 4.93515 14.5256C4.74925 14.0055 4.22477 13.6568 3.68448 13.7713L2.70621 13.9787C2.16592 14.0932 1.81614 14.6262 1.98184 15.1531C3.32107 19.4112 7.2982 22.5 11.9998 22.5C17.7987 22.5 22.4998 17.799 22.4998 12C22.4998 6.20101 17.7987 1.5 11.9998 1.5C9.21627 1.5 7.04815 2.76845 5.48857 4.07458L3.70689 2.29289C3.42089 2.00689 2.99077 1.92134 2.6171 2.07612C2.24342 2.2309 1.99978 2.59554 1.99978 3V8.5C1.99978 9.05228 2.4475 9.5 2.99978 9.5H8.49978C8.90424 9.5 9.26888 9.25636 9.42366 8.88268C9.57844 8.50901 9.49289 8.07889 9.20689 7.79289L7.62095 6.20695Z"
                fill="currentColor"></path>
        </g>
    </svg>
    <svg v-if="iconName === 'left-arrow-circle'" viewBox="-3 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" fill="currentColor" stroke="currentColor">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
        </g>
        <g id="SVGRepo_iconCarrier">
            <g id="icomoon-ignore"> </g>
            <path
                d="M13.114 2.887c-7.243 0-13.114 5.871-13.114 13.113s5.871 13.113 13.114 13.113c7.242 0 13.112-5.871 13.112-13.113s-5.87-13.113-13.112-13.113zM13.114 28.064c-6.653 0-12.065-5.412-12.065-12.064s5.412-12.063 12.065-12.063c6.652 0 12.063 5.412 12.063 12.063s-5.411 12.064-12.063 12.064z"
                fill="currentColor"> </path>
            <path d="M12.318 10.363l-0.742-0.742-6.379 6.379 6.379 6.379 0.742-0.742-5.113-5.113h12.726v-1.049h-12.726z"
                fill="currentColor"> </path>
        </g>
    </svg>
    <svg v-if="iconName === 'right-arrow-circle'" viewBox="-3 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" fill="currentColor" stroke="currentColor">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <g id="icomoon-ignore"> </g>
            <path
                d="M13.11 29.113c7.243 0 13.113-5.871 13.113-13.113s-5.87-13.113-13.113-13.113c-7.242 0-13.113 5.871-13.113 13.113s5.871 13.113 13.113 13.113zM13.11 3.936c6.652 0 12.064 5.412 12.064 12.064s-5.412 12.064-12.064 12.064c-6.653 0-12.064-5.412-12.064-12.064s5.411-12.064 12.064-12.064z"
                fill="currentColor"> </path>
            <path d="M13.906 21.637l0.742 0.742 6.378-6.379-6.378-6.379-0.742 0.742 5.112 5.112h-12.727v1.049h12.727z"
                fill="currentColor"> </path>
        </g>
    </svg>
    <svg v-if="iconName === 'see'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    <svg v-if="iconName === 'nextSvgVector'" fill="currentColor" version="1.1" id="XMLID_287_"
        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"
        xml:space="preserve">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <g id="next">
                <g>
                    <polygon points="6.8,23.7 5.4,22.3 15.7,12 5.4,1.7 6.8,0.3 18.5,12 "></polygon>
                </g>
            </g>
        </g>
    </svg>
    <svg v-if="iconName === 'trash'" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd"
            d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z"
            fill="currentColor" />
        <path d="M9 9H11V17H9V9Z" fill="currentColor" />
        <path d="M13 9H15V17H13V9Z" fill="currentColor" />
    </svg>
    <svg v-if="iconName === 'clipboard-checked'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path>
            <path d="M15 2H9a1 1 0 00-1 1v2a1 1 0 001 1h6a1 1 0 001-1V3a1 1 0 00-1-1z"></path>
            <path d="M9 13l2 2 4-4"></path>
        </g>
    </svg>
    <svg v-if="iconName === 'clipboard-arrow'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path d="M16 4h2a2 2 0 012 2v4M8 4H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2v-2"></path>
            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
            <path d="M21 14H11"></path>
            <path d="M15 10l-4 4 4 4"></path>
        </g>
    </svg>
    <svg v-if="iconName === 'clipboard-arrow-up'" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path
                d="M8 5.00005C7.01165 5.00082 6.49359 5.01338 6.09202 5.21799C5.71569 5.40973 5.40973 5.71569 5.21799 6.09202C5 6.51984 5 7.07989 5 8.2V17.8C5 18.9201 5 19.4802 5.21799 19.908C5.40973 20.2843 5.71569 20.5903 6.09202 20.782C6.51984 21 7.07989 21 8.2 21H15.8C16.9201 21 17.4802 21 17.908 20.782C18.2843 20.5903 18.5903 20.2843 18.782 19.908C19 19.4802 19 18.9201 19 17.8V8.2C19 7.07989 19 6.51984 18.782 6.09202C18.5903 5.71569 18.2843 5.40973 17.908 5.21799C17.5064 5.01338 16.9884 5.00082 16 5.00005M8 5.00005V7H16V5.00005M8 5.00005V4.70711C8 4.25435 8.17986 3.82014 8.5 3.5C8.82014 3.17986 9.25435 3 9.70711 3H14.2929C14.7456 3 15.1799 3.17986 15.5 3.5C15.8201 3.82014 16 4.25435 16 4.70711V5.00005M12 11V17M12 11L14 13M12 11L10 13"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </g>
    </svg>
    <svg v-if="iconName === 'download-file'" fill="currentColor" viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path
                d="M 15.5547 53.125 L 40.4453 53.125 C 45.2969 53.125 47.7109 50.6640 47.7109 45.7890 L 47.7109 24.5078 L 30.7422 24.5078 C 27.7422 24.5078 26.3359 23.0781 26.3359 20.0781 L 26.3359 2.8750 L 15.5547 2.8750 C 10.7266 2.8750 8.2891 5.3594 8.2891 10.2344 L 8.2891 45.7890 C 8.2891 50.6875 10.7266 53.125 15.5547 53.125 Z M 30.8125 21.2969 L 47.4531 21.2969 C 47.2891 20.3359 46.6094 19.3984 45.5078 18.2500 L 32.5703 5.1015 C 31.4922 3.9766 30.5078 3.2969 29.5234 3.1328 L 29.5234 20.0313 C 29.5234 20.875 29.9687 21.2969 30.8125 21.2969 Z M 28.0000 47.1719 C 27.5547 47.1719 27.1797 47.0078 26.6875 46.5859 L 20.1953 40.5859 C 19.8203 40.2578 19.6094 39.8594 19.6094 39.3203 C 19.6094 38.4062 20.3125 37.7266 21.2734 37.7266 C 21.7187 37.7266 22.1875 37.9140 22.5391 38.2422 L 24.5078 40.2578 L 26.3828 42.1562 L 26.2187 38.6172 L 26.2187 30.7187 C 26.2187 29.7813 27.0391 28.9844 28.0000 28.9844 C 28.9844 28.9844 29.8047 29.7813 29.8047 30.7187 L 29.8047 38.6172 L 29.6406 42.1562 L 31.4922 40.2578 L 33.4844 38.2422 C 33.8125 37.9140 34.2813 37.7266 34.7266 37.7266 C 35.6875 37.7266 36.3906 38.4062 36.3906 39.3203 C 36.3906 39.8594 36.2031 40.2344 35.8047 40.5859 L 29.3125 46.5859 C 28.8437 47.0078 28.4453 47.1719 28.0000 47.1719 Z">
            </path>
        </g>
    </svg>
    <svg v-if="iconName === 'pdf'" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="currentColor">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <g>
                <path class="fill-current"
                    d="M189.031,111.069V6.177L68.398,126.817h104.884C184.618,126.817,189.031,122.404,189.031,111.069z">
                </path>
                <path class="fill-current"
                    d="M252.038,254.612c2.19-1.672,4.397-15.914,8.777-37.329c4.397-21.398-1.104-34.563-12.605-32.899 c-11.535,1.63-14.26,14.794-13.157,28.519c1.087,13.708,8.208,29.072,12.605,37.856 C247.658,250.759,249.848,256.251,252.038,254.612z">
                </path>
                <path class="fill-current"
                    d="M198.258,365.431c-3.728,1.379-28.503,8.785-43.314,23.036c-14.829,14.277-13.742,26.881-7.138,31.27 c6.57,4.381,25.226-8.777,36.761-22.484c11.518-13.733,19.192-27.442,19.192-29.631 C203.759,365.431,202.672,363.777,198.258,365.431z">
                </path>
                <path class="fill-current"
                    d="M239.45,341.835c10.934-4.38,50.436-13.174,50.436-13.174s-25.762-30.701-29.071-36.745 C260.815,291.917,245.452,331.947,239.45,341.835z">
                </path>
                <path class="fill-current"
                    d="M429.091,0h-216.74v125.104c0,13.8-11.234,25.043-25.026,25.043H62.498V491.58 c0,11.259,9.161,20.42,20.411,20.42h346.182c11.25,0,20.411-9.161,20.411-20.42V20.404C449.502,9.152,440.342,0,429.091,0z M362.322,380.77c-19.325-2.633-42.814-22.719-62.707-40.246c-0.586-0.201-1.104-0.318-1.488-0.318 c-3.227,0-39.118,10.34-66.401,18.088c-39.854,71.266-79.558,91.612-98.198,68.032c-9.796-12.412-6.018-41.702,41.158-63.652 c9.478-4.38,22.668-9.57,38.048-14.26c13.14-23.864,26.397-56.838,33.82-76.356c-12.104-23.153-21.933-47.544-23.037-59.137 c-2.206-23.062,7.674-43.917,27.433-43.917c25.176,0,43.866,24.14,14.36,109.038c9.194,15.088,21.632,33.385,35.976,47.904 c24.792-4.722,49.566-6.185,59.865-5.567c35.507,2.14,42.88,18.716,43.966,31.337C406.222,364.337,390.307,384.623,362.322,380.77z ">
                </path>
                <path class="fill-current"
                    d="M358.478,331.947c-13.24-0.853-47.728,3.836-43.9,5.474c0,0,27.984,19.786,44.417,21.959 c16.5,2.19,22.535-4.932,22.535-12.062C381.53,340.18,375.495,333.05,358.478,331.947z">
                </path>
            </g>
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