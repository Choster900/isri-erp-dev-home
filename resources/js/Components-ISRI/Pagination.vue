<template>
    <div v-if="!emptyObject" class="px-6 py-4 bg-white shadow-lg rounded-sm border-slate-200 relative">
        <div>
            <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                <div class="grow text-center">
                    <ul class="inline-flex text-sm font-medium -space-x-px">
                        <li v-for="link in links" :key="link.label">
                            <span v-if="(link.label == 'Anterior')"
                                :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-[#29303C] shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                <div class="flex-1 text-right ml-2">
                                    <a @click="link.url ? $emit('get-data',link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer hover:text-indigo-500
                                  text-[#3c4557]">
                                        &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                    </a>
                                </div>
                            </span>
                            <span v-else-if="(link.label == 'Siguiente')"
                                :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-[#29303C] shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                <div class="flex-1 text-right">
                                    <a @click="link.url ? $emit('get-data',link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer hover:text-indigo-500
                                        text-[#3c4557]">
                                        <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                    </a>
                                </div>
                            </span>
                            <span class="cursor-pointer mt-2" v-else @click="link.url ? $emit('get-data',link.url) : ''"
                                :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-[#29303C] text-[#29303C] shadow-sm hover:text-indigo-500 hover:border-indigo-500' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                    class="min-w-[20px]">{{ link.label }}</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        emptyObject: {
            type: Boolean,
            default: false
        },
        links: {
            type: Array,
            default: []
        }
    },
}
</script>