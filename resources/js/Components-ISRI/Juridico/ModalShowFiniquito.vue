<template>
    <div class="m-1.5 p-10">
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
        <ProcessModal v-else maxWidth='3xl' :show="showSettlement" @close="$emit('cerrar-modal')">

            <div v-if="permits.ejecutar == 1" class="flex justify-center pt-2 content-between">
                <div class="px-2">
                    <GeneralButton color="bg-red-700 hover:bg-red-800" text="PDF" icon="pdf" @click="printPdf()" />
                </div>
            </div>

            <div class="mt-[0.5cm] mb-[1cm] mx-[1.5cm]">
                <p class="mb-[2cm] text-justify font-[Roboto]">
                    Yo, <span class="font-[Roboto] font-semibold">{{ finiquito.empleado ?
                        finiquito.empleado.persona.nombre_apellido : ""
                    }}</span>,
                    mayor de edad, de nacionalidad salvadoreña, <span class="font-[Roboto] font-semibold">{{
                        finiquito.empleado ?
                        finiquito.empleado.persona.profesion.nombre_profesion : "" }}</span>,
                    del domicilio de {{ finiquito.empleado ?
                        finiquito.empleado.persona.residencias[0].municipio.nombre_municipio : "" }},
                    departamento de {{ finiquito.empleado ?
                        finiquito.empleado.persona.residencias[0].municipio.departamento.nombre_departamento : "" }},
                    con Documento Único de identidad numero {{ finiquito.empleado ?
                        DUIToWords(finiquito.empleado.persona.dui_persona) : "" }},
                    por medio del presente documento OTORGO: AMPLIO Y SUFICIENTE FINIQUITO a favor del INSTITUTO SALVADOREÑO
                    DE REHABILITACION
                    INTEGRAL, institución a la cual estoy vinculado(a) desde el {{ hireDate }}, como consecuencia de lo
                    anterior
                    HAGO CONSTAR: que el INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL (ISRI) no me adeuda ninguna suma
                    de dinero
                    en concepto de pago por las prestaciones económicas contempladas en el Laudo Arbitral que rige la
                    relación
                    laboral entre el ISRI y sus empleados, representados en el mismo por SITRAISRI para el período
                    comprendido
                    {{ period }}. Así mismo HAGO CONSTAR: Que no tengo nada que reclamarle al INSTITUTO SALVADOREÑO DE
                    REHABILITACION
                    INTEGRAL referente a prestaciones económicas y sociales durante el período comprendido {{ period }},
                    como
                    consecuencia lo declaro solvente de toda obligación derivada de dicho Laudo Arbitral y exento de toda
                    responsabilidad para conmigo, extendiéndole amplio y total FINIQUITO. En fe de lo dicho, firmo el
                    presente documento en la
                    ciudad de San Salvador a los {{ signatureDateA }}.
                </p>
                <p class="font-[Roboto] pb-[1cm] border-b border-gray-400 text-justify">
                    En la ciudad de San Salvador a las {{ signatureTime }}, del día {{ signatureDate }}.
                    Ante mí, {{ finiquito.persona ? finiquito.persona.nombre_apellido : "" }},
                    {{ finiquito.persona ? finiquito.persona.profesion.nombre_profesion : "" }}, del domicilio de
                    {{ finiquito.persona ? finiquito.persona.residencias[0].municipio.nombre_municipio : "" }},
                    comparece {{ finiquito.empleado ? finiquito.empleado.persona.nombre_apellido : "" }}, mayor de edad, de
                    nacionalidad salvadoreña, {{ finiquito.empleado ? finiquito.empleado.persona.profesion.nombre_profesion
                        : "" }},
                    del domicilio de {{ finiquito.empleado ?
                        finiquito.empleado.persona.residencias[0].municipio.nombre_municipio : "" }},
                    departamento de {{ finiquito.empleado ?
                        finiquito.empleado.persona.residencias[0].municipio.departamento.nombre_departamento : "" }},
                    portador de su Documento Único de Identidad numero {{ finiquito.empleado ?
                        DUIToWords(finiquito.empleado.persona.dui_persona) : "" }},
                    y ME DICE: Que reconoce como suya la firma puesta en el anterior documento por medio del cual extiende
                    AMPLIO Y
                    SUFICIENTE FINIQUITO, a favor del INSTITUTO SALVADOREÑO DE REHABILITACIÓN INTEGRAL - ISRI, del domicilio
                    de SAN SALVADOR
                    en el departamento de SAN SALVADOR, institución con la cual me dice está vinculado(a) desde el día
                    {{ hireDate }} y que literalmente dice """"""
                    Yo, {{ finiquito.empleado ? finiquito.empleado.persona.nombre_apellido : "" }},
                    mayor de edad, de nacionalidad salvadoreña, {{ finiquito.empleado ?
                        finiquito.empleado.persona.profesion.nombre_profesion : "" }},
                    del domicilio de {{ finiquito.empleado ?
                        finiquito.empleado.persona.residencias[0].municipio.nombre_municipio : "" }},
                    con Documento Único de identidad numero {{ finiquito.empleado ?
                        DUIToWords(finiquito.empleado.persona.dui_persona) : "" }},
                    por medio del presente documento OTORGO: AMPLIO Y SUFICIENTE FINIQUITO a favor del INSTITUTO SALVADOREÑO
                    DE REHABILITACION
                    INTEGRAL, institución a la cual estoy vinculado(a) desde el {{ hireDate }}, como consecuencia de lo
                    anterior
                    HAGO CONSTAR: que el INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL (ISRI) no me adeuda ninguna suma
                    de dinero
                    en concepto de pago por las prestaciones económicas contempladas en el Laudo Arbitral que rige la
                    relación
                    laboral entre el ISRI y sus empleados, representados en el mismo por SITRAISRI para el período
                    comprendido
                    {{ period }}. Así mismo HAGO CONSTAR: Que no tengo nada que reclamarle al INSTITUTO SALVADOREÑO DE
                    REHABILITACION
                    INTEGRAL referente a prestaciones económicas y sociales durante el período comprendido {{ period }},
                    como
                    consecuencia lo declaro solvente de toda obligación derivada de dicho Laudo Arbitral y exento de toda
                    responsabilidad para conmigo, extendiéndole amplio y total FINIQUITO. """"""
                    Así se expresó el (la) compareciente a quien le expliqué los efectos legales de esta acta notarial,
                    escrita en una
                    hoja de papel simple y que comienza al pie del anterior documento. Y leído que le fue por mi todo lo
                    escrito
                    en un solo acto sin interrupción, ratifica su contenido y firmamos DOY FE. -
                </p>
                <div class="mt-[1.5cm]">
                    <p class="text-right font-[Roboto] font-semibold text-[18px] mb-8">Por $ {{
                        finiquito.monto_finiquito_laboral }}</p>
                    <p class="font-[Roboto] mb-20 text-justify">
                        Yo, <span class="font-[Roboto] font-semibold">{{ finiquito.empleado ?
                            finiquito.empleado.persona.nombre_apellido :
                            "" }}</span>
                        con Documento Único de Identidad número: {{ finiquito.empleado ?
                            DUIToWords(finiquito.empleado.persona.dui_persona) : "" }},
                        hago constar que he recibido en este acto la cantidad de: {{ amount }}, en concepto de pago por
                        compensación
                        económica correspondiente al año {{ year.toLowerCase() }} de los acuerdos pactados entre la
                        Administración del Instituto Salvadoreño de Rehabilitación Integral - ISRI, quien representa el
                        interés del Instituto y de todos sus empleados.
                    </p>
                    <p class="mb-20">F.________________________</p>
                    <p class="font-[Roboto] text-[12px] mb-20 text-justify">
                        EL SUSCRITO NOTARIO DA FE: Que la firma que calza el anterior documento y que se lee "ilegible"
                        es AUTENTICA por haber sido puesta a mi presencia de su puño y letra por
                        {{ finiquito.empleado ? finiquito.empleado.persona.nombre_apellido : "" }}, mayor de edad,
                        {{ finiquito.empleado ? finiquito.empleado.persona.profesion.nombre_profesion : "" }} del domicilio
                        de
                        {{ finiquito.empleado ? finiquito.empleado.persona.residencias[0].municipio.nombre_municipio : "" }}
                        departamento de
                        {{ finiquito.empleado ?
                            finiquito.empleado.persona.residencias[0].municipio.departamento.nombre_departamento : "" }},
                        persona a quien conozco e identifico legalmente por medio de su Documento Único de Identidad número
                        {{ finiquito.empleado ? DUIToWords(finiquito.empleado.persona.dui_persona) : "" }}, en la ciudad de
                        SAN SALVADOR, a
                        los {{ signatureDateA }}.
                    </p>
                </div>
            </div>


        </ProcessModal>
    </div>
</template>

<script>
import { useShowFiniquito } from '@/Composables/Juridico/Finiquito/useShowFiniquito.js';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'

import { toRefs, onMounted, ref } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal },
    props: {
        showSettlement: {
            type: Boolean,
            default: false,
        },
        finiquitoId: {
            type: Number,
            default: 0
        },
        permits: {
            type: Object,
            default: {}
        }
    },
    setup(props, context) {

        const { finiquitoId } = toRefs(props)

        const {
            isLoadingRequest, finiquito, hireDate, signatureDateA, period, signatureTime,
            signatureDate, amount, year,
            getInfoForModalFiniquito, DUIToWords, printPdf
        } = useShowFiniquito(context);

        onMounted(
            async () => {
                await getInfoForModalFiniquito(finiquitoId.value)
            }
        )


        return {
            isLoadingRequest, finiquito, hireDate, signatureDateA, period, signatureTime,
            signatureDate, amount, year,
            DUIToWords, printPdf
        }
    }
}
</script>

<style>
.table-row {
    display: flex;
    justify-content: space-between;
}</style>