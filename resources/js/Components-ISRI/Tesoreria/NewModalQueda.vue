<template>
    <div>
        <ProcessModal maxWidth='6xl' :show="showModal" @close="$emit('cerrar-modal')">

        </ProcessModal>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';

// Define las props utilizando la Composition API
const props = defineProps({
    showModal: { // prop muestra estado del modal para abrir y cerrar
        type: Boolean,
        default: false,
    },
    dataQuedan: { // prop has total information from "quedan"
        type: Object,
        required: true,
    },
    dataForSelectInRow: { // prop que muestra information en row
        type: Object,
        required: true,
    },
    totalAmountBySupplier: {
        type: Array,
        default: () => [],
        required: true,
    },
    amountByAcquisitionDocumentsDetails: {
        type: Array,
        default: () => [],
        required: true,
    },
});
</script>

<style lang="scss" scoped></style>
