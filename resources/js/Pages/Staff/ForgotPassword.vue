<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const isLoading = ref(false)
const formData = ref({
  email: ''
})
const validationErrors = ref({})

const submit = async (e) => {
  e.preventDefault()
  isLoading.value = true
  validationErrors.value = {}
  try {
    await router.post('/staff/forgot-password', formData.value, {
      onError: (errors) => {
        validationErrors.value = errors
      },
      preserveState: true,
    })
  } catch {
    // handled by Inertia
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
      <header class="mb-6 text-center">
        <img src="/images/encabezado-azul.png" alt="RUPAL Logo" class="mx-auto mb-4 w-full max-w-md h-auto rounded-xl shadow" />
        <h1 class="text-2xl font-bold text-gray-900 mb-2">
          Recuperar contraseña
        </h1>
        <p class="text-gray-500 text-sm">
          Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
        </p>
      </header>

      <div v-if="page.props.flash?.status" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2 text-sm">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <span>{{ page.props.flash.status }}</span>
      </div>

      <form @submit="submit">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 font-medium mb-1">Correo electrónico</label>
          <input
            id="email"
            type="email"
            v-model="formData.email"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="usuario@rupal.com"
            autocomplete="email"
            :disabled="isLoading"
          />
          <div v-if="validationErrors.email" class="text-red-500 text-xs mt-1">
            {{ validationErrors.email }}
          </div>
        </div>
        <button
          type="submit"
          class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center"
          :disabled="isLoading"
        >
          <span v-if="isLoading" class="loader mr-2"></span>
          Enviar enlace de recuperación
        </button>
      </form>
      <footer class="mt-6 text-center">
        <a href="/staff/login" class="text-sm text-blue-600 hover:text-blue-800 underline">
          Volver a iniciar sesión
        </a>
      </footer>
    </div>
  </div>
</template>

<style scoped>
.loader {
  border: 2px solid #e5e7eb;
  border-top: 2px solid #2563eb;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  animation: spin 0.8s linear infinite;
  display: inline-block;
  vertical-align: middle;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
