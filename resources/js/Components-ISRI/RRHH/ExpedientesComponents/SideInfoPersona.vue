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
        </div>

        <div class="bg-slate-100 rounded-md p-4">
            <div class="bg-white p-2 rounded-md">
                <div class=" space-x-5 pb-3">

                    <div>
                        <span class="text-xs font-medium block text-slate-500/70">Profesion</span>
                        <span class="text-xs font-medium block pb-3">LIC(A). EN CIENCIAS DE LA EDUCACION ESP. EDUCACION
                            FISICA DEPORTE Y RECREACION</span>

                        <span class="text-xs font-medium block text-slate-500/70">Dui de la persona en cuestion</span>
                        <span class="text-xs font-medium block pb-3">06184091-8</span>

                        <span class="text-xs font-medium block text-slate-500/70">Locacion</span>
                        <span class="text-xs font-medium block pb-3">Lourdes Colón, La Libertad</span>

                        <span class="text-xs font-medium block text-slate-500/70">Telefono</span>
                        <span class="text-xs font-medium block pb-3">(503) + 7040-5653</span>

                        <span class="text-xs font-medium block text-slate-500/70">Correo</span>
                        <span class="text-xs font-medium block pb-3">16adonaysergio@gmail.com</span>

                        <span class="text-xs font-medium block text-slate-500/70">Nombre del archivo</span>
                        <span class="text-xs font-medium block">Hoja de servicio.pdf</span>
                    </div>
                </div>
                <button class="bookmarkBtn w-full pl-1" title="PARA ELIMINAR ARCHIVO" @click="$emit('delete-file')"
                    :class="{ 'cursor-not-allowed': fileName == '' || fileName == null }">
                    <span class="IconContainer hover:w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 fill-[#1C242B] stroke-[#F34541]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </span>
                    <p class="text ml-2 w-full">{{ fileName }}</p>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, watch } from 'vue'
export default {
    emits: ['delete-file'],
    props: {
        fileName: {
            type: Object,
            default: () => { },
        },
    },
    setup(props) {
        return {
            
        }
    }
}
</script>

<style>
.bookmarkBtn {
    height: 35px;
    border-radius: 40px;
    border: 1px solid rgba(255, 255, 255, 0.349);
    background-color: #F34541;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition-duration: .3s;
    overflow: hidden;
}

.IconContainer {
    width: 25px;
    height: 25px;
    background: #1C242B;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    z-index: 2;
    transition-duration: .3s;
}

/* 
.fileName {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
} */

.text {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    z-index: 1;
    transition-duration: .3s;
    font-size: 0.65em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 230px;
}

.bookmarkBtn:hover .IconContainer {
    width: 100%;
    /* o el valor que prefieras */
    border-radius: 40px;
    transition-duration: .3s;


}

.bookmarkBtn:hover .text {
    transform: translate(10px);
    width: 0;
    font-size: 0;
    transition-duration: .3s;
}
</style>