<template>
    <div class="bg-slate-100 flex flex-col items-center pb-10 h-[640px] overflow-y-auto">
        <!--  <div class="mr-auto">
            <h1 class="flex py-4 px-5 gap-2 font-medium">
                <svg class="w-5 h-5" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                    <path class="cls-1"
                        d="M29,32H10a1,1,0,0,1,0-2H29a1,1,0,0,0,1-1V25a1,1,0,0,0-1-1H24.441a1,1,0,0,0-.948.684l-.088.263A3,3,0,0,1,20.559,27H11.441A3,3,0,0,1,8.6,24.949l-.088-.267A1,1,0,0,0,7.559,24H3a1,1,0,0,0-1,1v4a1,1,0,0,0,1,1H6a1,1,0,0,1,0,2H3a3,3,0,0,1-3-3V25a3,3,0,0,1,3-3H7.559a3,3,0,0,1,2.846,2.051l.088.267a1,1,0,0,0,.948.682h9.118a1,1,0,0,0,.948-.684l.088-.263A3,3,0,0,1,24.441,22H29a3,3,0,0,1,3,3v4A3,3,0,0,1,29,32Z">
                    </path>
                    <path class="cls-1"
                        d="M5,24a1,1,0,0,1-1-1V3A3,3,0,0,1,7,0H20.171a2.978,2.978,0,0,1,2.122.879l4.828,4.828A2.978,2.978,0,0,1,28,7.828V19a1,1,0,0,1-2,0V7.828a1.009,1.009,0,0,0-.293-.707L20.879,2.293A1,1,0,0,0,20.171,2H7A1,1,0,0,0,6,3V23A1,1,0,0,1,5,24Z">
                    </path>
                    <path class="cls-2" d="M23,12H13a1,1,0,0,1,0-2H23a1,1,0,0,1,0,2Z"></path>
                    <path class="cls-2" d="M23,16H9a1,1,0,0,1,0-2H23a1,1,0,0,1,0,2Z"></path>
                    <path class="cls-2" d="M19,20H9a1,1,0,0,1,0-2H19a1,1,0,0,1,0,2Z"></path>
                    <path class="cls-1" d="M26,8H21a1,1,0,0,1-1-1V2a1,1,0,0,1,2,0V6h4a1,1,0,0,1,0,2Z"></path>
                </svg> 
            </h1>
        </div> -->
        <!--    {{ anexoInformation }} -->
        <hr class="my-1 mx-7 border-t border-slate-300 mb-10">
        <div class="flex justify-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                v-if="anexoInformation.id_tipo_mime == 1" alt="" class="h-32 pb-4">
            <img src="https://icons-for-free.com/iconfiles/png/512/gallery+image+landscape+mobile+museum+open+line+icon-1320183049020185924.png"
                v-else alt="" class="h-32 pb-4">

        </div>
        <h1 class="px-8 font-semibold">{{ anexoInformation.nombre_archivo_anexo }}</h1>
        <div class="mr-auto">
            <span class="px-8 text-sm text-slate-500">10Mb || 100KB</span>
        </div>
        <hr class="my-3 mx-7 border-t border-slate-300">
        <div class="mr-auto">
            <h1 class="px-8 text-xs font-semibold">Descripcion del archivo</h1>
        </div>
        <span class="mx-8 text-justify text-xs text-slate-500">{{ anexoInformation.descripcion_archivo_anexo }}</span>
<!-- 
        <div class="  pt-3 w-full px-8">
            <button @click="alerta"
                class="border border-indigo-900 bg-indigo-800 hover:bg-indigo-900 rounded-lg  text-center  text-white-900 text-white text-xs font-semibold w-full py-1 px-2">
                Descargar archivo</button>
        </div>
        <div class="  pt-3 w-full px-8">
            <button @click="alerta"
                class="border border-red-900 bg-red-800 rounded-lg  hover:bg-red-900 text-center  text-white-900 text-white text-xs font-semibold w-full py-1 px-2">
                Eliminar archivo</button>
        </div> -->

    </div>
</template>

<script>
export default {
    props: ['anexoInformation'],
    setup() {
        const alerta = (() => alert("Comming soon..."))
        const alerta2 = (() => alert("Comming soon..."))


        return {
            alerta
        }
    }
}
</script>

<style></style>