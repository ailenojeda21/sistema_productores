<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import StaffLayout from '@/Layouts/StaffLayout.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  user: { type: Object, required: true },
  users: {
    type: [Array, Object],
    default: () => [],
  },
  filters: { type: Object, default: () => ({}) },
})

const filterName = ref(props.filters.name ?? '')
const filterEmail = ref(props.filters.email ?? '')

const isDeleteModalOpen = ref(false)
const userToDelete = ref(null)
const isDeleting = ref(false)
const togglingUserId = ref(null)

const normalize = (value) => (value ?? '').toString().toLowerCase().trim()

const userRows = computed(() => {
  if (Array.isArray(props.users)) {
    return props.users
  }

  return props.users?.data ?? []
})

const filteredUsers = computed(() => {
  const nameFilter = normalize(filterName.value)
  const emailFilter = normalize(filterEmail.value)

  return userRows.value.filter((staffUser) => {
    const matchesName = !nameFilter || normalize(staffUser.name).includes(nameFilter)
    const matchesEmail = !emailFilter || normalize(staffUser.email).includes(emailFilter)
    return matchesName && matchesEmail
  })
})

const hasFilters = computed(() => Boolean(filterName.value || filterEmail.value))

const clearFilters = () => {
  filterName.value = ''
  filterEmail.value = ''
}

const isUserActive = (staffUser) => staffUser?.active !== false

const roleLabel = (role) => (role === 'admin' ? 'Administrador' : 'Auditor')

const getLastAccessValue = (staffUser) => {
  return staffUser?.last_login_at || staffUser?.last_access_at || staffUser?.last_login || null
}

const formatDate = (value) => {
  if (!value) return '—'
  const date = new Date(value)
  if (Number.isNaN(date.getTime())) return '—'
  return date.toLocaleString('es-AR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const goToCreate = () => router.visit('/staff/users/create')

const editUser = (staffUser) => {
  router.visit(`/staff/users/${staffUser.id}/edit`)
}

const openDeleteModal = (staffUser) => {
  userToDelete.value = staffUser
  isDeleteModalOpen.value = true
}

const closeDeleteModal = () => {
  if (isDeleting.value) return
  isDeleteModalOpen.value = false
  userToDelete.value = null
}

const confirmDelete = () => {
  if (!userToDelete.value) return

  isDeleting.value = true
  router.delete(`/staff/users/${userToDelete.value.id}`, {
    preserveScroll: true,
    onFinish: () => {
      isDeleting.value = false
      closeDeleteModal()
    },
  })
}

const toggleUserStatus = (staffUser) => {
  if (!staffUser?.id) return

  togglingUserId.value = staffUser.id
  router.patch(`/staff/users/${staffUser.id}`, {
    active: !isUserActive(staffUser),
  }, {
    preserveScroll: true,
    onFinish: () => {
      togglingUserId.value = null
    },
  })
}
</script>

<template>
  <StaffLayout :user="user">
    <div class="space-y-5">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">Usuarios</h1>
          <p class="text-sm text-slate-500">Gestione los usuarios del staff y sus permisos.</p>
        </div>
        <button
          class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
          @click="goToCreate"
        >
          + Nuevo Usuario
        </button>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-xs font-semibold text-slate-600">Nombre</label>
            <input
              v-model="filterName"
              type="text"
              placeholder="Buscar por nombre"
              class="w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-0"
            />
          </div>
          <div class="space-y-1">
            <label class="text-xs font-semibold text-slate-600">Email</label>
            <input
              v-model="filterEmail"
              type="email"
              placeholder="Buscar por email"
              class="w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-0"
            />
          </div>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-sm">
          <div class="text-slate-500">
            Mostrando {{ filteredUsers.length }} de {{ userRows.length }} usuarios
          </div>
          <button
            v-if="hasFilters"
            class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50"
            @click="clearFilters"
          >
            Limpiar filtros
          </button>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="hidden md:block overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
              <tr>
                <th class="px-4 py-3 text-left font-semibold">Nombre</th>
                <th class="px-4 py-3 text-left font-semibold">Email · Rol</th>
                <th class="px-4 py-3 text-left font-semibold">Estado</th>
                <th class="px-4 py-3 text-left font-semibold">Último acceso</th>
                <th class="px-4 py-3 text-left font-semibold">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="staffUser in filteredUsers"
                :key="staffUser.id"
                class="hover:bg-slate-50/60"
              >
                <td class="px-4 py-3">
                  <div class="font-medium text-slate-900">{{ staffUser.name }}</div>
                  <div class="text-xs text-slate-500">#{{ staffUser.id }}</div>
                </td>
                <td class="px-4 py-3">
                  <div class="text-slate-700">{{ staffUser.email }}</div>
                  <div class="text-xs text-slate-500">{{ roleLabel(staffUser.role) }}</div>
                </td>
                <td class="px-4 py-3">
                  <span
                    class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-semibold"
                    :class="isUserActive(staffUser) ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                  >
                    <span
                      class="h-1.5 w-1.5 rounded-full"
                      :class="isUserActive(staffUser) ? 'bg-emerald-500' : 'bg-slate-400'"
                    ></span>
                    {{ isUserActive(staffUser) ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="px-4 py-3 text-slate-600">
                  {{ formatDate(getLastAccessValue(staffUser)) }}
                </td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap items-center gap-2">
                    <button
                      class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                      @click="editUser(staffUser)"
                    >
                      Editar
                    </button>
                    <button
                      class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                      @click="openDeleteModal(staffUser)"
                    >
                      Eliminar
                    </button>
                    <button
                      class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                      :disabled="togglingUserId === staffUser.id"
                      :class="togglingUserId === staffUser.id ? 'opacity-50 cursor-not-allowed' : ''"
                      @click="toggleUserStatus(staffUser)"
                    >
                      {{ isUserActive(staffUser) ? 'Desactivar' : 'Activar' }}
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredUsers.length === 0">
                <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                  No se encontraron usuarios con esos filtros.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="md:hidden divide-y divide-slate-100">
          <div v-for="staffUser in filteredUsers" :key="staffUser.id" class="p-4 space-y-3">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <p class="text-base font-semibold text-slate-900">{{ staffUser.name }}</p>
                <p class="text-sm text-slate-600 truncate">{{ staffUser.email }}</p>
                <p class="text-xs text-slate-500 mt-1">Rol: {{ roleLabel(staffUser.role) }}</p>
              </div>
              <span
                class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-xs font-semibold"
                :class="isUserActive(staffUser) ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
              >
                <span
                  class="h-1.5 w-1.5 rounded-full"
                  :class="isUserActive(staffUser) ? 'bg-emerald-500' : 'bg-slate-400'"
                ></span>
                {{ isUserActive(staffUser) ? 'Activo' : 'Inactivo' }}
              </span>
            </div>

            <p class="text-xs text-slate-500">
              Último acceso: {{ formatDate(getLastAccessValue(staffUser)) }}
            </p>

            <div class="flex flex-wrap gap-2">
              <button
                class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                @click="editUser(staffUser)"
              >
                Editar
              </button>
              <button
                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                @click="openDeleteModal(staffUser)"
              >
                Eliminar
              </button>
              <button
                class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                :disabled="togglingUserId === staffUser.id"
                :class="togglingUserId === staffUser.id ? 'opacity-50 cursor-not-allowed' : ''"
                @click="toggleUserStatus(staffUser)"
              >
                {{ isUserActive(staffUser) ? 'Desactivar' : 'Activar' }}
              </button>
            </div>
          </div>
          <div v-if="filteredUsers.length === 0" class="px-4 py-10 text-center text-slate-500">
            No se encontraron usuarios con esos filtros.
          </div>
        </div>
      </div>
    </div>

    <Modal :show="isDeleteModalOpen" maxWidth="lg" @close="closeDeleteModal">
      <div class="p-6">
        <div class="flex items-start gap-3">
          <div class="h-10 w-10 rounded-full bg-red-50 text-red-600 grid place-items-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-slate-900">Eliminar usuario</h3>
            <p class="text-sm text-slate-600 mt-1">
              Esta acción eliminará al usuario
              <span class="font-semibold text-slate-900">{{ userToDelete?.name }}</span>.
              No se puede deshacer.
            </p>
          </div>
        </div>

        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
          <button
            class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            :disabled="isDeleting"
            @click="closeDeleteModal"
          >
            Cancelar
          </button>
          <button
            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700"
            :disabled="isDeleting"
            :class="isDeleting ? 'opacity-60 cursor-not-allowed' : ''"
            @click="confirmDelete"
          >
            {{ isDeleting ? 'Eliminando...' : 'Eliminar' }}
          </button>
        </div>
      </div>
    </Modal>
  </StaffLayout>
</template>
