<script>
export default {
    props: {
        textLabel: {
            type: String,
            default: 'texto del label',
        },
        required: {
            type: Boolean,
            default: true,
        },
    },
    setup() {


        return {}
    }
}
</script>

<template>
    <label for="" class="text-xs">{{ textLabel }} <span v-if="required" class="text-red-600 font-extrabold">*</span></label>
</template>

<style lang="scss" scoped></style>
