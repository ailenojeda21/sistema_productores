import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: '127.0.0.1',
    },
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules/vue') || id.includes('node_modules/@inertiajs') || id.includes('node_modules/axios')) {
                        return 'vendor'
                    }
                    if (id.includes('node_modules/chart.js') || id.includes('node_modules/vue-chartjs')) {
                        return 'chart-vendor'
                    }
                },
            },
        },
    },
});