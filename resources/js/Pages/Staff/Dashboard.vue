<script setup>
import { computed } from 'vue'
import { Doughnut, Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Filler,
} from 'chart.js'
import StaffLayout from '@/Layouts/StaffLayout.vue'

// Registrar componentes de Chart.js
ChartJS.register(
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Filler
)

const props = defineProps({
  user: { type: Object, required: true },
  pageTitle: { type: String, default: 'Panel de administración' },
  kpiData: {
    type: Object,
    default: () => ({
      usuarios: {
        total: 0,
        nuevos30d: 0,
        nuevosPorMes: { labels: [], data: [] },
      },
      hectareas: {
        cultivadas: 0,
        totalLavalle: 10242,
        restantes: 10242,
        porcentaje: 0,
      },
    }),
  },
})

// Configuración del gráfico de Hectáreas (Doughnut)
const hectareasChartData = computed(() => ({
  labels: ['Hectáreas cultivadas', 'Hectáreas disponibles'],
  datasets: [
    {
      data: [
        props.kpiData.hectareas.cultivadas,
        props.kpiData.hectareas.restantes,
      ],
      backgroundColor: ['#2563eb', '#e2e8f0'], // blue-600, slate-200
      borderColor: ['#2563eb', '#e2e8f0'],
      borderWidth: 0,
      hoverOffset: 4,
    },
  ],
}))

const hectareasChartOptions = {
  responsive: true,
  maintainAspectRatio: true,
  cutout: '65%',
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        usePointStyle: true,
        padding: 16,
        font: {
          size: 12,
          family: 'Inter, system-ui, sans-serif',
        },
      },
    },
    tooltip: {
      callbacks: {
        label: (context) => {
          const value = context.raw
          const total = props.kpiData.hectareas.totalLavalle
          const percentage = ((value / total) * 100).toFixed(1)
          return `${context.label}: ${value.toLocaleString()} ha (${percentage}%)`
        },
      },
    },
  },
}

// Configuración del gráfico de Usuarios (Line)
const usuariosChartData = computed(() => ({
  labels: props.kpiData.usuarios.nuevosPorMes.labels,
  datasets: [
    {
      label: 'Usuarios nuevos',
      data: props.kpiData.usuarios.nuevosPorMes.data,
      borderColor: '#2563eb', // blue-600
      backgroundColor: 'rgba(37, 99, 235, 0.1)',
      borderWidth: 2,
      pointBackgroundColor: '#2563eb',
      pointBorderColor: '#ffffff',
      pointBorderWidth: 2,
      pointRadius: 4,
      pointHoverRadius: 6,
      tension: 0.4,
      fill: true,
    },
  ],
}))

const usuariosChartOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      callbacks: {
        label: (context) => {
          return `${context.raw} usuarios nuevos`
        },
      },
    },
  },
  scales: {
    x: {
      grid: {
        display: false,
      },
      ticks: {
        font: {
          size: 11,
        },
      },
    },
    y: {
      beginAtZero: true,
      grid: {
        color: '#f1f5f9',
      },
      ticks: {
        font: {
          size: 11,
        },
        stepSize: 1,
      },
    },
  },
}

// Formatear números
const formatNumber = (num) => {
  return num.toLocaleString('es-AR')
}
</script>

<template>
  <StaffLayout :user="user">
    <div class="space-y-6">
      <!-- KPI Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- KPI: Usuarios Totales -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm font-medium text-slate-500">Usuarios registrados</p>
              <p class="text-3xl font-bold text-slate-900 mt-1">
                {{ formatNumber(kpiData.usuarios.total) }}
              </p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-blue-50 grid place-items-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1a4 4 0 00-4-4h-1M9 20H4v-1a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 4a3 3 0 100-6" />
              </svg>
            </div>
          </div>
          <div class="mt-3 flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-50 text-green-700 text-xs font-medium">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              +{{ kpiData.usuarios.nuevos30d }} este mes
            </span>
            <span class="text-xs text-slate-500">últimos 30 días</span>
          </div>
        </div>

        <!-- KPI: Hectáreas Cultivadas -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm font-medium text-slate-500">Hectáreas cultivadas</p>
              <p class="text-3xl font-bold text-slate-900 mt-1">
                {{ formatNumber(kpiData.hectareas.cultivadas) }}
              </p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 grid place-items-center">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
              </svg>
            </div>
          </div>
          <div class="mt-3">
            <div class="flex items-center gap-2">
              <div class="flex-1 bg-slate-100 rounded-full h-2">
                <div 
                  class="bg-emerald-500 h-2 rounded-full transition-all duration-500"
                  :style="{ width: `${kpiData.hectareas.porcentaje}%` }"
                ></div>
              </div>
              <span class="text-sm font-semibold text-emerald-600">
                {{ kpiData.hectareas.porcentaje }}%
              </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
              sobre {{ formatNumber(kpiData.hectareas.totalLavalle) }} ha del Dpto. Lavalle
            </p>
          </div>
        </div>
      </div>

      <!-- Charts Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico: Hectáreas -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
          <div class="mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Uso de hectáreas</h3>
            <p class="text-sm text-slate-500">Distribución del área cultivada en Lavalle</p>
          </div>
          <div class="relative h-64">
            <Doughnut 
              :data="hectareasChartData" 
              :options="hectareasChartOptions" 
            />
            <!-- Porcentaje en el centro -->
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
              <span class="text-3xl font-bold text-slate-900">{{ kpiData.hectareas.porcentaje }}%</span>
              <span class="text-xs text-slate-500">ocupado</span>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-4 text-center">
            <div class="bg-slate-50 rounded-lg p-3">
              <p class="text-2xl font-bold text-blue-600">{{ formatNumber(kpiData.hectareas.cultivadas) }}</p>
              <p class="text-xs text-slate-500">ha cultivadas</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-3">
              <p class="text-2xl font-bold text-slate-400">{{ formatNumber(kpiData.hectareas.restantes) }}</p>
              <p class="text-xs text-slate-500">ha disponibles</p>
            </div>
          </div>
        </div>

        <!-- Gráfico: Crecimiento de Usuarios -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
          <div class="mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Crecimiento de usuarios</h3>
            <p class="text-sm text-slate-500">Nuevos registros por mes (últimos 6 meses)</p>
          </div>
          <div class="h-64">
            <Line 
              :data="usuariosChartData" 
              :options="usuariosChartOptions" 
            />
          </div>
          <div class="mt-4 flex items-center justify-between bg-blue-50 rounded-lg p-3">
            <div>
              <p class="text-xs text-slate-500">Total nuevos (últimos 6 meses)</p>
              <p class="text-xl font-bold text-blue-600">
                {{ formatNumber(kpiData.usuarios.nuevosPorMes.data.reduce((a, b) => a + b, 0)) }}
              </p>
            </div>
            <div class="text-right">
              <p class="text-xs text-slate-500">Promedio mensual</p>
              <p class="text-xl font-bold text-slate-700">
                {{ formatNumber(Math.round(kpiData.usuarios.nuevosPorMes.data.reduce((a, b) => a + b, 0) / 6)) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </StaffLayout>
</template>
