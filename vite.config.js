import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Frontend entrypoints (for @vite)
                'assets/common/css/style.css',
                'assets/common/js/index.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build', // frontend build
        manifest: true,
        rollupOptions: {
            input: [
                'assets/common/css/style.css',
                'assets/common/js/index.js',
            ],
        },
    },
    resolve: {
        alias: {
            '@brokeret': '/assets/common', // optional alias
        },
    },
});
