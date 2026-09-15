import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta principal EDESSI (tomada del logo oficial)
                edessi: {
                    50: '#EAF2FA',
                    100: '#CDE3F4',
                    400: '#4C96D6',
                    500: '#2C7BC2',
                    600: '#1B6FB5',
                    700: '#15568D',
                    800: '#0B2E5E',
                    900: '#081F40',
                },
                acento: {
                    100: '#FAEEDA',
                    500: '#EF9F27',
                    600: '#D98A16',
                },
                // Modo oscuro: negro + azul del logo (versión "fondo negro")
                noche: {
                    bg: '#0A0A0F',
                    surface: '#15161D',
                    border: '#262832',
                },
            },
        },
    },

    plugins: [forms],
};