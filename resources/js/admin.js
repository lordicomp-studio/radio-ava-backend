import './bootstrap';
import '../css/admin.css';

import { createApp, h } from 'vue';
import { createInertiaApp , Link } from '@inertiajs/vue3';
import { createPinia } from 'pinia'
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
// import {Head} from '@inertiajs/vue3'; // this is used for setting the title of web page(the <Head> tag).


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const pinia = createPinia()

import i18n from "./i18n";


createInertiaApp({
    url: window.location.pathname,
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(i18n)
            .mixin({ methods: { route } })
            .component('InertiaLink', Link)
            .mount(el)
    },
})
