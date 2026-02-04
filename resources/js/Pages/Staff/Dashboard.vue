<script setup>
import { computed } from 'vue'
import StaffLayout from '@/Layouts/StaffLayout.vue'

const props = defineProps({
  user: { type: Object, required: true },
  pageTitle: { type: String, default: 'Panel de administración' },
})

const title = computed(() => props.pageTitle)
</script>

<template>
  <StaffLayout :user="user">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
      <h2 class="text-2xl font-semibold text-slate-900">{{ title }}</h2>
      <p class="text-slate-600 mt-2">
        Bienvenido al panel de administración. Selecciona una opción del menú para comenzar.
      </p>
    </div>
  </StaffLayout>
</template>
