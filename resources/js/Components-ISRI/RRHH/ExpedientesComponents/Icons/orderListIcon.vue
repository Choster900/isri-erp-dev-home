<template>
    <svg class="w-6 h-6 " viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="1"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path
                d="M8 6L21 6.00078M8 12L21 12.0008M8 18L21 18.0007M3 6.5H4V5.5H3V6.5ZM3 12.5H4V11.5H3V12.5ZM3 18.5H4V17.5H3V18.5Z"
                :stroke="modeListSelected ? '#6366f1' : '#64748b'" stroke-width="3" stroke-linecap="round"
                stroke-linejoin="round">
            </path>
        </g>
    </svg>
</template>

<script>
export default {
    props: ["modeListSelected"]
}
</script>

<style></style>