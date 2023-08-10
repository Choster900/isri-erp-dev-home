<template>
    <!-- Event 1 -->
    <li class="relative pb-4 last-of-type:pb-0">
        <div class="pl-6  mb-2">
            <div @click="hiddenInformation = !hiddenInformation"
                class="flex w-full justify-between text-xs font-medium uppercase cursor-pointer text-slate-800  mb-0.5 hover:fill-indigo-500 hover:text-indigo-500">
                <div class="justify-start text-base">RRHH-161-2023</div>
                <div class="justify-end  ">
                    <svg class="octicon octicon-fold" viewBox="0 0 16 16" version="1.1" v-if="hiddenInformation" width="16"
                        height="16" aria-hidden="true">
                        <path
                            d="M10.896 2H8.75V.75a.75.75 0 0 0-1.5 0V2H5.104a.25.25 0 0 0-.177.427l2.896 2.896a.25.25 0 0 0 .354 0l2.896-2.896A.25.25 0 0 0 10.896 2ZM8.75 15.25a.75.75 0 0 1-1.5 0V14H5.104a.25.25 0 0 1-.177-.427l2.896-2.896a.25.25 0 0 1 .354 0l2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25Zm-6.5-6.5a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z">
                        </path>
                    </svg>

                    <svg v-else class="octicon octicon-unfold " viewBox="0 0 16 16" version="1.1" width="16" height="16"
                        aria-hidden="true">
                        <path
                            d="m8.177.677 2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25a.75.75 0 0 1-1.5 0V4H5.104a.25.25 0 0 1-.177-.427L7.823.677a.25.25 0 0 1 .354 0ZM7.25 10.75a.75.75 0 0 1 1.5 0V12h2.146a.25.25 0 0 1 .177.427l-2.896 2.896a.25.25 0 0 1-.354 0l-2.896-2.896A.25.25 0 0 1 5.104 12H7.25v-1.25Zm-5-2a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="text-sm mb-2" v-show="!hiddenInformation">
                <a class="font-medium text-slate-800">
                    <span class="font-normal ">Acuerdo Laboral -</span>
                    Lorem ipsum dolor sit amet consectetur
                    adipisicing elit. Cumque quibusdam optio beatae alias fugit impedit,
                    perspiciatis dolor explicabo et, sint nihil ipsum libero mollitia
                    molestiae repellat eligendi voluptatum vel omnis!
                    <p class="font-normal pt-3 italic">20/2/2023 - 21/2/2023</p>
                </a>
            </div>
        </div>
        <!-- Timeline element -->
        <div aria-hidden="true">
            <div class="absolute top-1 -bottom-1 left-0.5 ml-px w-0.5 bg-slate-200">
            </div>
            <div class="absolute top-1 left-0 ml- w-2 h-2 rounded-full bg-slate-400">
            </div>
        </div>
    </li>
</template>

<script>
export default {
    data() {
        return {
            hiddenInformation: false,
        }
    },
}
</script>

<style></style>