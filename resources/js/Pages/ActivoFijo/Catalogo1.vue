
<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from '@/Layouts/AppLayout.vue';
</script>



<template>

  <Head title="Activo Fijo" />

  <AppLayoutVue>
    
  </AppLayoutVue>


</template>

<script>
export default {
  data: function () {
    return {
      changeState: false,
      showModal: false,
    };
  },
  methods: {
    changeStateFromModal() {
      this.showModal = !this.showModal;
    },
  },
};
</script>

<style>

</style>
