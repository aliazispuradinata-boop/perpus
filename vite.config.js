import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/components/navbar.css',
                'resources/css/pages/landing.css',
                'resources/js/pages/landing.js',
                'resources/css/pages/books.css',
                'resources/js/pages/books.js',
                'resources/css/pages/admin.css',
                'resources/js/pages/admin.js'
            ],
            refresh: true,
        }),
    ],
});
