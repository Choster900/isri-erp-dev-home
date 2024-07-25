<template>
    <div class="space-y-8">
        <div>
            <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
                <span v-if="!isSidebarOpen"
                    class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-centerw-6"
                    aria-hidden="true">•••</span>
                <span v-else class="lg:sidebar-expanded:block 2xl:block">Modulos</span>
            </h3>
            <ul class="mt-3">
                <ModulesVue :color="color" v-for="(module, index) in menuModules"  :modulo="module"
                    :StateFromModal="isSidebarOpen" @triggerModalFromMenu="handleTriggerModalFromMenu" />
            </ul>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps, defineEmits } from 'vue';
import ModulesVue from './Modules.vue';
import { usePage } from '@inertiajs/vue3';

// Define props with types and default values
const props = defineProps({
    isSidebarOpen: {
        type: Boolean,
        required: true
    },
    color: {
        type: String,
        default: ''
    }
});

// Define emits for event handling
const emit = defineEmits(['triggerModalFromMenu']);

// Compute the menu URLs from the Inertia page props
const menuModules = computed(() => usePage().props.menu.urls);


// Method to emit event to show modal from menu
const handleTriggerModalFromMenu = () => {
    emit('triggerModalFromMenu');
};

</script>

<style></style>
