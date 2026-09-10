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
                edessi: {
                    50: '#EAF1F8',
                    100: '#CFE0EF',
                    600: '#0C447C',
                    700: '#0A3A6B',
                    800: '#082E55',
                },
                acento: {
                    100: '#FAEEDA',
                    500: '#EF9F27',
                    600: '#D98A16',
                },
                // Paleta modo oscuro (inspirada en dashboard tipo CRM)
                noche: {
                    bg: '#12142B',
                    surface: '#1B1E3D',
                    border: '#2A2E55',
                },
                neon: {
                    purple: '#8B5CF6',
                    pink: '#EC4899',
                    cyan: '#22D3EE',
                },
            },
        },
    },

    plugins: [forms],
};