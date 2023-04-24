<template>
  <div class="">
    <div class="text-center">

      <div class="border-2 border-black mx-8 mb-7" id="main-container" ref="contenedorPdfQuedan">
        <div class=" md:flex flex-row justify-between">
          <div class="mb-2 md:mr-2 md:mb-0 basis-1/4 pt-1 px-8"></div>
          <div class="mb-2 md:mx-10 md:mb-0 basis-1/2 pt-7 ">
            <div class="border-2 border-black ">
              <h3 style class="text-[18px] mb-5 font-semibold text-gray-600">
                Documento de seguimiento de pagos
              </h3>
            </div>
          </div>
          <div class="mb-2 md:mr-2 md:mb-0 basis-1/4 pt-1 px-8 "></div>
        </div>

        <div class="mx-20 ">
          <div class="mb-7 md:flex flex-row justify-items-center">
            <div class=" md:mr-2 md:mb-0 w-100">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px]">Giro del proveedor:</label>
                <div style="width: 385px;" class="border-b-2 border-black placeholder-slate-400 text-[12px] py-2 ">
                  {{ dataQuedan.proveedor.giro.id_giro }}-{{ dataQuedan.proveedor.giro.nombre_giro }}</div>
              </div>
            </div>
            <div class=" md:mr-2 md:mb-0 w-25">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px]">Porcentaje de IVA:</label>
                <div style="width: 40px;" class="border-b-2 border-black placeholder-slate-400 py-2 text-[12px]">
                  {{ (dataQuedan.proveedor.sujeto_retencion.iva_sujeto_retencion * 100) + " %" }}</div>
              </div>
            </div>
            <div class=" md:mr-2 md:mb-0 w-25">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px] text-sm">Porcentaje de
                  ISR:</label>
                <div style="width: 40px;" class="border-b-2 border-black placeholder-slate-400  py-2 text-[12px]">
                  {{ (dataQuedan.proveedor.sujeto_retencion.isrl_sujeto_retencion * 100) + " %" }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="ml-36 mb-3">
          <div class="mb-7 md:flex flex-row justify-items-center">
            <div class="mb-4 md:mr-2 md:mb-0 w-100">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px]">Quedan:</label>
                <div style="width: 50px;" class="border-b-2 border-black placeholder-slate-400 text-[12px] py-2 ">
                  {{ dataQuedan.id_quedan }}</div>
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 w-25">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px]">Fecha emision</label>
                <div style="width: 100px;" class="border-b-2 border-black placeholder-slate-400 py-2 text-[12px]">
                  {{ dataQuedan.fecha_emision_quedan }}</div>
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 w-25">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px] text-sm">Cheque:</label>
                <div style="width: 50px;" class="border-b-2 border-black placeholder-slate-400  py-2 text-[12px]">
                  {{ dataQuedan.monto_liquido_quedan }}</div>
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 w-25">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px] text-sm">Renta:</label>
                <div style="width: 50px;" class="border-b-2 border-black placeholder-slate-400  py-2 text-[12px]">
                  {{ dataQuedan.monto_isr_quedan }}</div>
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 w-25">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px] text-sm">IVA:</label>
                <div style="width: 50px;" class="border-b-2 border-black placeholder-slate-400  py-2 text-[12px]">
                  {{ dataQuedan.monto_iva_quedan }}</div>
              </div>
            </div>
            <div class="mb-4 md:mr-2 md:mb-0 w-25">
              <div class="relative flex w-full flex-row">
                <label for="" class="flex items-center text-[14px] text-sm">Total:</label>
                <div style="width: 50px;" class="border-b-2 border-black placeholder-slate-400  py-2 text-[12px]">
                  {{ totalCheque }}</div>
              </div>
            </div>

          </div>
        </div>


        <div class="max-w-7xl mx-auto pb-20 sm:px-6 lg:px-8">
          <table class="table-auto mx-auto">
            <thead>
              <tr>
                <th class="border-2 border-black text-gray-600 py-2">
                  <div class="text-sm" style="margin-top: -12px;">PROVEEDOR</div>
                </th>
                <th class="border-2 border-black text-gray-600 py-2" colspan="8">
                  <div class="text-sm" style="margin-top: -12px;">DATOS DEL QUEDAN</div>
                </th>
              </tr>
              <tr>
                <td class="border-2 border-black">
                  <div class="text-[12px] mx-auto py-2" style="word-break: break-word; width: 160px; margin-top: -12px;">
                    {{ dataQuedan.proveedor.razon_social_proveedor }}
                  </div>
                </td>
                <th class="border-2 border-black text-gray-600" colspan="3">
                  <div class="text-sm" style="margin-top: -12px;"> NUMERO DE ACUERDO</div>
                </th>
                <th class="border-2 border-black text-gray-600" colspan="8">
                  <div class="text-sm" style="margin-top: -12px;"> NUMERO DE COMPROMISO </div>
                </th>
              </tr>
              <tr>
                <th class="border-2 border-black text-gray-600 py-2">
                  <div class="text-sm" style="margin-top: -12px;"> ACUERDO CONTRATACION</div>
                </th>
                <td class="border-2 border-black" colspan="3">
                  <div class="text-[12px] mx-auto py-2" style="word-break: break-word; width: 160px; margin-top: -12px;">
                    {{ dataQuedan.numero_acuerdo_quedan }}
                  </div>
                </td>
                <td class="border-2 border-black" colspan="8">
                  <div class="text-[12px] mx-auto py-2" style="word-break: break-word; width: 160px; margin-top: -12px;">
                    {{ dataQuedan.numero_compromiso_ppto_quedan }}
                  </div>
                </td>
              </tr>

              <tr>
                <td class="border-2 border-black" colspan="1">
                  <div class="text-[12px] mx-auto py-2" style="word-break: break-word; width: 160px; margin-top: -12px;">
                    {{ dataQuedan.acuerdo_compra.nombre_acuerdo_compra }}
                  </div>
                </td>
                <th class="border-2 border-black text-gray-600" colspan="8">
                  <div class="text-sm" style="margin-top: -12px;"> DETALLE QUEDAN</div>
                </th>
              </tr>
              <tr>
                <th class="border-2 border-black  text-gray-600 ">
                  <div class="text-sm py-2" style="margin-top: -12px;"> FACTURA</div>
                </th>
                <th class="border-2 border-black px-10 text-gray-600" colspan="2">
                  <div class="text-sm" style="margin-top: -12px;"> DEPENDENCIA</div>
                </th>
                <th class="border-2 border-black  px-8 text-gray-600">
                  <div class="text-sm" style="margin-top: -12px;"> NUMERO ACTA</div>
                </th>
                <th class="border-2 border-black w-64  px-10 text-gray-600" colspan="2">
                  <div class="text-sm" style="margin-top: -12px;"> CONCEPTO</div>
                </th>
                <th class="border-2 border-black w-40  px-10 text-gray-600">
                  <div class="text-sm" style="margin-top: -12px;"> MONTO</div>
                </th>
              </tr>
            </thead>
            <tbody class="text-sm" id="content">


              <tr v-for="(row, rowIndex) in dataQuedan.detalle_quedan" :key="rowIndex">


                  <td class="border-2 border-black">
                    {{ row.numero_factura_det_quedan }}
                  </td>

                  <td class="border-2 border-black" colspan="2">

                  </td>
                  <td class="border-2 border-black">{{ cell }}</td>

                  <td colspan="2">
                    {{ cell }}
                  </td>

                  <td class="border-2 border-black">
                    <table>
                      <tr>
                        <th
                          class="border-2 border-r-black border-b-black border-l-transparent border-t-transparent text-sm text-gray-600 py-2"
                          style="writing-mode: vertical-rl; transform: rotate(180deg);">
                          PRODUCTO
                        </th>
                        <td class="w-full border-2 border-b-black border-x-transparent border-t-transparent">
                          {{ cell.producto }}
                        </td>
                      </tr>
                      <tr>
                        <th
                          class="border-2 border-r-black border-t-black border-l-transparent border-b-transparent text-gray-600 py-2"
                          style="writing-mode: vertical-rl; transform: rotate(180deg);">
                          SERVICIO
                        </th>
                        <td>
                          {{ cell.servicio }}
                        </td>
                      </tr>
                    </table>

                  </td>


              </tr>

            </tbody>
            <!--             <tbody>
              <tr id="esconder" class="border-none">
                <td class="py-3 border-none"></td>
              </tr>
              <tr>
                <td class="border-2 border-black " colspan="1" rowspan="2">
                  Descripción
                </td>
                <td class="border-2 border-black" colspan="4" rowspan="2">
                  {{ dataQuedan.descripcion_quedan }}

                </td>
                <th class="border-2 border-black text-sm text-gray-600">
                  PROYECTO FINANCIADO
                </th>
                <th class="border-2 border-black py-2 text-sm text-gray-600">
                  PRIORIDAD DE PAGO
                </th>
              </tr>
              <tr>

                <td class="border-2 border-black " colspan="1">
                  <div class="relative flex h-8 w-full flex-row-reverse ">
                    {{ dataQuedan.id_prioridad_pago }} </div>
                </td>
                <td class="border-2 border-black " colspan="1">
                  <div class="relative flex h-8 w-full flex-row-reverse ">
                    {{ dataQuedan.id_proy_financiado }}
                  </div>
                </td>
              </tr>
            </tbody> -->
          </table>


          <div class="row footer mt-4 mb-10">
            <div class="text-center border-2 border-solid border-gray-400 w-80 text-[15px] py-1">
              {{ dataQuedan.nombre_empleado_tesoreria }}
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
    dataQuedan: {//prop has total information from "quedan"
      type: Object,
      required: true,
    },
    totalCheque: {//prop has total information from "quedan"
      type: String,
      required: true,
    },
  }
}
</script>

<style>
/* Estilos del componente */
</style>
