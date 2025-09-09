import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/style.css',
                'resources/js/index.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            input: [
                'resources/css/style.css',
                'resources/js/index.js',
            ],
        },
    },
    resolve: {
        alias: {
            '@brokeret': '/assets/common',
        },
    },  
});
