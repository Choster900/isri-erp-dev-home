<template>
    <div class="h-screen relative mb-10">
        <div class="h-[90%] mt-3 mb-4">
            <div class="text-center">
                <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[150px] h-[75px] mx-auto">
                <h2 class="text-[14px] mt-2">UNIDAD FINANCIERA INSTITUCIONAL</h2>
                <h2 class="text-[14px] ">INFORME DE INGRESOS RECIBIDOS POR TESORERIA INSTITUCIONAL</h2>
            </div>
            <div class="flex">
                <div class="text-center text-[14px] mb-2 w-full" style="margin-left: 60px;">DIA: {{ date }}</div>
                <div class="z-20 text-right text-[14px] flex justify-end mb-2">
                    <p>ORIGINAL</p>
                </div>
            </div>

            <table class="border-collapse w-full mr-2 mb-1 text-[10px]">
                <thead>
                    <tr>
                        <th class="border border-black py-2 px-4 text-center w-[10%] text-[10px]" rowspan="2">
                            CENTRO
                        </th>
                        <th class="border border-black py-2 px-4 text-center w-[35%] text-[10px]" rowspan="2">
                            CONCEPTO
                        </th>
                        <th class="border border-black py-1 px-4 text-center w-[40%] text-[10px]" colspan="7">
                            ESPECIFICO PRESUPUESTARIO
                        </th>
                        <th class="border border-black py-2 px-4 text-center w-[15%] text-[10px]" rowspan="2">
                            TOTAL REMESA <br>
                            {{ financing_source }} <br>
                            CTA.NO.00210240906
                        </th>
                    </tr>
                    <tr class="w-full">
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14199</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14202</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14204</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14299</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">15799</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">15502</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">16304</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="concept in daily_income_report" :key="concept.id_det_recibo_ingreso" class="h-auto">
                        <td class="border border-black  text-center center-vertically"> {{ concept.codigo_dependencia }}
                        </td>
                        <td class="border border-black  text-center center-vertically"> {{ concept.observacion }}</td>
                        <td class="border border-black center-vertically text-right"> ${{ concept['14199'] }}</td>
                        <td class="border border-black center-vertically text-right"> ${{ concept['14202'] }}</td>
                        <td class="border border-black center-vertically text-right"> ${{ concept['14204'] }}</td>
                        <td class="border border-black center-vertically text-right"> ${{ concept['14299'] }}</td>
                        <td class="border border-black center-vertically text-right"> ${{ concept['15799'] }}</td>
                        <td class="border border-black center-vertically text-right"> ${{ concept['15502'] }}</td>
                        <td class="border border-black center-vertically text-right"> ${{ concept['16304'] }}</td>
                        <td class="border border-black  text-center center-vertically">${{ concept.total }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black center-vertically text-center text-[10px] font-bold" colspan="2">
                            TOTALES POR ESPECIFICO PRESUPUESTARIO Y POR FONDO
                        </td>
                        <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14199 }}
                        </td>
                        <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14202 }}
                        </td>
                        <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14204 }}
                        </td>
                        <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14299 }}
                        </td>
                        <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code15799 }}
                        </td>
                        <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code15502 }}
                        </td>
                        <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code16304 }}
                        </td>
                        <td class="border border-black center-vertically text-center font-bold"> ${{ total_remittance }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-left mb-2">
                <p class="text-[10px]">Documentos Adjuntos: Recibos N°: {{ array_receipt_numbers }}</p>
            </div>
            <div class="text-center mb-7">
                <p class="font-bold text-[14px]">TOTAL REMESA: ${{ total_remittance }}</p>
            </div>
        </div>
        <div class="h-[10%] mt-10 ">
            <div class="flex justify-between items-end mb-4">
                <div class="w-1/2 text-center">
                    <p class="font-bold text-[12px]">Lic Carmen Elena Ayala de Chavez</p>
                    <p class="font-bold text-[12px]">Tesorera Institucional</p>
                </div>
                <div class="w-1/2 text-center">
                    <p class="font-bold text-[12px]">Sra. Sonia Evelyn Melara</p>
                    <p class="font-bold text-[12px]">Colectora</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page_break mt-7">
        <div class="h-screen relative mb-10">
            <div class="h-[90%] mt-3 mb-4">
                <div class="text-center">
                    <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[150px] h-[75px] mx-auto">
                    <h2 class="text-[14px] mt-2">UNIDAD FINANCIERA INSTITUCIONAL</h2>
                    <h2 class="text-[14px] ">INFORME DE INGRESOS RECIBIDOS POR TESORERIA INSTITUCIONAL</h2>
                </div>
                <div class="flex">
                    <div class="ml-14 text-center text-[14px] mb-2 w-full" style="margin-left: 253px;">DIA: {{ date }}</div>
                    <div class="z-20 text-right flex justify-end mb-2 text-[14px] w-1/2">
                        <p>COPIA: PRESUPUESTO</p>
                    </div>
                </div>

                <table class="border-collapse w-full mr-2 mb-1 text-[10px]">
                    <thead>
                        <tr>
                            <th class="border border-black py-2 px-4 text-center w-[10%] text-[10px]" rowspan="2">
                                CENTRO
                            </th>
                            <th class="border border-black py-2 px-4 text-center w-[35%] text-[10px]" rowspan="2">
                                CONCEPTO
                            </th>
                            <th class="border border-black py-1 px-4 text-center w-[40%] text-[10px]" colspan="7">
                                ESPECIFICO PRESUPUESTARIO
                            </th>
                            <th class="border border-black py-2 px-4 text-center w-[15%] text-[10px]" rowspan="2">
                                TOTAL REMESA <br>
                                {{ financing_source }} <br>
                                CTA.NO.00210240906
                            </th>
                        </tr>
                        <tr class="w-full">
                            <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14199</th>
                            <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14202</th>
                            <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14204</th>
                            <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14299</th>
                            <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">15799</th>
                            <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">15502</th>
                            <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">16304</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="concept in daily_income_report" :key="concept.id_det_recibo_ingreso" class="h-auto">
                            <td class="border border-black  text-center center-vertically"> {{ concept.codigo_dependencia }}
                            </td>
                            <td class="border border-black  text-center center-vertically"> {{ concept.observacion }}</td>
                            <td class="border border-black center-vertically text-right"> ${{ concept['14199'] }}</td>
                            <td class="border border-black center-vertically text-right"> ${{ concept['14202'] }}</td>
                            <td class="border border-black center-vertically text-right"> ${{ concept['14204'] }}</td>
                            <td class="border border-black center-vertically text-right"> ${{ concept['14299'] }}</td>
                            <td class="border border-black center-vertically text-right"> ${{ concept['15799'] }}</td>
                            <td class="border border-black center-vertically text-right"> ${{ concept['15502'] }}</td>
                            <td class="border border-black center-vertically text-right"> ${{ concept['16304'] }}</td>
                            <td class="border border-black  text-center center-vertically">${{ concept.total }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black center-vertically text-center text-[10px] font-bold" colspan="2">
                                TOTALES POR ESPECIFICO PRESUPUESTARIO Y POR FONDO
                            </td>
                            <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14199
                            }}
                            </td>
                            <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14202
                            }}
                            </td>
                            <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14204
                            }}
                            </td>
                            <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code14299
                            }}
                            </td>
                            <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code15799
                            }}
                            </td>
                            <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code15502
                            }}
                            </td>
                            <td class="border border-black center-vertically text-right font-bold"> ${{ all_totals.code16304
                            }}
                            </td>
                            <td class="border border-black center-vertically text-center font-bold"> ${{ total_remittance }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-left mb-2">
                    <p class="text-[10px]">Documentos Adjuntos: Recibos N°: {{ array_receipt_numbers }}</p>
                </div>
                <div class="text-center mb-7">
                    <p class="font-bold text-[14px]">TOTAL REMESA: ${{ total_remittance }}</p>
                </div>
            </div>
            <div class="h-[10%] mt-10 ">
                <div class="flex justify-between items-end mb-4">
                    <div class="w-1/2 text-center">
                        <p class="font-bold text-[12px]">Lic Carmen Elena Ayala de Chavez</p>
                        <p class="font-bold text-[12px]">Tesorera Institucional</p>
                    </div>
                    <div class="w-1/2 text-center">
                        <p class="font-bold text-[12px]">Sra. Sonia Evelyn Melara</p>
                        <p class="font-bold text-[12px]">Colectora</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="mt-3">


    </div> -->
    <!-- <div class=""></div>
    <div class="page_break mt-7">
        <div class="flex flex-col mt-7">
            <table class="border-collapse w-full mr-2">
                <thead>
                    <tr>
                        <th class="border border-black py-2 px-4 text-center w-[10%] text-[10px]" rowspan="2">
                            CENTRO
                        </th>
                        <th class="border border-black py-2 px-4 text-center w-[35%] text-[10px]" rowspan="2">
                            CONCEPTO
                        </th>
                        <th class="border border-black py-1 px-4 text-center w-[40%] text-[10px]" colspan="7">
                            ESPECIFICO PRESUPUESTARIO
                        </th>
                        <th class="border border-black py-2 px-4 text-center w-[15%] text-[10px]" rowspan="2">
                            TOTAL REMESA <br>
                            RECURSOS PROPIOS <br>
                            CTA.NO.00210240906
                        </th>
                    </tr>
                    <tr class="w-full">
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14199</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14202</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14204</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">14299</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">15799</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">15502</th>
                        <th class="border border-black py-1 px-4 text-center w-1/7 text-[10px]">16304</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black py-2 px-4 text-center"> </td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                        <td class="border border-black py-2 px-4 text-center"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div> -->
</template>

<script>
export default {
    props: {
        daily_income_report: {
            type: Array,
            default: [],
        },
        financing_source: {
            type: String,
            default: ''
        },
        date: {
            type: String,
            default: ''
        },
        total_remittance: {
            type: String,
            default: ''
        },
        all_totals: {
            type: Array,
            default: [],
        },
        array_receipt_numbers: {
            type: String,
            default: ''
        }
    },
    data() {
        return {

        }
    },
    computed: {
    }
}
</script>

<style>
/* td, th {
  vertical-align: middle;
} */
.center-vertically {
    align-items: right;
    padding-bottom: 10px;
}

table {
    table-layout: fixed;
}

.page_break {
    page-break-before: always;
}</style>