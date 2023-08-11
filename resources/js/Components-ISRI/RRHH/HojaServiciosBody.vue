<script setup>
import GeneralInformationVue from './GeneralInformation.vue'
import FooterServiciosBodyVue from './FooterServiciosBody.vue'

</script>
<template>
    <div class="grow flex flex-col md:translate-x-0 transition-transform duration-300 ease-in-out"
        :class="profileSidebarOpen ? 'translate-x-1/3' : 'translate-x-0'">

        <!-- Profile background -->
        <div class="relative h-56 bg-slate-200">
            <img class="object-cover h-full w-full "
                src="https://pbs.twimg.com/profile_banners/20736511/1670818292/1500x500" alt="Profile background" />
            <!-- Close button -->
            <button class="md:hidden absolute top-4 left-4 sm:left-6 text-white opacity-80 hover:opacity-100"
                @click.stop="$emit('toggle-profilesidebar')" aria-controls="profile-sidebar"
                :aria-expanded="profileSidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="relative px-4 sm:px-6 pb-8">

            <!-- Pre-header -->
            <div class="-mt-16 mb-6 sm:mb-3">

                <div class="flex flex-col items-center sm:flex-row sm:justify-between sm:items-end">

                    <!-- Avatar -->
                    <div class="image-container">
                        <img class="rounded-full border-4 border-white"
                            :src="userData && userData.fotos && userData.fotos.length > 0 ? userData.fotos[userData['fotos'].length - 1].url_foto : ''" />
                    </div>

                </div>
            </div>

            <!-- Header -->
            <header class="text-center sm:text-left mb-6">
                <!-- Name -->
                <div class="inline-flex items-start mb-2">
                    <h1 class="text-2xl text-slate-800 font-bold"> {{ userData != ''? `${userData.empleado.codigo_empleado} - ${userData.pnombre_persona ? userData.pnombre_persona : ''} ${userData.snombre_persona ? userData.snombre_persona : ''} ${userData.tnombre_persona ? userData.snombre_persona : ''} ${userData.papellido_persona ? userData.papellido_persona : ''} ${userData.sapellido_persona ? userData.sapellido_persona : ''} ${userData.tapellido_persona ? userData.tapellido_persona : ''} `:'' }}</h1>
                    <div class="flex space-x-2 sm:mb-2 ">
                        <DropDownOptions position="right-up">
                            <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                <div class="font-semibold text-xs">Imprimir todo</div>
                            </div>
                            <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                <div class="font-semibold text-xs w-full truncate">Imprimir lo selecionado</div>
                            </div>
                        </DropDownOptions>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center sm:justify-start space-x-4">
                    <div class="flex items-center">
                        <svg fill="#64748b" class="h-5 w-5" viewBox="0 0 183.41 183.41" stroke="#64748b"
                            stroke-width="2.7510749999999997">
                            <path
                                d="M160.766,0H64.054c-5.307,0-9.965,2.755-12.662,6.902h8.915c1.154-0.524,2.402-0.859,3.748-0.859h96.711 c4.999,0,9.061,4.064,9.061,9.064v125.577c0,4.604-3.495,8.269-7.946,8.841c-0.292,2.29-1.054,4.422-2.259,6.26h1.145 c8.336,0,15.113-6.771,15.113-15.101V15.101C175.879,6.777,169.102,0,160.766,0z">
                            </path>
                            <path
                                d="M140.057,13.804H43.348c-5.307,0-9.965,2.759-12.666,6.905h8.918c1.154-0.523,2.399-0.861,3.748-0.861h96.708 c5.011,0,9.072,4.064,9.072,9.066v125.579c0,4.604-3.501,8.258-7.946,8.83c-0.292,2.289-1.06,4.42-2.259,6.271h1.139 c8.342,0,15.125-6.771,15.125-15.113V28.914C155.176,20.581,148.398,13.804,140.057,13.804z">
                            </path>
                            <path
                                d="M119.347,27.611H22.639c-8.336,0-15.113,6.771-15.113,15.107v125.58c0,8.33,6.777,15.107,15.113,15.107h96.708 c8.343,0,15.126-6.771,15.126-15.107V42.718C134.473,34.388,127.689,27.611,119.347,27.611z M128.42,168.298 c0,4.993-4.067,9.066-9.073,9.066H22.639c-5.005,0-9.07-4.067-9.07-9.066V42.718c0-4.997,4.064-9.067,9.07-9.067h96.708 c5.006,0,9.073,4.07,9.073,9.067V168.298z">
                            </path>
                            <path
                                d="M80.236,107.747c6.777-4.549,11.447-13.548,11.447-21.644c0-11.43-9.252-20.7-20.688-20.7 c-11.43,0-20.685,9.271-20.685,20.7c0,8.096,4.67,17.095,11.429,21.644c-16.185,4.11-28.202,18.194-28.202,28.625 c0,12.324,74.933,12.324,74.933,0C108.46,125.941,96.452,111.857,80.236,107.747z M70.996,145.604l-10.507-10.485l8.835-21.361 H69.23l-3.428-3.945c1.668,0.597,3.386,0.962,5.188,0.962c1.806,0,3.517-0.359,5.179-0.943l-3.435,3.921h-0.076l8.842,21.361 L70.996,145.604z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium uppercase whitespace-nowrap text-slate-500 ml-2">UNIVERSITARIO
                            8059-ING(A). DE SISTEMAS INFORMATICOS</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 fill-current shrink-0 text-slate-400" viewBox="0 0 16 16">
                            <path
                                d="M11 0c1.3 0 2.6.5 3.5 1.5 1 .9 1.5 2.2 1.5 3.5 0 1.3-.5 2.6-1.4 3.5l-1.2 1.2c-.2.2-.5.3-.7.3-.2 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1.1-1.2c.6-.5.9-1.3.9-2.1s-.3-1.6-.9-2.2C12 1.7 10 1.7 8.9 2.8L7.7 4c-.4.4-1 .4-1.4 0-.4-.4-.4-1 0-1.4l1.2-1.1C8.4.5 9.7 0 11 0ZM8.3 12c.4-.4 1-.5 1.4-.1.4.4.4 1 0 1.4l-1.2 1.2C7.6 15.5 6.3 16 5 16c-1.3 0-2.6-.5-3.5-1.5C.5 13.6 0 12.3 0 11c0-1.3.5-2.6 1.5-3.5l1.1-1.2c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4L2.9 8.9c-.6.5-.9 1.3-.9 2.1s.3 1.6.9 2.2c1.1 1.1 3.1 1.1 4.2 0L8.3 12Zm1.1-6.8c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-4.2 4.2c-.2.2-.5.3-.7.3-.2 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l4.2-4.2Z" />
                        </svg>
                        <a
                            class="text-sm font-medium whitespace-nowrap text-indigo-500 hover:text-indigo-600 ml-2 cursor-pointer">utec2.unifsoporte@isri.gob.sv</a>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center sm:justify-start space-x-4 mt-1">

                    <div class="flex items-center">
                        <svg class="h-5 w-5" viewBox="0 0 24.00 24.00" fill="none" stroke="#64748b">

                            <path
                                d="M3 9H21M7 3V5M17 3V5M6 12H8M11 12H13M16 12H18M6 15H8M11 15H13M16 15H18M6 18H8M11 18H13M16 18H18M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                                stroke="#64748b" stroke-width="1.752" stroke-linecap="round"></path>

                        </svg>
                        <a class="text-sm font-medium whitespace-nowrap text-slate-500 ml-2">Fecha de ingreso: Lunes, 5
                            enero, 2023</a>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 17.9998C16.142 18.0343 19.5937 14.0798 19.5603 9.8043C19.5268 5.52875 16.142 2.03476 12 2.00026C7.858 1.96576 4.52734 5.4038 4.56077 9.67936C4.59419 13.9549 7.858 17.9653 12 17.9998Z"
                                stroke="#64748b" stroke-width="1.5" stroke-linecap="round"></path>
                            <path d="M15.5 9C15.4867 7.35641 14.1436 6.01326 12.5 6" stroke="#64748b" stroke-width="1.5"
                                stroke-linecap="round"></path>
                            <path
                                d="M12 20.3502C12.3212 20.3502 12.4818 20.3502 12.5933 20.3283C13.2466 20.1999 13.6441 19.5557 13.4511 18.9384C13.4181 18.833 13.342 18.6962 13.1896 18.4227M12 20.3502C11.6788 20.3502 11.5182 20.3502 11.4067 20.3283C10.7534 20.1999 10.3559 19.5557 10.5489 18.9384C10.5819 18.833 10.658 18.6962 10.8104 18.4227M12 20.3502V22.5"
                                stroke="#64748b" stroke-width="1.5" stroke-linecap="round"></path>

                        </svg>
                        <a class="text-sm font-medium whitespace-nowrap text-slate-500 ml-2">Fecha de nacimiento: 24 de
                            julio</a>
                    </div>
                </div>
            </header>

            <!-- Tabs -->
            <div class="relative mb-6">
                <div class="absolute bottom-0 w-full h-px bg-slate-200" aria-hidden="true"></div>
                <ul class="relative text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8">
                    <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                        <a class="block pb-3 cursor-pointer  whitespace-nowrap "
                            @click="isSelected.showInformation = true; isSelected.showAcuerdos = false"
                            :class="isSelected.showInformation ? 'border-b-2 text-indigo-500 border-indigo-500' : 'text-slate-500'">General</a>
                    </li>
                    <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                        <a class="block pb-3 cursor-pointer hover:text-slate-600 whitespace-nowrap"
                            @click="isSelected.showAcuerdos = true; isSelected.showInformation = false"
                            :class="isSelected.showAcuerdos ? 'border-b-2 text-indigo-500 border-indigo-500' : 'text-slate-500'">Acuerdos</a>
                    </li>
                </ul>
            </div>

            <!-- GENERAL -->
            <GeneralInformationVue :showInformation="isSelected.showInformation" />
            <FooterServiciosBodyVue :showAcuerdos="isSelected.showAcuerdos" :deals=" userData != ''?userData.empleado.acuerdo_laboral:[]"/>
        </div>

    </div>
</template>
  
<script>
export default {
    props: {
        userData: {
            type: Array,
            required: true,
            default: [],
        }
    },
    data() {
        return {
            isSelected: {
                showInformation: true,
                showAcuerdos: false,
            }
        }
    },
    watch: {
        userData() {
            console.log(this.userData);
        }
    }
}
</script>
<style>
.image-container {
    width: 125px;
    height: 130px;
    overflow: hidden;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>