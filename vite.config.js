import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/style.scss",
                "resources/sass/app.scss",
                "resources/css/plugins.bundle.css",
                "resources/js/app.js",
                "resources/js/product/item-selection.js",
                "resources/js/product/provider-selection.js",
                "resources/js/supply/item-edit.js",
                "resources/js/src/roles/permissions.js",
                "resources/js/product/invoice-selection.js",
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                compilerOptions: {
                    isCustomElement: (tag) => ['Head'].includes(tag),
                },
            },
            script: {
                defineModel: true,
            }
        }),
    ],
    resolve: {
        alias: {
            '@layouts': path.resolve(__dirname, 'resources/js/Layouts'),
            '@components': path.resolve(__dirname, 'resources/js/Components'),
            '@pages': path.resolve(__dirname, 'resources/js/Pages'),
            '@src': path.resolve(__dirname, 'resources/js/src'),
        }
    },
});
