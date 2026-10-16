import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// Font (Oswald & Plus Jakarta Sans) dimuat lewat <link> Google Fonts di <head>, sama dengan landing page
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        // Port tetap supaya BKK, PPDB, dan frontend landing page (5173) bisa jalan bersamaan
        port: 5175,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
