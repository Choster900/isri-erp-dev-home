<script setup>
import moment from 'moment';
</script>
<template>
    <div class="h-[26.92cm] w-full">
        <div class="flex-container">
            <img src="../../../img/escudo-fondo-blanco.png" alt="Escudo El Salvador"
                class="w-[700px] h-[700px] object-contain relative">
            <div class="absolute top-0 left-0 w-full h-full bg-opacity-90 bg-white pointer-events-none">
                <div class="text-center mt-2 relative">
                    <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[250px] mx-auto">
                    <h2 class="font-[Bembo] font-bold text-[15px] ">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
                    <h2 class="font-[Bembo] font-bold text-[15px] ">SOLICITUD DE PERMISOS</h2>
                    <h2 class="font-[Bembo] font-bold text-[15px] ">(Control Interno)</h2>
                </div>
                <!-- First row -->
                <div class="flex w-full justify-between items-center mt-7">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 mt-[-2px]">
                                    JEFE DEL DEPARTAMENTO DE RECURSOS HUMANOS - ISRI
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Second row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[48%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 ">
                                    CENTRO DE ATENCIÓN, DEPARTAMENTO O SECCIÓN:
                                </label>
                            </div>
                            <div class="text-left w-[52%] text-[13px] font-bold border-b border-gray-700"
                                :class="centro1 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ centro1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Third row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[13px] font-bold border-b border-gray-700"
                                :class="centro2 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ centro2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[46%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3">
                                    DENOMINACION PLAZA (Ley de salarios o contrato):
                                </label>
                            </div>
                            <div class="text-left w-[54%] text-[13px] font-bold border-b border-gray-700"
                                :class="role1 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ role1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fifth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[13px] font-bold border-b border-gray-700"
                                :class="role2 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ role2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sixth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-[21%] ml-[1cm]">
                            <div class="flex justify-start w-[50%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 mr-2">
                                    CÓDIGO:
                                </label>
                            </div>
                            <div class="text-center w-[50%] text-[13px] font-bold border-b border-gray-700 mr-1">
                                <p class="mb-[5px] font-[MuseoSans]">{{ permission.empleado.codigo_empleado }}</p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[21%]">
                            <div class="flex justify-start w-[50%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 ml-1">
                                    PARTIDA:
                                </label>
                            </div>
                            <div class="text-center w-[50%] text-[13px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px] ">{{ permission.plaza_asignada.partida_plaza_asignada }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[29%]">
                            <div class="flex justify-start w-[55%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 ml-2">
                                    SUB PARTIDA:
                                </label>
                            </div>
                            <div class="mr-1 text-center w-[45%] text-[13px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">{{ permission.plaza_asignada.subpartida_plaza_asignada
                                }}</p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[29%] mr-[1cm]">
                            <div class="flex justify-start w-[50%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 ml-3">
                                    CONTRATO:
                                </label>
                            </div>
                            <div class="ml-1 text-center w-[50%] text-[13px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">{{ contract }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seventh row -->
                <div class="flex w-full justify-between items-center mt-4">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 mt-[-2px]">
                                    MOTIVO:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Eighth row -->
                <div class="flex w-full justify-between items-center mt-1">
                    <div class="flex w-full text-left">
                        <div class="flex justify-start w-[10%] ml-[1cm]"></div>
                        <div class="relative flex flex-row w-[30%]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-bold font-[MuseoSans] text-[11px] mb-0.3 mt-[-2px]">
                                    ENFERMEDAD
                                </label>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[30%]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-bold font-[MuseoSans] text-[11px] mb-0.3 mt-[-2px]">
                                    PERSONAL
                                </label>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[30%] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-bold font-[MuseoSans] text-[11px] mb-0.3 mt-[-2px]">
                                    OTROS
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ninth row -->
                <div class="flex w-full justify-between items-center mt-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[10%] ml-[1cm]"></div>
                            <div class="flex justify-start w-[30%]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[11px] mb-[5px]">
                                        CON GOCE DE SUELDO
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[11px]">
                                    <div class="w-6 h-4 border border-black mr-2 mt-[5px]">
                                        <div v-if="permission.id_tipo_permiso === 3" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[30%]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[11px] mb-[5px]">
                                        CON GOCE DE SUELDO
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[11px]">
                                    <div class="w-6 h-4 border border-black mr-2 mt-[5px]">
                                        <div v-if="permission.id_tipo_permiso === 1" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[30%] mr-[1cm]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[11px] mb-[5px]">
                                        MISION OFICIAL
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[11px]">
                                    <div class="w-6 h-4 border border-black mr-2 mt-[5px]">
                                        <div v-if="permission.id_tipo_permiso === 5" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tenth row -->
                <div class="flex w-full justify-between items-center mt-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[10%] ml-[1cm]"></div>
                            <div class="flex justify-start w-[30%]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[11px] mb-[5px]">
                                        SIN GOCE DE SUELDO
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[11px]">
                                    <div class="w-6 h-4 border border-black mr-2 mt-[5px]">
                                        <div v-if="permission.id_tipo_permiso === 4" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[30%]">
                                <div class="flex justify-start w-[70%]">
                                    <label class="font-[MuseoSans] text-[11px] mb-[5px]">
                                        SIN GOCE DE SUELDO
                                    </label>
                                </div>
                                <div class="text-left w-[30%] text-[11px]">
                                    <div class="w-6 h-4 border border-black mr-2 mt-[5px]">
                                        <div v-if="permission.id_tipo_permiso === 2" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[30%] mr-[1cm]"></div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full justify-between items-center mt-4">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm]">
                            <div class="flex justify-start w-[12%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 mt-[-2px]">
                                    Observación:
                                </label>
                            </div>
                            <div class="mr-[1cm] text-left w-[88%] font-bold text-[13px] border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">
                                    {{
                                        permission.id_tipo_permiso != 6 && permission.id_tipo_permiso != 5 ?
                                        observation1 : ''
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eleventh row -->
                <div class="flex w-full justify-between items-center mt-4">
                    <div class="flex w-full text-left mr-[1cm] ml-[1cm]">
                        <div class="relative justify-center flex flex-row w-[38%] mx-auto">
                            <div class="flex w-full">
                                <label for=""
                                    class="font-[MuseoSans] font-bold text-[11px] mx-auto underline underline-offset-2">
                                    MISION OFICIAL
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Twelfth row -->
                <div class="flex w-full justify-start items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-[75%] ml-[1cm] ">
                            <div class="flex justify-start w-[11%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-2">
                                    Destino:
                                </label>
                            </div>
                            <div class="text-left w-[89%] text-[13px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ permission.destino_permiso }}</p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[25%] mr-[1cm] ">
                            <div class="flex justify-start w-[40%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-2 ml-1">
                                    Regresará:
                                </label>
                            </div>
                            <div class="flex flex-row text-left w-[60%] text-[13px] ">
                                <div class="flex justify-start w-[50%]">
                                    <div class="flex justify-start w-[50%]">
                                        <label class="font-[MuseoSans] text-[11px] mt-[2px] ml-2">
                                            SI
                                        </label>
                                    </div>
                                    <div class="text-left w-[50%] text-[11px]">
                                        <div class="w-3 h-2 border border-black mt-[13px]">
                                            <div v-if="permission.retornar_empleado_permiso === 1"
                                                class="w-full h-full bg-black">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-start w-[50%]">
                                    <div class="flex justify-start w-[50%]">
                                        <label class="font-[MuseoSans] text-[11px] mt-[2px]">
                                            NO
                                        </label>
                                    </div>
                                    <div class="text-left w-[50%] text-[11px]">
                                        <div class="w-3 h-2 border border-black mt-[13px]">
                                            <div v-if="permission.retornar_empleado_permiso === 0"
                                                class="w-full h-full bg-black">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Thirteenth row -->
                <div class="flex w-full justify-start items-center mt-1">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[12%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-2">
                                    Descripción:
                                </label>
                            </div>
                            <div class="text-left w-[88%] text-[13px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">
                                    {{
                                        permission.id_tipo_permiso == 5 ? observation1 : ''
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fourteenth row -->
                <div class="flex w-full justify-between items-center mt-6">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-[5%] ml-[1cm]">
                        </div>
                        <div class="relative flex flex-row w-[14%]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-1">
                                    FECHA:
                                </label>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[32%]">
                            <div class="flex justify-start w-[40%]">
                                <label for="" class="font-[MuseoSans] text-[11px] mb-0.3 mt-[3px]">
                                    DESDE
                                </label>
                            </div>
                            <div class="text-center w-[60%] text-[11px] font-bold border-b border-gray-700 mr-[40px]">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    {{ permission.fecha_inicio_permiso ? formatDate(permission.fecha_inicio_permiso) :
                                        '-------------' }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[14%]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-1">
                                    HORA:
                                </label>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[32%]">
                            <div class="flex justify-start w-[50%]">
                                <label for="" class="font-[MuseoSans] text-[11px] mb-0.3 mt-[3px]">
                                    INICIO / SALIDA
                                </label>
                            </div>
                            <div class="text-center w-[50%] text-[11px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    {{ permission.hora_entrada_permiso ? formatHour(permission.hora_entrada_permiso) :
                                        '-------------' }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[5%] mr-[1cm]">
                        </div>
                    </div>
                </div>
                <!-- Fifteenth row -->
                <div class="flex w-full justify-between items-center mt-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-[19%] ml-[1cm]">
                        </div>
                        <div class="relative flex flex-row w-[32%]">
                            <div class="flex justify-start w-[40%]">
                                <label for="" class="font-[MuseoSans] text-[11px] mb-0.3 mt-[4px]">
                                    HASTA
                                </label>
                            </div>
                            <div class="text-center w-[60%] text-[11px] font-bold border-b border-gray-700 mr-[40px]">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    {{ permission.fecha_fin_permiso ? formatDate(permission.fecha_fin_permiso) :
                                        '-------------' }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[14%]"></div>
                        <div class="relative flex flex-row w-[32%]">
                            <div class="flex justify-start w-[50%]">
                                <label for="" class="font-[MuseoSans] text-[11px] mb-0.3 mt-[4px]">
                                    FIN / REGRESO
                                </label>
                            </div>
                            <div class="text-center w-[50%] text-[11px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    {{ permission.hora_salida_permiso ? formatHour(permission.hora_salida_permiso) :
                                        '-------------' }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[5%] mr-[1cm]"></div>
                    </div>
                </div>
                <!-- Sixteenth row -->
                <div class="flex w-full justify-between items-center mt-2">
                    <div class="flex w-full text-left">
                        <div class="relative justify-center flex flex-row w-[38%] mx-auto" id="container">
                            <div class="flex w-[60%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3 mt-[4px]">
                                    TIEMPO UTILIZADO:
                                </label>
                            </div>
                            <div class="text-center w-[40%] text-[13px] font-bold border-b border-gray-700 mr-[40px]">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    {{ timeUsed }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seventeenth row -->
                <div class="flex w-full justify-between items-center mt-[50px]">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[50%]  ml-[1cm]">
                                <div class="flex justify-start w-[60%]  mx-auto">
                                    <div class="flex justify-start w-[8%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] mb-1 mt-[-2px]">
                                            F.
                                        </label>
                                    </div>
                                    <div class="text-left w-[92%] font-bold text-[13px] border-b border-gray-700">
                                        <p class="mb-[5px] font-[MuseoSans]">{{ }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[50%]  mr-[1cm]">
                                <div class="flex justify-start w-[60%]  mx-auto">
                                    <div class="flex justify-start w-[8%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] mb-1 mt-[-2px]">
                                            F.
                                        </label>
                                    </div>
                                    <div class="text-left w-[92%] font-bold text-[13px] border-b border-gray-700">
                                        <p class="mb-[5px] font-[MuseoSans]">{{ }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Eighteenth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="text-center w-[50%] text-[13px] font-bold ml-[1cm]">
                                <p class="font-[MuseoSans] mx-auto">
                                    {{ permission.empleado.titulo_profesional.codigo_titulo_profesional }}
                                    {{ permission.empleado.persona.pnombre_persona }}
                                    {{ permission.empleado.persona.snombre_persona }}
                                    {{ permission.empleado.persona.tnombre_persona }}
                                    {{ permission.empleado.persona.papellido_persona }}
                                    {{ permission.empleado.persona.sapellido_persona }}
                                    {{ permission.empleado.persona.tapellido_persona }}
                                </p>
                            </div>
                            <div class="text-center w-[50%] text-[13px] font-bold mr-[1cm]">
                                <p class="font-[MuseoSans] mx-auto">
                                    {{ jefatura2 }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nineteenth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[50%] ml-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[13px] mx-auto mt-[-2px]">
                                    Solicitante
                                </label>
                            </div>
                            <div class="flex justify-start w-[50%] mr-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[13px] mx-auto mt-[-2px]">
                                    Jefe Inmediato
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- twentieth row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full mr-[1cm] ml-[1cm]">
                            <div class="text-center w-[50%] text-[13px] mx-auto">
                                <div class="flex justify-start w-[60%] mx-auto">
                                    <div class="flex justify-start w-[8%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] mb-1 mt-[-2px]">
                                            F.
                                        </label>
                                    </div>
                                    <div class="text-left w-[92%] font-bold text-[13px] border-b border-gray-700">
                                        <p class="mb-[5px] font-[MuseoSans]">{{ }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Twenty-first row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full mr-[1cm] ml-[1cm]">
                            <div class="text-center w-[50%] text-[13px] font-bold mx-auto">
                                <p class="font-[MuseoSans]">
                                    {{ jefatura3 }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Twenty-second row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-center w-[50%] mx-auto">
                                <label for="" class="font-[MuseoSans] text-[13px] mt-[-2px]">
                                    Director de Centro o Jefe de Departamento
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer rows -->
                <div class="flex w-full justify-start items-center mt-16">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm]">
                            <div class="text-left w-full text-[8px]">
                                <label for="" class="font-[MuseoSans] text-[8px] mt-[-2px]">
                                    USAR FORMULARIO PARA
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-start items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm]">
                            <div class="text-left w-full text-[8px]">
                                <label for="" class="font-[MuseoSans] text-[8px] mt-[-2px]">
                                    * Enfermedades por horas y hasta 3 días (con o sin prescripcion medica)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-start items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm]">
                            <div class="text-left w-full text-[8px]">
                                <label for="" class="font-[MuseoSans] text-[8px] mt-[-2px]">
                                    * Misiones oficiales (hasta 5 días)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-start items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm]">
                            <div class="text-left w-full text-[8px]">
                                <label for="" class="font-[MuseoSans] text-[8px] mt-[-2px]">
                                    * Permisos personales con o sin goce de sueldo (hasta 5 días)
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
        role1: {
            type: String,
            default: ''
        },
        role2: {
            type: String,
            default: ''
        }
    },
    data: function () {
        return {
            target: '0804',
            contract: '49/2021',
            seleccion: 5,
            timeInHours: '8 H.',
            jefatura1: 'ING. MELVIN ULISES HERRERA JIRON',
            jefatura2: 'ING. JOSUE TOBIAS RIVAS',
            jefatura3: 'LICDA. KARLA GUADALUPE CASTANEDA DE ORELLANA',
            text1: ''
        }
    },
    methods: {
        formatTime(time) {
            if (!time) {
                return '0'
            } else {
                const [hours, minutes] = time.split(':').map(Number);
                return `${hours > 0 ? hours + ' H. ' : ''} ${minutes > 0 ? minutes + ' min.' : ''}`
            }
        },
        formatDate(date) {
            // Parsea la fecha desde el backend
            const fechaParseada = moment(date, 'YYYY-MM-DD');

            // Formatea la fecha en el nuevo formato 'DD/MM/YYYY'
            return fechaParseada.format('DD/MM/YYYY') ?? '-------------';
        },
        formatHour(time) {
            let [hora, minutos] = time.split(':');
            let amPm = parseInt(hora) < 12 ? 'AM' : 'PM';

            // Si la hora es 12, cambia 'AM' a 'PM' y ajusta la hora a 12
            if (parseInt(hora) === 12) {
                amPm = 'MD';
            } else if (parseInt(hora) === 0) {
                // Si la hora es 00, ajusta la hora a 12
                hora = '12';
            } else {
                hora = (parseInt(hora) % 12).toString();
            }

            // Añade un 0 delante si los minutos tienen un solo dígito
            minutos = minutos.padStart(2, '0');

            return `${hora}:${minutos} ${amPm}`;
        }
    },
    watch: {
    },
    computed: {
        timeUsed: function () {
            if (this.permission.fecha_fin_permiso) {
                const startDateFormated = moment(this.permission.fecha_inicio_permiso, 'YYYY/MM/DD').toDate()
                const endDateFormated = moment(this.permission.fecha_fin_permiso, 'YYYY/MM/DD').toDate()

                let currentDate = new Date(startDateFormated);
                let daysDifference = 0;

                while (currentDate <= endDateFormated) {
                    const dayOfWeek = currentDate.getDay(); // 0 (domingo) a 6 (sábado)
                    if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                        daysDifference++;
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                const resultInMinutes = daysDifference * 8 * 60

                const hours = Math.floor(resultInMinutes / 60);
                const minutes = resultInMinutes % 60;
                const formatResult = `${hours}:${minutes}`
                return this.formatTime(formatResult)

            } else {
                if (this.permission.hora_salida_permiso) {
                    const [endHours, endMinutes] = this.permission.hora_salida_permiso.split(':').map(Number);
                    const [startHours, startMinutes] = this.permission.hora_entrada_permiso.split(':').map(Number);
                    const result = ((endHours * 60) + endMinutes) - ((startHours * 60) + startMinutes)

                    const hours = Math.floor(result / 60);
                    const minutes = result % 60;
                    const formatResult = `${hours}:${minutes}`
                    return this.formatTime(formatResult)
                } else {
                    return '0'
                }
            }
        },
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