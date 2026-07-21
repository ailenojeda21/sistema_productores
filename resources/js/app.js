import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Mount OnboardingGuide on Blade dashboard pages
import OnboardingGuide from './Components/OnboardingGuide.vue';

const onboardingEl = document.querySelector('[data-onboarding]');
if (onboardingEl) {
    createApp(OnboardingGuide, {
        profileCompleteness: Number(onboardingEl.dataset.profile),
        propiedadesCompleteness: Number(onboardingEl.dataset.propiedades),
        cultivosCompleteness: Number(onboardingEl.dataset.cultivos),
        maquinariasCompleteness: Number(onboardingEl.dataset.maquinarias),
        comercializacionCompleteness: Number(onboardingEl.dataset.comercializacion),
        profileUrl: onboardingEl.dataset.urlProfile,
        propiedadesUrl: onboardingEl.dataset.urlPropiedades,
        cultivosUrl: onboardingEl.dataset.urlCultivos,
        maquinariaUrl: onboardingEl.dataset.urlMaquinaria,
        comerciosUrl: onboardingEl.dataset.urlComercios,
    }).mount(onboardingEl);
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
