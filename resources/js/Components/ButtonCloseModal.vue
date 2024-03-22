<template>
    <div class=" flex  justify-center pt-4 pb-4 content-between">
        <svg class="h-7 w-7 absolute top-0 right-0 mt-3 cursor-pointer" viewBox="0 0 25 25"
            @click="$emit('close')">
            <path fill="currentColor"
                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
        </svg>
    </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped></style>
