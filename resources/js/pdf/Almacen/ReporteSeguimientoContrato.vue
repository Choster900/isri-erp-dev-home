<template>
    <div>
        
    </div>
</template>

<script>

export default {
    props: {
        products: {
            type: Object,
            default: {}
        },
        contractName: {
            type: String,
            default: ""
        },
        purchaseProcess: {
            type: String,
            default: ""
        },
        startDate: {
            type: String,
            default: ""
        },
        endDate: {
            type: String,
            default: ""
        }
    },
    setup(props) {
        return {
        }
    }
}
</script>

<style>

</style>