import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { createSSRApp, h } from 'vue';
import { createPinia } from 'pinia';
import { DefineComponent } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => (title ? `${title} - ${appName}` : appName),

        layout: (name) => {
            switch (true) {
                case name === 'Welcome':
                    return null;
                case name.startsWith('auth/'):
                    return AuthLayout;
                case name.startsWith('settings/'):
                    return [AppLayout, SettingsLayout];
                default:
                    return AppLayout;
            }
        },

        resolve: (name) => {
            const pages = import.meta.glob<{ default: DefineComponent }>('./pages/**/*.vue', { eager: true });
            return pages[`./pages/${name}.vue`];
        },

        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(createPinia());
        },
    }),
);