<template>
    <table class="w-full border-collapse">
        <tbody>
            <tr>
                <td class="border-l border-black border-t" rowspan="4">
                    <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                </td>
                <td class="border border-b-0 border-black text-center font-medium uppercase" rowspan="2">
                    <div class="text-sm">INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL</div>
                </td>
                <td class="border border-l-0 border-b-0 border-black text-[8pt] font-normal pr-3 px-1.5 pb-2">
                    <div>FECHA DE CREACIÓN: 12/12/2021</div>
                </td>
            </tr>
            <tr>
                <td class="border border-l-0 border-b-0 border-black text-[8pt] font-normal px-1.5 pb-2">
                    <div>ID 00000-1</div>
                </td>
            </tr>
            <tr>
                <td class="border border-x border-b border-black text-center font-medium px-8" rowspan="2">
                    <div class="text-sm">HOJA DE SERVICIOS</div>
                </td>
                <td class="border border-l-0 border-b-0 border-black text-[8pt] font-normal px-1.5 pb-2">
                    <div>DEPENDENCIA: ADMON</div>
                </td>
            </tr>
            <tr>
                <td class="border border-l-0 border-black text-[8pt] font-normal px-1.5 pb-2">
                    <div>CODIGO: VERSION 1.0</div>
                </td>
            </tr>
            <tr>
                <td class="border border-l-0 border-black bg-black h-5 text-white text-center text-[10pt] pb-2" colspan="3">
                    <div>ESPECIFICACIONES</div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="flex items-center justify-center pt-4 px-2 ">
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">NOMBRE:</div>
            </label>
            <div
                class="text-left text-[8pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">SERGIO ADONAY LOPEZ MEJIA</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">DUI:</div>
            </label>
            <div
                class="text-left text-[8pt] w- text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">06184091-8</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">INGRESO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-32 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">19/Abril/1994</div>
            </div>
        </div>

        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">LUGAR FECHA NAC:</div>
            </label>
            <div
                class="text-left text-[8pt] w-52 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">ROSARIO DE MORA, DEPTO. SAN SALVADOR 8/OCTUBRE/1954</div>
            </div>
        </div>

    </div>


    <div class="flex items-center justify-start pt-4 px-2 gap-2">
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">EDUCACION:</div>
            </label>
            <div
                class="text-left text-[8pt] w-60 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">
                    {{ $options.filters.truncate('BACHILLER TEC. INDUSTRIAL OPC. MECANICA AUTOMOTRIZ', 35, '...') }}

                </div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">CARGO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-72 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">
                    {{ $options.filters.truncate('JEFE DE UNIDAD DE ESTADÍSTICA DE REHABILITACIÓN Y EPIDEMIOLOGÍA', 45,
                        '...') }}

                </div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">SUELDO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-12 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">$1200</div>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-start pt-4 px-2 gap-2">
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">DIRECCION:</div>
            </label>
            <div
                class="text-left text-[8pt] w-full text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">
                    {{ $options.filters.truncate(
                        '75 AV. NTE Y CALLE MANO DE LEON, PJE HERNANDEZ, CASA #3 FRENTE A URBANIZACION BOSQUES DE LA ESCALON, SAN ANTONIO ABAD, SAN SALVADOR'
                        , 115, '...') }}


                </div>
            </div>
        </div>
        <!--  <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">LUGAR Y FECHA DE NACIMIENTO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-52 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">ROSARIO DE MORA, DEPTO. SAN SALVADOR 8/OCTUBRE/1954</div>
            </div>
        </div> -->
    </div>

    <div class="pt-10">
        <label class="text-[8pt]">
            <div class="mb-1">INFORMACION ADICIONAL:</div>
        </label>
        <div class="">
            <div class="text-left text-[8pt] font-medium capitalize h-5 pb-2">
                <div class="mb-1">TIENE OTRA PLAZA DE MEDICO ESPECIALISTA EN OTRO CENTRO</div>
            </div>
        </div>
    </div>


    <!--  <div class="flex items-center justify-center pt-2 gap-2 pb-7">
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">Cedula N°:</div>
            </label>
            <div
                class=" text-[8pt] w-10 text-sm text-center font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">06184091-8</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">DIRECCION:</div>
            </label>
            <div
                class="text-left text-[8pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">MIGUEL JOSUE TOBIAS RIVAS</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">LUGAR Y FECHA DE NACIMIENTO :</div>
            </label>
            <div
                class="text-left text-[8pt] w-64 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" style="margin-top: -5px;">COORDINADOR DEL AREA DE SISTEMAS DE...</div>
            </div>
        </div>
        
    </div> -->


    <!--  <div class="flex items-center justify-center pt-4 gap-28">
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">EMPLEADO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" >SERGIO ADONAY LOPEZ MEJIA</div>
            </div>
        </div>
        <div class="flex flex-row items-center text-[7pt] font-normal gap-2">
            <label class="text-[8pt]">
                <div class="" style="margin-top: -9px;">PUESTO:</div>
            </label>
            <div
                class="text-left text-[8pt] w-56 text-sm font-medium capitalize h-5 border-x-0 border-t-0 border-b border-black">
                <div class="" >TECNICO EN SOPORTE INFORMATICO</div>
            </div>
        </div>
    </div> -->

    <!-- 
    <div class="flex items-center justify-center">
        <div class="text-[15pt] w-1/4 -mt-16 text-start font-bold">
            Sombre el empleado
        </div>
        <p class="w-1/2 text-[9.3pt] mb-1 text-justify">

            El empleado {{ `${userData.pnombre_persona ? userData.pnombre_persona : ''} ${userData.snombre_persona ?
                userData.snombre_persona : ''} ${userData.tnombre_persona ? userData.snombre_persona : ''}
                        ${userData.papellido_persona ? userData.papellido_persona : ''} ${userData.sapellido_persona ?
                    userData.sapellido_persona : ''} ${userData.tapellido_persona ? userData.tapellido_persona : ''}` }} con numero
            de DUI {{ userData.dui_persona }} extendedida
            (PENDIENTE)
            {{ userData.fecha_nac_persona }}
            con estado civil {{ userData.estado_civil.nombre_estado_civil }} con direccion {{
                `${userData.municipio.departamento.pais.id_pais}-${userData.municipio.departamento.pais.nombre_pais}
                        ${userData.municipio.departamento.id_departamento}-${userData.municipio.departamento.nombre_departamento}
                        ${userData.municipio.id_municipio}-${userData.municipio.nombre_municipio}` }}, con lugar y fecha de
            nacimiento (PENDIENTE)
            Educacion {{ userData.profesion.nombre_profesion }}, Ingreso el {{ userData.empleado.fecha_contratacion_empleado
            }}, Cargo,
            {{
                userData.empleado.plazas_asignadas.filter((plaza) => plaza.estado_plaza_asignada == 1).map((plaza, index) => {
                    return `${plaza.detalle_plaza.plaza.nombre_plaza} con salario
                        $${userData.empleado.plazas_asignadas.filter((plaza) => plaza.estado_plaza_asignada ==
                        1)[index].salario_plaza_asignada}`
                }
                ).join(", ")
            }}


        </p>
    </div>

    <hr class="h-0.5 border-none mx-14 mb-1 mt-7 bg-[#3D3D3D]">
    <div class="w-1/3">
        <div class="text-[15pt] font-bold  bg-white sticky top-0  text-start pl-24">
            Acuerdos
        </div>
    </div>
    <div class="w-11/12 flex justify-center pl-24">
        <div class="text-[9.3pt]  pt-1">
            <div class="" v-for="i in userData.empleado.acuerdo_laboral" :key="i" id="page2el">
                <h1 class="text-sm font-bold pt-10">{{ i.oficio_acuerdo_laboral }}</h1>
                <h2 class="text-xs font-semibold">{{ i.tipo_acuerdo_laboral.nombre_tipo_acuerdo_laboral }} - <span
                        class="italic font-light">{{ i.fecha_acuerdo_laboral }}</span>
                </h2>
                <p class="text-justify mb-1">
                    {{ i.comentario_acuerdo_laboral }}
                </p>
            </div>
        </div>
    </div> -->
</template>

<script>
import { truncateString } from '@/mixins/truncateString';
export default {
    mixins: [truncateString],
    props: ["userData"],
    data() {
        return {
            flags: 0,
        }
    }
}
</script>

