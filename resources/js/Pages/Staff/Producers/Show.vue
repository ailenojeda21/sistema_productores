
  
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
              <h3 class="font-semibold text-slate-900 mb-3">{{ prop.direccion }}</h3>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Hectáreas:</span>
                  <span class="text-slate-900 font-medium text-sm">{{ prop.hectareas }} ha</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Tipo de tenencia:</span>
                  <span class="text-slate-900 text-sm">
                    {{ prop.tipo_tenencia === 'otros' && prop.especificar_tenencia 
                      ? 'otros - ' + prop.especificar_tenencia 
                      : (prop.tipo_tenencia || 'No especificado') }}
                  </span>
                </div>
                <div v-if="prop.especificar_tenencia" class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Detalle tenencia:</span>
                  <span class="text-slate-900 text-sm">{{ prop.especificar_tenencia }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Derecho de riego:</span>
                  <span :class="prop.derecho_riego ? 'text-green-600 font-semibold' : 'text-slate-400'">
                    {{ prop.derecho_riego ? 'Sí' : 'No' }}
                  </span>
                </div>
                <div v-if="prop.derecho_riego" class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Tipo de derecho:</span>
                  <span class="text-slate-900 text-sm">{{ prop.tipo_derecho_riego || 'No especificado' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Malla:</span>
                  <span :class="prop.malla ? 'text-green-600 font-semibold' : 'text-slate-400'">
                    {{ prop.malla ? 'Sí' : 'No' }}
                  </span>
                </div>
                <div v-if="prop.malla" class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Hectáreas con malla:</span>
                  <span class="text-slate-900 font-medium text-sm">{{ prop.hectareas_malla }} ha</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Cierre perimetral:</span>
                  <span :class="prop.cierre_perimetral ? 'text-green-600 font-semibold' : 'text-slate-400'">
                    {{ prop.cierre_perimetral ? 'Sí' : 'No' }}
                  </span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">RUT:</span>
                  <span :class="prop.rut ? 'text-green-600 font-semibold' : 'text-slate-400'">
                    {{ prop.rut ? 'Sí' : 'No' }}
                  </span>
                </div>
                <div v-if="prop.rut && prop.rut_valor" class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">N° RUT:</span>
                  <span class="text-slate-900 text-sm">{{ Math.floor(prop.rut_valor) }}</span>
                </div>
                <div v-if="prop.rut_archivo_url" class="flex justify-between py-1 border-b border-slate-100">
                  <span class="text-slate-500 text-sm">Archivo RUT:</span>
                  <a :href="prop.rut_archivo_url" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    Ver archivo
                  </a>
                </div>
                <div v-if="prop.lat && prop.lng" class="flex items-center gap-2 py-1 border-b border-slate-100 col-span-2 md:col-span-3">
                  <span class="text-slate-500 text-sm">Coordenadas:</span>
                  <span class="text-slate-900 text-xs font-mono">{{ prop.lat }}, {{ prop.lng }}</span>
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
          <div class="divide-y divide-slate-200">
            <div v-for="maq in maquinarias" :key="maq.id" class="px-6 py-4">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-semibold text-slate-900">
                    {{ maq.tractor ? 'Tractor' : 'Sin tractor' }}
                    <span v-if="maq.modelo_tractor" class="text-slate-600 font-normal">· Modelo {{ maq.modelo_tractor }}</span>
                  </h3>
                  <p class="text-sm text-slate-500 mt-1" v-if="maq.propiedad?.direccion">
                    {{ maq.propiedad.direccion }}
                  </p>
                </div>
              </div>
              <div class="mt-2 text-sm text-slate-600" v-if="maq.implementos?.length">
                <span class="text-slate-500">Implementos:</span> {{ maq.implementos.join(', ') }}
              </div>
              <div class="mt-2 text-sm text-slate-400" v-else>
                Sin implementos cargados
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

      <!-- PDF Report (hidden on screen, visible on print) -->
      <div id="pdf-report" class="pdf-container">
        <!-- Encabezado Institucional -->
        <div class="pdf-header">
          <div class="pdf-logo">
            <svg class="pdf-logo-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <div class="pdf-logo-text">
              <div class="pdf-logo-title">SISTEMA RUPAL</div>
              <div class="pdf-logo-subtitle">Registro Único de Productores Agropecuarios</div>
            </div>
          </div>
          <div class="pdf-fecha">
            Fecha de emisión: {{ new Date().toLocaleDateString('es-AR') }}
          </div>
        </div>

        <!-- Título Principal -->
        <div class="pdf-title-section">
          <h1 class="pdf-main-title">INFORME DETALLADO DE PRODUCTOR</h1>
          <div class="pdf-subtitle">ID: {{ producer.id }} | {{ producer.name }}</div>
        </div>

        <!-- Sección 1: Datos del Productor -->
        <div class="pdf-section">
          <h2 class="pdf-section-title">1. Datos del Productor</h2>
          <table class="pdf-data-table">
            <tbody>
              <tr>
                <td class="pdf-field-label">Nombre completo:</td>
                <td class="pdf-field-value">{{ producer.name }}</td>
                <td class="pdf-field-label">DNI:</td>
                <td class="pdf-field-value">{{ producer.dni || 'No registrado' }}</td>
              </tr>
              <tr>
                <td class="pdf-field-label">Correo electrónico:</td>
                <td class="pdf-field-value">{{ producer.email }}</td>
                <td class="pdf-field-label">Teléfono:</td>
                <td class="pdf-field-value">{{ producer.telefono || 'No registrado' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Sección 2: Resumen General -->
        <div class="pdf-section">
          <h2 class="pdf-section-title">2. Resumen General</h2>
          <table class="pdf-summary-table">
            <thead>
              <tr>
                <th>Total Propiedades</th>
                <th>Total Cultivos</th>
                <th>Total Maquinarias</th>
                <th>Total Hectáreas</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="pdf-stat-value">{{ stats.propiedades }}</td>
                <td class="pdf-stat-value">{{ stats.cultivos }}</td>
                <td class="pdf-stat-value">{{ stats.maquinarias }}</td>
                <td class="pdf-stat-value">{{ stats.hectareas }} ha</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Sección 3: Propiedades -->
        <div v-if="propiedades.length" class="pdf-section">
          <h2 class="pdf-section-title">3. Propiedades Registradas</h2>
          <div v-for="(prop, index) in propiedades" :key="prop.id" class="pdf-property-block">
            <h3 class="pdf-property-title">3.{{ index + 1 }} {{ prop.direccion }}</h3>
            <table class="pdf-property-table">
              <tbody>
                <tr>
                  <td class="pdf-field-label">Hectáreas totales:</td>
                  <td class="pdf-field-value">{{ prop.hectareas }} ha</td>
                  <td class="pdf-field-label">Tipo de tenencia:</td>
                  <td class="pdf-field-value">
                    {{ prop.tipo_tenencia === 'otros' && prop.especificar_tenencia 
                      ? 'Otros - ' + prop.especificar_tenencia 
                      : (prop.tipo_tenencia || 'No especificado') }}
                  </td>
                </tr>
                <tr>
                  <td class="pdf-field-label">Derecho de riego:</td>
                  <td class="pdf-field-value">{{ prop.derecho_riego ? 'Sí' : 'No' }}</td>
                  <td v-if="prop.derecho_riego" class="pdf-field-label">Tipo de derecho:</td>
                  <td v-if="prop.derecho_riego" class="pdf-field-value">{{ prop.tipo_derecho_riego || 'No especificado' }}</td>
                </tr>
                <tr>
                  <td class="pdf-field-label">Malla:</td>
                  <td class="pdf-field-value">{{ prop.malla ? 'Sí' : 'No' }}</td>
                  <td v-if="prop.malla" class="pdf-field-label">Hectáreas con malla:</td>
                  <td v-if="prop.malla" class="pdf-field-value">{{ prop.hectareas_malla }} ha</td>
                </tr>
                <tr>
                  <td class="pdf-field-label">Cierre perimetral:</td>
                  <td class="pdf-field-value">{{ prop.cierre_perimetral ? 'Sí' : 'No' }}</td>
                  <td class="pdf-field-label">RUT:</td>
                  <td class="pdf-field-value">{{ prop.rut ? 'Sí' : 'No' }}</td>
                </tr>
                <tr v-if="prop.rut && prop.rut_valor">
                  <td class="pdf-field-label">N° RUT:</td>
                  <td class="pdf-field-value">{{ Math.floor(prop.rut_valor) }}</td>
                  <td v-if="prop.rut_archivo_url" class="pdf-field-label">Archivo RUT:</td>
                  <td v-if="prop.rut_archivo_url" class="pdf-field-value">
                    <a :href="prop.rut_archivo_url" target="_blank" class="pdf-link">Ver archivo</a>
                  </td>
                </tr>
                <tr v-if="prop.lat && prop.lng">
                  <td class="pdf-field-label">Coordenadas:</td>
                  <td class="pdf-field-value pdf-coords" colspan="3">{{ prop.lat }}, {{ prop.lng }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Sección 4: Cultivos -->
        <div v-if="cultivos.length" class="pdf-section">
          <h2 class="pdf-section-title">4. Cultivos Registrados</h2>
          <table class="pdf-full-table">
            <thead>
              <tr>
                <th style="width: 5%">N°</th>
                <th style="width: 25%">Propiedad</th>
                <th style="width: 20%">Nombre</th>
                <th style="width: 15%">Tipo</th>
                <th style="width: 15%">Hectáreas</th>
                <th style="width: 20%">Riego</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(cult, index) in cultivos" :key="cult.id">
                <td class="pdf-cell-center">{{ index + 1 }}</td>
                <td>{{ cult.propiedad?.direccion || 'No especificada' }}</td>
                <td>{{ cult.nombre }}</td>
                <td>{{ cult.tipo }}</td>
                <td class="pdf-cell-center">{{ cult.hectareas }} ha</td>
                <td>{{ cult.tecnologia_riego || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Sección 5: Maquinarias -->
        <div v-if="maquinarias.length" class="pdf-section">
          <h2 class="pdf-section-title">5. Maquinarias por Propiedad</h2>
          <div v-for="(maq, index) in maquinarias" :key="maq.id" class="pdf-machinery-block">
            <h3 class="pdf-machinery-title">5.{{ index + 1 }} {{ maq.propiedad?.direccion || 'Propiedad no especificada' }}</h3>
            
            <table class="pdf-machinery-info-table">
              <tbody>
                <tr>
                  <td class="pdf-field-label">Tractor:</td>
                  <td class="pdf-field-value">{{ maq.tractor ? 'Sí' : 'No' }}</td>
                  <td v-if="maq.tractor && maq.modelo_tractor" class="pdf-field-label">Modelo:</td>
                  <td v-if="maq.tractor && maq.modelo_tractor" class="pdf-field-value">{{ maq.modelo_tractor }}</td>
                </tr>
              </tbody>
            </table>

            <h4 class="pdf-implementos-title">Implementos disponibles:</h4>
            <table class="pdf-implementos-table">
              <thead>
                <tr>
                  <th>Implemento</th>
                  <th style="width: 30%">Disponible</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(value, key) in maq.implementos_flags || {}" :key="key">
                  <td class="pdf-implemento-name">{{ formatImplementoName(key) }}</td>
                  <td class="pdf-cell-center">{{ value ? 'Sí' : 'No' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Sección 6: Comercialización -->
        <div v-if="comercio" class="pdf-section">
          <h2 class="pdf-section-title">6. Comercialización</h2>
          <table class="pdf-data-table">
            <tbody>
              <tr>
                <td class="pdf-field-label">Infraestructura de empaque:</td>
                <td class="pdf-field-value">{{ comercio.infraestructura_empaque ? 'Sí' : 'No' }}</td>
                <td class="pdf-field-label">Vende en finca:</td>
                <td class="pdf-field-value">{{ comercio.vende_en_finca ? 'Sí' : 'No' }}</td>
              </tr>
              <tr v-if="comercio.mercados && comercio.mercados.length">
                <td class="pdf-field-label">Mercados donde comercializa:</td>
                <td class="pdf-field-value pdf-list-cell" colspan="3">
                  <ul class="pdf-table-list">
                    <li v-for="mercado in comercio.mercados" :key="mercado">{{ mercado }}</li>
                  </ul>
                </td>
              </tr>
              <tr v-if="comercio.cooperativas && comercio.cooperativas.length">
                <td class="pdf-field-label">Cooperativas de comercialización:</td>
                <td class="pdf-field-value pdf-list-cell" colspan="3">
                  <ul class="pdf-table-list">
                    <li v-for="coop in comercio.cooperativas" :key="coop">{{ coop }}</li>
                  </ul>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Sección 7: Cooperativas del Productor -->
        <div v-if="producer.cooperativas && producer.cooperativas.length" class="pdf-section">
          <h2 class="pdf-section-title">7. Pertenece a Cooperativas</h2>
          <ol class="pdf-numbered-list">
            <li v-for="coop in producer.cooperativas" :key="coop">{{ coop }}</li>
          </ol>
        </div>

        <!-- Mensaje sin datos -->
        <div v-if="!propiedades.length && !cultivos.length && !maquinarias.length && !comercio" class="pdf-section">
          <p class="pdf-no-data">Este productor no tiene datos registrados en los módulos del sistema.</p>
        </div>

        <!-- Pie de página -->
        <div class="pdf-footer">
          <div class="pdf-footer-content">
            <p class="pdf-footer-text">Este documento fue generado por el Sistema RUPAL - Registro Único de Productores Agropecuarios</p>
            <p class="pdf-footer-text">Fecha de generación: {{ new Date().toLocaleDateString('es-AR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
          </div>
          <div class="pdf-signature-section">
            <div class="pdf-signature-line">
              <div class="pdf-signature-box">
                <div class="pdf-signature-line-text"></div>
                <p class="pdf-signature-label">Firma y Sello</p>
                <p class="pdf-signature-org">Organismo Certificante</p>
              </div>
            </div>
          </div>
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
  // Guardar título original
  const originalTitle = document.title

  // Formatear fecha como YYYY-MM-DD
  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')
  const dateStr = `${year}-${month}-${day}`

  // Generar nombre de archivo
  const fileName = `Informe_Productor_${props.producer.name}_${dateStr}`

  // Cambiar título del documento (usado por el navegador al guardar PDF)
  document.title = fileName

  // Imprimir
  window.print()

  // Restaurar título original después de un momento
  setTimeout(() => {
    document.title = originalTitle
  }, 100)
}

// Helper function para formatear nombres de implementos
const formatImplementoName = (key) => {
  const names = {
    'arado': 'Arado',
    'rastra': 'Rastra',
    'niveleta_comun': 'Niveleta común',
    'niveleta_laser': 'Niveleta láser',
    'cincel_cultivadora': 'Cincel cultivadora',
    'desmalezadora': 'Desmalezadora',
    'pulverizadora_tractor': 'Pulverizadora tractor',
    'mochila_pulverizadora': 'Mochila pulverizadora',
    'cosechadora': 'Cosechadora',
    'enfardadora': 'Enfardadora',
    'retroexcavadora': 'Retroexcavadora',
    'carro_carreton': 'Carro carretón'
  }
  return names[key] || key
}
</script>

<style>
/* PDF Report - Hidden on screen */
#pdf-report {
  display: none;
}

@media print {
  /* Ocultar sidebar y header al imprimir */
  aside, header, .fixed, nav {
    display: none !important;
  }

  /* Evitar recortes por wrappers con height/overflow (muy común en layouts) */
  html, body {
    height: auto !important;
    overflow: visible !important;
  }
  .min-h-screen, .h-screen, .overflow-hidden, .overflow-y-auto, .overflow-auto {
    height: auto !important;
    overflow: visible !important;
  }

  /* Ocultar contenido web normal */
  #printable-report,
  .space-y-6 > .flex,
  .text-center.text-sm.text-slate-500 {
    display: none !important;
  }

  /* Mostrar PDF report (no fijar tamaño A4 acá: lo maneja @page) */
  #pdf-report {
    display: block !important;
    width: auto !important;
    min-height: auto !important;
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
    box-sizing: border-box !important;
  }

  /* Configuración de página A4 */
  @page {
    size: A4;
    margin: 15mm 20mm;
  }

  body {
    background: white !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  /* ===== ENCABEZADO INSTITUCIONAL ===== */
  .pdf-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 15px;
    border-bottom: 3px solid #1e40af;
    margin-bottom: 20px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-logo {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .pdf-logo-icon {
    width: 48px;
    height: 48px;
    color: #1e40af;
  }

  .pdf-logo-title {
    font-size: 22px;
    font-weight: 800;
    color: #1e40af;
    letter-spacing: 0.5px;
  }

  .pdf-logo-subtitle {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .pdf-fecha {
    font-size: 11px;
    color: #64748b;
    text-align: right;
    font-weight: 500;
  }

  /* ===== TÍTULO PRINCIPAL ===== */
  .pdf-title-section {
    text-align: center;
    margin-bottom: 25px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-main-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 5px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .pdf-subtitle {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
  }

  /* ===== SECCIONES ===== */
  /* CLAVE: NO bloquear cortes dentro de secciones largas */
  .pdf-section {
    margin-bottom: 25px;
    page-break-inside: auto;
    break-inside: auto;
    page-break-before: auto;
    break-before: auto;
  }

  .pdf-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e40af;
    margin: 0 0 12px 0;
    padding-bottom: 6px;
    border-bottom: 2px solid #e2e8f0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    page-break-after: avoid;
    break-after: avoid;
  }

  /* ===== TABLAS BASE ===== */
  /* CLAVE: no page-break-inside: avoid en tablas globales (si son largas se deben partir) */
  .pdf-data-table,
  .pdf-summary-table,
  .pdf-property-table,
  .pdf-full-table,
  .pdf-machinery-info-table,
  .pdf-implementos-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
    margin-bottom: 15px;
    page-break-inside: auto;
    break-inside: auto;
  }

  .pdf-data-table th,
  .pdf-data-table td,
  .pdf-summary-table th,
  .pdf-summary-table td,
  .pdf-property-table th,
  .pdf-property-table td,
  .pdf-full-table th,
  .pdf-full-table td,
  .pdf-machinery-info-table th,
  .pdf-machinery-info-table td,
  .pdf-implementos-table th,
  .pdf-implementos-table td {
    border: 1px solid #cbd5e1;
    padding: 8px 10px;
    vertical-align: middle;
  }

  .pdf-data-table thead,
  .pdf-summary-table thead,
  .pdf-full-table thead,
  .pdf-implementos-table thead {
    background-color: #1e40af;
    color: white;
  }

  .pdf-data-table th,
  .pdf-summary-table th,
  .pdf-full-table th,
  .pdf-implementos-table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 9px;
    letter-spacing: 0.3px;
  }

  .pdf-data-table tbody tr:nth-child(even),
  .pdf-full-table tbody tr:nth-child(even),
  .pdf-implementos-table tbody tr:nth-child(even) {
    background-color: #f8fafc;
  }

  /* Repetir encabezado de tablas en nuevas páginas */
  thead { display: table-header-group; }
  tfoot { display: table-footer-group; }

  /* IMPORTANTE: NO forzar avoid en tr global, permite que tablas largas fluyan */
  /* tr { page-break-inside: avoid; }  <-- NO */

  /* ===== CAMPOS Y VALORES ===== */
  .pdf-field-label {
    background-color: #f1f5f9;
    font-weight: 600;
    color: #475569;
    width: 25%;
    font-size: 9px;
  }

  .pdf-field-value {
    color: #0f172a;
    font-weight: 500;
  }

  .pdf-coords {
    font-family: "Courier New", monospace;
    font-size: 9px;
    color: #64748b;
  }

  /* ===== TABLA RESUMEN ===== */
  .pdf-summary-table {
    margin-bottom: 20px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-summary-table td {
    text-align: center;
    font-size: 18px;
    font-weight: 700;
    color: #1e40af;
    padding: 15px;
  }

  .pdf-stat-value {
    color: #1e40af;
  }

  /* ===== PROPIEDADES ===== */
  /* Evitar cortar cada bloque de propiedad (pero permitir que la sección total siga en otra página) */
  .pdf-property-block {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    background-color: #fafafa;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-property-title {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    margin: 0 0 10px 0;
    padding-bottom: 5px;
    border-bottom: 1px solid #cbd5e1;
  }

  .pdf-property-table {
    margin-bottom: 0;
    background-color: white;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  /* ===== MAQUINARIAS ===== */
  .pdf-machinery-block {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    background-color: #fafafa;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-machinery-title {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    margin: 0 0 10px 0;
    padding-bottom: 5px;
    border-bottom: 1px solid #cbd5e1;
  }

  .pdf-machinery-info-table {
    background-color: white;
    margin-bottom: 12px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-implementos-title {
    font-size: 10px;
    font-weight: 600;
    color: #475569;
    margin: 0 0 8px 0;
    text-transform: uppercase;
  }

  .pdf-implementos-table {
    background-color: white;
    page-break-inside: auto;
    break-inside: auto;
  }

  .pdf-implemento-name {
    text-transform: capitalize;
  }

  /* ===== TABLA CULTIVOS ===== */
  .pdf-full-table {
    font-size: 9px;
    page-break-inside: auto;
    break-inside: auto;
  }

  .pdf-full-table th {
    padding: 10px 8px;
  }

  .pdf-full-table td {
    padding: 8px;
  }

  .pdf-cell-center {
    text-align: center;
  }

  /* ===== LISTAS ===== */
  .pdf-list-section {
    margin-top: 15px;
    margin-bottom: 15px;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-list-title {
    font-size: 10px;
    font-weight: 600;
    color: #475569;
    margin: 0 0 8px 0;
    text-transform: uppercase;
  }

  .pdf-list {
    list-style: disc;
    margin: 0;
    padding-left: 20px;
  }

  .pdf-list li {
    font-size: 10px;
    color: #334155;
    margin-bottom: 3px;
  }

  .pdf-numbered-list {
    list-style: decimal;
    margin: 0;
    padding-left: 25px;
  }

  .pdf-numbered-list li {
    font-size: 10px;
    color: #334155;
    margin-bottom: 4px;
  }

  /* Listas dentro de celdas de tabla */
  .pdf-list-cell {
    padding: 10px !important;
  }

  .pdf-table-list {
    list-style: disc;
    margin: 0;
    padding-left: 18px;
    font-size: 9px;
    line-height: 1.5;
  }

  .pdf-table-list li {
    margin-bottom: 2px;
    color: #0f172a;
  }

  /* ===== ENLACES ===== */
  .pdf-link {
    color: #1e40af;
    text-decoration: underline;
    font-weight: 600;
  }

  /* ===== MENSAJE SIN DATOS ===== */
  .pdf-no-data {
    text-align: center;
    font-size: 11px;
    color: #64748b;
    font-style: italic;
    padding: 20px;
    border: 1px dashed #cbd5e1;
    background-color: #f8fafc;
  }

  /* ===== PIE DE PÁGINA ===== */
  .pdf-footer {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 2px solid #e2e8f0;
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .pdf-footer-content {
    text-align: center;
    margin-bottom: 30px;
  }

  .pdf-footer-text {
    font-size: 9px;
    color: #64748b;
    margin: 2px 0;
  }

  .pdf-signature-section {
    display: flex;
    justify-content: flex-end;
    margin-top: 30px;
  }

  .pdf-signature-box {
    width: 200px;
    text-align: center;
  }

  .pdf-signature-line-text {
    border-bottom: 1px solid #334155;
    height: 40px;
    margin-bottom: 8px;
  }

  .pdf-signature-label {
    font-size: 10px;
    font-weight: 600;
    color: #334155;
    margin: 0 0 2px 0;
    text-transform: uppercase;
  }

  .pdf-signature-org {
    font-size: 8px;
    color: #64748b;
    margin: 0;
  }

  /* ===== Saltos recomendados (si querés arrancar módulos grandes en hoja nueva) ===== */
  /* Opcional: descomentá si querés que cada módulo empiece en una página nueva */
  /*
  .pdf-section--propiedades,
  .pdf-section--cultivos,
  .pdf-section--maquinarias,
  .pdf-section--comercio {
    page-break-before: always;
    break-before: page;
  }
  */
}

</style>
