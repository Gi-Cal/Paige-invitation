import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/homepage.js',
                'resources/js/invitation.js',
                'resources/js/admin-rsvp.js',
            ],
            refresh: true,
        }),
    ],
});