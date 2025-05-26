<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const activeWindow = ref(null);
const windows = ref([]);

function openWindow(type, title) {
    // Check if window already exists
    const existingWindow = windows.value.find(w => w.type === type);

    if (existingWindow) {
        // Bring to front
        activeWindow.value = existingWindow.id;
    } else {
        // Create new window
        const windowId = Date.now();
        windows.value.push({
            id: windowId,
            type,
            title,
            position: { x: windows.value.length * 20, y: windows.value.length * 20 },
            size: { width: 500, height: 400 },
            minimized: false
        });
        activeWindow.value = windowId;
    }
}

function closeWindow(windowId) {
    const index = windows.value.findIndex(w => w.id === windowId);
    if (index !== -1) {
        windows.value.splice(index, 1);
        if (activeWindow.value === windowId) {
            activeWindow.value = windows.value.length > 0 ? windows.value[windows.value.length - 1].id : null;
        }
    }
}

function minimizeWindow(windowId) {
    const window = windows.value.find(w => w.id === windowId);
    if (window) {
        window.minimized = !window.minimized;
    }
}

function bringToFront(windowId) {
    activeWindow.value = windowId;
}
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Sistema de Gestión Agrícola
                </h2>
                <div class="flex items-center space-x-4">
                    <Link :href="route('profile.edit')"
                        class="px-3 py-1 text-sm text-blue-600 border border-blue-200 rounded hover:bg-blue-50 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Perfil
                    </Link>
                    <Link :href="route('logout')" method="post" as="button"
                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar Sesión
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Dashboard Modules -->
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-600 text-blue-800 rounded shadow-sm">
                    <p>Bienvenido al sistema de gestión de productores agrícolas. Seleccione un módulo para comenzar.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div @click="openWindow('properties', 'Propiedades')"
                        class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-gray-700 font-medium">Propiedades</span>
                    </div>
                    <div @click="openWindow('files', 'Archivos')"
                        class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-gray-700 font-medium">Archivos</span>
                    </div>
                    <div @click="openWindow('machinery', 'Maquinaria')"
                        class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-gray-700 font-medium">Maquinaria</span>
                    </div>
                    <div @click="openWindow('implements', 'Implementos')"
                        class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="text-gray-700 font-medium">Implementos</span>
                    </div>
                    <div @click="openWindow('crops', 'Cultivos')"
                        class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-gray-700 font-medium">Cultivos</span>
                    </div>
                    <div @click="openWindow('irrigation', 'Tecnología de Riego')"
                        class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="text-gray-700 font-medium">Tecnología Riego</span>
                    </div>
                </div>

                <!-- Quick Access Toolbar -->
                <div class="mt-6 p-4 border border-gray-200 rounded-md bg-white shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Acceso Rápido</h2>
                    <div class="flex flex-wrap items-center gap-3">
                        <button @click="openWindow('new-property', 'Nueva Propiedad')"
                            class="px-3 py-2 text-sm text-blue-600 border border-blue-200 rounded hover:bg-blue-50 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Nueva Propiedad
                        </button>
                        <button @click="openWindow('new-crop', 'Nuevo Cultivo')"
                            class="px-3 py-2 text-sm text-blue-600 border border-blue-200 rounded hover:bg-blue-50 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Nuevo Cultivo
                        </button>
                        <button @click="openWindow('new-machinery', 'Nueva Maquinaria')"
                            class="px-3 py-2 text-sm text-blue-600 border border-blue-200 rounded hover:bg-blue-50 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Nueva Maquinaria
                        </button>
                    </div>
                </div>

                <!-- Task bar for minimized windows -->
                <div v-if="windows.length > 0"
                    class="fixed bottom-0 left-0 right-0 bg-gray-100 border-t border-gray-300 p-2 flex items-center space-x-2 z-20">
                    <div v-for="window in windows" :key="window.id" @click="bringToFront(window.id)"
                        class="px-3 py-1 rounded cursor-pointer text-sm flex items-center"
                        :class="activeWindow === window.id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">
                        {{ window.title }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MDI Windows -->
        <div v-for="window in windows" :key="window.id"
            class="fixed bg-white rounded-md shadow-lg overflow-hidden border border-gray-300 z-10"
            :class="{ 'hidden': window.minimized, 'z-30': activeWindow === window.id }" :style="{
                top: `${window.position.y}px`,
                left: `${window.position.x}px`,
                width: `${window.size.width}px`,
                height: `${window.size.height}px`
            }" @mousedown="bringToFront(window.id)">
            <!-- Window title bar -->
            <div class="flex justify-between items-center bg-blue-600 text-white p-2 cursor-move">
                <h3 class="text-sm font-semibold">{{ window.title }}</h3>
                <div class="flex items-center space-x-2">
                    <button @click="minimizeWindow(window.id)" class="text-white hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                        </svg>
                    </button>
                    <button @click="closeWindow(window.id)" class="text-white hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Window content -->
            <div class="p-4 overflow-auto" :style="{ height: `calc(${window.size.height}px - 36px)` }">
                <!-- Different content for each window type -->
                <div v-if="window.type === 'properties'" class="h-full">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">Listado de Propiedades</h4>
                    <p class="text-gray-600">Cargando propiedades...</p>
                </div>

                <div v-else-if="window.type === 'new-property'" class="h-full">
                    <form class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                            <input id="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="ubicacion">Ubicación</label>
                            <input id="ubicacion" type="text" class="w-full p-2 border border-gray-300 rounded"
                                required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="superficie">Superficie
                                (ha)</label>
                            <input id="superficie" type="number" class="w-full p-2 border border-gray-300 rounded"
                                required>
                        </div>
                        <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">Guardar
                            Propiedad</button>
                    </form>
                </div>

                <div v-else-if="window.type === 'new-crop'" class="h-full">
                    <form class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                            <input id="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="tipo">Tipo</label>
                            <input id="tipo" type="text" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1" for="propiedad">Propiedad</label>
                            <select id="propiedad" class="w-full p-2 border border-gray-300 rounded" required>
                                <option value="">Seleccione una propiedad</option>
                                <option value="1">Propiedad 1</option>
                                <option value="2">Propiedad 2</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">Guardar
                            Cultivo</button>
                    </form>
                </div>

                <!-- Templates for other window types -->
                <div v-else class="h-full flex items-center justify-center">
                    <p class="text-gray-600">Contenido de {{ window.title }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.cursor-move {
    cursor: move;
}
</style>
