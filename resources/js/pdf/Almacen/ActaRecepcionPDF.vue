<template>
    <!-- <pre class="py-2">{{ recToPrint }}</pre> -->
    <div class="py-2 mb-2">
        <div class="flex w-full">
            <!-- Columna 1 -->
            <div class="w-[23%]  border border-gray-500 p-1">
                <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-full my-auto">
            </div>
            <!-- Columna 2 -->
            <div class="w-[77%]  border-y border-r border-gray-500 p-1">
                <p class="font-[Bembo] text-center text-[12px] font-bold">ALMACEN CENTRAL</p>
                <p class="font-[Bembo] text-center text-[12px] font-bold">
                    {{
                        recToPrint.det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion.nombre_tipo_doc_adquisicion
                    }}
                    {{ recToPrint.det_doc_adquisicion.documento_adquisicion.numero_doc_adquisicion }}
                </p>
                <p class="font-[Bembo] text-center text-[12px] font-bold">
                    {{ recToPrint.det_doc_adquisicion.nombre_det_doc_adquisicion }}
                </p>
            </div>
        </div>

        <div class="flex w-full border-x border-gray-500">
            <div class="w-[50%] flex justify-start items-center font-[MuseoSans] text-[12px] mt-[-5px] pb-[8px]">
                Proveedor: <span class="ml-1 font-bold font-[MuseoSans] text-[12px]">
                    {{ recToPrint.det_doc_adquisicion.documento_adquisicion.proveedor.razon_social_proveedor }}
                </span>
            </div>
            <div class="w-[50%] flex justify-start items-center font-[MuseoSans] text-[12px] mt-[-5px] pb-[8px]">
                Column2
            </div>
        </div>
        <div class="flex w-full border-b border-x border-gray-500">
            <div class="w-[50%] flex justify-start font-[MuseoSans] items-center text-[12px] mt-[-5px] pb-[8px]">
                Column1
            </div>
            <div class="w-[50%] flex justify-start font-[MuseoSans] items-center text-[12px] mt-[-5px] pb-[8px]">
                Column2
            </div>
        </div>

        <div
            class="flex border-x border-b border-gray-500 justify-center items-center font-[MuseoSans] text-[12px] mt-[-5px] pb-[8px]">
            Acta Numero
        </div>


    </div>
</template>

<script>
import { upperCase } from 'lodash';

export default {
    components: {},
    props: {
        recToPrint: {
            type: Object,
            default: {},
        }
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