<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Mobile Header -->
    <header class="lg:hidden sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-slate-200">
      <div class="px-4 sm:px-6 h-14 flex items-center justify-between">
        <button
          class="h-12 w-12 rounded-xl bg-black text-white grid place-items-center shadow-sm flex-shrink-0 active:scale-[0.98] transition"
          @click="toggleSidebar"
          aria-label="Abrir menú"
        >
          <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div class="flex items-center gap-2">
          <img src="/images/logo.png" alt="Logo RUPAL" class="h-8 w-8 object-contain" />
          <span class="text-sm font-semibold text-slate-800">Sistema RUPAL</span>
        </div>

        <div class="h-8 w-8 rounded-lg bg-slate-100 grid place-items-center text-slate-700 text-xs font-semibold">
          {{ (user?.name || 'U').slice(0, 1).toUpperCase() }}
        </div>
      </div>
    </header>

    <!-- Mobile Drawer -->
    <div v-if="isSidebarOpen" class="lg:hidden fixed inset-0 z-30">
      <div class="absolute inset-0 bg-black/40" @click="closeSidebar" aria-hidden="true"></div>

      <aside class="absolute left-0 top-0 h-full w-56 bg-black text-white flex flex-col">
        <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
          <div class="flex items-center gap-3">
            <img src="/images/logo.png" alt="Logo RUPAL" class="h-14 w-14 object-contain rounded-2xl" />
            <div>
              <p class="text-xs uppercase tracking-[0.25em] text-slate-300/70">Sistema RUPAL</p>
            </div>
          </div>

          <button class="h-10 w-10 rounded-lg bg-white/10 grid place-items-center" @click="closeSidebar" aria-label="Cerrar menú">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <nav class="flex flex-col flex-1 px-4 py-6 text-sm">
          <button
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 ring-1 ring-white/10 text-white font-medium hover:bg-blue-600 hover:ring-blue-500 transition-all duration-200"
            @click="navigate('/staff/dashboard')"
          >
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1z" />
            </svg>
            Inicio
          </button>

          <button
            class="mt-2 w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-200/90 hover:bg-blue-600 hover:text-white transition-all duration-200"
            @click="navigate('/staff/productores')"
          >
            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 4a3 3 0 100-6" />
            </svg>
            Productores
          </button>

          <button
            class="mt-2 w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-200/90 transition-all duration-200"
            :class="isAdmin ? 'hover:bg-blue-600 hover:text-white cursor-pointer' : 'opacity-50 cursor-not-allowed bg-slate-800/50'"
            :disabled="!isAdmin"
            @click="isAdmin && navigate('/staff/users/create')"
          >
            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M8.5 7a4 4 0 118 0 4 4 0 01-8 0zM20 8v6m3-3h-6" />
            </svg>
            Agregar usuario
          </button>

          <div class="mt-auto pt-8 pb-4">
            <button
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-semibold shadow-md
                     transition-all duration-200 hover:bg-red-600 hover:shadow-lg active:bg-red-700 active:scale-[0.99]"
              @click="logout"
            >
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
              </svg>
              Cerrar sesión
            </button>
          </div>
        </nav>
      </aside>
    </div>

    <div class="flex min-h-screen">
      <!-- Desktop Sidebar -->
      <aside class="hidden lg:flex w-64 shrink-0 min-h-screen flex-col bg-black text-white">
        <div class="px-6 py-6">
          <div class="flex flex-col items-center gap-2">
            <img src="/images/logo.png" alt="Logo RUPAL" class="h-32 w-32 object-contain rounded-3xl" />
            <p class="text-base font-semibold text-white">Sistema Integrado RUPAL</p>
          </div>
        </div>

        <nav class="flex flex-col flex-1 px-4 py-6 text-sm">
          <button
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 ring-1 ring-white/10 text-white font-medium hover:bg-blue-600 hover:ring-blue-500 transition-all duration-200"
            @click="navigate('/staff/dashboard')"
          >
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1z" />
            </svg>
            Inicio
          </button>

          <button
            class="mt-2 w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-200/90 hover:bg-blue-600 hover:text-white transition-all duration-200"
            @click="navigate('/staff/productores')"
          >
            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 4a3 3 0 100-6" />
            </svg>
            Productores
          </button>

          <button
            class="mt-2 w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-200/90 transition-all duration-200"
            :class="isAdmin ? 'hover:bg-blue-600 hover:text-white cursor-pointer' : 'opacity-50 cursor-not-allowed bg-slate-800/50'"
            :disabled="!isAdmin"
            @click="isAdmin && navigate('/staff/users/create')"
          >
            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M8.5 7a4 4 0 118 0 4 4 0 01-8 0zM20 8v6m3-3h-6" />
            </svg>
            Agregar usuario
          </button>

          <div class="mt-auto pt-8 pb-4">
            <button
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-800 text-white font-semibold shadow-md
                     transition-all duration-200 hover:bg-red-600 hover:shadow-lg active:bg-red-700 active:scale-[0.99]"
              @click="logout"
            >
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
              </svg>
              Cerrar sesión
            </button>
          </div>
        </nav>
      </aside>

      <!-- Content -->
      <div class="flex-1 min-w-0">
        <header class="hidden lg:block sticky top-0 z-10 bg-white/70 backdrop-blur border-b border-slate-200">
          <div class="max-w-6xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-end">
            <div class="flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white">
              <div class="h-7 w-7 rounded-lg bg-slate-100 grid place-items-center text-slate-700 text-xs font-semibold">
                {{ (user?.name || 'U').slice(0, 1).toUpperCase() }}
              </div>
              <span class="text-sm text-slate-700">{{ user?.name }}</span>
            </div>
          </div>
        </header>

        <!-- ✅ Slot: acá va el contenido de cada vista -->
        <main class="px-4 sm:px-6 lg:px-8 py-8">
          <div class="max-w-6xl mx-auto">
            <slot />
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  user: { type: Object, required: true },
})

const isAdmin = computed(() => props.user?.role === 'admin')
const isSidebarOpen = ref(false)

const toggleSidebar = () => (isSidebarOpen.value = !isSidebarOpen.value)
const closeSidebar = () => (isSidebarOpen.value = false)

const navigate = (path) => {
  closeSidebar()
  router.visit(path)
}

const logout = () => {
  closeSidebar()
  router.post('/staff/logout')
}
</script>
