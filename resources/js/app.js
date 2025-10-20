import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { url } from '@src/utils';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin);

        // Helper functions
        app.config.globalProperties.url = url;

        Date.prototype.addDays = function (days) {
            let date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);

            return date;
        }

        Date.prototype.subDays = function (days) {
            let date = new Date(this.valueOf());
            date.setDate(date.getDate() - days);

            return date;
        }

        Object.defineProperties(Date, {
            MIN_VALUE: {
                value: -864000000000000
            },
            MAX_VALUE: {
                value: 864000000000000
            }
        });

        window.url = url;

        app.mount(el);
    },
});
