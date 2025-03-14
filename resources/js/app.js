import '../css/app.css';
import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/src/js';
import { Ziggy } from './ziggy';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app =  createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy);
            

            app.mount(el);
            AOS.init();

            delete el.dataset.page;
            app.mixin({
                mounted() {
                    AOS.refresh();
                },
            });

            return app;
    },
    progress: {
        color: '#4B5563',
    },
});
