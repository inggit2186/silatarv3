import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: {
                // Frontend entrypoints (loaded on all pages)
                'app': 'resources/css/app.css',
                'home': 'resources/css/neo-mirai-home.css',

                // Admin entrypoint
                'admin-css': 'resources/css/admin-new.css',

                // JS entrypoint
                'app-js': 'resources/js/app.js',
            },
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost'
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    build: {
        chunkSizeWarningLimit: 600,
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    // Vendor chunks - split dependencies untuk better caching
                    if (id.includes('node_modules')) {
                        // Alpine.js - loaded separately for caching
                        if (id.includes('alpinejs') || id.includes('@alpinejs')) {
                            return 'vendor-alpine';
                        }
                        // Livewire
                        if (id.includes('livewire') || id.includes('@livewire')) {
                            return 'vendor-livewire';
                        }
                        // Other vendor libraries
                        return 'vendor';
                    }
                },
            },
        },
    },
});
