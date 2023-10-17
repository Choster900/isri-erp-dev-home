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
                    <span class="font-[Bembo] font-bold text-[15px] absolute top-[5px] right-[10px]">F012</span>
                    <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[250px] mx-auto">
                    <h2 class="font-[Bembo] font-bold text-[14px] ">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
                    <h2 class="font-[Bembo] font-bold text-[14px]">SOLICITUD DE LICENCIA</h2>
                </div>
                <!-- First row -->
                <div class="flex w-full justify-end mt-3">
                    <div class="flex w-full justify-end text-right ml-[1cm] mr-[1cm]">
                        <div class="relative flex flex-row w-[50%]">
                            <div class="flex justify-end w-[55%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-1 mr-2">
                                    Fecha:
                                </label>
                            </div>
                            <div class="text-center w-[45%] text-[14px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans]">
                                    {{ moment(permission.fecha_reg_permiso, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Second row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    Gerente Administrativo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Second row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    Instituto Salvadoreño de Rehabilitación Integral
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Third row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    Presente.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourth row -->
                <div class="flex w-full justify-between items-center mt-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ">
                            <div class="flex justify-start w-[43%] ml-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-1">
                                    Remito a usted solicitud de Licencia del Sr(a).
                                </label>
                            </div>
                            <div class="text-left w-[57%] text-[14px] font-bold border-b border-gray-700 mr-[1cm]">
                                <p class="mb-[5px] ml-1 font-[MuseoSans]">
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
                <!-- Sixth row -->
                <div class="flex w-full">
                    <div class="flex w-full text-left ">
                        <div class="relative flex flex-row w-[17%] ml-[1cm] ">
                            <div class="flex justify-start w-[47%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3">
                                    Código:
                                </label>
                            </div>
                            <div class="text-center w-[53%] text-[14px] font-bold border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans]">
                                    {{ permission.empleado.codigo_empleado }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[18%] ">
                            <div class="flex justify-start w-[48%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 ml-1">
                                    Partida:
                                </label>
                            </div>
                            <div class="text-center w-[52%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px] ">
                                    {{ permission.plaza_asignada.partida_plaza_asignada }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[21%] ">
                            <div class="flex justify-start w-[60%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 ml-1">
                                    Sub Partida:
                                </label>
                            </div>
                            <div class="text-center w-[40%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    {{ permission.plaza_asignada.subpartida_plaza_asignada }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[24%] ">
                            <div class="flex justify-start w-[24%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 ml-1">
                                    NUP:
                                </label>
                            </div>
                            <div class="text-center w-[76%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    {{ permission.empleado.nup_empleado }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[20%] mr-[1cm] ">
                            <div class="flex justify-start w-[40%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 ml-1">
                                    Salario:
                                </label>
                            </div>
                            <div class="text-center w-[60%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] mb-[5px]">
                                    ${{ permission.plaza_asignada.salario_plaza_asignada }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ">
                            <div class="flex justify-start w-[36%] ml-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-1">
                                    Cargo (por Ley de Salario o Contrato):
                                </label>
                            </div>
                            <div class="text-left w-[64%] text-[14px] font-bold border-b border-gray-700 mr-[1cm]">
                                <p class="mb-[5px] ml-1 font-[MuseoSans]">{{ role1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fifth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="text-left w-full text-[14px] font-bold border-b border-gray-700"
                                :class="role2 ? '' : 'py-2'">
                                <p class="mb-[5px] font-[MuseoSans] ml-3">{{ role2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourth row -->
                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ">
                            <div class="flex justify-start w-[24%] ml-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-1">
                                    Centro o Departamento:
                                </label>
                            </div>
                            <div class="text-left w-[76%] text-[14px] font-bold border-b border-gray-700 mr-[1cm]">
                                <p class="mb-[5px] ml-1 font-[MuseoSans]">{{ centro1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourth row -->
                <div class="flex w-full justify-between items-center mt-6">
                    <div class="flex w-[55%] text-left ml-[1cm] mr-[1cm]">
                        <div class="relative flex flex-row w-[55%] ">
                            <div class="flex justify-start w-[75%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-1">
                                    SOLICITA LICENCIA POR:
                                </label>
                            </div>
                            <div class="mr-1 text-center justify-center w-[25%] text-[14px] border-b border-gray-700">
                                <p class="mb-[5px] font-[MuseoSans] font-bold">{{ totalDays }}</p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[45%]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-1 ml-1">
                                    día(s) en concepto de:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seventh row -->
                <div class="flex w-full justify-between items-center mt-3">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm] ">
                            <div class="flex justify-start w-[25%]">
                                <div class="flex justify-start w-[60%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="typeOfPermission === 1 ? 'font-bold' : ''">
                                        Enfermedad
                                    </label>
                                </div>
                                <div class="text-left w-[40%] text-[14px] ">
                                    <div class="w-5 h-5 border border-black mr-2 mt-[3px]">
                                        <div v-if="typeOfPermission === 1" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[25%]">
                                <div class="flex justify-start w-[60%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="typeOfPermission === 2 ? 'font-bold' : ''">
                                        Personal
                                    </label>
                                </div>
                                <div class="text-left w-[40%] text-[14px] ">
                                    <div class="w-5 h-5 border border-black mr-2 mt-[3px]">
                                        <div v-if="typeOfPermission === 2" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[50%]">
                                <div class="flex justify-start w-[80%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="typeOfPermission === 3 ? 'font-bold' : ''">
                                        Misión Oficial
                                    </label>
                                </div>
                                <div class="text-left w-[20%] text-[14px] ">
                                    <div class="w-5 h-5 border border-black mr-2 mt-[3px]">
                                        <div v-if="typeOfPermission === 3" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seventh row -->
                <div class="flex w-full justify-between items-center mt-1">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm] ">
                            <div class="flex justify-start w-[25%]">
                                <div class="flex justify-start w-[60%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="typeOfPermission === 4 ? 'font-bold' : ''">
                                        Maternidad
                                    </label>
                                </div>
                                <div class="text-left w-[40%] text-[14px] ">
                                    <div class="w-5 h-5 border border-black mr-2 mt-[3px]">
                                        <div v-if="typeOfPermission === 4" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[25%]">
                                <div class="flex justify-start w-[60%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="typeOfPermission === 5 ? 'font-bold' : ''">
                                        Duelo
                                    </label>
                                </div>
                                <div class="text-left w-[40%] text-[14px] ">
                                    <div class="w-5 h-5 border border-black mr-2 mt-[3px]">
                                        <div v-if="typeOfPermission === 5" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[50%]">
                                <div class="flex justify-start w-[80%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="typeOfPermission === 6 ? 'font-bold' : ''">
                                        Atención a parientes por enfermedad gravísima
                                    </label>
                                </div>
                                <div class="text-left w-[20%] text-[14px] ">
                                    <div class="w-5 h-5 border border-black mr-2 mt-[3px]">
                                        <div v-if="typeOfPermission === 6" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full justify-between items-center mt-6">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-[16%]">
                                <label for="" class="font-[MuseoSans] text-[13px] mb-0.3"
                                    :class="observation1 ? '' : 'mb-1.5'">
                                    OBSERVACIONES:
                                </label>
                            </div>
                            <div class="text-left w-[84%] font-bold text-[13px] border-b border-gray-700 ">
                                <p class="mb-[5px] font-bold font-[MuseoSans] ml-1">
                                    {{
                                        observation1
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full justify-between items-center mt-6">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm] ">
                            <div class="flex justify-start w-[25%]">
                            </div>
                            <div class="flex justify-start w-[25%]">
                                <div class="flex justify-start w-[60%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="salary === 1 ? 'font-bold' : ''">
                                        CON GOCE
                                    </label>
                                </div>
                                <div class="text-left w-[40%] text-[14px] ">
                                    <div class="w-6 h-4 border border-black mr-2 mt-[3px]">
                                        <div v-if="salary === 1" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[25%]">
                                <div class="flex justify-start w-[60%]">
                                    <label class="font-[MuseoSans] text-[14px] mt-[-5px]"
                                        :class="salary === 2 ? 'font-bold' : ''">
                                        SIN GOCE
                                    </label>
                                </div>
                                <div class="text-left w-[40%] text-[14px] ">
                                    <div class="w-6 h-4 border border-black mr-2 mt-[3px]">
                                        <div v-if="salary === 2" class="w-full h-full bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start w-[25%]">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Second row -->
                <div class="flex w-full justify-between items-center mt-4">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm]">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                    En el periodo:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full justify-between items-center">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm] mr-[1cm] ">
                            <div class="flex justify-start w-[20%]">
                            </div>
                            <div class="flex justify-start w-[30%]">
                                <div class="flex justify-center w-[40%]">
                                    <label class="font-[MuseoSans] text-[14px]">
                                        Desde:
                                    </label>
                                </div>
                                <div class="text-center w-[60%] font-bold text-[13px] border-b border-gray-700 ">
                                    <p class="mb-[5px] font-bold font-[MuseoSans] ml-1">
                                        {{
                                            moment(permission.fecha_inicio_permiso, 'YYYY-MM-DD').format('DD/MM/YYYY')
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex justify-start w-[30%]">
                                <div class="flex justify-center w-[40%]">
                                    <label class="font-[MuseoSans] text-[14px]">
                                        Hasta:
                                    </label>
                                </div>
                                <div class="text-center w-[60%] font-bold text-[13px] border-b border-gray-700 ">
                                    <p class="mb-[5px] font-bold font-[MuseoSans] ml-1">
                                        {{
                                            moment(permission.fecha_fin_permiso, 'YYYY-MM-DD').format('DD/MM/YYYY')
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex justify-start w-[20%]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nineteenth row -->
                <div class="flex w-full justify-between items-center mt-[40px]">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[50%] ml-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[13px] mx-auto mt-[-2px]">
                                    
                                </label>
                            </div>
                            <div class="flex justify-start w-[50%] mr-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[13px] mx-auto mt-[-2px]">
                                    APROBADO:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seventeenth row -->
                <div class="flex w-full justify-between items-center mt-6">
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
                                    {{ jefatura1 }}
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
                                    Director y/o Jefe Departamento / Unidad
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nineteenth row -->
                <div class="flex w-full justify-between items-center mt-[30px]">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[50%] ml-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[13px] mx-auto mt-[-2px]">
                                    VISTO BUENO:
                                </label>
                            </div>
                            <div class="flex justify-start w-[50%] mr-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[13px] mx-auto mt-[-2px]">
                                    AUTORIZADO:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seventeenth row -->
                <div class="flex w-full justify-between items-center mt-6">
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
                                    {{ jefatura2 }}
                                </p>
                            </div>
                            <div class="text-center w-[50%] text-[13px] font-bold mr-[1cm]">
                                <p class="font-[MuseoSans] mx-auto">
                                    {{ jefatura3 }}
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
                                    Gerente Médico y de Servicios de Rehabilitación
                                </label>
                            </div>
                            <div class="flex justify-start w-[50%] mr-[1cm]">
                                <label for="" class="font-[MuseoSans] text-[13px] mx-auto mt-[-2px]">
                                    Gerente Administrativo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer rows -->
                <div class="flex w-full justify-start items-center mt-4">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full ml-[1cm]">
                            <div class="text-left w-full text-[8px]">
                                <label for="" class="font-[MuseoSans] text-[8px] mt-[-2px]">
                                    USAR FORMULARIO PARA:
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
                                    * Licencia por enfermedad con prescripción médica (mayores a 3 días)
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
                                    * Permiso personal sin goce de sueldo (1 día en adelante)
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
                                    * Misión oficial (mayores a 5 días)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End footer rows -->
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
        },
    },
    data: function () {
        return {
            target: '0804',
            dia2: '12/09/2023',
            jefatura1: 'ING. ERICK SALOMON TICAS ALFARO',
            jefatura2: 'DR. HUGO ERNESTO CORDERO',
            jefatura3: 'LICDA. KARLA GUADALUPE CASTANEDA DE ORELLANA',
            employee: 'MERCY LOURDES SANTAMARIA DE MARTINEZ',
            nup: '345832150015',
            salary: '$800.00',
            plaza: 'JEFE DE UNIDAD DEL CONTROL Y RESGUARDO DEL ACTIVO FIJO INSTITUCIONAL'
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
        typeOfPermission: function () {
            switch (this.permission.id_tipo_permiso) {
                case 1: // Personal con goce
                case 2: // Personal sin goce
                    return 2;
                case 3: // Enfermedad con goce
                case 4: // Enfermedad sin goce
                    return 1;
                case 5: // Mision oficial
                    return 3;
                case 6: // No marcacion
                    return null;
                default:
                    console.error('Ha ocurrido un error');
                    return null;
            }
        },
        salary: function () {
            switch (this.permission.id_tipo_permiso) {
                case 1: // Personal con goce
                    return 1;
                case 2: // Personal sin goce
                    return 2;
                case 3: // Enfermedad con goce
                    return 1;
                case 4: // Enfermedad sin goce
                    return 2;
                case 5: // Mision oficial
                    return 1;
                case 6: // No marcacion
                    return null;
                default:
                    console.error('Ha ocurrido un error');
                    return null;
            }
        },
        totalDays: function () {
            const startDateFormated = moment(this.permission.fecha_inicio_permiso, 'YYYY/MM/DD').toDate();
            const endDateFormated = moment(this.permission.fecha_fin_permiso, 'YYYY/MM/DD').toDate();

            let currentDate = new Date(startDateFormated);
            let daysDifference = 0;

            while (currentDate <= endDateFormated) {
                daysDifference++;
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return daysDifference;
        }
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