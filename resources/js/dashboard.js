import './bootstrap';
import { createApp } from 'vue';
import OnboardingGuide from './Components/OnboardingGuide.vue';

document.querySelectorAll('[data-onboarding]').forEach(el => {
    createApp(OnboardingGuide, {
        profileCompleteness: Number(el.dataset.profile),
        propiedadesCompleteness: Number(el.dataset.propiedades),
        cultivosCompleteness: Number(el.dataset.cultivos),
        maquinariasCompleteness: Number(el.dataset.maquinarias),
        comercializacionCompleteness: Number(el.dataset.comercializacion),
        profileUrl: el.dataset.urlProfile,
        propiedadesUrl: el.dataset.urlPropiedades,
        cultivosUrl: el.dataset.urlCultivos,
        maquinariaUrl: el.dataset.urlMaquinaria,
        comerciosUrl: el.dataset.urlComercios,
    }).mount(el);
});
