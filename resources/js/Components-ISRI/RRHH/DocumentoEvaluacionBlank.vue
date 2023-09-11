<template>
    <div class="mx-4 overflow-y-auto max-h-[calc(100vh-100px)] p-3 mb-4">
        <table>
            <tbody>
                <tr>
                    <td class="border border-black" rowspan="4">
                        <div class="w-44 px-1.5"><img src="../../../img/isri-gob.png" alt=""></div>
                    </td>
                    <td class="border border-black text-center font-medium uppercase" rowspan="2">
                        Seguimiento de Desempeño</td>
                    <td class="border border-black text-[8pt] font-semibold pr-3 px-1.5">FECHA CREACION: dd/mm/yyyy</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold   px-1.5">ID 00000-0</td>
                </tr>
                <tr>
                    <td class="border border-black text-center font-medium px-8" rowspan="2">DESEMPEÑO DE
                        PERSONAL ADMINISTRATIVO</td>
                    <td class="border border-black text-[8pt] font-semibold py-0.5 px-1.5">DEPENDENCIA:
                        ADMON</td>
                </tr>
                <tr>
                    <td class="border border-black text-[8pt] font-semibold  py-0.5 px-1.5">CODIGO: VERSION
                        1.0</td>
                </tr>
                <tr>
                    <td class="border border-black bg-black h-5 text-white text-center text-[10pt] " colspan="3">
                        ESPECIFICACIONES</td>
                </tr>
            </tbody>
        </table>
        <div class="flex items-center justify-center pt-4 gap-28">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">EMPLEADO: </label>
                <input type="text"
                    class=" text-left text-[9pt] w-56 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO: </label>
                <input type="text"
                    class=" text-left text-[9pt] w-52 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
        </div>
        <div class="flex items-center justify-center pt-2 gap-10 pb-7">
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">JEFE INME: </label>
                <input type="text" class=" text-left text-[9pt]  text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex   flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUESTO : </label>
                <input type="text"
                    class=" text-left text-[9pt] w-56 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
            <div class="flex  flex-row">
                <label for="" class="flex items-center text-[7pt] font-semibold">PUNTAJE TOTAL:</label>
                <input type="text"
                    class=" text-left text-[9pt] w-20 text-sm font-medium capitalize  h-5 border-x-0 border-t-0">
            </div>
        </div>
        <table class="pb-5" v-for="i in 4" :key="i">
            <tbody>
                <tr>
                    <td class="border border-black bg-black h-10 text-[10pt] text-white text-center" colspan="2">
                        <div class="flex justify-between items-center">
                            <span class="mx-auto">NOMBRE CATEGORIA</span>
                            <span class="text-end pr-8">PUNTOS</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black bg-slate-300/60" rowspan="2">
                        <div class="w-40 p-1.5 text-[9pt] text-justify text-[#4f4f4f] font-medium"></div>
                    </td>
                </tr>
                <tr>
                    <td class="border border-black">

                        <table class="" id="rubrica_rendimiento">
                            <tr v-for="j in 4" :key="j">
                                <td class=" border-l-0 border-r border-t-0 border-b border-black  w-[490px]">

                                </td>
                                <td
                                    class="border-x-0  border-t-0 border-b  border-black justify-center text-center px-10 py-4">
                                    <div class="container">
                                        <label>
                                            <input type="radio" disabled>
                                            <span class="text-xs justify-center text-center"> </span>
                                        </label>
                                    </div>
                                </td>
                            </tr>


                        </table>
                    </td>

                </tr>

            </tbody>
        </table>


    </div>
</template>

<script>


export default {

}
</script>

<style scoped>
.container form {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
}

.container label {
    display: flex;
    cursor: pointer;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    margin-bottom: 0.375em;
}

.container label input {
    position: absolute;
    left: -9999px;
}

.container label input:checked+span {
    background-color: #ffffff;
    color: rgb(0, 0, 0);
}

.container label input:checked+span:before {
    box-shadow: inset 0 0 0 0.4375em #000000;
}

.container label span {
    display: flex;
    align-items: center;
    padding: 0.375em 0.75em 0.375em 0.375em;
    /*  border-radius: 99em; */
    transition: 0.25s ease;
    color: #000000;
}

.container label span:hover {
    background-color: #d6d6e5;
}

.container label span:before {
    display: flex;
    flex-shrink: 0;
    content: "";
    background-color: #fff;
    width: 1.5em;
    height: 1.5em;
    /* border-radius: 50%; */
    margin-right: 0.375em;
    transition: 0.25s ease;
    box-shadow: inset 0 0 0 0.125em #000000;
}
</style>