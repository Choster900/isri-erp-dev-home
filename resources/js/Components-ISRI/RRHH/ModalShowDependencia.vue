
<template>
    <div class="m-1.5">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class="bg-gray-200 rounded-lg p-1 font-semibold">
                    <span class="text-blue-800">CARGANDO...</span>
                </div>
            </div>
        </div>
        <ProcessModal v-else :show="showModalDetail" @close="$emit('cerrar-modal')" :center="true" :rounded=true maxWidth="2xl">
            <div class="px-5 py-4">
                <div class="text-sm">
                    <div class="flex items-center justify-center bg-[#1F3558] py-1.5 rounded-t-lg mt-4">
                        <svg class="text-white " width="22px" height="22px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M22 8.52V3.98C22 2.57 21.36 2 19.77 2H15.73C14.14 2 13.5 2.57 13.5 3.98V8.51C13.5 9.93 14.14 10.49 15.73 10.49H19.77C21.36 10.5 22 9.93 22 8.52ZM22 19.77V15.73C22 14.14 21.36 13.5 19.77 13.5H15.73C14.14 13.5 13.5 14.14 13.5 15.73V19.77C13.5 21.36 14.14 22 15.73 22H19.77C21.36 22 22 21.36 22 19.77ZM10.5 8.52V3.98C10.5 2.57 9.86 2 8.27 2H4.23C2.64 2 2 2.57 2 3.98V8.51C2 9.93 2.64 10.49 4.23 10.49H8.27C9.86 10.5 10.5 9.93 10.5 8.52ZM10.5 19.77V15.73C10.5 14.14 9.86 13.5 8.27 13.5H4.23C2.64 13.5 2 14.14 2 15.73V19.77C2 21.36 2.64 22 4.23 22H8.27C9.86 22 10.5 21.36 10.5 19.77Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                        <h1 class="ml-2 text-white">{{ depToShow.nombre_dependencia }}</h1>
                    </div>
                    <div class="flex w-full pb-4 border-b border-x border-gray-400 rounded-b-lg">
                        <div class="mt-3 w-full mx-4 relative">
                            <div class="w-full border-b border-gray-300">
                                <p class="text-gray-700 text-center text-[14px] font-semibold  mb-2">INFORMACION GENERAL</p>
                            </div>
                            <div class="mt-3 flex">
                                <div class="w-[60%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg width="20px" height="20px" viewBox="0 0 20 20" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <g id="layer1">
                                                    <path
                                                        d="M 10 0 L 9.6054688 0.015625 L 9.2148438 0.0625 L 8.828125 0.13867188 L 8.4492188 0.24414062 L 8.078125 0.38085938 L 7.7207031 0.54492188 L 7.3769531 0.73632812 L 7.0488281 0.95507812 L 6.7382812 1.1972656 L 6.4492188 1.4648438 L 6.1816406 1.7539062 L 5.9375 2.0605469 L 5.71875 2.3867188 L 5.5253906 2.7304688 L 5.359375 3.0859375 L 5.2246094 3.4550781 L 5.1171875 3.8339844 L 5.0390625 4.21875 L 4.9941406 4.609375 L 4.9785156 5 L 4.9941406 5.6132812 L 5.0371094 6.2246094 L 5.1132812 6.8320312 L 5.21875 7.4355469 L 5.3535156 8.0332031 L 5.5175781 8.625 L 5.7089844 9.2050781 L 5.9296875 9.7773438 L 6.1796875 10.335938 L 6.4550781 10.884766 L 6.6386719 11.193359 L 6.8476562 11.484375 L 6.9101562 11.582031 L 6.9472656 11.689453 L 6.9570312 11.804688 L 6.9414062 11.919922 L 6.8984375 12.025391 L 6.8359375 12.121094 L 6.75 12.197266 L 6.6484375 12.253906 L 6.5390625 12.287109 L 5.8183594 12.419922 L 5.1054688 12.576172 L 4.3964844 12.755859 L 3.6953125 12.960938 L 3.0019531 13.191406 L 2.3164062 13.445312 L 2.0371094 13.570312 L 1.7734375 13.722656 L 1.5253906 13.902344 L 1.296875 14.103516 L 1.0917969 14.328125 L 0.91015625 14.570312 L 0.75390625 14.832031 L 0.62304688 15.107422 L 0.4453125 15.578125 L 0.29492188 16.056641 L 0.171875 16.542969 L 0.076171875 17.035156 L 0.0078125 17.53125 L 0 17.736328 L 0.01953125 17.9375 L 0.06640625 18.134766 L 0.13867188 18.324219 L 0.23632812 18.501953 L 0.35546875 18.666016 L 0.5 18.810547 L 0.65820312 18.935547 L 0.8359375 19.037109 L 1.0234375 19.117188 L 1.2207031 19.166016 L 2.3789062 19.375 L 3.5429688 19.552734 L 4.7109375 19.701172 L 5.8828125 19.820312 L 7.0566406 19.910156 L 8.234375 19.970703 L 9.4121094 20 L 10.587891 20 L 11.765625 19.970703 L 12.943359 19.910156 L 14.117188 19.820312 L 15.289062 19.701172 L 16.457031 19.552734 L 17.619141 19.375 L 18.779297 19.166016 L 18.976562 19.117188 L 19.164062 19.037109 L 19.339844 18.935547 L 19.5 18.810547 L 19.642578 18.666016 L 19.763672 18.501953 L 19.861328 18.324219 L 19.933594 18.134766 L 19.980469 17.9375 L 20 17.736328 L 19.992188 17.53125 L 19.923828 17.035156 L 19.828125 16.542969 L 19.705078 16.056641 L 19.554688 15.578125 L 19.376953 15.107422 L 19.248047 14.832031 L 19.089844 14.570312 L 18.908203 14.328125 L 18.703125 14.103516 L 18.474609 13.902344 L 18.226562 13.722656 L 17.962891 13.570312 L 17.683594 13.445312 L 16.998047 13.191406 L 16.304688 12.960938 L 15.603516 12.755859 L 14.896484 12.576172 L 14.183594 12.419922 L 13.462891 12.287109 L 13.351562 12.253906 L 13.25 12.197266 L 13.166016 12.121094 L 13.101562 12.025391 L 13.060547 11.919922 L 13.042969 11.804688 L 13.054688 11.689453 L 13.091797 11.582031 L 13.152344 11.484375 L 13.363281 11.193359 L 13.546875 10.884766 L 13.822266 10.335938 L 14.070312 9.7773438 L 14.291016 9.2050781 L 14.484375 8.625 L 14.648438 8.0332031 L 14.783203 7.4355469 L 14.886719 6.8320312 L 14.962891 6.2246094 L 15.007812 5.6132812 L 15.021484 5 L 15.007812 4.609375 L 14.960938 4.21875 L 14.882812 3.8339844 L 14.777344 3.4550781 L 14.640625 3.0859375 L 14.474609 2.7304688 L 14.283203 2.3867188 L 14.064453 2.0605469 L 13.818359 1.7539062 L 13.552734 1.4648438 L 13.263672 1.1972656 L 12.953125 0.95507812 L 12.625 0.73632812 L 12.279297 0.54492188 L 11.921875 0.38085938 L 11.552734 0.24414062 L 11.173828 0.13867188 L 10.785156 0.0625 L 10.394531 0.015625 L 10 0 z M 9.9980469 1 L 10.349609 1.015625 L 10.697266 1.0625 L 11.039062 1.1367188 L 11.373047 1.2421875 L 11.697266 1.375 L 12.009766 1.5371094 L 12.304688 1.7246094 L 12.582031 1.9355469 L 12.841797 2.171875 L 13.076172 2.4296875 L 13.291016 2.7070312 L 13.480469 3 L 13.640625 3.3105469 L 13.775391 3.6328125 L 13.880859 3.9667969 L 13.957031 4.3066406 L 14.001953 4.6523438 L 14.017578 5.0019531 L 14.003906 5.5644531 L 13.962891 6.1269531 L 13.892578 6.6855469 L 13.794922 7.2402344 L 13.671875 7.7910156 L 13.521484 8.3339844 L 13.345703 8.8691406 L 13.140625 9.3945312 L 12.912109 9.9101562 L 12.658203 10.414062 L 12.521484 10.644531 L 12.363281 10.863281 L 12.236328 11.046875 L 12.142578 11.248047 L 12.076172 11.460938 L 12.041016 11.679688 L 12.041016 11.902344 L 12.074219 12.121094 L 12.138672 12.333984 L 12.234375 12.535156 L 12.359375 12.71875 L 12.507812 12.882812 L 12.681641 13.023438 L 12.875 13.136719 L 13.082031 13.220703 L 13.296875 13.273438 L 13.982422 13.400391 L 14.662109 13.548828 L 15.335938 13.720703 L 16.003906 13.916016 L 16.664062 14.134766 L 17.318359 14.376953 L 17.529297 14.472656 L 17.728516 14.59375 L 17.912109 14.736328 L 18.078125 14.898438 L 18.222656 15.078125 L 18.345703 15.277344 L 18.445312 15.484375 L 18.603516 15.902344 L 18.738281 16.328125 L 18.847656 16.761719 L 18.931641 17.197266 L 18.992188 17.640625 L 18.990234 17.763672 L 18.958984 17.884766 L 18.896484 17.990234 L 18.8125 18.082031 L 18.707031 18.146484 L 18.587891 18.185547 L 17.455078 18.388672 L 16.316406 18.5625 L 15.173828 18.708984 L 14.027344 18.826172 L 12.878906 18.912109 L 11.726562 18.970703 L 10.576172 19 L 9.421875 19 L 8.2714844 18.970703 L 7.1210938 18.912109 L 5.9726562 18.826172 L 4.8242188 18.708984 L 3.6835938 18.5625 L 2.5429688 18.388672 L 1.4101562 18.185547 L 1.2929688 18.146484 L 1.1855469 18.082031 L 1.1015625 17.990234 L 1.0410156 17.884766 L 1.0078125 17.763672 L 1.0058594 17.640625 L 1.0683594 17.197266 L 1.1523438 16.761719 L 1.2617188 16.328125 L 1.3945312 15.902344 L 1.5527344 15.484375 L 1.6542969 15.277344 L 1.7773438 15.078125 L 1.921875 14.898438 L 2.0878906 14.736328 L 2.2714844 14.59375 L 2.4707031 14.472656 L 2.6816406 14.376953 L 3.3339844 14.134766 L 3.9941406 13.916016 L 4.6621094 13.720703 L 5.3378906 13.548828 L 6.0175781 13.400391 L 6.7011719 13.273438 L 6.9179688 13.220703 L 7.125 13.136719 L 7.3183594 13.023438 L 7.4902344 12.882812 L 7.6386719 12.71875 L 7.765625 12.535156 L 7.859375 12.333984 L 7.9238281 12.121094 L 7.9570312 11.902344 L 7.9570312 11.679688 L 7.921875 11.460938 L 7.8574219 11.248047 L 7.7617188 11.046875 L 7.6367188 10.863281 L 7.4785156 10.644531 L 7.3417969 10.414062 L 7.0859375 9.9101562 L 6.8574219 9.3945312 L 6.6542969 8.8691406 L 6.4785156 8.3339844 L 6.328125 7.7910156 L 6.203125 7.2402344 L 6.1074219 6.6855469 L 6.0371094 6.1269531 L 5.9960938 5.5644531 L 5.9804688 5.0019531 L 5.9980469 4.6523438 L 6.0429688 4.3066406 L 6.1191406 3.9667969 L 6.2246094 3.6328125 L 6.359375 3.3105469 L 6.5195312 3 L 6.7089844 2.7070312 L 6.921875 2.4296875 L 7.1582031 2.171875 L 7.4179688 1.9355469 L 7.6953125 1.7246094 L 7.9902344 1.5371094 L 8.3007812 1.375 L 8.625 1.2421875 L 8.9589844 1.1367188 L 9.3027344 1.0625 L 9.6503906 1.015625 L 9.9980469 1 z "
                                                        style="fill:#3B82F6; fill-opacity:1; stroke:none; stroke-width:0px;">
                                                    </path>
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="ml-2">Empleado a cargo</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.jefatura ? depToShow.jefatura.pnombre_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.snombre_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.tnombre_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.papellido_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.sapellido_persona : '' }}
                                        {{ depToShow.jefatura ? depToShow.jefatura.tapellido_persona : '' }}
                                    </p>
                                </div>
                                <div class="w-[40%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg fill="#3B82F6" width="20px" height="20px" viewBox="0 0 50 50"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M9 0C7.355469 0 6 1.355469 6 3L6 50L44 50L44 3C44 1.355469 42.644531 0 41 0 Z M 9 2L41 2C41.554688 2 42 2.445313 42 3L42 48L38 48L38 36L27 36L27 48L8 48L8 3C8 2.445313 8.445313 2 9 2 Z M 12 6L12 14L23 14L23 6 Z M 27 6L27 14L38 14L38 6 Z M 14 8L21 8L21 12L14 12 Z M 29 8L36 8L36 12L29 12 Z M 12 16L12 24L23 24L23 16 Z M 27 16L27 24L38 24L38 16 Z M 14 18L21 18L21 22L14 22 Z M 29 18L36 18L36 22L29 22 Z M 12 26L12 34L23 34L23 26 Z M 27 26L27 34L38 34L38 26 Z M 14 28L21 28L21 32L14 32 Z M 29 28L36 28L36 32L29 32 Z M 12 36L12 44L23 44L23 36 Z M 14 38L21 38L21 42L14 42 Z M 29 38L36 38L36 48L29 48Z">
                                                </path>
                                            </g>
                                        </svg>
                                        <span class="ml-1.5">Centro</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.centro_atencion ?
                                            depToShow.centro_atencion.codigo_centro_atencion : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 flex">
                                <div class="w-[25%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg class="text-gray-700" width="20px" height="20px" viewBox="0 0 16 16"
                                            fill="#3B82F6" xmlns="http://www.w3.org/2000/svg">
                                            <g stroke-width="0"></g>
                                            <g stroke-linecap="round" stroke-linejoin="round"></g>
                                            <g>
                                                <path
                                                    d="M0 2.96491C0 1.87972 0.879721 1 1.96491 1H4.77193C5.85712 1 6.73684 1.87972 6.73684 2.96491V3.52632C6.73684 4.61151 5.85712 5.49123 4.77193 5.49123H1.96491C0.879721 5.49123 0 4.61151 0 3.52632V2.96491ZM1.96491 2.68421C1.80989 2.68421 1.68421 2.80989 1.68421 2.96491V3.52632C1.68421 3.68134 1.80989 3.80702 1.96491 3.80702H4.77193C4.92696 3.80702 5.05263 3.68134 5.05263 3.52632V2.96491C5.05263 2.80988 4.92696 2.68421 4.77193 2.68421H1.96491Z" />
                                                <path
                                                    d="M8.14035 3.24561C8.14035 2.78053 8.51737 2.40351 8.98246 2.40351H15.1579C15.623 2.40351 16 2.78053 16 3.24561C16 3.7107 15.623 4.08772 15.1579 4.08772H8.98246C8.51737 4.08772 8.14035 3.7107 8.14035 3.24561Z" />
                                                <path
                                                    d="M0.280702 8.29825C0.280702 7.83316 0.657725 7.45614 1.12281 7.45614H11.7895C12.2546 7.45614 12.6316 7.83316 12.6316 8.29825C12.6316 8.76333 12.2546 9.14035 11.7895 9.14035H1.12281C0.657725 9.14035 0.280702 8.76333 0.280702 8.29825Z" />
                                                <path
                                                    d="M0.280702 13.3509C0.280702 12.8858 0.657725 12.5088 1.12281 12.5088H14.5965C15.0616 12.5088 15.4386 12.8858 15.4386 13.3509C15.4386 13.816 15.0616 14.193 14.5965 14.193H1.12281C0.657725 14.193 0.280702 13.816 0.280702 13.3509Z" />
                                                <path
                                                    d="M14.8772 9.14035C15.3423 9.14035 15.7193 8.76333 15.7193 8.29825C15.7193 7.83316 15.3423 7.45614 14.8772 7.45614C14.4121 7.45614 14.0351 7.83316 14.0351 8.29825C14.0351 8.76333 14.4121 9.14035 14.8772 9.14035Z" />
                                            </g>
                                        </svg>

                                        <span class="ml-2">Codigo</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.codigo_dependencia }}
                                    </p>
                                </div>
                                <div class="w-[35%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <g clip-path="url(#clip0_15_529)">
                                                    <rect width="24" height="24" fill="white"></rect>
                                                    <path
                                                        d="M2.01394 6.87134C1.34749 10.0618 3.85967 13.8597 7.01471 17.0147C10.1698 20.1698 13.9676 22.682 17.1581 22.0155C19.782 21.4674 21.1215 20.0697 21.8754 18.8788C22.1355 18.4678 22.0042 17.9344 21.6143 17.6436L17.9224 14.8897C17.5243 14.5928 16.9685 14.633 16.6174 14.9842L14.6577 16.9438C14.6577 16.9438 12.7529 16.3246 10.2288 13.8006C7.70482 11.2766 7.08564 9.37175 7.08564 9.37175L9.04529 7.4121C9.39648 7.06091 9.43671 6.5052 9.13975 6.10709L6.38585 2.4151C6.09505 2.02525 5.56163 1.89395 5.15068 2.15407C3.9597 2.90794 2.56203 4.24747 2.01394 6.87134Z"
                                                        stroke="#3B82F6" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_15_529">
                                                        <rect width="24" height="24" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </g>
                                        </svg>
                                        <span class="ml-1.5">Telefono</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.telefono_dependencia ?? 'Sin registro.' }}
                                    </p>
                                </div>
                                <div class="w-[40%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg height="20px" width="20px" version="1.1" id="_x32_"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 512 512" xml:space="preserve" fill="#3B82F6">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <g>
                                                    <path class="st0"
                                                        d="M490.237,90.753c-13.382-13.412-32.076-21.764-52.532-21.756H74.295C33.258,69.012,0.015,102.247,0,143.284 v225.432c0.015,41.036,33.258,74.272,74.295,74.286h363.41c20.456,0.008,39.15-8.344,52.532-21.755 C503.648,407.866,512,389.171,512,368.716V143.284C512,122.829,503.648,104.135,490.237,90.753z M74.295,107.04h363.41 c8.701,0.008,16.466,3.136,22.714,8.21L256,269.478L51.58,115.25C57.829,110.175,65.594,107.047,74.295,107.04z M38.042,368.716 V143.284c0.016-5.826,1.486-11.205,3.894-16.05l141.813,129.998L41.55,384.044C39.365,379.393,38.058,374.252,38.042,368.716z M437.705,404.96H74.295c-8.204-0.007-15.581-2.771-21.637-7.319l150.618-121.825l22.818,21.711 c16.911,15.529,42.902,15.529,59.813,0l22.818-21.711l150.61,121.825C453.279,402.189,445.901,404.953,437.705,404.96z M473.958,368.716c-0.016,5.535-1.322,10.677-3.508,15.328L328.251,257.233l141.805-129.998c2.415,4.844,3.886,10.217,3.901,16.05 V368.716z">
                                                    </path>
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="ml-1.5">Email</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.email_dependencia ?? 'Sin registro.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 flex">
                                <div class="w-full">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg class="text-gray-700" height="22px" width="22px" viewBox="0 0 24 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M9 20L3 17V4L9 7M9 20L15 17M9 20V7M15 17L21 20V7L15 4M15 17V4M9 7L15 4"
                                                    stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                </path>
                                            </g>
                                        </svg>
                                        <span class="ml-1.5">Dirección</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.direccion_dependencia ? depToShow.direccion_dependencia :
                                            'Sin registro.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 flex">
                                <div class="w-full">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg width="22px" height="22px" viewBox="0 0 64 64"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true" role="img" class="iconify iconify--emojione-monotone"
                                            preserveAspectRatio="xMidYMid meet" fill="#3B82F6">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M32 2L16 18h10v15h12V18h10z" fill="#3B82F6"></path>
                                                <path
                                                    d="M32 62c-3.308 0-6-2.848-6-6.348v-9.307C26 42.848 28.692 40 32 40s6 2.848 6 6.346v9.307C38 59.152 35.308 62 32 62m0-18.617c-1.654 0-3 1.33-3 2.963v9.307c0 1.633 1.346 2.965 3 2.965s3-1.332 3-2.965v-9.307c0-1.633-1.346-2.963-3-2.963"
                                                    fill="#3B82F6"></path>
                                                <path d="M22 40H10v3h4.5v19h3V43H22z" fill="#3B82F6"></path>
                                                <path
                                                    d="M48 40h-6v22h3v-9.309h3c3.308 0 6-2.848 6-6.348C54 42.846 51.308 40 48 40m0 9.309h-3v-5.926h3c1.654 0 3 1.328 3 2.961s-1.346 2.965-3 2.965"
                                                    fill="#3B82F6"></path>
                                            </g>
                                        </svg>
                                        <span class="ml-1.5">Dependencia jerárquica</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.dependencia_superior ?
                                            depToShow.dependencia_superior.nombre_dependencia
                                            + ' (' + depToShow.dependencia_superior.codigo_dependencia + ')' : '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 flex">
                                <div class="w-[35%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg class="text-blue-500" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8 13C7.44772 13 7 12.5523 7 12C7 11.4477 7.44772 11 8 11C8.55228 11 9 11.4477 9 12C9 12.5523 8.55228 13 8 13Z"
                                                fill="currentColor" />
                                            <path
                                                d="M8 17C7.44772 17 7 16.5523 7 16C7 15.4477 7.44772 15 8 15C8.55228 15 9 15.4477 9 16C9 16.5523 8.55228 17 8 17Z"
                                                fill="currentColor" />
                                            <path
                                                d="M11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16Z"
                                                fill="currentColor" />
                                            <path
                                                d="M16 17C15.4477 17 15 16.5523 15 16C15 15.4477 15.4477 15 16 15C16.5523 15 17 15.4477 17 16C17 16.5523 16.5523 17 16 17Z"
                                                fill="currentColor" />
                                            <path
                                                d="M11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12Z"
                                                fill="currentColor" />
                                            <path
                                                d="M16 13C15.4477 13 15 12.5523 15 12C15 11.4477 15.4477 11 16 11C16.5523 11 17 11.4477 17 12C17 12.5523 16.5523 13 16 13Z"
                                                fill="currentColor" />
                                            <path
                                                d="M8 7C7.44772 7 7 7.44772 7 8C7 8.55228 7.44772 9 8 9H16C16.5523 9 17 8.55228 17 8C17 7.44772 16.5523 7 16 7H8Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6 3C4.34315 3 3 4.34315 3 6V18C3 19.6569 4.34315 21 6 21H18C19.6569 21 21 19.6569 21 18V6C21 4.34315 19.6569 3 18 3H6ZM18 5H6C5.44772 5 5 5.44772 5 6V18C5 18.5523 5.44772 19 6 19H18C18.5523 19 19 18.5523 19 18V6C19 5.44772 18.5523 5 18 5Z"
                                                fill="currentColor" />
                                        </svg>
                                        <span class="ml-1.5">Fecha de registro</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.fecha_reg_dependencia ?
                                            moment(depToShow.fecha_reg_dependencia).format('DD/MM/YYYY') : 'Sin registro' }}
                                    </p>
                                </div>
                                <div class="w-[45%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg class="text-blue-500" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8 13C7.44772 13 7 12.5523 7 12C7 11.4477 7.44772 11 8 11C8.55228 11 9 11.4477 9 12C9 12.5523 8.55228 13 8 13Z"
                                                fill="currentColor" />
                                            <path
                                                d="M8 17C7.44772 17 7 16.5523 7 16C7 15.4477 7.44772 15 8 15C8.55228 15 9 15.4477 9 16C9 16.5523 8.55228 17 8 17Z"
                                                fill="currentColor" />
                                            <path
                                                d="M11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16Z"
                                                fill="currentColor" />
                                            <path
                                                d="M16 17C15.4477 17 15 16.5523 15 16C15 15.4477 15.4477 15 16 15C16.5523 15 17 15.4477 17 16C17 16.5523 16.5523 17 16 17Z"
                                                fill="currentColor" />
                                            <path
                                                d="M11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12Z"
                                                fill="currentColor" />
                                            <path
                                                d="M16 13C15.4477 13 15 12.5523 15 12C15 11.4477 15.4477 11 16 11C16.5523 11 17 11.4477 17 12C17 12.5523 16.5523 13 16 13Z"
                                                fill="currentColor" />
                                            <path
                                                d="M8 7C7.44772 7 7 7.44772 7 8C7 8.55228 7.44772 9 8 9H16C16.5523 9 17 8.55228 17 8C17 7.44772 16.5523 7 16 7H8Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6 3C4.34315 3 3 4.34315 3 6V18C3 19.6569 4.34315 21 6 21H18C19.6569 21 21 19.6569 21 18V6C21 4.34315 19.6569 3 18 3H6ZM18 5H6C5.44772 5 5 5.44772 5 6V18C5 18.5523 5.44772 19 6 19H18C18.5523 19 19 18.5523 19 18V6C19 5.44772 18.5523 5 18 5Z"
                                                fill="currentColor" />
                                        </svg>
                                        <span class="ml-1.5">Fecha ultima modificación</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.fecha_mod_dependencia ?
                                            moment(depToShow.fecha_mod_dependencia).format('DD/MM/YYYY') : 'Sin registro.' }}
                                    </p>
                                </div>
                                <div class="w-[20%]">
                                    <p class="text-gray-500 text-[14px] flex items-center">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M2.45001 14.97C3.52001 18.41 6.40002 21.06 9.98002 21.79"
                                                    stroke="#3B82F6" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path
                                                    d="M2.04999 10.98C2.55999 5.93 6.81998 2 12 2C17.18 2 21.44 5.94 21.95 10.98"
                                                    stroke="#3B82F6" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M14.01 21.8C17.58 21.07 20.45 18.45 21.54 15.02" stroke="#3B82F6"
                                                    stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </g>
                                        </svg>
                                        <span class="ml-1.5">Estado</span>
                                    </p>
                                    <p class="text-navy-700 text-[13px] font-semibold">
                                        {{ depToShow.estado_dependencia === 1 ? 'Activo' : 'Inactivo' }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <GeneralButton class="mr-1" text="Cerrar" icon="delete" @click="$emit('cerrar-modal')" />
                    </div>
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import ProcessModal from "@/Components-ISRI/AllModal/ProcessModal.vue";
import InputError from "@/Components/InputError.vue";
import { useDependencia } from '@/Composables/RRHH/Dependencia/useDependencia.js';
import { ref, toRefs, onMounted, } from 'vue';
import "vue3-toastify/dist/index.css";
import moment from 'moment';

export default {
    components: { ProcessModal, InputError },
    props: {
        showModalDetail: {
            type: Boolean,
            default: false
        },
        dependencyId: {
            type: Number,
            default: 0
        }
    },
    emits: ["cerrar-modal", "get-table"],
    setup(props, context) {
        const { dependencyId } = toRefs(props)
        const {
            isLoadingRequest,
            depToShow,
            getInfoForModalDependencias,
        } = useDependencia(context);

        onMounted(
            async () => {
                await getInfoForModalDependencias(dependencyId.value)
            }
        )

        return {
            isLoadingRequest,
            depToShow,
            moment
        };
    },
};
</script>

<style>
/* Customize the loader's appearance using Tailwind CSS classes or your custom styles */
.loader {
    border-top-color: #3498db;
    border-left-color: #3498db;
}</style>
