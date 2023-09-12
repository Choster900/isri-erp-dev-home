<script setup>
import moment from 'moment';
</script>
<template>
    <div class="h-[26.92cm] w-full">
        <div class="flex-container">
            <img src="../../../img/escudo-fondo-blanco.png" alt="Escudo El Salvador"
                class="w-[700px] h-[700px] object-contain relative">
            <div class="absolute top-0 left-0 w-full h-full bg-opacity-90 bg-white pointer-events-none">
                <div class="text-center mt-4 relative">
                    <span class="font-[Bembo] font-bold text-[15px] absolute top-[5px] right-[10px]">F026</span>
                    <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[275px] mx-auto">
                    <h2 class="font-[Bembo] font-bold text-[16px] ">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
                    <h2 class="font-[Bembo] font-bold text-[16px] ">REGISTRO DE NO MARCACIÓN DE TARJETA DE ASISTENCIA</h2>
                </div>
                <!-- First row -->
                <!-- <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="w-1/3 ml-[1cm]">
                        </div>
                        <div class="relative flex w-2/3 flex-row mr-[1cm]">
                            <div class="flex justify-end w-1/2">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px] mr-4">
                                    No de tarjeta de marcación
                                </label>
                            </div>
                            <div class="h-full ml-1 text-center w-1/2 text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] ml-4 mb-[5px]">{{ target }}</p>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- Second row -->
                <div class="flex w-full justify-between items-center mt-10">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-[35%] ml-[1cm]">
                            <div class="flex justify-start w-[55%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px] mr-2">
                                    SAN SALVADOR
                                </label>
                            </div>
                            <div class="mr-5 text-center w-[45%] text-[14px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans]">{{ dia }}</p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[40%]">
                            <div class="flex justify-start w-[20%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px] ml-2">
                                    DE
                                </label>
                            </div>
                            <div class="mr-4 text-center w-[80%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">{{ mes }}</p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[25%] mr-[1cm]">
                            <div class="flex justify-start w-[30%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px] ml-3">
                                    DE
                                </label>
                            </div>
                            <div class="ml-1 text-center w-[70%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">{{ anio }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Third row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[10%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    NOMBRE:
                                </label>
                            </div>
                            <div class="text-left w-[90%] text-[14px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">
                                    {{ permission.empleado.persona.pnombre_persona }}
                                    {{ permission.empleado.persona.snombre_persona }}
                                    {{ permission.empleado.persona.tnombre_persona }}
                                    {{ permission.empleado.persona.papellido_persona }}
                                    {{ permission.empleado.persona.sapellido_persona }}
                                    {{ permission.empleado.persona.tapellido_persona }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[52%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    CENTRO DE ATENCIÓN, DEPARTAMENTO O SECCIÓN:
                                </label>
                            </div>
                            <div class="text-left w-[48%] text-[14px] font-bold border-b border-gray-700"
                                :class="centro1 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ centro1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fifth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[14px] font-bold border-b border-gray-700"
                                :class="centro2 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ centro2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sixth row -->
                <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    MOTIVO:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seventh row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-1/2  ml-[1cm]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[14px] mb-[5px]"
                                        :class="permission.id_motivo_permiso === 4 ? 'font-bold' : ''">
                                        OLVIDO DE TARJETA
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[14px]">
                                    <div class="w-6 h-6 border border-black mr-2 mt-[2px]">
                                        <div v-if="permission.id_motivo_permiso === 4" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-1/2 mr-[1cm]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[14px] mb-[5px]"
                                        :class="permission.id_motivo_permiso === 5 ? 'font-bold' : ''">
                                        PERDIDA DE TARJETA
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[14px]">
                                    <div class="w-6 h-6 border border-black mr-2 mt-[2px]">
                                        <div v-if="permission.id_motivo_permiso === 5" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Eighth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-1/2">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[14px] mb-[5px]"
                                        :class="permission.id_motivo_permiso != 5 && permission.id_motivo_permiso != 4 ? 'font-bold' : ''">
                                        OTROS
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[14px]">
                                    <div class="w-6 h-6 border border-black mr-2 mt-[2px]">
                                        <div v-if="permission.id_motivo_permiso != 5 && permission.id_motivo_permiso != 4"
                                            class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ninth row -->
                <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[50%]">
                                <div class="flex justify-start w-[50%]">
                                    <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                        HORA ENTRADA:
                                    </label>
                                </div>
                                <div class="text-center w-[50%] text-[14px] border-b border-gray-700">
                                    <p class="mb-[5px] font-bold font-[MuseoSans] ml-3">
                                        {{ permission.hora_entrada_permiso ? formatHour(permission.hora_entrada_permiso) :
                                            '-------------' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tenth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[50%]">
                                <div class="flex justify-start w-[50%]">
                                    <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                        HORA SALIDA:
                                    </label>
                                </div>
                                <div class="text-center w-[50%] text-[14px] border-b border-gray-700">
                                    <p class="mb-[5px] font-bold font-[MuseoSans] ml-3">
                                        {{ permission.hora_salida_permiso ? formatHour(permission.hora_salida_permiso) :
                                            '-------------' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Eleventh row -->
                <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[90%]">
                                <div class="flex justify-start w-[85%]">
                                    <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                        CANTIDAD DE PERMISOS DE NO MARCACION (incluyendo este) EN {{ mes }}:
                                    </label>
                                </div>
                                <div class="text-center w-[15%] text-[14px] border-b border-gray-700">
                                    <p class="mb-[5px] font-bold font-[MuseoSans] ">{{ limite }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Twelfth row -->
                <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    Observaciones:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Thirteenth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[14px] border-b border-gray-700"
                                :class="observation1 ? '' : 'py-2'">
                                <p class="mb-[5px] font-bold font-[MuseoSans] ml-3">{{ observation1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourteenth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[14px] border-b border-gray-700"
                                :class="observation2 ? '' : 'py-2'">
                                <p class="font-bold mb-[5px] font-[MuseoSans] ml-3">{{ observation2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fifteenth row -->
                <div class="flex w-full justify-between items-center mt-20">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[70%]">
                                <div class="flex justify-start w-[5%]">
                                    <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                        F:
                                    </label>
                                </div>
                                <div class="text-left w-[95%] font-bold text-[14px] border-b border-gray-700">
                                    <p class="mb-[5px] font-[MuseoSans] ml-3">{{ jefatura }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sixteenth row -->
                <div class="flex w-full justify-between items-center mt-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    NOMBRE Y FIRMA DEL DIRECTOR, ADMINISTRADOR JEFE DEPARTAMENTO O SECCIÓN
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        permission: {
            type: Array,
            default: [],
        },
        centro1: {
            type: String,
            default: ''
        },
        centro2: {
            type: String,
            default: ''
        },
        observation1: {
            type: String,
            default: ''
        },
        observation2: {
            type: String,
            default: ''
        },
        dia: {
            type: String,
            default: ''
        },
        mes: {
            type: String,
            default: ''
        },
        anio: {
            type: String,
            default: ''
        },
        limite: {
            type: String,
            default: ''
        },
    },
    data: function () {
        return {
            target: '0804',
            //nombre: 'CESAR IVAN ABARCA VELASQUEZ',
            seleccion: 3,
            hora1: '',
            hora2: '4:20 PM',
            jefatura: 'ING. MIGUEL JOSUÉ TOBIAS RIVAS',
            amountOfPermissions: 2
        }
    },
    methods: {
        formatHour(time) {
            const [hora, minutos] = time.split(':');
            const hora12 = (parseInt(hora) % 12).toString();
            const amPm = parseInt(hora) < 12 ? 'AM' : 'PM';

            // Añade un 0 delante si la hora tiene un solo dígito
            const horaFormateada = hora12.padStart(2, '0');
            // Añade un 0 delante si los minutos tienen un solo dígito
            const minutosFormateados = minutos.padStart(2, '0');

            return `${horaFormateada}:${minutosFormateados} ${amPm}`;
        }
    },
    watch: {
    },
    computed: {
    },
}
</script>

<style scoped>
.flex-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

img {
    max-height: 100%;
    max-width: 100%;
}
</style>