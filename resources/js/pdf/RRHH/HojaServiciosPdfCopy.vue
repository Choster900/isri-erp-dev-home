<template>
    <div class="flex items-center pt-11">
        <div class="bg-[#3D3D3D] p-4 w-7/12 h-[132px] flex flex-col justify-center">
            <div class="flex items-center gap-3">
                <img src="../../../img/default-avatar2.jpg" alt="Foto de la persona" class="w-16 h-16 rounded-full mb-2">
                <div class="mb-6">
                    <h2 class="text-base text-white font-semibold"> {{ userData != '' ?
                        `${userData.empleado.codigo_empleado}
                                            - ${userData.pnombre_persona ? userData.pnombre_persona : ''} ${userData.snombre_persona ?
                            userData.snombre_persona : ''} ${userData.tnombre_persona ? userData.snombre_persona : ''}
                                            ${userData.papellido_persona ? userData.papellido_persona : ''} ${userData.sapellido_persona ?
                            userData.sapellido_persona : ''} ${userData.tapellido_persona ? userData.tapellido_persona : ''}
                        `: '' }}</h2>
                    <p class="text-xs text-white">
                        {{ userData.empleado.plazas_asignadas.filter((plaza) => plaza.estado_plaza_asignada ==
                            1).map((plaza, index) => {
                                return `${plaza.detalle_plaza.plaza.nombre_plaza}`
                            }
                            ).join(", ") }}

                    </p>
                </div>
            </div>
        </div>
        <!--  <pre>
            {{ dataUser }}
        </pre> -->
        <div class="ml-4">
            <div class="mb-2">
                <div class="flex items-center"> <!-- Use "items-center" to vertically align items -->
                    <span class="rounded-full border border-black p-0.5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path
                                d="M2.00589 4.54166C1.905 3.11236 3.11531 2 4.54522 2H7.60606C8.34006 2 9.00207 2.44226 9.28438 3.1212L10.5643 6.19946C10.8761 6.94932 10.6548 7.81544 10.0218 8.32292L9.22394 8.96254C8.86788 9.24798 8.74683 9.74018 8.95794 10.1448C10.0429 12.2241 11.6464 13.9888 13.5964 15.2667C14.008 15.5364 14.5517 15.4291 14.8588 15.0445L15.6902 14.003C16.1966 13.3687 17.0609 13.147 17.8092 13.4594L20.8811 14.742C21.5587 15.0249 22 15.6883 22 16.4238V19.5C22 20.9329 20.8489 22.0955 19.4226 21.9941C10.3021 21.3452 2.65247 13.7017 2.00589 4.54166Z"
                                fill="#3D3D3D"></path>
                        </svg>
                    </span>
                    <span class="ml-2 mb-4 text-xs">
                        +503 {{ userData.telefono_persona || 'No definido' }}
                    </span>
                </div>
            </div>
            <div class="mb-2">
                <div class="flex items-center"> <!-- Use "items-center" to vertically align items -->
                    <span class="rounded-full border border-black p-0.5">
                        <svg fill="#3D3D3D" class="w-5 h-5" viewBox="-0.5 0.5 42 42">
                            <path
                                d="M40.5,31.5v-18c0,0-18.2,12.7-19.97,13.359C18.79,26.23,0.5,13.5,0.5,13.5v18c0,2.5,0.53,3,3,3h34 C40.029,34.5,40.5,34.061,40.5,31.5z M40.471,9.971c0-1.821-0.531-2.471-2.971-2.471h-34c-2.51,0-3,0.78-3,2.6l0.03,0.28 c0,0,18.069,12.44,20,13.12C22.57,22.71,40.5,10.1,40.5,10.1L40.471,9.971z">
                            </path>

                        </svg>
                    </span>
                    <span class="ml-2 mb-4 text-xs">
                        {{ userData.empleado.email_institucional_empleado }}
                    </span>
                </div>
            </div>
            <div class="mb-2">
                <div class="flex items-center"> <!-- Use "items-center" to vertically align items -->
                    <span class="rounded-full border border-black p-0.5">
                        <svg class="w-5 h-5" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">
                            <g id="SVGRepo_iconCarrier">
                                <title>location-filled</title>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="location-outline" fill="#3D3D3D" transform="translate(106.666667, 42.666667)">
                                        <path
                                            d="M149.333333,7.10542736e-15 C231.807856,7.10542736e-15 298.666667,66.8588107 298.666667,149.333333 C298.666667,176.537017 291.413333,202.026667 278.683512,224.008666 C270.196964,238.663333 227.080238,313.32711 149.333333,448 C71.5864284,313.32711 28.4697022,238.663333 19.9831547,224.008666 C7.25333333,202.026667 2.84217094e-14,176.537017 2.84217094e-14,149.333333 C2.84217094e-14,66.8588107 66.8588107,7.10542736e-15 149.333333,7.10542736e-15 Z M149.333333,85.3333333 C113.987109,85.3333333 85.3333333,113.987109 85.3333333,149.333333 C85.3333333,184.679557 113.987109,213.333333 149.333333,213.333333 C184.679557,213.333333 213.333333,184.679557 213.333333,149.333333 C213.333333,113.987109 184.679557,85.3333333 149.333333,85.3333333 Z"
                                            id="Combined-Shape"> </path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <span class="ml-2 mb-4 text-xs">
                        {{
                            `${userData.municipio.departamento.pais.id_pais}-${userData.municipio.departamento.pais.nombre_pais}
                                                ${userData.municipio.departamento.id_departamento}-${userData.municipio.departamento.nombre_departamento}
                                                ${userData.municipio.id_municipio}-${userData.municipio.nombre_municipio}` }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <hr class="h-0.5 border-none mx-14 mb-3 mt-7 bg-[#3D3D3D]">


    <div class="flex items-center justify-center">
        <div class="text-[15pt] w-1/4 -mt-16 text-start font-bold">
            Sobre el empleado
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
    </div>
</template>

<script>
export default {
    props: ["userData"],
    data() {
        return {
            flags: 0,
        }
    }
}
</script>

