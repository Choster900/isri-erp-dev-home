<template>
    <div class="border bg-white p-4 rounded-lg">

        <div class="flex justify-between">
            <h1 class="font-medium text-base pb-1 pl-1 flex gap-1">Informacion General
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            </h1>
            <button class="text-xs underline hover:no-underline flex pt-1" @click="sectionView = 'mainSection'">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M6 12L11 7M6 12L11 17" stroke="#000000" stroke-width="0.72" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg> <span class="">Categorias</span>
            </button>
        </div>

        <div class="bg-slate-100 rounded-md p-4">
            <div class="bg-white p-2 rounded-md">
                <div class="flex space-x-5 pb-3">
                    <div class="w-1/3">
                        <span class="text-xs font-medium block text-slate-500/70">Dui</span>
                        <span class="text-xs font-medium block">06184091-8</span>
                    </div>

                    <div>
                        <span class="text-xs font-medium block text-slate-500/70">Nombre</span>
                        <span class="text-xs font-medium block pb-3">Sergio Adonay Lopez Mejia</span>

                        <span class="text-xs font-medium block text-slate-500/70">Locacion</span>
                        <span class="text-xs font-medium block pb-3">Lourdes Colón, La Libertad</span>

                        <span class="text-xs font-medium block text-slate-500/70">Telefono</span>
                        <span class="text-xs font-medium block">(503) + 7040-5653</span>
                    </div>
                </div>
                <div class="h-7 w-full bg-blue-700 rounded-md px-4 flex justify-between pt-1.5">
                    <span class="text-xs text-white">
                        <span class="truncate w-48 block">nombre del archivo.pdf</span>
                    </span>
                    <span class="text-xs text-white">100MB</span>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style></style>