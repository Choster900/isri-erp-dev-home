<template>
    <div class="my-7 mx-4">
        <table border="0" cellpadding="0" cellspacing="0" class="border-2 border-black sheet0 ">
            <col class="col0" style="width: 5cm;">
            <col class="col1"   style="width: 37mm;">
            <col class="col2">
            <col class="col3">
            <col class="col4">
            <col class="col5">
            <col class="col6">
            <col class="col7">
            <tbody>
                <tr>
                    <td class="border border-black py-2 px-1 sin-borders" rowspan="2">
                        <img src="../../../img/MINISTERIO_DE_HACIENDA.png" alt="" class="h-[70px] w-96">
                    </td>
                    <td class="border border-black sin-borders" colspan="7">
                        <div class="relative text-center text-sm"
                            style="/* top: 7px; */ margin-top: 4px; font-family: 'Alumni Sans', sans-serif;">
                            C E R T I F I C A D O &nbsp; D E &nbsp; S E G U R O &nbsp; C O L E C T I V O &nbsp; D E
                            &nbsp; V I D A
                        </div>
                    </td>
                </tr>
                <tr>
                     <td class="border border-black text-xs px-4 sin-borders" colspan="7">
                        <div class="relative text-center" style="top: -10px;">
                            Reservado Ministerio de Hacienda: &nbsp;
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-[7pt] font-semibold pl-2 py-1.5 sin-borders" colspan="8">
                        <div style="margin-top: -12px;"> I&#41; DATOS GENERALES</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black text-[7pt] pl-2 py-1.5" colspan="6">
                        <div class="relative pb-[10px]" style="top: -6px;">
                            1&#41; Nombre del asegurado según DUI
                        </div>
                    </td>
                    <td class="border border-black text-[7pt] pl-2 py-1.5" colspan="2">
                        <div class="relative pb-[10px]" style="top: -6px;">
                            2&#41; Número del DUI
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="border border-black text-[7pt] pl-2 py-1.5" colspan="2">
                        <div class="relative pb-[10px] w-[]" style="top: -6px;" >
                            3&#41; Lugar y fecha de Nacimiento:
                        </div>

                    </td>
                    <td class="border border-black text-[7pt] pl-2 py-1.5" colspan="2">
                        <div class="relative pb-[10px]" style="top: -6px;">
                            4&#41; Cargo:
                        </div>
                    </td>
                    <td class="border border-black text-[7pt] pl-2 py-1.5" colspan="4">
                        <div class="relative pb-[10px]" style="top: -6px;">
                            5&#41; Dirección del Asegurado:
                        </div>
                    </td>
                </tr>
                <tr class="row5">
                    <td class="border border-black text-[7pt] pl-2 py-1.5" colspan="4">
                        <div class="relative pb-[15px]" style="top: -6px;">
                            6&#41; Unidad Primaria en que labora
                        </div>
                    </td>
                    <td class="border border-black text-[7pt] pl-2 py-1.5" colspan="4">
                        <div class="relative pb-[15px]" style="top: -6px;">
                            7&#41; Unidad Secundaria
                        </div>
                    </td>

                </tr>
                <tr>
                    <td class="text-[7pt] font-semibold pl-2 " colspan="8">
                        <div style="margin-top: -5px;" class="pb-1">II&#41; BENEFICIARIOS</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black text-[7pt] pl-2" colspan="5" style="letter-spacing: 0.1em;">
                        <div style="margin-top: -5px;" class="pb-1">NOMBRE Y APELLIDO</div>
                    </td>
                    <td class="border border-black text-[7pt] text-center" colspan="2" style="letter-spacing: 0.2em;">
                        <div style="margin-top: -5px;" class="pb-1">PARENTESCO</div>
                    </td>
                    <td class="border border-black text-[7pt] text-center">
                        <div style="margin-top: -5px;" class="pb-1">%</div>

                    </td>

                </tr>
                <tr v-for="i in 6" :key="i">
                    <td class="border border-black text-[7pt] pl-2" colspan="5">
                        <div style="margin-top: -5px;" class="pb-2"> {{ i }}</div>
                    </td>
                    <td class="border border-black text-[7pt]" colspan="2"></td>
                    <td class="border border-black text-[7pt]"></td>
                </tr>
                <tr class="row9">
                    <td class="text-[7pt] font-semibold pl-2 " colspan="8">
                        <div style="margin-top: -5px;" class="pb-1">III&#41; AUTORIZACIÓN</div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black text-[7pt] pl-2">
                        <div style="margin-top: -5px;" class="pb-1"> 1&#41; Firma del Asegurado</div>

                    </td>
                    <td class="border border-black text-[7pt] pl-2" colspan="2">

                        <div style="margin-top: -5px;" class="pb-1"> 2 &#41; Lugar y Fecha</div>

                    </td>
                    <td class="border border-black text-[7pt] pl-2" colspan="3">
                        <div style="margin-top: -5px;" class="pb-1"> 3&#41; Sello de la Unidad
                        </div>

                    </td>
                    <td class="border border-black text-[7pt] pl-2" colspan="2">
                        <div style="margin-top: -5px;" class="pb-1"> 4&#41; Firma de Autorizado
                        </div>

                    </td>
                </tr>
                <tr>
                    <td class="py-5 border border-black"> </td>
                    <td class="py-5 border border-black" colspan="2"> </td>
                    <td class="py-5 border border-black" colspan="3"> </td>
                    <td class="py-5 border border-black" colspan="2"> </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {

}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Alumni+Sans:wght@800&display=swap');

table.sheet0 col.col0 {
    width: 126.37777658pt
}

table.sheet0 col.col1 {
    width: 66.06666592pt
}

table.sheet0 col.col2 {
    width: 97.59999958pt
}

table.sheet0 col.col3 {
    width: 27pt
}

table.sheet0 col.col4 {
    width: 44.13333254pt
}

table.sheet0 col.col5 {
    width: 44.09999923pt
}

table.sheet0 col.col6 {
    width: 95.19999916pt
}

table.sheet0 col.col7 {
    width: 65.06666592pt
}

sin-borders {
    border: none !important;
}
</style>