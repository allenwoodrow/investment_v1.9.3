import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin';
import Components from 'unplugin-vue-components/vite';
import {PrimeVueResolver} from 'unplugin-vue-components/resolvers';
import { fileURLToPath, URL } from 'node:url'
import { chunkSplitPlugin } from 'vite-plugin-chunk-split';

// @ts-expect-error
export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};
    const base = (process.env.VITE_APP_ENV === 'production') ? '/' : './'
    return defineConfig({
        base: base,
        plugins: [
            chunkSplitPlugin(),
            laravel({
                input: [
                    './resources/js/main.ts'
                ],
                refresh: true,
            }),
            vue(),
            Components({
                resolvers: [
                    PrimeVueResolver()
                ]
            }),
        ],
        server: {
            hmr: {
                host: 'localhost'
            }
        },
        resolve: {
            alias: {
            '@/*': fileURLToPath(new URL('./resources/js', import.meta.url))
            }
        },
        optimizeDeps: {
            force: true,
        }
    })
}