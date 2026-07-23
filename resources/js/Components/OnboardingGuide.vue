<script setup>
import { ref, computed, watch, onMounted } from 'vue'

const DISMISS_DURATION = 24 * 60 * 60 * 1000
const CONGRATULATIONS_KEY = 'congratulations'

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
const allComplete = computed(() => steps.every(s => s.isComplete()))
const isDismissed = ref(true)
const isCongratulationsDismissed = ref(true)
const transitioning = ref(false)

const show = computed(() => currentStep.value !== null && !isDismissed.value)
const showCongratulations = computed(() => allComplete.value && !isCongratulationsDismissed.value)

function shouldShowCurrentStep() {
    if (!currentStep.value) return false
    const key = `onboarding_dismissed_${currentStep.value.key}`
    const stored = localStorage.getItem(key)
    if (!stored) return true
    if (Date.now() - Number(stored) > DISMISS_DURATION) {
        localStorage.removeItem(key)
        return true
    }
    return false
}

function shouldShowCongratulationsCard() {
    if (!allComplete.value) return false
    const key = `onboarding_dismissed_${CONGRATULATIONS_KEY}`
    const stored = localStorage.getItem(key)
    if (!stored) return true
    if (Date.now() - Number(stored) > DISMISS_DURATION) {
        localStorage.removeItem(key)
        return true
    }
    return false
}

function updateDismissedState() {
    steps.forEach(s => {
        if (s.isComplete()) {
            localStorage.removeItem(`onboarding_dismissed_${s.key}`)
        }
    })
    isDismissed.value = !shouldShowCurrentStep()
    isCongratulationsDismissed.value = !shouldShowCongratulationsCard()
}

onMounted(updateDismissedState)

watch(currentStep, (next, prev) => {
    if (!next) return
    if (!prev || next.key !== prev.key) {
        updateDismissedState()
        transitioning.value = true
        setTimeout(() => { transitioning.value = false }, 500)
    }
})

function dismiss() {
    isDismissed.value = true
    if (currentStep.value) {
        localStorage.setItem(`onboarding_dismissed_${currentStep.value.key}`, String(Date.now()))
    }
}

function dismissCongratulations() {
    isCongratulationsDismissed.value = true
    localStorage.setItem(`onboarding_dismissed_${CONGRATULATIONS_KEY}`, String(Date.now()))
}

function go() {
    if (currentStep.value?.url) {
        window.location.href = currentStep.value.url
    }
}
</script>

<template>
    <div v-if="currentStep || allComplete" class="relative" style="min-height: 0">
        <Transition name="onboarding">
            <div v-if="show && currentStep" class="bg-white rounded-lg shadow-lg p-4 border-l-4 border-naranja-oscuro">
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

            <div v-else-if="showCongratulations" class="bg-white rounded-lg shadow-lg p-4 border-l-4 border-green-500">
                <button
                    @click="dismissCongratulations"
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors"
                    aria-label="Cerrar"
                >
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>

                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-green-500 text-xl">check_circle</span>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">¡Completado!</span>
                </div>

                <h3 class="text-sm font-bold text-gray-800 mb-1 pr-6">¡Felicidades! Completaste tu perfil</h3>

                <p class="text-xs text-gray-600 mb-3">Todos los datos de tu perfil están completos.</p>

                <button
                    class="w-full py-2 px-4 bg-naranja-oscuro text-white text-sm font-semibold rounded hover:bg-opacity-90 transition"
                >
                    Descargar comprobante de registro
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
