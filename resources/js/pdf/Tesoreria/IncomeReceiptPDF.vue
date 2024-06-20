<template>
    <div class="flex flex-col ">
        <div class="">
            <!-- Contenido para la sección superior aquí -->
            <div class="flex justify-between items-center mt-0">
                <!-- Encabezado izquierdo  -->
                <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                    <img src="../../../img/escudo-nacional.png" alt="Escudo El Salvador"
                        class="w-[50px] h-[50px] object-contain mb-[5px]">
                    <div class="text-center text-[12px] font-bold mx-5">REPUBLICA DE EL SALVADOR</div>
                    <div class="text-center text-[12px] font-bold mx-5">MINISTERIO DE SALUD</div>
                </div>
                <!-- Encabezado centrado  -->
                <div class="title-container flex flex-col justify-center items-center">
                    <h1 class=" text-[13px] font-bold">RECIBO DE INGRESO</h1>
                    <p class=" text-[12px] font-bold mt-[20px]">ORIGINAL - INTERESADO</p>
                    <div class=" relative flex flex-row justify-center mt-[25px]">
                        <label for="" class="flex items-center text-[10px] ">NUMERO</label>
                        <div class="ml-1 w-[150px] border-b border-gray-700 text-[11px]">
                            <p class="mb-[5px] font-bold">{{ receipt_to_print.numero_recibo_ingreso }}</p>
                        </div>
                    </div>
                </div>
                <!-- Encabezado derecho -->
                <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                    <img src="../../../img/isri-logo2.png" alt="Logo ISRI"
                        class="w-[50px] h-[50px] object-contain mb-[5px]">
                    <div class="text-center text-[12px] font-bold mx-5" style="text-align: center;">INSTITUTO
                        SALVADOREÑO DE
                        REHABILITACION INTEGRAL</div>
                </div>
            </div>
            <!-- Contenedor 3 -->
            <div class="flex justify-between items-center mt-2 mb-2">
                <div class="flex text-left w-2/3 ml-5">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[10px]">UNIDAD FINANCIERA INSTITUCIONAL
                            (UFI-ISRI)</label>
                    </div>
                </div>
                <div class="flex w-1/4 text-left mr-5">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[10px]">FECHA</label>
                        <div class="ml-1 text-center w-full border-b border-gray-700 text-[11px] font-bold">
                            <p class="mb-[5px]">{{ fecha_recibo }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 mb-5 w-[100%]">
                <table class="table-auto w-full mx-5">
                    <tbody>
                        <tr>
                            <td class="h-auto border border-black" colspan="2">
                                <!-- First row, first container -->
                                <div class="flex justify-between items-center">
                                    <div class="flex w-3/4 text-left ml-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="w-max-1/3 flex items-center text-[10px] mb-[5px]">NOMBRE O
                                                RAZON SOCIAL</label>
                                            <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                style="width:405px;">
                                                <p class="ml-1 mb-[5px]">{{ receipt_to_print.cliente_recibo_ingreso }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 text-left mr-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[10px] mb-[5px]">POR</label>
                                            <div
                                                class="ml-1 text-center w-full border-b border-gray-700 text-[10px] font-bold">
                                                <p class="ml-1 mb-[5px]">{{ formatedAmount }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- First row, second container -->
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex w-full text-left ml-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="w-max-1/3 flex items-center text-[10px] mb-[5px]">TOTAL EN
                                                LETRAS</label>
                                            <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                style="width:633px;">
                                                <p class="ml-1 mb-[5px]">{{ receipt_to_print.monto_letras }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="h-auto">
                            <td class="text-center w-1/2 border border-black border-t-0 border-r-0">
                                <h2 class="text-[10px] mb-1">CARGO EN CAJA</h2>
                            </td>
                            <td class="text-center w-1/2 border border-black border-t-0">
                                <h2 class="text-[10px] mb-1">CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                            </td>
                        </tr>
                        <tr class="h-auto">
                            <td class="justify-center items-start border-black border-l border-b">
                                <div class="h-64 flex flex-col">
                                    <div class="flex justify-center items-start mb-2 h-3/4">
                                        <div class="flex w-full text-left mx-4 mt-0">
                                            <div class="font-bold flex items-center text-[11px]">
                                                {{ receipt_to_print.descripcion_recibo_ingreso }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start mb-2 h-1/4">
                                        <div class="w-full flex flex-col h-auto">
                                            <div
                                                class="mb-1 flex w-max-full justify-center mx-4 h-1/2 border-t border-black border-solid">
                                                <p class="w-max-full flex items-center text-[11px]">Tesorero,
                                                    Pagador o Colector</p>
                                            </div>
                                            <div class="w-max-full flex justify-center mx-4 h-1/2">
                                                <div class="ml-1 text-center w-full text-[10px] font-bold">
                                                    <p class="ml-1 mb-[5px]">{{ empleado }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="border border-black border-t-0">
                                <div class="h-64 flex flex-col">
                                    <div class="flex justify-center items-start mb-1 h-[10%]">
                                        <div class="font-bold flex w-full text-left text-[10px] mx-4 mt-0">
                                            {{ receipt_to_print.cuenta_presupuestal.codigo_ccta_presupuestal }} {{ nombre_cuenta }}
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start mb-1 h-[90%]">
                                        <div class="w-full flex flex-col h-1/8">
                                            <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                class="relative flex w-full flex-row center-vertically mt-1">
                                                <div class="font-bold flex items-center text-[10px] w-2/3 mx-4">
                                                    <label class="">
                                                        {{ detail.concepto_ingreso.dependencia ?
                                                            detail.concepto_ingreso.dependencia.codigo_dependencia + ' - ' : ''
                                                        }}
                                                        {{
                                                            detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                    </label>
                                                </div>
                                                <div class="w-1/3 relative mr-1">
                                                    <span
                                                        class=" absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                    <div class="text-right w-full text-[10px] font-bold pl-8 py-0">
                                                        <p class="">{{ detail.monto_det_recibo_ingreso }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-center items-start mb-1 h-1/4 mt-2">
                                                <div class="relative flex w-full flex-row">
                                                    <label for=""
                                                        class="font-bold flex items-center text-[10px] w-2/3 mx-4 mt-0">
                                                        TOTAL:
                                                    </label>
                                                    <div class="w-1/3 relative mr-1">
                                                        <span
                                                            class="absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                        <div
                                                            class="border-t border-gray-700 text-right w-full text-[10px] font-bold pl-8 py-0">
                                                            <p class="">{{ receipt_to_print.monto_recibo_ingreso }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="border-t-2 border-dashed border-black">
            <!-- Contenido para la sección superior aquí -->
            <div class="flex justify-between items-center mt-2">
                <!-- Encabezado izquierdo  -->
                <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                    <img src="../../../img/escudo-nacional.png" alt="Escudo El Salvador"
                        class="w-[50px] h-[50px] object-contain mb-[5px]">
                    <div class="text-center text-[12px] font-bold mx-5">REPUBLICA DE EL SALVADOR</div>
                    <div class="text-center text-[12px] font-bold mx-5">MINISTERIO DE SALUD</div>
                </div>
                <!-- Encabezado centrado  -->
                <div class="title-container flex flex-col justify-center items-center">
                    <h1 class=" text-[13px] font-bold">RECIBO DE INGRESO</h1>
                    <p class=" text-[12px] font-bold mt-[20px]">COPIA - CONTABILIDAD</p>
                    <div class=" relative flex flex-row justify-center mt-[25px]">
                        <label for="" class="flex items-center text-[10px] ">NUMERO</label>
                        <div class="ml-1 w-[150px] border-b border-gray-700 text-[11px]">
                            <p class="mb-[5px] font-bold">{{ receipt_to_print.numero_recibo_ingreso }}</p>
                        </div>
                    </div>
                </div>
                <!-- Encabezado derecho -->
                <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                    <img src="../../../img/isri-logo2.png" alt="Logo ISRI"
                        class="w-[50px] h-[50px] object-contain mb-[5px]">
                    <div class="text-center text-[12px] font-bold mx-5" style="text-align: center;">INSTITUTO
                        SALVADOREÑO DE
                        REHABILITACION INTEGRAL</div>
                </div>
            </div>
            <!-- Contenedor 3 -->
            <div class="flex justify-between items-center mt-2 mb-2">
                <div class="flex text-left w-2/3 ml-5">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[10px]">UNIDAD FINANCIERA INSTITUCIONAL
                            (UFI-ISRI)</label>
                    </div>
                </div>
                <div class="flex w-1/4 text-left mr-5">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[10px]">FECHA</label>
                        <div class="ml-1 text-center w-full border-b border-gray-700 text-[11px] font-bold">
                            <p class="mb-[5px]">{{ fecha_recibo }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 mb-5 w-[100%]">
                <table class="table-auto w-full mx-5">
                    <tbody>
                        <tr>
                            <td class="h-auto border border-black" colspan="2">
                                <!-- First row, first container -->
                                <div class="flex justify-between items-center">
                                    <div class="flex w-3/4 text-left ml-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="w-max-1/3 flex items-center text-[10px] mb-[5px]">NOMBRE O
                                                RAZON SOCIAL</label>
                                            <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                style="width:405px;">
                                                <p class="ml-1 mb-[5px]">{{ receipt_to_print.cliente_recibo_ingreso }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 text-left mr-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[10px] mb-[5px]">POR</label>
                                            <div
                                                class="ml-1 text-center w-full border-b border-gray-700 text-[10px] font-bold">
                                                <p class="ml-1 mb-[5px]">{{ formatedAmount }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- First row, second container -->
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex w-full text-left ml-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="w-max-1/3 flex items-center text-[10px] mb-[5px]">TOTAL EN
                                                LETRAS</label>
                                            <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                style="width:633px;">
                                                <p class="ml-1 mb-[5px]">{{ receipt_to_print.monto_letras }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="h-auto">
                            <td class="text-center w-1/2 border border-black border-t-0 border-r-0">
                                <h2 class="text-[10px] mb-1">CARGO EN CAJA</h2>
                            </td>
                            <td class="text-center w-1/2 border border-black border-t-0">
                                <h2 class="text-[10px] mb-1">CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                            </td>
                        </tr>
                        <tr class="h-auto">
                            <td class="justify-center items-start border-black border-l border-b">
                                <div class="h-64 flex flex-col">
                                    <div class="flex justify-center items-start mb-2 h-3/4">
                                        <div class="flex w-full text-left mx-4 mt-0">
                                            <div class="font-bold flex items-center text-[11px]">
                                                {{ receipt_to_print.descripcion_recibo_ingreso }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start mb-2 h-1/4">
                                        <div class="w-full flex flex-col h-auto">
                                            <div
                                                class="mb-1 flex w-max-full justify-center mx-4 h-1/2 border-t border-black border-solid">
                                                <p class="w-max-full flex items-center text-[11px]">Tesorero,
                                                    Pagador o Colector</p>
                                            </div>
                                            <div class="w-max-full flex justify-center mx-4 h-1/2">
                                                <div class="ml-1 text-center w-full text-[10px] font-bold">
                                                    <p class="ml-1 mb-[5px]">{{ empleado }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="border border-black border-t-0">
                                <div class="h-64 flex flex-col">
                                    <div class="flex justify-center items-start mb-1 h-[10%]">
                                        <div class="font-bold flex w-full text-left text-[10px] mx-4 mt-0">
                                            {{ receipt_to_print.cuenta_presupuestal.codigo_ccta_presupuestal }} {{ nombre_cuenta }}
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start mb-1 h-[90%]">
                                        <div class="w-full flex flex-col h-auto">
                                            <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                class="relative flex w-full flex-row center-vertically mt-1">
                                                <div class="font-bold flex items-center text-[10px] w-2/3 mx-4">
                                                    <label class="">
                                                        {{ detail.concepto_ingreso.dependencia ?
                                                            detail.concepto_ingreso.dependencia.codigo_dependencia + ' - ' : ''
                                                        }}
                                                        {{
                                                            detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                    </label>
                                                </div>
                                                <div class="w-1/3 relative mr-1">
                                                    <span
                                                        class=" absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                    <div class="text-right w-full text-[10px] font-bold pl-8 py-0">
                                                        <p class="">{{ detail.monto_det_recibo_ingreso }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-center items-start mb-1 h-1/4 mt-2">
                                                <div class="relative flex w-full flex-row">
                                                    <label for=""
                                                        class="font-bold flex items-center text-[10px] w-2/3 mx-4 mt-0">
                                                        TOTAL:
                                                    </label>
                                                    <div class="w-1/3 relative mr-1">
                                                        <span
                                                            class="absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                        <div
                                                            class="border-t border-gray-700 text-right w-full text-[10px] font-bold pl-8 py-0">
                                                            <p class="">{{ receipt_to_print.monto_recibo_ingreso }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="pagebreak">
            <div class="">
                <!-- Contenido para la sección superior aquí -->
                <div class="flex justify-between items-center mt-0">
                    <!-- Encabezado izquierdo  -->
                    <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                        <img src="../../../img/escudo-nacional.png" alt="Escudo El Salvador"
                            class="w-[50px] h-[50px] object-contain mb-[5px]">
                        <div class="text-center text-[12px] font-bold mx-5">REPUBLICA DE EL SALVADOR</div>
                        <div class="text-center text-[12px] font-bold mx-5">MINISTERIO DE SALUD</div>
                    </div>
                    <!-- Encabezado centrado  -->
                    <div class="title-container flex flex-col justify-center items-center">
                        <h1 class=" text-[13px] font-bold">RECIBO DE INGRESO</h1>
                        <p class=" text-[12px] font-bold mt-[20px]">COPIA - TESORERIA</p>
                        <div class=" relative flex flex-row justify-center mt-[25px]">
                            <label for="" class="flex items-center text-[10px] ">NUMERO</label>
                            <div class="ml-1 w-[150px] border-b border-gray-700 text-[11px]">
                                <p class="mb-[5px] font-bold">{{ receipt_to_print.numero_recibo_ingreso }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Encabezado derecho -->
                    <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                        <img src="../../../img/isri-logo2.png" alt="Logo ISRI"
                            class="w-[50px] h-[50px] object-contain mb-[5px]">
                        <div class="text-center text-[12px] font-bold mx-5" style="text-align: center;">INSTITUTO
                            SALVADOREÑO DE
                            REHABILITACION INTEGRAL</div>
                    </div>
                </div>
                <!-- Contenedor 3 -->
                <div class="flex justify-between items-center mt-2 mb-2">
                    <div class="flex text-left w-2/3 ml-5">
                        <div class="relative flex w-full flex-row">
                            <label for="" class="flex items-center text-[10px]">UNIDAD FINANCIERA INSTITUCIONAL
                                (UFI-ISRI)</label>
                        </div>
                    </div>
                    <div class="flex w-1/4 text-left mr-5">
                        <div class="relative flex w-full flex-row">
                            <label for="" class="flex items-center text-[10px]">FECHA</label>
                            <div class="ml-1 text-center w-full border-b border-gray-700 text-[11px] font-bold">
                                <p class="mb-[5px]">{{ fecha_recibo }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-2 mb-5 w-[100%]">
                    <table class="table-auto w-full mx-5">
                        <tbody>
                            <tr>
                                <td class="h-auto border border-black" colspan="2">
                                    <!-- First row, first container -->
                                    <div class="flex justify-between items-center">
                                        <div class="flex w-3/4 text-left ml-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for=""
                                                    class="w-max-1/3 flex items-center text-[10px] mb-[5px]">NOMBRE O
                                                    RAZON SOCIAL</label>
                                                <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                    style="width:405px;">
                                                    <p class="ml-1 mb-[5px]">{{ receipt_to_print.cliente_recibo_ingreso }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex w-1/4 text-left mr-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for="" class="flex items-center text-[10px] mb-[5px]">POR</label>
                                                <div
                                                    class="ml-1 text-center w-full border-b border-gray-700 text-[10px] font-bold">
                                                    <p class="ml-1 mb-[5px]">{{ formatedAmount }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- First row, second container -->
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex w-full text-left ml-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for="" class="w-max-1/3 flex items-center text-[10px] mb-[5px]">TOTAL
                                                    EN
                                                    LETRAS</label>
                                                <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                    style="width:633px;">
                                                    <p class="ml-1 mb-[5px]">{{ receipt_to_print.monto_letras }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="h-auto">
                                <td class="text-center w-1/2 border border-black border-t-0 border-r-0">
                                    <h2 class="text-[10px] mb-1">CARGO EN CAJA</h2>
                                </td>
                                <td class="text-center w-1/2 border border-black border-t-0">
                                    <h2 class="text-[10px] mb-1">CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                                </td>
                            </tr>
                            <tr class="h-auto">
                                <td class="justify-center items-start border-black border-l border-b">
                                    <div class="h-64 flex flex-col">
                                        <div class="flex justify-center items-start mb-2 h-3/4">
                                            <div class="flex w-full text-left mx-4 mt-0">
                                                <div class="font-bold flex items-center text-[11px]">
                                                    {{ receipt_to_print.descripcion_recibo_ingreso }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-start mb-2 h-1/4">
                                            <div class="w-full flex flex-col h-auto">
                                                <div
                                                    class="mb-1 flex w-max-full justify-center mx-4 h-1/2 border-t border-black border-solid">
                                                    <p class="w-max-full flex items-center text-[11px]">Tesorero,
                                                        Pagador o Colector</p>
                                                </div>
                                                <div class="w-max-full flex justify-center mx-4 h-1/2">
                                                    <div class="ml-1 text-center w-full text-[10px] font-bold">
                                                        <p class="ml-1 mb-[5px]">{{ empleado }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-black border-t-0">
                                    <div class="h-64 flex flex-col">
                                        <div class="flex justify-center items-start mb-1 h-[10%]">
                                            <div class="font-bold flex w-full text-left text-[10px] mx-4 mt-0">
                                                {{ receipt_to_print.cuenta_presupuestal.codigo_ccta_presupuestal }} {{ nombre_cuenta }}
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-start mb-1 h-[90%]">
                                            <div class="w-full flex flex-col h-auto">
                                                <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                    class="relative flex w-full flex-row center-vertically mt-1">
                                                    <div class="font-bold flex items-center text-[10px] w-2/3 mx-4">
                                                        <label class="">
                                                            {{ detail.concepto_ingreso.dependencia ?
                                                                detail.concepto_ingreso.dependencia.codigo_dependencia + ' - ' :
                                                                ''
                                                            }}
                                                            {{
                                                                detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                        </label>
                                                    </div>
                                                    <div class="w-1/3 relative mr-1">
                                                        <span
                                                            class=" absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                        <div class="text-right w-full text-[10px] font-bold pl-8 py-0">
                                                            <p class="">{{ detail.monto_det_recibo_ingreso }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-center items-start mb-1 h-1/4 mt-2">
                                                    <div class="relative flex w-full flex-row">
                                                        <label for=""
                                                            class="font-bold flex items-center text-[10px] w-2/3 mx-4 mt-0">
                                                            TOTAL:
                                                        </label>
                                                        <div class="w-1/3 relative mr-1">
                                                            <span
                                                                class="absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                            <div
                                                                class="border-t border-gray-700 text-right w-full text-[10px] font-bold pl-8 py-0">
                                                                <p class="">{{ receipt_to_print.monto_recibo_ingreso }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="border-t-2 border-dashed border-black">
                <!-- Contenido para la sección superior aquí -->
                <div class="flex justify-between items-center mt-2">
                    <!-- Encabezado izquierdo  -->
                    <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                        <img src="../../../img/escudo-nacional.png" alt="Escudo El Salvador"
                            class="w-[50px] h-[50px] object-contain mb-[5px]">
                        <div class="text-center text-[12px] font-bold mx-5">REPUBLICA DE EL SALVADOR</div>
                        <div class="text-center text-[12px] font-bold mx-5">MINISTERIO DE SALUD</div>
                    </div>
                    <!-- Encabezado centrado  -->
                    <div class="title-container flex flex-col justify-center items-center">
                        <h1 class=" text-[13px] font-bold">RECIBO DE INGRESO</h1>
                        <p class=" text-[12px] font-bold mt-[20px]">COPIA - CENTRO DE ATENCION</p>
                        <div class=" relative flex flex-row justify-center mt-[25px]">
                            <label for="" class="flex items-center text-[10px] ">NUMERO</label>
                            <div class="ml-1 w-[150px] border-b border-gray-700 text-[11px]">
                                <p class="mb-[5px] font-bold">{{ receipt_to_print.numero_recibo_ingreso }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Encabezado derecho -->
                    <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                        <img src="../../../img/isri-logo2.png" alt="Logo ISRI"
                            class="w-[50px] h-[50px] object-contain mb-[5px]">
                        <div class="text-center text-[12px] font-bold mx-5" style="text-align: center;">INSTITUTO
                            SALVADOREÑO DE
                            REHABILITACION INTEGRAL</div>
                    </div>
                </div>
                <!-- Contenedor 3 -->
                <div class="flex justify-between items-center mt-2 mb-2">
                    <div class="flex text-left w-2/3 ml-5">
                        <div class="relative flex w-full flex-row">
                            <label for="" class="flex items-center text-[10px]">UNIDAD FINANCIERA INSTITUCIONAL
                                (UFI-ISRI)</label>
                        </div>
                    </div>
                    <div class="flex w-1/4 text-left mr-5">
                        <div class="relative flex w-full flex-row">
                            <label for="" class="flex items-center text-[10px]">FECHA</label>
                            <div class="ml-1 text-center w-full border-b border-gray-700 text-[11px] font-bold">
                                <p class="mb-[5px]">{{ fecha_recibo }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-2 mb-5 w-[100%]">
                    <table class="table-auto w-full mx-5">
                        <tbody>
                            <tr>
                                <td class="h-auto border border-black" colspan="2">
                                    <!-- First row, first container -->
                                    <div class="flex justify-between items-center">
                                        <div class="flex w-3/4 text-left ml-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for=""
                                                    class="w-max-1/3 flex items-center text-[10px] mb-[5px]">NOMBRE O
                                                    RAZON SOCIAL</label>
                                                <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                    style="width:405px;">
                                                    <p class="ml-1 mb-[5px]">{{ receipt_to_print.cliente_recibo_ingreso }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex w-1/4 text-left mr-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for="" class="flex items-center text-[10px] mb-[5px]">POR</label>
                                                <div
                                                    class="ml-1 text-center w-full border-b border-gray-700 text-[10px] font-bold">
                                                    <p class="ml-1 mb-[5px]">{{ formatedAmount }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- First row, second container -->
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex w-full text-left ml-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for="" class="w-max-1/3 flex items-center text-[10px] mb-[5px]">TOTAL
                                                    EN
                                                    LETRAS</label>
                                                <div class="ml-1 text-left w-full border-b border-gray-700 text-[10px] font-bold"
                                                    style="width:633px;">
                                                    <p class="ml-1 mb-[5px]">{{ receipt_to_print.monto_letras }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="h-auto">
                                <td class="text-center w-1/2 border border-black border-t-0 border-r-0">
                                    <h2 class="text-[10px] mb-1">CARGO EN CAJA</h2>
                                </td>
                                <td class="text-center w-1/2 border border-black border-t-0">
                                    <h2 class="text-[10px] mb-1">CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                                </td>
                            </tr>
                            <tr class="h-auto">
                                <td class="justify-center items-start border-black border-l border-b">
                                    <div class="h-64 flex flex-col">
                                        <div class="flex justify-center items-start mb-2 h-3/4">
                                            <div class="flex w-full text-left mx-4 mt-0">
                                                <div class="font-bold flex items-center text-[11px]">
                                                    {{ receipt_to_print.descripcion_recibo_ingreso }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-start mb-2 h-1/4">
                                            <div class="w-full flex flex-col h-auto">
                                                <div
                                                    class="mb-1 flex w-max-full justify-center mx-4 h-1/2 border-t border-black border-solid">
                                                    <p class="w-max-full flex items-center text-[11px]">Tesorero,
                                                        Pagador o Colector</p>
                                                </div>
                                                <div class="w-max-full flex justify-center mx-4 h-1/2">
                                                    <div class="ml-1 text-center w-full text-[10px] font-bold">
                                                        <p class="ml-1 mb-[5px]">{{ empleado }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-black border-t-0">
                                    <div class="h-64 flex flex-col">
                                        <div class="flex justify-center items-start mb-1 h-[10%]">
                                            <div class="font-bold flex w-full text-left text-[10px] mx-4 mt-0">
                                                {{ receipt_to_print.cuenta_presupuestal.codigo_ccta_presupuestal }} {{ nombre_cuenta }}
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-start mb-1 h-[90%]">
                                            <div class="w-full flex flex-col h-auto">
                                                <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                    class="relative flex w-full flex-row center-vertically mt-1">
                                                    <div class="font-bold flex items-center text-[10px] w-2/3 mx-4">
                                                        <label class="">
                                                            {{ detail.concepto_ingreso.dependencia ?
                                                                detail.concepto_ingreso.dependencia.codigo_dependencia + ' - ' :
                                                                ''
                                                            }}
                                                            {{
                                                                detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                        </label>
                                                    </div>
                                                    <div class="w-1/3 relative mr-1">
                                                        <span
                                                            class=" absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                        <div class="text-right w-full text-[10px] font-bold pl-8 py-0">
                                                            <p class="">{{ detail.monto_det_recibo_ingreso }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-center items-start mb-1 h-1/4 mt-2">
                                                    <div class="relative flex w-full flex-row">
                                                        <label for=""
                                                            class="font-bold flex items-center text-[10px] w-2/3 mx-4 mt-0">
                                                            TOTAL:
                                                        </label>
                                                        <div class="w-1/3 relative mr-1">
                                                            <span
                                                                class="absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                            <div
                                                                class="border-t border-gray-700 text-right w-full text-[10px] font-bold pl-8 py-0">
                                                                <p class="">{{ receipt_to_print.monto_recibo_ingreso }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        receipt_to_print: {
            type: Array,
            default: [],
        },
        formatedAmount: {
            type: String,
            default: '',
        },
        empleado: {
            type: String,
            default: '',
        },
        nombre_cuenta: {
            type: String,
            default: '',
        },
        fecha_recibo: {
            type: String,
            default: '',
        },
    }
    ,
    computed: {
    }
}
</script>
<style>
#pagebreak {
    page-break-before: always;
}

.center-vertically {
    align-items: center;
}

/* .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
} */

.left {
    flex: 0 0 50%;
    text-align: left;
    font-size: 16px;
    font-weight: bold;
    margin-left: 20px;
}

.right {
    flex: 0 0 50%;
    text-align: left;
    font-size: 16px;
    font-weight: bold;
    margin-right: 20px;
}

.title-container {
    flex: 1;
    text-align: center;
    align-self: flex-start;
}

.logo-container {
    flex: 0 0 30%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.logo {
    width: 50px;
    height: 50px;
    object-fit: contain;
    margin-bottom: 10px;
}

.institution {
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-left: 20px;
    margin-right: 20px;
}</style>