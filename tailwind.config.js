const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            gridTemplateRows: {
                // Agregando nueva clase para manejar Grid Template Rows
                7:'repeat(7, minmax(0, 1fr))',
                layout: '200px minmax(900px, 1fr) 100px',
            },
            maxWidth: {
                '8xl': '83rem', // Agregando el nuevo tamaño de ancho máximo 8xl
            },
        },
    },

    plugins: [require("@tailwindcss/forms"), require("tailwindcss-animated")],
};
