<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import HojaServiciosSidebarVue from "@/Components-ISRI/RRHH/HojaServiciosSidebar.vue";
import HojaServiciosBodyVue from "@/Components-ISRI/RRHH/HojaServiciosBody.vue";
</script>

<template>
    <Head title="RRHH - Hoja de servicios" />
    <AppLayoutVue nameSubModule="RRHH - Hoja De Servicios" :autoPadding="false">
        <div class="relative flex">
            <HojaServiciosSidebarVue @send-user-data-when-is-click="reciveUserData" :is-profile-selected="profileSelected"
                @send-first-user-data="reciveUserData" />
            <HojaServiciosBodyVue :user-data="userData" @profile-selected="profile" />
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
        reciveUserData(data) {
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
