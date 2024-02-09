<template>
    <Head title="Proceso - Bienes y Servicios" />
    <AppLayoutVue nameSubModule="Compras - Bienes y Servicios" :colorSide="' bg-[#343a40] '">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="shoModalBienesServicios = true" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Generar documento" icon="add" />
            </div>
        </div>

        <ModalBienesServicios :showModal="shoModalBienesServicios"  @cerrar-modal="shoModalBienesServicios = false;"/>
    </AppLayoutVue>
</template>

<script>
import { usePermissions } from '@/Composables/General/usePermissions';
import { ref, toRefs } from 'vue';
import ModalBienesServicios from '@/Components-ISRI/UCP/BienesServicios/ModalBienesServicios.vue';

export default {
    components: { ModalBienesServicios },
    props: {
        menu: {
            type: Object,
            default: () => { },
        },
    },
    setup(props) {
        const { menu } = toRefs(props);
        const shoModalBienesServicios = ref(false);
        const permits = usePermissions(menu.value, window.location.pathname);

        return {
            shoModalBienesServicios,
            permits
        }
    }
}
</script>

<style></style>
