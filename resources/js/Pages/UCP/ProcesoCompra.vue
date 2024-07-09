
<template>

    <Head title="Catalogos - Proceso Compra" />
    <AppLayoutVue nameSubModule="Catalogos - Proceso Compra" :colorSide="' bg-[#343a40] '">
            proceso de compra de ucp
    </AppLayoutVue>

</template>

<script>
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { toRefs } from 'vue';
export default {

    props:{
        menu: { type: Object, default: () => ({}) },
    },
    setup(props) {

        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        return {}
    }
}
</script>
<style></style>
