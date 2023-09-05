<template>
    <div class="h-[26.92cm] w-full">
        <div class="flex-container">
            <img src="../../../img/escudo-fondo-blanco.png" alt="Escudo El Salvador"
                class="w-[700px] h-[700px] object-contain relative">
            <div class="absolute top-0 left-0 w-full h-full bg-opacity-90 bg-white pointer-events-none">
                <div class="text-center mt-4 relative">
                    <span class="font-[Bembo] font-bold text-[14px] absolute top-[5px] right-[10px]">F026</span>
                    <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[275px] mx-auto">
                    <h2 class="font-[Bembo] font-bold text-[16px] ">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
                    <h2 class="font-[Bembo] font-bold text-[16px] ">REGISTRO DE NO MARCACIÓN DE TARJETA DE ASISTENCIA</h2>
                </div>
                <!-- First row -->
                <div class="flex w-full justify-between items-center mt-7">
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
                </div>
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
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ nombre }}</p>
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
                                    CENTRO DE ATENCION, DEPARTAMENTO O SECCION:
                                </label>
                            </div>
                            <div class="text-left w-[48%] text-[14px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ centro }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fifth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[14px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ centro }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sixth row -->
                <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] font-bold text-[14px] mb-0.3 mt-[-2px]">
                                    MOTIVOS:
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
                                    <label class="font-[MuseoSans] text-[14px] mb-[5px]">
                                        OLVIDO DE TARJETA
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[14px]">
                                    <div class="w-6 h-6 border border-black mr-2 mt-[2px]">
                                        <div v-if="seleccion === 1" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-1/2 mr-[1cm]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[14px] mb-[5px]">
                                        PERDIDA DE TARJETA
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[14px]">
                                    <div class="w-6 h-6 border border-black mr-2 mt-[2px]">
                                        <div v-if="seleccion === 2" class="w-full h-full bg-black"></div>
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
                                    <label class="font-[MuseoSans] text-[14px] mb-[5px]">
                                        OTROS
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[14px]">
                                    <div class="w-6 h-6 border border-black mr-2 mt-[2px]">
                                        <div v-if="seleccion === 3" class="w-full h-full bg-black"></div>
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
                                    <label for="" class="font-[MuseoSans] font-bold text-[14px] mb-0.3 mt-[-2px]">
                                        HORA ENTRADA:
                                    </label>
                                </div>
                                <div class="text-center w-[50%] text-[14px] border-b border-gray-700">
                                    <p class="mb-[5px] font-[MuseoSans] ml-3">{{ hora1 ? hora1 : '-------------'  }}</p>
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
                                    <label for="" class="font-[MuseoSans] font-bold text-[14px] mb-0.3 mt-[-2px]">
                                        HORA SALIDA:
                                    </label>
                                </div>
                                <div class="text-center w-[50%] text-[14px] border-b border-gray-700">
                                    <p class="mb-[5px] font-[MuseoSans] ml-3">{{ hora2 ? hora2 : '-------------'  }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Eleventh row -->
                <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] font-bold text-[14px] mb-0.3 mt-[-2px]">
                                    Observaciones:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Twelfth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[14px] border-b border-gray-700"
                            :class="observation ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ observation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Thirteenth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[14px] border-b border-gray-700"
                            :class="observation2 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ observation2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourteenth row -->
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
                                    <p class="mb-[5px] font-[MuseoSans] ml-3">{{ jefatura  }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fifteenth row -->
                <div class="flex w-full justify-between items-center mt-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    NOMBRE Y FIRMA DEL DIRECTOR, ADMINISTRADOR JEFE DEPARTAMENTO O SECCION
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
    },
    data: function () {
        return {
            target: '0804',
            dia: '22',
            mes: 'NOVIEMBRE',
            anio: '2023',
            nombre: 'CESAR IVAN ABARCA VELASQUEZ',
            centro: 'UNIDAD DE CONSULTA EXTERNA',
            centro2: 'CONT. CENTRO 2',
            seleccion: 3,
            hora1: '',
            hora2: '4:20 PM',
            observation: 'first observation',
            observation2: '',
            jefatura: 'ING. MIGUEL JOSUÉ TOBIAS RIVAS'
        }
    },
    methods: {
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