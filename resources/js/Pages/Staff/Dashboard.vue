<template>
  <div class="min-h-screen bg-slate-50">
    <div class="flex">
      <!-- Sidebar -->
     <aside class="hidden lg:flex w-72 shrink-0 min-h-screen flex-col bg-black text-white">

        <!-- Brand -->
        <div class="px-6 py-6 border-b border-white/10">
          <p class="text-[11px] uppercase tracking-[0.25em] text-slate-300/70">Sistema RUPAL</p>
          <div class="mt-2 flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-white/10 ring-1 ring-white/10 grid place-items-center">
             
            </div>
         
          </div>
        </div>

       <!-- Nav -->
<nav class="flex flex-col flex-1 px-4 py-6 space-y-2 text-sm">
  <!-- Inicio -->
  <button
    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 ring-1 ring-white/10 text-white font-medium"
    @click="$inertia.visit('/staff/dashboard')"
  >
    <span class="h-2 w-2 rounded-full bg-emerald-400" />
    Inicio
  </button>

  <!-- Productores -->
  <button
    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-200/90 hover:bg-white/10 hover:text-white transition"
    @click="$inertia.visit('/staff/productores')"
  >
    <span class="h-2 w-2 rounded-full bg-slate-500" />
    Productores
  </button>

  <!-- Agregar usuario -->
  <button
    v-if="isAdmin"
    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-200/90 hover:bg-white/10 hover:text-white transition"
    @click="$inertia.visit('/staff/users/create')"
  >
    <span class="h-2 w-2 rounded-full bg-slate-500" />
    Agregar usuario
  </button>

  <!-- Spacer automático -->
  <div class="mt-auto pt-4">
    <!-- Cerrar sesión -->
    <button
      class="w-full flex items-center gap-3 px-4 py-3 rounded-xl
             bg-blue-600 text-white hover:bg-blue-700 transition
             focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      @click="$inertia.post('/staff/logout')"
    >
      <span class="h-2 w-2 rounded-full bg-white" />
      Cerrar sesión
    </button>
  </div>
</nav>

        <!-- User / Logout -->
      
      </aside>

      <!-- Content -->
      <div class="flex-1 min-w-0">
        <!-- Header simple -->
        <header class="sticky top-0 z-10 bg-white/70 backdrop-blur border-b border-slate-200">
          <div class="max-w-6xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white">
                <div class="h-7 w-7 rounded-lg bg-slate-100 grid place-items-center text-slate-700 text-xs font-semibold">
                  {{ (user?.name || 'U').slice(0, 1).toUpperCase() }}
                </div>
                <span class="text-sm text-slate-700">{{ user?.name }}</span>
              </div>


            </div>
          </div>
        </header>

        <!-- Body simple -->
        <main class="px-6 lg:px-8 py-10">
          <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
              <h2 class="text-2xl font-semibold text-slate-900">{{ title }}</h2>
              <p class="text-slate-600 mt-2">
                Esta es una vista básica sin gráficos. Acá podés renderizar tu contenido según la opción del menú.
              </p>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  user: { type: Object, required: true },
  // opcional: si querés cambiar el título desde el backend
  pageTitle: { type: String, default: 'Panel de administración' },
 
})

const isAdmin = computed(() => props.user?.role === 'admin')
const title = computed(() => props.pageTitle || 'Panel de administración')

</script>
