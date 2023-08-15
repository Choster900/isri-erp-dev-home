<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import HojaServiciosSidebarVue from "@/Components-ISRI/RRHH/HojaServiciosSidebar.vue";
import HojaServiciosBodyVue from "@/Components-ISRI/RRHH/HojaServiciosBody.vue";
</script>

<template>
    <Head title="RRHH - Acuerdos de contratacion" />
    <AppLayoutVue nameSubModule="RRHH - Acuerdos" :autoPadding="false">
        <div class="relative flex">
            <HojaServiciosSidebarVue @sendUserData="reciviendoUserData" :is-profile-selected="profileSelected"
                @sentFirtUserData="reciviendoUserData" />
            <HojaServiciosBodyVue :userData="userData" @profileSelected="profile" />
        </div>
    </AppLayoutVue>
</template>

<script>
export default {
    data() {
        return {
            userData: [],
            profileSelected: [],
        };
    },
    methods: {
        reciviendoUserData(data) {
            this.userData = [];
            this.userData = data;
        },
        profile(data) {
            this.profileSelected = [];
            this.profileSelected = data;
        },

    },
};
</script>

<style></style>
