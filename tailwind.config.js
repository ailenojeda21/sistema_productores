import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'sap': {
                    // Colores principales
                    'primary': '#0A6ED1', // Azul principal SAP
                    'primary-dark': '#0070F2', // Azul oscuro para hover
                    'secondary': '#354A5F', // Azul grisáceo

                    // Colores neutros
                    'bg': '#F5F6F7', // Fondo gris claro
                    'border': '#D9D9D9', // Bordes
                    'text': '#32363A', // Texto principal
                    'text-secondary': '#6A6D70', // Texto secundario

                    // Colores de acento
                    'success': '#107E3E', // Verde para éxito/confirmar
                    'error': '#BB0000', // Rojo para errores/eliminar
                    'warning': '#E9730C', // Naranja para advertencias

                    // Colores para estados
                    'info': '#0A6ED1', // Azul para información
                    'done': '#107E3E', // Verde para completado
                    'pending': '#E9730C', // Naranja para pendiente
                }
            },
        },
    },

    plugins: [forms],
};
