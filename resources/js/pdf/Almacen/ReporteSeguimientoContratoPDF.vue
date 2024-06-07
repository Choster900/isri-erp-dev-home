<template>
    <div id="doc-header" class="flex justify-between items-center border-y-4 border-gray-600 pb-1 mb-2 mx-4">
        <div class="text-left flex flex-col">
            <p class="font-[Roboto] text-[13px] font-bold pb-[5px]">INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL
            </p>
            <p class="font-[Roboto] text-[13px] font-bold pb-[5px]">ALMACEN GENERAL</p>
            <p class="font-[Roboto] text-[13px] font-bold pb-[5px]">SEGUIMIENTO DE PRODUCTOS POR CONTRATO</p>
        </div>
        <div class="text-right">
            <p class="font-[Roboto] uppercase text-[12px] pb-[5px]">DEL
                {{ startDate }}
            </p>
            <p class="font-[Roboto] uppercase text-[12px] pb-[5px]">AL
                {{ endDate }}
            </p>
        </div>
    </div>
    <div class="mx-4">
        <p class="font-[Roboto] text-[12px] mb-1 pb-2">CONTRATO: <span class="font-[Roboto] text-[12px] font-bold">{{
            contractName }}</span></p>
    </div>

    <div class="mx-4">
        <div
            class="grid grid-cols-[18%_82%] bg-[#001c48] text-white border-x border-t border-gray-600 bg-opacity-80">
            <!-- Table header -->
            <div class="flex items-center justify-center border-r border-gray-600 h-[30px]">
                <p class="text-center font-[MuseoSans] font-bold text-[11px] pb-[10px]">PRODUCTO
                </p>
            </div>
            <div class="flex items-center justify-center h-[30px]">
                <p class="text-center font-[MuseoSans] text-[11px] pb-[10px] font-bold">RECEPCIONES MENSUALES
                    {{ purchaseProcess === 5 ? '(EN DÓLARES)' : '' }}
                </p>
            </div>
        </div>
        <template v-for="(prod, index) in products" :key="index">
            <!-- Table content -->
            <div class="grid grid-cols-[18%_82%] hover:bg-gray-300 w-full border-l border-y border-gray-600 bg-opacity-80"
                :class="index % 2 === 0 ? 'bg-slate-200' : 'bg-white'" style="page-break-inside: avoid;">
                <div
                    class="flex items-center justify-center border-r border-gray-600">
                    <p class="font-[MuseoSans] text-[10px] font-bold h-full p-0.5 ">
                        {{ prod.producto }}
                    </p>
                </div>
                <div class="flex-row items-center justify-center h-full">
                    <div class="">
                        <div class="grid grid-cols-6">
                            <template v-for="(month, index2) in prod.meses" :key="index2">
                                <div v-if="index2 <= 5"
                                    class="flex-row items-center justify-center border-r border-b border-gray-600">
                                    <p class="font-[MuseoSans] text-[12px] text-center underline">
                                        {{
                                            month.mes }}</p>
                                    <div class="grid grid-cols-2">
                                        <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center pb-[3px]">
                                            Contratado</p>
                                        <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center pb-[3px]">
                                            Recibido </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="grid grid-cols-6 h-[30px] border-b border-gray-600">
                            <template v-for="(month, index3) in prod.meses" :key="index3">
                                <div v-if="index3 <= 5" class="w-full grid grid-cols-2">
                                    <div class="w-full flex items-center justify-center border-r border-gray-600">
                                        <p class="font-[MuseoSans] text-[10px] pb-[5px]">
                                            {{ month.res.cant_pa }} </p>
                                    </div>
                                    <div class="w-full flex items-center justify-center border-r border-gray-600">
                                        <p class="font-[MuseoSans] text-[10px] pb-[5px]">
                                            {{ month.res.cant_rec }} </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="">
                        <div class="grid grid-cols-6">
                            <template v-for="(month, index2) in prod.meses" :key="index2">
                                <div v-if="index2 >= 6"
                                    class="flex-row items-center justify-center border-r border-b border-gray-600">
                                    <p class="font-[MuseoSans] text-[12px] text-center underline">
                                        {{
                                            month.mes }}</p>
                                    <div class="grid grid-cols-2">
                                        <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center pb-[3px]">
                                            Contratado</p>
                                        <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center pb-[3px]">
                                            Recibido </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="grid grid-cols-6 h-[30px] border-gray-600">
                            <template v-for="(month, index3) in prod.meses" :key="index3">
                                <div v-if="index3 >= 6" class="w-full grid grid-cols-2">
                                    <div class="w-full flex items-center justify-center border-r border-gray-600">
                                        <p class="font-[MuseoSans] text-[10px] pb-[5px]">
                                            {{ month.res.cant_pa }} </p>
                                    </div>
                                    <div class="w-full flex items-center justify-center border-r border-gray-600">
                                        <p class="font-[MuseoSans] text-[10px] pb-[5px]">
                                            {{ month.res.cant_rec }} </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

</template>

<script>

export default {
    props: {
        products: {
            type: Object,
            default: {}
        },
        contractName: {
            type: String,
            default: ""
        },
        purchaseProcess: {
            type: String,
            default: ""
        },
        startDate: {
            type: String,
            default: ""
        },
        endDate: {
            type: String,
            default: ""
        }
    },
    setup(props) {
        return {
        }
    }
}
</script>

<style></style>