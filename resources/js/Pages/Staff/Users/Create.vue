<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import StaffLayout from '@/Layouts/StaffLayout.vue'

const props = defineProps({
  user: { type: Object, required: true },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'auditor'
})

const showPassword = ref(false)
const isSubmitting = ref(false)

const submitForm = () => {
  isSubmitting.value = true
  
  router.post('/staff/users', form, {
    onFinish: () => {
      isSubmitting.value = false
    },
    onSuccess: () => {
      router.visit('/staff/dashboard')
    }
  })
}

const goBack = () => {
  router.visit('/staff/dashboard')
}
</script>

<template>
  <StaffLayout :user="user">
    <div class="w-full max-w-4xl mx-auto">
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <!-- Header -->
        <div class="px-6 py-3 border-b border-slate-100 flex items-center gap-3">
          <button
            class="h-8 w-8 rounded-lg border border-slate-200 bg-white grid place-items-center text-slate-700 hover:bg-slate-50 transition"
            @click="goBack"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Crear Nuevo Usuario</h2>
            <p class="text-xs text-slate-500">Complete los datos del nuevo usuario staff</p>
          </div>
        </div>

        <form @submit.prevent="submitForm" class="p-5">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Columna izquierda: Datos personales -->
            <div class="space-y-3">
              <!-- Nombre -->
              <div class="space-y-1">
                <label for="name" class="block text-sm font-medium text-slate-700">
                  Nombre completo <span class="text-red-500">*</span>
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                  :class="{ 'border-red-500': errors.name }"
                  placeholder="Ej: Juan Pérez"
                >
                <p v-if="errors.name" class="text-xs text-red-600">{{ errors.name }}</p>
              </div>

              <!-- Email -->
              <div class="space-y-1">
                <label for="email" class="block text-sm font-medium text-slate-700">
                  Correo electrónico <span class="text-red-500">*</span>
                </label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                  :class="{ 'border-red-500': errors.email }"
                  placeholder="ejemplo@correo.com"
                >
                <p v-if="errors.email" class="text-xs text-red-600">{{ errors.email }}</p>
              </div>

              <!-- Contraseñas en grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Contraseña -->
                <div class="space-y-1">
                  <label for="password" class="block text-sm font-medium text-slate-700">
                    Contraseña <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input
                      id="password"
                      v-model="form.password"
                      :type="showPassword ? 'text' : 'password'"
                      required
                      minlength="8"
                      class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm pr-10"
                      :class="{ 'border-red-500': errors.password }"
                      placeholder="Mín. 8 caracteres"
                    >
                    <button
                      type="button"
                      class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                      @click="showPassword = !showPassword"
                    >
                      <svg v-if="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                      </svg>
                      <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                  </div>
                  <p v-if="errors.password" class="text-xs text-red-600">{{ errors.password }}</p>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="space-y-1">
                  <label for="password_confirmation" class="block text-sm font-medium text-slate-700">
                    Confirmar <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    :type="showPassword ? 'text' : 'password'"
                    required
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                    :class="{ 'border-red-500': errors.password_confirmation }"
                    placeholder="Repita"
                  >
                  <p v-if="errors.password_confirmation" class="text-xs text-red-600">{{ errors.password_confirmation }}</p>
                </div>
              </div>
              <p class="text-xs text-slate-500 -mt-1">La contraseña debe tener al menos 8 caracteres</p>
            </div>

            <!-- Columna derecha: Rol -->
            <div class="space-y-3">
              <div class="space-y-1">
                <label class="block text-sm font-medium text-slate-700">
                  Rol del usuario <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                  <!-- Auditor -->
                  <label
                    class="flex items-start gap-3 p-3 rounded-lg border-2 cursor-pointer transition-all duration-200"
                    :class="form.role === 'auditor' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:border-blue-300'"
                  >
                    <input
                      v-model="form.role"
                      type="radio"
                      value="auditor"
                      class="w-4 h-4 mt-0.5 text-blue-600 focus:ring-blue-500"
                    >
                    <div class="flex-1 min-w-0">
                      <div class="font-medium text-slate-900 text-sm">Auditor</div>
                      <div class="text-xs text-slate-500">Puede consultar y exportar datos de productores</div>
                    </div>
                  </label>

                  <!-- Administrador -->
                  <label
                    class="flex items-start gap-3 p-3 rounded-lg border-2 cursor-pointer transition-all duration-200"
                    :class="form.role === 'admin' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:border-blue-300'"
                  >
                    <input
                      v-model="form.role"
                      type="radio"
                      value="admin"
                      class="w-4 h-4 mt-0.5 text-blue-600 focus:ring-blue-500"
                    >
                    <div class="flex-1 min-w-0">
                      <div class="font-medium text-slate-900 text-sm">Administrador</div>
                      <div class="text-xs text-slate-500">Acceso completo, gestión de usuarios y configuración</div>
                    </div>
                  </label>
                </div>
                <p v-if="errors.role" class="text-xs text-red-600">{{ errors.role }}</p>
              </div>

              <!-- Resumen de permisos -->
              <div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
                <h4 class="text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Permisos del rol seleccionado</h4>
                <ul class="space-y-1 text-xs text-slate-600">
                  <li v-if="form.role === 'auditor'" class="flex items-center gap-2">
                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Ver productores y sus datos
                  </li>
                  <li v-if="form.role === 'auditor'" class="flex items-center gap-2">
                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Exportar reportes
                  </li>
                  <li v-if="form.role === 'auditor'" class="flex items-center gap-2 opacity-50">
                    <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="line-through">Gestionar usuarios</span>
                  </li>
                  <li v-if="form.role === 'admin'" class="flex items-center gap-2">
                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Todos los permisos de auditor
                  </li>
                  <li v-if="form.role === 'admin'" class="flex items-center gap-2">
                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Crear, editar y eliminar usuarios
                  </li>
                  <li v-if="form.role === 'admin'" class="flex items-center gap-2">
                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Configuración del sistema
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Botones -->
          <div class="flex flex-col sm:flex-row gap-3 pt-4 mt-4 border-t border-slate-200">
            <button
              type="button"
              class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition text-sm order-2 sm:order-1"
              @click="goBack"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="flex-1 px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition shadow-md text-sm order-1 sm:order-2"
              :disabled="isSubmitting"
              :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }"
            >
              <span v-if="isSubmitting" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creando...
              </span>
              <span v-else>Crear Usuario</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </StaffLayout>
</template>
