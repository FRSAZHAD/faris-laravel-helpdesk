/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.ts',
        './resources/**/*.vue',
        './node_modules/primevue/**/*.{js,vue,ts}',
    ],
    theme: {
        extend: {},
    },
    plugins: [require('tailwindcss-primeui')],
};