
<template>
    <div class="flex flex-col xl:flex-row xl:space-x-16" v-show="showInformation">
        <div class="space-y-4 mb-8 xl:mb-0">
            <h2 class="text-slate-800 font-semibold">Informacion general del empleado</h2>
            <div>
                <div class="flex flex-col md:flex-row  md:space-y-0">
                    <div class="flex-1 w-96">
                        <article class="bg-white shadow-md rounded border border-slate-200 p-5 mb-1" v-for="index in 2"
                            :key="index">
                            <div class="flex flex-start space-x-4">
                                <div class="grow">
                                    <h2 class="font-semibold text-slate-800 mb-2">
                                        <a href="/community/forum-post" class="">8-ADMINISTRADOR DE CENTRO</a>
                                    </h2>
                                    <div class="flex gap-2">
                                        <h1 class="text-sm">Salario base: <span class="font-semibold">$750.99</span></h1>
                                        <h1 class="text-sm">Salario base: <span class="font-semibold">$2500.50</span></h1>
                                    </div>
                                    <footer class="flex flex-wrap text-sm">
                                        <div
                                            class="flex items-center after:block after:content-['·'] last:after:content-[''] after:text-sm after:text-slate-400 after:px-2">
                                            <a class="font-medium text-indigo-500 hover:text-indigo-600" href="#0">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 fill-current"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.686 5.708 10.291.313c-.4-.4-.999-.4-1.399 0s-.4 1 0 1.399l.6.6-6.794 3.696-1-1C1.299 4.61.7 4.61.3 5.009c-.4.4-.4 1 0 1.4l1.498 1.498 2.398 2.398L.6 14.001 2 15.4l3.696-3.697L9.692 15.7c.5.5 1.199.2 1.398 0 .4-.4.4-1 0-1.4l-.999-.998 3.697-6.695.6.6c.599.6 1.199.2 1.398 0 .3-.4.3-1.1-.1-1.499Zm-7.193 6.095L4.196 7.507l6.695-3.697 1.298 1.299-3.696 6.694Z">
                                                        </path>
                                                    </svg> En ejecucion
                                                </div>
                                            </a>
                                        </div>
                                        <div
                                            class="flex items-center after:block after:content-['·'] last:after:content-[''] after:text-sm after:text-slate-400 after:px-2">
                                            <span class="text-slate-500">7d</span>
                                        </div>
                                        <div
                                            class="flex items-center after:block after:content-['·'] last:after:content-[''] after:text-sm after:text-slate-400 after:px-2">
                                            <span class="text-slate-500">688 Comments</span>
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        </article>
                    </div>
                    <div class="px-4 py-2 rounded-md overflow-x-auto">
                        <aside class="xl:min-w-56 xl:w-60 space-y-3">
                            <div class="text-sm">
                                <h3 class="font-medium text-slate-800 uppercase">Vive en</h3>
                                <div class="uppercase font-semibold">SLV - EL SALVADOR 06-SAN SALVADOR 0614-SAN SALVADOR</div>
                            </div>
                            <div class="text-sm">
                                <h3 class="font-medium uppercase text-slate-800">Genero</h3>
                                <div class="uppercase font-semibold">Masculino</div>
                            </div>
                            <div class="text-sm">
                                <div class="flex">
                                    <div class="font-medium uppercase text-slate-800 pr-2">Dui: </div>
                                    <div class="uppercase font-semibold">06184091-8</div>
                                </div>
                                <div class="flex">
                                    <div class="font-medium uppercase text-slate-800 pr-2">nit: </div>
                                    <div class="uppercase font-semibold">06184091-8</div>
                                </div>
                            </div>
                            <div class="text-sm">
                                <h3 class="font-medium uppercase text-slate-800">Telefono</h3>
                                <div class="uppercase font-semibold">6162-1431</div>
                            </div>
                        </aside>
                    </div>

                </div>
            </div>
        </div>



    </div>
</template>

<script>
export default {
    props: {
        showInformation: {
            type: Boolean,
            default: false,
        },


    },
}
</script>

<style></style>