<script setup>
import HeaderVue from "@/Layouts/Header.vue";
import { Head } from "@inertiajs/vue3";

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});
const updatePassword = () => {
    form.put(route('index.cambiarContraseña'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <Head title="Cambio de contraseña" />

    <div class="flex h-screen overflow-hidden">
        <div
            class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden"
        >
            <HeaderVue nameSubModule="Administración" />

            <main class="bg-gray-100/50 h-full">
                <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
                    <div class="flex items-center justify-center">
                            <section>
                                <header>
                                    <h2
                                        class="text-lg font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        Actualizar contraseña
                                    </h2>

                                    <p
                                        class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        Asegurese de escribir una contraseña nueva lo suficientemente segura
                                    </p>
                                </header>

                                <form
                                    @submit.prevent="updatePassword"
                                    class="mt-6 space-y-6"
                                >
                                    <div>
                                        <InputLabel
                                            for="current_password"
                                            value="Contraseña actual"
                                        />

                                        <TextInput
                                            id="current_password"
                                            ref="currentPasswordInput"
                                            v-model="form.current_password"
                                            type="password"
                                            class="mt-1 block w-full"
                                            autocomplete="current-password"
                                        />

                                        <InputError
                                            :message="
                                                form.errors.current_password
                                            "
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="password"
                                            value="Nueva contraseña"
                                        />

                                        <TextInput
                                            id="password"
                                            ref="passwordInput"
                                            v-model="form.password"
                                            type="password"
                                            class="mt-1 block w-full"
                                            autocomplete="new-password"
                                        />

                                        <InputError
                                            :message="form.errors.password"
                                            class="mt-2"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="password_confirmation"
                                            value="Repita nueva contraseña"
                                        />

                                        <TextInput
                                            id="password_confirmation"
                                            v-model="form.password_confirmation"
                                            type="password"
                                            class="mt-1 block w-full"
                                            autocomplete="new-password"
                                        />

                                        <InputError
                                            :message="
                                                form.errors
                                                    .password_confirmation
                                            "
                                            class="mt-2"
                                        />
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <PrimaryButton
                                            :disabled="form.processing"
                                            >Guardar</PrimaryButton
                                        >
                                        <a class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" href="/dashboard">
                                            Cancelar
                                        </a>
                                    </div>
                                </form>
                            </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
export default {};
</script>
