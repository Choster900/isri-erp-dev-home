import './bootstrap';
import '../css/app.css';
import '../css/GlobalApp.css'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import Select2 from 'vue3-select2-component';

import jQuery from 'jquery'
window.jQuery = window.$ = jQuery

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import 'flatpickr/dist/flatpickr.css';
import '../css/FlatPickr_theme.css'
import moment from 'moment';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || '';

createInertiaApp({
    title: (title) => `${title}  ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueSweetalert2)
            .component('Select2', Select2)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
