<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import HojaServiciosBodyVue from "@/Components-ISRI/RRHH/ArchivosInstitucionalesComponents/HojaServiciosBody.vue";
</script>

<template>
    <Head title="RRHH - Hoja de servicios" />
    <AppLayoutVue nameSubModule="RRHH - Hoja De Servicios" :autoPadding="false">
        <div class="relative flex">
<!--             <HojaServiciosSidebarVue @send-user-data-when-is-click="reciveUserData" :is-profile-selected="profileSelected"
                @send-first-user-data="reciveUserData" /> -->
            <HojaServiciosBodyVue :user-data="userMatches[0]" @profile-selected="profile" />
        </div>
    </AppLayoutVue>
</template>

<script>
export default {
    data() {
        return {
            userData: [],
            profileSelected: [],
            userMatches: [],// Objecto que almacena los usuarios encontrados por el string de busqueda
        };
    },
    methods: {

        async searchingUsers() {
            try {
                this.isLoading = true
                const response = await axios.post('/get-my-own-information');
                console.log(response);
                this.userMatches = response.data;
            } catch (error) {
                console.log('Error en la búsqueda:', error)
            } finally {
                this.isLoading = false
            }

        },
        reciveUserData(data) {
            this.userData = [];
            this.userData = data;
        },
        profile(data) {
            this.profileSelected = [];
            this.profileSelected = data;
        },

    },
    mounted() {
        this.searchingUsers()
    },
};
</script>

<style></style>
