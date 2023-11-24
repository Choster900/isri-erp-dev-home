import { toast } from "vue3-toastify";

export function useShowToast(toastFunction, message, options = {}) {
    const toastOptions = {
        autoClose: 5000,
        position: "top-right",
        transition: "zoom",
        toastBackgroundColor: "white",
        ...options,
    };

    toastFunction(message, toastOptions);
}