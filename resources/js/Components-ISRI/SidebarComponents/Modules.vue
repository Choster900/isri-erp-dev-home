<template>
    <li class="px-3 py-2 mb-0.5 last:mb-0 cursor-pointer"
        :class="color ? 'bg-gray-600' : (isActivePath ? 'bg-[#010b2c]' : '')">
        <a @click="handleMenuClick" class="block text-slate-200 hover:text-white truncate transition duration-150">
            <div class="flex items-center justify-between" id="content-menus">
                <div class="flex items-center">
                    <!-- Show different icon based on menu state -->
                    <svg v-if="isMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1" class="w-7 h-7" :class="{
                            [isActivePath ? 'fill-current stroke-black' : 'fill-current text-slate-600']: true,
                            [color ? 'text-gray-400 stroke-black' : 'text-indigo-600']: true
                        }">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        class="w-7 h-7" :class="{
                            [isActivePath ? 'fill-current stroke-black' : 'fill-current text-slate-600']: true,
                            [color ? 'text-gray-400 stroke-black' : 'text-indigo-600']: true
                        }">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                    </svg>

                    <span class="text-sm ml-3 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 no-select">
                        {{ module.menu }}
                    </span>
                </div>
                <div class="flex shrink-0 ml-2">
                    <!-- Rotate arrow based on menu state -->
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400"
                        :class="isMenuOpen ? 'rotate-360' : 'rotate-180'" viewBox="0 0 12 12">
                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"></path>
                    </svg>
                </div>
            </div>
        </a>
        <div class="lg:sidebar-expanded:block 2xl:block">
            <ul class="pl-9 mt-1" :class="isMenuOpen ? 'hidden' : ''">
                <!-- Render LinksSidebar component for each submenu item -->
                <LinksSidebar v-for="(submenuItem, index) in module.submenu" :key="index" :infoModule="submenuItem"
                    @coindiciendoRuta="handlePathSelected" />
            </ul>
        </div>
    </li>
</template>

<script setup>
import { ref, watch } from 'vue';
import LinksSidebar from './LinksSidebar.vue';

// Define props
const props = defineProps({
    isSidebarOpen: {
        type: Boolean,
        required: true
    },
    module: {
        type: Object,
        required: true
    },
    color: {
        type: String,
        default: ''
    }
});

// State variables
const isMenuOpen = ref(true);
const isActivePath = ref(false);

const emit = defineEmits(['triggerModalFromMenu']);


// Handle menu click
const handleMenuClick = () => {
    if (props.isSidebarOpen) {
        isMenuOpen.value = !isMenuOpen.value;
    } else {
        emit('triggerModalFromMenu');
    }
};

// Handle path selection
const handlePathSelected = () => {
    isActivePath.value = true;
    isMenuOpen.value = false;
};

// Watch prop changes
watch(() => props.isSidebarOpen, (newValue) => {
    if (!newValue) {
        isMenuOpen.value = true;
    } else {
        if (isActivePath.value) {
            isMenuOpen.value = !newValue;
        }
    }
});

</script>

<style>
.no-select {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
</style>
