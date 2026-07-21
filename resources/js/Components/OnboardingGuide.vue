<script setup>
import { ref, computed, watch, onMounted } from 'vue'

const props = defineProps({
    profileCompleteness: { type: Number, default: 0 },
    propiedadesCompleteness: { type: Number, default: 0 },
    cultivosCompleteness: { type: Number, default: 0 },
    maquinariasCompleteness: { type: Number, default: 0 },
    comercializacionCompleteness: { type: Number, default: 0 },
    profileUrl: { type: String, default: '' },
    propiedadesUrl: { type: String, default: '' },
    cultivosUrl: { type: String, default: '' },
    maquinariaUrl: { type: String, default: '' },
    comerciosUrl: { type: String, default: '' },
})

const steps = [
    { step: 1, key: 'profile', title: 'Completa tu perfil', description: 'Completa tu información personal para comenzar a utilizar el sistema.', url: props.profileUrl, isComplete: () => props.profileCompleteness === 100 },
    { step: 2, key: 'propiedades', title: 'Registra tu primera propiedad', description: 'Ahora registra al menos una propiedad para continuar.', url: props.propiedadesUrl, isComplete: () => props.propiedadesCompleteness === 100 },
    { step: 3, key: 'cultivos', title: 'Completa tus cultivos', description: 'Agrega los cultivos de tus propiedades.', url: props.cultivosUrl, isComplete: () => props.cultivosCompleteness === 100 },
    { step: 4, key: 'maquinarias', title: 'Registra tu maquinaria', description: 'Registra la maquinaria disponible.', url: props.maquinariaUrl, isComplete: () => props.maquinariasCompleteness === 100 },
    { step: 5, key: 'comercializacion', title: 'Completa tu comercialización', description: 'Indica cómo comercializas tu producción.', url: props.comerciosUrl, isComplete: () => props.comercializacionCompleteness === 100 },
]

const currentStep = computed(() => steps.find(s => !s.isComplete()) || null)
const isDismissed = ref(true)
const transitioning = ref(false)

const show = computed(() => currentStep.value !== null && !isDismissed.value)

onMounted(() => {
    if (currentStep.value) {
        const stored = localStorage.getItem(`onboarding_dismissed_${currentStep.value.key}`)
        isDismissed.value = !!stored
    }
})

watch(currentStep, (next, prev) => {
    if (!next) return
    if (!prev || next.key !== prev.key) {
        const stored = localStorage.getItem(`onboarding_dismissed_${next.key}`)
        isDismissed.value = !!stored
        transitioning.value = true
        setTimeout(() => { transitioning.value = false }, 500)
    }
})

function dismiss() {
    isDismissed.value = true
    if (currentStep.value) {
        localStorage.setItem(`onboarding_dismissed_${currentStep.value.key}`, 'true')
    }
}

function go() {
    if (currentStep.value?.url) {
        window.location.href = currentStep.value.url
    }
}
</script>

<template>
    <div v-if="currentStep" class="relative" style="min-height: 0">
        <Transition name="onboarding">
            <div v-if="show" class="bg-white rounded-lg shadow-lg p-4 border-l-4 border-naranja-oscuro">
                <button
                    @click="dismiss"
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors"
                    aria-label="Cerrar"
                >
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>

                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-naranja-oscuro text-xl">notifications</span>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Paso {{ currentStep.step }} de 5</span>
                </div>

                <h3 class="text-sm font-bold text-gray-800 mb-1 pr-6">{{ currentStep.title }}</h3>

                <p class="text-xs text-gray-600 mb-3">{{ currentStep.description }}</p>

                <button
                    @click="go"
                    class="w-full py-2 px-4 bg-naranja-oscuro text-white text-sm font-semibold rounded hover:bg-opacity-90 transition"
                >
                    Ir ahora
                </button>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.onboarding-enter-active {
    transition: all 0.4s ease-out;
}
.onboarding-leave-active {
    transition: all 0.25s ease-in;
}
.onboarding-enter-from {
    opacity: 0;
    transform: translateY(-12px);
}
.onboarding-leave-to {
    opacity: 0;
    transform: translateY(-12px);
}
</style>
