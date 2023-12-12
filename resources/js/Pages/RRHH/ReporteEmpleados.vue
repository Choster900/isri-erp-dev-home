<template>
    <Head title="Reporte - Empleados" />
    <AppLayoutVue nameSubModule="RRHH - Reporte de Empleados" :autoPadding="false">

        <div class="py-3 w-full border-b border-slate-200 flex justify-center items-center">
            <button @click="openModal()"
                class="bg-emerald-600 hover:bg-emerald-800 text-white text-[14px] font-bold py-2 px-3 rounded-full">
                NUEVO REPORTE
            </button>
        </div>
        <!-- Tabla de reporte -->
        <div v-if="load != 0 && queryResult.length > 0" > <!-- Titulo del reporte-->
            <div class="rounded-md border-b border-slate-200 py-0.5">
                <div class="mx-8 text-[14px]">
                    <p class="font-bembo text-center">INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL</p>
                    <p class="font-bembo text-center">
                        {{ computedDependencyInfo }}
                    </p>
                    <p class="font-bembo text-center">{{ computedTitle }}</p>
                    <p class="font-bembo text-center">
                        <span class="font-semibold">{{ computedDate }}</span>
                    </p>
                </div>
            </div>
            <!-- Table header -->
            <div class="text-[14px] mx-10 my-3 flex justify-between items-center">
                <p class="text-orange-600 font-semibold mr-8">EMPLEADOS <span class="text-gray-500">({{ queryResult.length }})</span></p>
                <div class="flex">
                    <div class="flex items-center cursor-pointer text-slate-700 hover:text-green-600">
                        <svg class="h-4 w-4 text-green-500" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26 26" xml:space="preserve"
                            fill="#000000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path fill="currentColor"
                                        d="M25.162,3H16v2.984h3.031v2.031H16V10h3v2h-3v2h3v2h-3v2h3v2h-3v3h9.162 C25.623,23,26,22.609,26,22.13V3.87C26,3.391,25.623,3,25.162,3z M24,20h-4v-2h4V20z M24,16h-4v-2h4V16z M24,12h-4v-2h4V12z M24,8 h-4V6h4V8z">
                                    </path>
                                    <path fill="currentColor"
                                        d="M0,2.889v20.223L15,26V0L0,2.889z M9.488,18.08l-1.745-3.299c-0.066-0.123-0.134-0.349-0.205-0.678 H7.511C7.478,14.258,7.4,14.494,7.277,14.81l-1.751,3.27H2.807l3.228-5.064L3.082,7.951h2.776l1.448,3.037 c0.113,0.24,0.214,0.525,0.304,0.854h0.028c0.057-0.198,0.163-0.492,0.318-0.883l1.61-3.009h2.542l-3.037,5.022l3.122,5.107 L9.488,18.08L9.488,18.08z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span class="ml-2 font-semibold text-[14px]">EXPORTAR</span>
                    </div>
                    <div class="flex ml-4 items-center cursor-pointer text-slate-700 hover:text-red-600">
                        <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 1920 1920"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g fill-rule="evenodd">
                                    <path
                                        d="M1185.46.034V564.74h564.705v1355.294H168.99V.034h1016.47ZM900.508 677.68c-16.829 0-31.963 7.567-42.918 21.007-49.807 59.972-31.398 193.016-18.748 272.075 2.823 17.958.452 36.141-7.228 52.518l-107.86 233.223c-7.905 16.942-20.555 30.608-36.592 39.53-68.104 37.835-182.287 89.675-196.066 182.626-4.97 30.268 5.082 56.357 28.574 79.85 15.925 15.133 35.238 22.7 56.245 22.7 81.43 0 132.819-71.717 188.273-148.517 24.62-34.221 61.666-55.229 102.437-60.876 76.349-10.503 167.83-32.527 223.172-46.983 27.897-7.341 56.358-5.534 83.802 3.162 48.565 15.586 66.975 25.073 122.768 25.073 50.371 0 84.818-11.746 101.534-34.447 13.44-16.828 16.715-39.53 10.164-65.619-11.858-42.804-2.033-89.675-133.044-89.675-29.365 0-57.94 2.824-81.77 6.099-36.819 4.97-73.299-10.955-97.016-40.885-32.301-40.546-65.167-88.433-87.981-123.219-16.151-24.508-21.572-53.986-16.264-83.124 15.473-84.706 18.41-147.615-23.492-206.683-17.619-25.186-41.223-37.835-67.99-37.835Zm397.903-660.808 434.936 434.937h-434.936V16.873Z">
                                    </path>
                                    <path
                                        d="M791.057 1297.943c92.273-43.37 275.916-65.28 275.916-65.28-92.386-88.998-145.92-215.04-145.92-215.04-43.257 126.607-119.718 264.282-129.996 280.32">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span class="ml-2 text-[14px] font-semibold">PDF</span>
                    </div>
                </div>
            </div>
            <!-- Table body -->
            <div class="mx-10 overflow-x-auto">
                <div class="container mx-auto">
                    <div class="bg-white shadow-md rounded mt-2">
                        <div class="flex justify-between bg-teal-800 px-2 py-2 text-white text-[13px]">
                            <div class="w-[8%] text-center">CODIGO</div>
                            <div class="w-[32%] text-center">NOMBRE</div>
                            <div class="w-[10%] text-center">DUI</div>
                            <div class="w-[15%] text-center">PENSIONADO</div>
                            <div class="w-[35%] text-center">PUESTO</div>
                        </div>
                        <div v-for="(employee, index) in paginatedData" :key="index" :class="{
                            'bg-gray-200': index % 2 === 0,
                            'bg-gray-100': index % 2 !== 0
                        }"
                            class="flex justify-between border-b border-gray-100 text-[14px] px-2 py-2 hover:bg-gray-300">
                            <div class="w-[8%] text-center break-words overflow-wrap flex items-center justify-center">{{
                                employee.codigo_empleado }}</div>
                            <div class="w-[32%] text-center break-words overflow-wrap flex items-center justify-center">
                                {{ employee.persona.pnombre_persona }}
                                {{ employee.persona.snombre_persona }}
                                {{ employee.persona.tnombre_persona }}
                                {{ employee.persona.papellido_persona }}
                                {{ employee.persona.sapellido_persona }}
                                {{ employee.persona.tapellido_persona }}
                            </div>
                            <div class="w-[10%] text-center break-words overflow-wrap flex items-center justify-center">
                                {{ employee.persona.dui_persona }}
                            </div>
                            <div class="w-[15%] text-center break-words overflow-wrap flex items-center justify-center">
                                {{ employee.pensionado_empleado === 1 ? 'SI' : 'NO' }}
                            </div>
                            <div class="w-[35%] text-center break-words overflow-wrap flex flex-col items-center">
                                <template v-for="(plaza, index) in employee.plazas_asignadas" :key="index">
                                    <p :class="index != 0 ? 'border-t border-slate-400 w-full' : ''">
                                        {{
                                            plaza.detalle_plaza.plaza.nombre_plaza
                                        }}
                                    </p>
                                    <p :class="index === 0 ? 'pb-2' : ''">
                                        {{ ' (' +
                                            formatDate(plaza.fecha_plaza_asignada) +
                                            (plaza.fecha_renuncia_plaza_asignada ? ' - ' +
                                                formatDate(plaza.fecha_renuncia_plaza_asignada) + ')' :
                                                employee.id_estado_empleado === 1 ? ')' : ' - sin registro)') }}
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="(currentPage === totalPages)" class="mx-10 hover:bg-gray-300">
                <div class="py-2">
                    <div class="flex justify-between  px-2 py-2 text-black text-[15px]">
                        <div class="w-[40%] font-semibold text-center">TOTAL Empleados : {{ queryResult.length }}</div>
                        <div class="w-[5%] font-semibold text-center"></div>
                        <div class="w-[35%] font-semibold text-center">Pensionado SI = {{ retirementY }} ; NO = {{ retirementN }}</div>
                        <div class="w-[20%] font-semibold text-center"></div>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div class="flex justify-center items-center mt-4 pb-4">
                <button @click="currentPage > 1 ? currentPage-- : null" :disabled="currentPage === 1"
                    class="px-3 py-1 mr-2 rounded border focus:outline-none focus:ring transition text-teal-700 border-teal-700 hover:text-white hover:bg-teal-700 active:bg-teal-800 focus:ring-teal-400">
                    Anterior
                </button>
                <div class="flex">
                    <button v-if="shouldShowFirstPage" @click="changePage(1)"
                        class="px-3 py-1 mr-2 rounded bg-white text-teal-700  border border-teal-700 hover:text-white hover:bg-teal-800 hover:border-teal-700">
                        1
                    </button>
                    <span class="mr-2 bg-white text-teal-700" v-if="shouldShowEllipsisBefore">...</span>
                    <button v-for="page in pagesToShow" :key="page" @click="changePage(page)"
                        :class="{ 'bg-white text-teal-700': currentPage !== page, 'bg-teal-700 text-white': currentPage === page }"
                        class="px-3 py-1 mr-2 rounded border border-teal-700 hover:text-white hover:bg-teal-800 hover:border-teal-700">
                        {{ page }}
                    </button>
                    <span class="mr-2 bg-white text-teal-700" v-if="shouldShowEllipsisAfter">...</span>
                    <button v-if="shouldShowLastPage" @click="changePage(totalPages)"
                        class="px-3 py-1 mr-2 rounded bg-white text-teal-700  border border-teal-700 hover:text-white hover:bg-teal-800 hover:border-teal-700">
                        {{ totalPages }}
                    </button>
                </div>
                <button @click="currentPage < totalPages ? currentPage++ : null" :disabled="currentPage === totalPages"
                    class="px-3 py-1 rounded border focus:outline-none focus:ring transition text-teal-700 border-teal-700 hover:text-white hover:bg-teal-700 active:bg-teal-800 focus:ring-teal-400">
                    Siguiente
                </button>
            </div>
        </div>
        <div v-else-if="load > 0" class="mt-10">
            <div class="flex border border-gray-400 rounded-lg mt-1 mx-[200px]">
                <div class="border-r border-gray-400 w-[15%] flex items-center justify-center py-2">
                    <svg class="text-orange-400" fill="currentColor" width="48px" height="48px" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <path
                                    d="M19.79,16.72,11.06,1.61A1.19,1.19,0,0,0,9,1.61L.2,16.81C-.27,17.64.12,19,1.05,19H19C19.92,19,20.26,17.55,19.79,16.72ZM11,17H9V15h2Zm0-4H9L8.76,5h2.45Z">
                                </path>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="w-[85%] rounded-r-lg">
                    <h2 class="text-orange-500 text-center font-semibold">¡Sin resultados!</h2>
                    <p class="mx-2 mb-1 font-semibold text-center">No existen resultados para los filtros seleccionados, intenta nuevamente.
                    </p>
                </div>
            </div>
        </div>

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

        <!-- Modal -->
        <transition v-else-if="showModal" name="modal-fade">
            <div class="fixed inset-0 flex items-center justify-center z-50 mt-10 w-full overflow-y-auto">
                <div class="fixed inset-0 bg-black opacity-60"></div>
                <div class="bg-white rounded-lg z-10 modal-slide w-full max-w-2xl max-h-full relative sin-scroll">
                    <!-- Botón "X" para cerrar el modal -->
                    <div class="bg-[#3c4557] rounded-t-lg">
                        <button @click="showModal = false"
                            class="absolute top-0 right-0 mt-2 mr-2 text-white hover:text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <p class="text-center text-white text-lg font-normal mb-4 py-1.5">Filtros de reporte</p>
                    </div>
                    <div class="mb-4 md:flex flex-row justify-items-start mx-4">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Centro de
                                atención
                                <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-10 w-full flex-row-reverse">
                                <Multiselect v-model="reportInfo.parentId" :options="mainCenters" :searchable="true"
                                    placeholder="Seleccione centro" @change="reportInfo.depId = ''" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#001c48]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                            <InputError v-for="(item, index) in errors.parentId" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Dependencia
                                jerárquica
                            </label>
                            <div class="relative font-semibold flex h-10 w-full flex-row-reverse">
                                <Multiselect v-model="reportInfo.depId" :options="depFilter" :searchable="true"
                                    placeholder="Seleccione dependencia" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#001c48]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 md:flex flex-row justify-center mx-4">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Estado empleados
                                <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-10 w-full flex-row-reverse">
                                <Multiselect v-model="reportInfo.status" :options="states" :searchable="true"
                                    placeholder="Seleccione estado" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#001c48]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                            <InputError v-for="(item, index) in errors.status" :key="index" class="mt-2" :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Tipo
                                contratación
                            </label>
                            <div class="relative font-semibold flex h-10 w-full flex-row-reverse">
                                <Multiselect v-model="reportInfo.typeOfContract" :options="typesOfContract"
                                    :searchable="true" placeholder="Seleccione tipo" />
                                <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#001c48]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:flex flex-row justify-center mb-4 mx-4">
                        <!-- <div class="mb-5 md:mr-2 md:mb-0 basis-1/2 justify-center text-center">
                            <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">
                                ¿Filtro de fecha?
                            </label>
                            <label for="checbox1" class="text-sm font-medium text-gray-600 dark:text-white ml-4 mr-1">SI
                            </label>
                            <input type="checkbox" v-model="reportInfo.rangeY" id="checbox1"
                                class="rounded mr-3 border-gray-500  text-emerald-500 shadow-sm"
                                @click="reportInfo.rangeY ? reportInfo.rangeY = false : reportInfo.rangeY = true; rangeN = false" />
                            <label for="checbox2" class="text-sm font-medium text-gray-600 dark:text-white ml-4 mr-1">NO
                            </label>
                            <input type="checkbox" v-model="rangeN" id="checbox2"
                                class="rounded mr-3 border-gray-500  text-emerald-500 shadow-sm"
                                @click="rangeN ? rangeN = false : rangeN = true; reportInfo.rangeY = false" />
                        </div> -->
                        <div class="mb-5 md:mr-2 md:mb-0 basis-1/2 justify-start text-left">
                            <date-time-picker-m v-model="reportInfo.startDate" label="Registrados hasta:" />
                            <InputError v-for="(item, index) in errors.startDate" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>
                    <div class="flex justify-center my-4">
                        <button @click="showModal = false"
                            class="block mt-4 bg-white border border-gray-600 hover:bg-gray-600 hover:text-white text-gray-600 font-semibold py-1 px-3 rounded">
                            Cerrar
                        </button>
                        <button
                            class="block mt-4 ml-2 bg-emerald-600 hover:bg-emerald-800 text-white font-semibold py-1 px-3 rounded"
                            @click="getDataForReport(reportInfo)">
                            Generar
                        </button>
                    </div>
                </div>
            </div>
        </transition>


    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import moment from 'moment';
import InputError from "@/Components/InputError.vue";
import { jsPDF } from "jspdf";
import { ref, toRefs, computed, onMounted, watch } from 'vue';
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useReportesRRHH } from '@/Composables/RRHH/Reporte/useReportesRRHH.js';
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

export default {
    components: { Head, AppLayoutVue, DateTimePickerM },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const rangeN = ref(false)

        const currentPage = ref(1)
        const pageSize = ref(5)

        const retirementY = ref(0)
        const retirementN = ref(0)

        const title = ref("")

        const reportInfo = ref({
            depId: '',
            parentId: '',
            status: '',
            typeOfContract: '',
            startDate: '',
            rangeY: false
        })

        const {
            queryResult, getInfoForModal, depFilter, showModal, cleanObject,
            getDataForReport, states, typesOfContract, isLoadingRequest,
            mainCenters, errors, staticObject, load
        } = useReportesRRHH(reportInfo, context)

        watch(
            () => showModal.value,
            (newValue, oldValue) => {
                if (!newValue) {
                    currentPage.value = 1
                    retirementN.value = 0
                    retirementY.value = 0
                    calculateRetirement()
                }
            }
        );

        const changePage = (page) => {
            currentPage.value = page
        }

        const calculateRetirement = (() => {
            if (queryResult.value.length > 0) {
                queryResult.value.forEach((element) => {
                    if (element.pensionado_empleado === 1) {
                        retirementY.value++
                    } else {
                        retirementN.value++
                    }
                })
            } else {
                return 0
            }
        })

        const computedDependencyInfo = computed(() => {
            const parentId = staticObject.value.parentId
            const depId = staticObject.value.depId
            const center = mainCenters.value.find((element) => element.value === parentId)
            const dependency = depFilter.value.find((element) => element.value === depId)
            title.value = dependency ? dependency.label : title.value

            if (parentId && !depId) {
                return center.label
            } else
                if (parentId && depId) {
                    return center.codigo_centro_atencion + " - " + title.value
                }
                else {
                    return "TODOS LOS CENTROS"
                }
        })

        const computedTitle = computed(() => {
            const state = staticObject.value.status
            const typeC = staticObject.value.typeOfContract
            const selSt = states.value.find((e) => e.value === state)
            const selType = typesOfContract.value.find((e) => e.value === typeC)
            let st = ""
            let typC = ""
            if (state) {
                st = selSt.label + "S "
            }
            if (typeC) {
                typC = "POR " + selType.label
            }

            return "REPORTE DE EMPLEADOS " + st + typC
        })

        const computedDate = computed(() => {
            const startD = staticObject.value.startDate
            const date = startD ? formatDate(startD) : moment().format('DD/MM/YYYY')
            return "REPORTE AL " + date
        })

        const formatDate = (date) => {
            const dateF = moment(date).format('DD/MM/YYYY')
            return date ? dateF : ""
        }

        const totalPages = computed(() => {
            return Math.ceil(queryResult.value.length / pageSize.value);
        });

        const pagesToShow = computed(() => {
            const showPages = 1; // Cantidad de páginas que quieres mostrar alrededor de la página actual
            const startPage = Math.max(1, currentPage.value - showPages);
            const endPage = Math.min(totalPages.value, currentPage.value + showPages);

            const pages = [];
            for (let i = startPage; i <= endPage; i++) {
                pages.push(i);
            }
            return pages;
        });


        const shouldShowEllipsisBefore = computed(() => pagesToShow.value[0] > 2);
        const shouldShowFirstPage = computed(() => pagesToShow.value[0] > 1);

        const shouldShowEllipsisAfter = computed(() => pagesToShow.value[pagesToShow.value.length - 1] < totalPages.value - 1);
        const shouldShowLastPage = computed(() => pagesToShow.value[pagesToShow.value.length - 1] < totalPages.value);


        const paginatedData = computed(() => {
            const startIndex = (currentPage.value - 1) * pageSize.value;
            const endIndex = currentPage.value * pageSize.value;
            return queryResult.value.slice(startIndex, endIndex);
        });

        const openModal = async () => {
            await getInfoForModal();
            showModal.value = true
        }

        return {
            permits, queryResult, reportInfo, computedDependencyInfo, computedTitle,
            errors, cleanObject, getDataForReport, computedDate, retirementN, retirementY,
            depFilter, showModal, states, rangeN, load, formatDate,
            typesOfContract, isLoadingRequest, mainCenters, openModal,

            shouldShowEllipsisBefore, currentPage, pageSize, changePage, totalPages, shouldShowLastPage,
            shouldShowEllipsisAfter, pagesToShow, paginatedData, shouldShowFirstPage
        };
    },
}
</script>

<style>
.sin-scroll {
    overflow: auto;
}

/* Estilos para ocultar la barra de desplazamiento en navegadores webkit */
.sin-scroll::-webkit-scrollbar {
    width: 0;
    height: 0;
}

/* Estilos para Firefox */
.sin-scroll {
    scrollbar-width: none;
    /* Firefox */
}

/* Definición de la transición */
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}

.modal-fade-enter,
.modal-fade-leave-to {
    opacity: 0;
}

/* Animación de deslizamiento */
.modal-slide {
    transform: translateY(0);
}

.modal-slide-enter,
.modal-slide-leave-to {
    transform: translateY(20px);
    /* Ajusta según tu necesidad */
}
</style>