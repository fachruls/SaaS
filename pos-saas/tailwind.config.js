import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                },
            },
            boxShadow: {
                'soft':    '0 2px 15px -3px rgba(0,0,0,.07), 0 10px 20px -2px rgba(0,0,0,.04)',
                'soft-lg': '0 10px 40px -10px rgba(0,0,0,.15)',
                'inner-soft': 'inset 0 2px 8px 0 rgba(0,0,0,.06)',
            },
            animation: {
                'slide-in-right': 'slideInRight 0.2s ease-out',
                'fade-in':        'fadeIn 0.15s ease-out',
                'bounce-sm':      'bounceSm 0.3s ease-out',
            },
            keyframes: {
                slideInRight: {
                    '0%':   { transform: 'translateX(10px)', opacity: '0' },
                    '100%': { transform: 'translateX(0)',    opacity: '1' },
                },
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                bounceSm: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%':      { transform: 'scale(1.05)' },
                },
            },
        },
    },
    plugins: [forms],
};
