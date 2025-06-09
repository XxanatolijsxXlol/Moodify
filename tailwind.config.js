import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Enable dark mode using the 'class' strategy

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Optional: Add custom colors for consistency
            colors: {
                indigo: {
                    600: '#4f46e5', // Light mode primary
                    900: '#312e81', // Dark mode primary
                },
                gray: {
                    100: '#f3f4f6', // Light mode background
                    900: '#111827', // Dark mode background
                },
            },
        },
    },

    plugins: [forms],
};