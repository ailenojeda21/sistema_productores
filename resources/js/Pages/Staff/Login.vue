<script setup>
// 1. Imports
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// 4. Reactive state
const isLoading = ref(false)
const formData = ref({
  email: '',
  password: ''
})
const validationErrors = ref({})
const errorMessage = ref('')

// 6. Methods
const submit = async (e) => {
  e.preventDefault()
  isLoading.value = true
  validationErrors.value = {}
  errorMessage.value = ''
  try {
    await router.post('/staff/login', formData.value, {
      onError: (errors) => {
        validationErrors.value = errors
      },
      onSuccess: () => {
        // Redirige al dashboard o página principal del staff
        router.visit('/staff/dashboard')
      }
    })
  } catch (error) {
    errorMessage.value = 'Error al iniciar sesión'
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
          Bienvenido al Administrador del Sistema RUPAL
        </h1>
        <p class="text-gray-500 text-sm">Por favor ingrese sus credenciales para continuar</p>
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
            autocomplete="username"
            :disabled="isLoading"
          />
          <div v-if="validationErrors.email" class="text-red-500 text-xs mt-1">
            {{ validationErrors.email }}
          </div>
        </div>
        <div class="mb-6">
          <label for="password" class="block text-gray-700 font-medium mb-1">Contraseña</label>
          <input
            id="password"
            type="password"
            v-model="formData.password"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="********"
            autocomplete="current-password"
            :disabled="isLoading"
          />
          <div v-if="validationErrors.password" class="text-red-500 text-xs mt-1">
            {{ validationErrors.password }}
          </div>
        </div>
        <button
          type="submit"
          class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center"
          :disabled="isLoading"
        >
          <span v-if="isLoading" class="loader mr-2"></span>
          Iniciar sesión
        </button>
        <div class="mt-4 text-center">
          <a href="/staff/forgot-password" class="text-sm text-blue-600 hover:text-blue-800 underline">
            ¿Olvidaste tu contraseña?
          </a>
        </div>
        <div v-if="errorMessage" class="text-red-600 text-sm mt-4 text-center">
          {{ errorMessage }}
        </div>
      </form>
      <footer class="mt-6 text-center text-xs text-gray-400">
        &copy; {{ new Date().getFullYear() }} RUPAL. Todos los derechos reservados.
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
