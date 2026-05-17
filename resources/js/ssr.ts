import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createSSRApp, h } from 'vue';
import type { Config, RouteParams } from 'ziggy-js';
import { route as ziggyRoute } from 'ziggy-js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./pages/**/*.vue'),
            ),
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) });

            // Configure Ziggy for SSR...
            const ziggy = page.props.ziggy as Record<string, unknown> & {
                location: string;
            };
            const ziggyConfig = {
                ...ziggy,
                location: new URL(ziggy.location),
            } as unknown as Config;

            // Create route function...
            const route = (
                name: string,
                params?: object,
                absolute?: boolean,
            ): string =>
                ziggyRoute(
                    name,
                    params as RouteParams<string>,
                    absolute,
                    ziggyConfig,
                );

            // Make route function available globally...
            app.config.globalProperties.route = route as typeof ziggyRoute;

            // Make route function available globally for SSR...
            if (typeof window === 'undefined') {
                (globalThis as Record<string, unknown>).route = route;
            }

            app.use(plugin);

            return app;
        },
    }),
);
