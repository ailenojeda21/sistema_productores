<template>
  <StaffLayout :user="authUser">
    <div class="max-w-6xl mx-auto space-y-6">
      <!-- Header with back button and print -->
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <button
            @click="router.visit('/staff/producers')"
            class="h-10 w-10 rounded-xl border border-slate-200 bg-white grid place-items-center text-slate-700 hover:bg-slate-50 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <nav class="flex items-center text-sm text-slate-500">
            <button @click="router.visit('/staff/dashboard')" class="hover:text-slate-800 transition">Dashboard</button>
            <span class="mx-2">/</span>
            <button @click="router.visit('/staff/producers')" class="hover:text-slate-800 transition">Productores</button>
            <span class="mx-2">/</span>
            <span class="text-slate-800 font-medium">{{ producer.name }}</span>
          </nav>
        </div>
        <button
          @click="printReport"
          class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-md"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          Imprimir / Guardar PDF
        </button>
      </div>

      <!-- Report Content for Printing -->
      <div id="printable-report" class="space-y-6">
        <!-- Header Info -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
          <div class="flex items-start gap-4">
            <div class="h-16 w-16 rounded-2xl bg-blue-100 grid place-items-center text-blue-600">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div class="flex-1">
              <h1 class="text-2xl font-bold text-slate-900">{{ producer.name }}</h1>
              <div class="mt-2 flex flex-wrap gap-4 text-sm text-slate-600">
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                  </svg>
                  DNI: {{ producer.dni || 'No registrado' }}
                </span>
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  {{ producer.email }}
                </span>
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  {{ producer.telefono || 'No registrado' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistics Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ stats.propiedades }}</div>
            <div class="text-sm text-slate-600">Propiedades</div>
          </div>
          <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ stats.cultivos }}</div>
            <div class="text-sm text-slate-600">Cultivos</div>
          </div>
          <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-orange-600">{{ stats.maquinarias }}</div>
            <div class="text-sm text-slate-600">Maquinarias</div>
          </div>
          <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ stats.hectareas }} ha</div>
            <div class="text-sm text-slate-600">Total Hectáreas</div>
          </div>
        </div>

        <!-- Propiedades Section -->
        <div v-if="propiedades.length" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Propiedades ({{ propiedades.length }})
            </h2>
          </div>
          <div class="divide-y divide-slate-200">
            <div v-for="prop in propiedades" :key="prop.id" class="px-6 py-4">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-semibold text-slate-900">{{ prop.direccion }}</h3>
                  <p class="text-sm text-slate-600 mt-1">
                    {{ prop.hectareas }} ha · {{ prop.tipo_tenencia || 'Sin tipo' }}
                  </p>
                </div>
                <div class="text-right text-sm">
                  <div v-if="prop.derecho_riego" class="text-green-600">✓ Riego</div>
                  <div v-if="prop.malla" class="text-green-600">✓ Malla</div>
                  <div v-if="prop.cierre_perimetral" class="text-green-600">✓ Cierre</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cultivos Section -->
        <div v-if="cultivos.length" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              Cultivos ({{ cultivos.length }})
            </h2>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
              <thead class="bg-slate-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nombre</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tipo</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Hectáreas</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Manejo</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Riego</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">
                <tr v-for="cult in cultivos" :key="cult.id">
                  <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ cult.nombre }}</td>
                  <td class="px-6 py-4 text-sm text-slate-600">{{ cult.tipo }}</td>
                  <td class="px-6 py-4 text-sm text-slate-600">{{ cult.hectareas }}</td>
                  <td class="px-6 py-4 text-sm text-slate-600">{{ cult.manejo_cultivo }}</td>
                  <td class="px-6 py-4 text-sm text-slate-600">{{ cult.tecnologia_riego || '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Maquinarias Section -->
        <div v-if="maquinarias.length" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
              <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Maquinarias ({{ maquinarias.length }})
            </h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
            <div v-for="maq in maquinarias" :key="maq.id" class="border border-slate-200 rounded-xl p-4">
              <div class="font-semibold text-slate-900">{{ maq.nombre }}</div>
              <div class="text-sm text-slate-600 mt-1">{{ maq.tipo }}</div>
              <div class="text-xs text-slate-500 mt-2">
                Estado: <span :class="maq.estado === 'Propio' ? 'text-green-600' : 'text-blue-600'">{{ maq.estado }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Comercio Section -->
        <div v-if="comercio" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              Comercialización
            </h2>
          </div>
          <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="flex justify-between py-2 border-b border-slate-100">
                <span class="text-slate-600">Infraestructura:</span>
                <span :class="comercio.infraestructura_empaque ? 'text-green-600 font-semibold' : 'text-slate-400'">
                  {{ comercio.infraestructura_empaque ? 'Sí' : 'No' }}
                </span>
              </div>
              <div class="flex justify-between py-2 border-b border-slate-100">
                <span class="text-slate-600">Vende en finca:</span>
                <span :class="comercio.vende_en_finca ? 'text-green-600 font-semibold' : 'text-slate-400'">
                  {{ comercio.vende_en_finca ? 'Sí' : 'No' }}
                </span>
              </div>
            </div>
            <div v-if="comercio.mercados && comercio.mercados.length">
              <h4 class="text-sm font-semibold text-slate-700 mb-2">Mercados:</h4>
              <div class="flex flex-wrap gap-2">
                <span v-for="mercado in comercio.mercados" :key="mercado" class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                  {{ mercado }}
                </span>
              </div>
            </div>
            <div v-if="comercio.cooperativas && comercio.cooperativas.length">
              <h4 class="text-sm font-semibold text-slate-700 mb-2">Cooperativas:</h4>
              <div class="flex flex-wrap gap-2">
                <span v-for="coop in comercio.cooperativas" :key="coop" class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">
                  {{ coop }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Cooperativas del Usuario -->
        <div v-if="producer.cooperativas && producer.cooperativas.length" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
          <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
            Pertenece a Cooperativas
          </h2>
          <div class="flex flex-wrap gap-2">
            <span v-for="coop in producer.cooperativas" :key="coop" class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm rounded-full">
              {{ coop }}
            </span>
          </div>
        </div>

        <!-- No data message -->
        <div v-if="!propiedades.length && !cultivos.length && !maquinarias.length && !comercio" class="bg-slate-50 rounded-2xl border border-slate-200 p-10 text-center">
          <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-slate-600">Este productor no tiene datos registrados en los módulos.</p>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center text-sm text-slate-500 pt-4 border-t border-slate-200">
        Reporte generado el {{ new Date().toLocaleDateString('es-AR') }} por Sistema RUPAL
      </div>
    </div>
  </StaffLayout>
</template>

<script setup>
import StaffLayout from '@/Layouts/StaffLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  authUser: { type: Object, required: true },
  producer: { type: Object, required: true },
  propiedades: { type: Array, default: () => [] },
  cultivos: { type: Array, default: () => [] },
  maquinarias: { type: Array, default: () => [] },
  comercio: { type: Object, default: null },
  stats: { 
    type: Object, 
    default: () => ({
      propiedades: 0,
      cultivos: 0,
      maquinarias: 0,
      hectareas: 0
    })
  }
})

const printReport = () => {
  window.print()
}
</script>

<style>
@media print {
  /* Ocultar sidebar y header al imprimir */
  aside, header, .fixed, nav {
    display: none !important;
  }
  
  /* Ajustar contenido para impresión */
  #printable-report {
    width: 100% !important;
    max-width: none !important;
  }
  
  /* Mostrar todo el contenido */
  .overflow-hidden, .overflow-x-auto {
    overflow: visible !important;
  }
}
</style>
