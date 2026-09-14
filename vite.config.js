import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/english/app.js',
                'resources/js/english/forest/app.js',
                'resources/js/english/home/app.js',
            ],
            refresh: true,
        }),
    ],
});
