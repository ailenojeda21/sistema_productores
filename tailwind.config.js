import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/**/*.vue', // Asegúrate de incluir esta línea
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
        // Aquí defines tus colores personalizados
        'naranja-oscuro': '#F39200',
        'amarillo-claro': '#F5B410',
        'azul-marino': '#223362',
      },
        },
    },

    plugins: [forms],
};
