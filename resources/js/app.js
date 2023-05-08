import "./bootstrap";
import "../css/app.css";
import "../css/GlobalApp.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

import GeneralButton from "@/Components-ISRI/ComponentsToForms/GeneralButton.vue";
import TextInput from "@/Components-ISRI/ComponentsToForms/TextInput.vue";
import LabelToInput from "@/Components-ISRI/ComponentsToForms/LabelToInput.vue";
import Checkbox from "@/Components-ISRI/ComponentsToForms/Checkbox.vue"; //TODO: Fix style
import moment from "moment";
moment.lang("es", {
    months: "Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre".split(
        "_"
    ),
    monthsShort:
        "Enero._Feb._Mar_Abr._May_Jun_Jul._Ago_Sept._Oct._Nov._Dec.".split("_"),
    weekdays: "Domingo_Lunes_Martes_Miercoles_Jueves_Viernes_Sabado".split("_"),
    weekdaysShort: "Dom._Lun._Mar._Mier._Jue._Vier._Sab.".split("_"),
    weekdaysMin: "Do_Lu_Ma_Mi_Ju_Vi_Sa".split("_"),
});
import Multiselect from "@vueform/multiselect";
import Datepicker from "vue3-datepicker";
import RadioButton from "@/Components-ISRI/ComponentsToForms/RadioButton.vue"; //TODO: Fix style
import DatepickerTest from "@/Components-ISRI/ComponentsToForms/FlatPickr.vue";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import flatPickr from "vue-flatpickr-component";
import DropDownOptions from "@/Components-ISRI/DropDownOptions.vue";

import jQuery from "jquery";
window.jQuery = window.$ = jQuery;

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

import "flatpickr/dist/flatpickr.css";
import "../css/FlatPickr_theme.css";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "";

const manageError = (errors) => {
    let code_error = errors.response.status;
    let msg;
    if (code_error >= 500) {
        console.log(errors.response.data.message);
        msg = "Error al conectarse con el servidor.";
    } else if (code_error >= 400 && code_error < 500) {
        console.log(errors.response.data.message);
        msg = "Funcionalidad no disponible, consulte con el administrador.";
    } else {
        console.log(errors.response.data.message);
        msg = "Lo sentimos, hubo un error al procesar la petición.";
    }
    return msg;
};

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
            .mixin({ methods: { manageError } })
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: "#EA0B18",
    },
});
