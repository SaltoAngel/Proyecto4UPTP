import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',     // Para Tailwind
                'resources/sass/app.scss',   // Para Bootstrap
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});