<template>
    <div class="">
        <p class="mb-[1.9cm] text-justify">
            Yo, <span class="font-semibold">{{ finiquito.empleado ? finiquito.empleado.persona.nombre_apellido : ""
            }}</span>,
            mayor de edad, de nacionalidad salvadoreña, <span class="font-semibold">{{ finiquito.empleado ?
                finiquito.empleado.persona.profesion.nombre_profesion : "" }}</span>,
            del domicilio de {{ finiquito.empleado ?
                finiquito.empleado.persona.residencias[0].municipio.nombre_municipio : "" }},
            con Documento Único de identidad numero {{ finiquito.empleado ?
                DUIWords : "" }},
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
            ciudad de San Salvador a los {{ signatureDate }}.
        </p>
        <p class="pb-[1cm] text-justify mt-[-5px]">
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
                DUIWords : "" }},
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
                DUIWords : "" }},
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
    </div>

    <div id="pagebreak">
        <p class="text-right font-semibold text-[18px] mb-8">Por $ {{ finiquito.monto_finiquito_laboral }}</p>
        <p class="mb-[100px] text-justify">
            Yo, <span class="font-semibold">{{ finiquito.empleado ? finiquito.empleado.persona.nombre_apellido :
                "" }}</span>
            con Documento Único de Identidad número: {{ finiquito.empleado ?
                DUIWords : "" }},
            hago constar que he recibido en este acto la cantidad de: {{ amount }}, en concepto de pago por
            compensación
            económica correspondiente al año {{ year.toLowerCase() }} de los acuerdos pactados entre la
            Administración del Instituto Salvadoreño de Rehabilitación Integral - ISRI, quien representa el
            interés
            del Instituto y de todos sus empleados.
        </p>
        <div class="flex w-full mb-[100px]">
            <p class="w-[3%]">
                F.
            </p>
            <p class="w-[50%] border-b border-black mb-[-5px]">

            </p>
        </div>
        <p class="text-[12px] mb-20 text-justify">
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
            {{ finiquito.empleado ? DUIWords : "" }}, en la ciudad de
            SAN SALVADOR, a
            los {{ signatureDateA }}.
        </p>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        finiquito: {
            type: Object,
            default: {},
        },
        hireDate: {
            type: String,
            default: '',
        },
        signatureDate: {
            type: String,
            default: '',
        },
        signatureDateA: {
            type: String,
            default: '',
        },
        signatureTime: {
            type: String,
            default: '',
        },
        period: {
            type: String,
            default: '',
        },
        amount: {
            type: String,
            default: '',
        },
        year: {
            type: String,
            default: '',
        },
        DUIWords: {
            type: String,
            default: '',
        },
    },
    setup() {
        return {

        }
    }
};
</script>
<style>
#pagebreak {
    page-break-before: always;
}

.center-vertically {
    align-items: center;
}
</style>