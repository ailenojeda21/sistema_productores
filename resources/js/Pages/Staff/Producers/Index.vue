<template>
  <StaffLayout :user="user">
    <div class="max-w-5xl mx-auto space-y-4">
      <!-- Breadcrumb -->
      <nav class="flex items-center text-sm text-slate-500 mb-2">
        <button 
          @click="router.visit('/staff/dashboard')" 
          class="hover:text-slate-800 transition flex items-center gap-1"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1z" />
          </svg>
          Dashboard
        </button>
        <span class="mx-2">/</span>
        <span class="text-slate-800 font-medium">Productores</span>
      </nav>

      <div>
        <h1 class="text-2xl font-bold text-slate-900">Productores</h1>
        <p class="text-sm text-slate-600">Buscar por DNI o nombre.</p>
      </div>

      <!-- Barra de búsqueda -->
      <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <input
            v-model="form.dni"
            class="w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-0"
            placeholder="DNI"
            inputmode="numeric"
          />
          <input
            v-model="form.name"
            class="w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-0"
            placeholder="Nombre"
          />
        </div>

        <div class="mt-3 flex gap-2">
          <button
            class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800"
            @click="search"
          >
            Buscar
          </button>

          <button
            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50"
            @click="clear"
          >
            Limpiar
          </button>
        </div>
      </div>

      <!-- Listado -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div v-if="producers.data && producers.data.length" class="divide-y divide-slate-100">
          <button
            v-for="p in producers.data"
            :key="p.id"
            class="w-full text-left px-4 py-3 hover:bg-slate-50"
            @click="goShow(p.id)"
          >
            <div class="font-semibold text-slate-900">{{ p.name }}</div>
            <div class="text-xs text-slate-600">DNI: {{ p.dni ?? '-' }} · {{ p.email ?? '-' }}</div>
          </button>
        </div>

        <div v-else class="px-4 py-10 text-center text-slate-500">
          Sin resultados.
        </div>

        <!-- paginación -->
        <div 
          v-if="producers.data && producers.data.length && producers.last_page > 1" 
          class="flex items-center justify-between px-4 py-3 border-t border-slate-200"
        >
          <button
            class="px-3 py-2 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50 disabled:opacity-50"
            :disabled="producers.current_page <= 1"
            @click="goPage(producers.current_page - 1)"
          >
            Anterior
          </button>

          <div class="text-xs text-slate-600">
            Página {{ producers.current_page }} de {{ producers.last_page }}
          </div>

          <button
            class="px-3 py-2 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50 disabled:opacity-50"
            :disabled="producers.current_page >= producers.last_page"
            @click="goPage(producers.current_page + 1)"
          >
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </StaffLayout>
</template>

<script setup>
import StaffLayout from '@/Layouts/StaffLayout.vue'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  user: { type: Object, required: true },
  producers: { 
    type: Object, 
    default: () => ({
      data: [],
      current_page: 1,
      last_page: 1,
    })
  },
  filters: { type: Object, default: () => ({}) },
})

const form = reactive({
  dni: props.filters.dni ?? '',
  name: props.filters.name ?? '',
})

const search = () => {
  router.get('/staff/producers', { ...form }, { preserveState: true, replace: true })
}

const clear = () => {
  form.dni = ''
  form.name = ''
  search()
}

const goPage = (pageNumber) => {
  router.get('/staff/producers', { ...form, page: pageNumber }, { preserveState: true, replace: true })
}

const goShow = (id) => router.visit(`/staff/producers/${id}`)
</script>
