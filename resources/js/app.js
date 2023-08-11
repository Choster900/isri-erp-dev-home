import "./bootstrap";
import "../css/app.css";
import "../css/GlobalApp.css";
import "./plugins/chart.js"
import "./plugins/requestHelpers.js"
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { manageError } from '@/mixins/handleErrorResponse.js';
import { getPermissions } from '@/mixins/getPermissions.js';
import { showToast } from '@/mixins/showToast.js';
import { executeRequest } from "@/plugins/requestHelpers.js";
import GeneralButton from "@/Components-ISRI/ComponentsToForms/GeneralButton.vue";
import TextInput from "@/Components-ISRI/ComponentsToForms/TextInput.vue";
import LabelToInput from "@/Components-ISRI/ComponentsToForms/LabelToInput.vue";
import Checkbox from "@/Components-ISRI/ComponentsToForms/Checkbox.vue"; //TODO: Fix style
import Multiselect from "@vueform/multiselect";
import Datepicker from "vue3-datepicker";
import RadioButton from "@/Components-ISRI/ComponentsToForms/RadioButton.vue"; //TODO: Fix style
import DatepickerTest from "@/Components-ISRI/ComponentsToForms/FlatPickr.vue";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import flatPickr from "vue-flatpickr-component";
import DropDownOptions from "@/Components-ISRI/DropDownOptions.vue";
import InputError from "@/Components/InputError.vue";
import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
moment.locale('es', localeData)
import jQuery from "jquery";
window.jQuery = window.$ = jQuery;

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

import "flatpickr/dist/flatpickr.css";
import "../css/FlatPickr_theme.css";

import VueLazyLoad from 'vue-lazyload'

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "";

createInertiaApp({
    title: (title) => `${title}  ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueSweetalert2)
            .component("AppLayoutVue", AppLayoutVue)
            .component("moment", moment)
            .component("InputError", InputError)
            .component("Multiselect", Multiselect)
            .component("DropDownOptions", DropDownOptions)
            .component("GeneralButton", GeneralButton)
            .component("flatPickr", flatPickr)
            .component("Checkbox", Checkbox)
            .component("TextInput", TextInput)
            .component("LabelToInput", LabelToInput)
            .component("Datepicker", Datepicker)
            .component("RadioButton", RadioButton)
            .component("DatepickerTest", DatepickerTest)
            .mixin({ methods: { manageError,executeRequest,getPermissions,showToast } })
            .use(ZiggyVue, Ziggy, VueLazyLoad)
            .mount(el);
    },
    progress: {
        color: "#EA0B18",
    },
});
