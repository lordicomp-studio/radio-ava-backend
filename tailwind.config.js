const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors:{
                sidebar:{
                    text: '#9899ac',
                    svgHover: '#009ef7',
                    bgHover: '#1B1B28',
                },
                body: {
                    light: '#F2F4F7',
                    // dark:
                },
                white: {
                    DEFAULT: '#ffffff',
                    active: '#F5F8FA',
                },
                primary: {
                    DEFAULT: '#009EF7',
                    active: '#0095e8',
                    light: '#F1FAFF',
                },
                light: {
                    DEFAULT: '#F5F8FA',
                    active: '#eff2f5',
                },
                secondary: {
                    DEFAULT: '#E4E6EF',
                    active: '#b5b5c3',
                },
                success: {
                    DEFAULT: '#50CD89',
                    active: '#47be7e',
                    light: '#E8FFF3',
                },
                info: {
                    DEFAULT: '#7239EA',
                    active: '#5014d0',
                    light: '#F8F5FF',
                },
                warning: {
                    DEFAULT: '#FFC700',
                    active: '#f1bc00',
                    light: '#FFF8DD',
                },
                danger: {
                    DEFAULT: '#F1416C',
                    active: '#d9214e',
                    light: '#FFF5F8',
                },
                dark: {
                    DEFAULT: '#181C32',
                    active: '#131628',
                    light: '#EFF2F5',
                },
            },
            fontFamily: {
                sans: ['irsans', 'Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
