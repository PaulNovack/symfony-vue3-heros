import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import * as path from 'path';
import tailwindcss from 'tailwindcss';
import { resolve } from 'path'
// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src'),  // This defines '@' as an alias to the 'src' folder
    },
  },
  css: {
    postcss: {
      plugins: [tailwindcss()],
    },
  },
  build: {
    outDir: 'public/build/',
    emptyOutDir: true,
    manifest: 'manifest.json',
    rollupOptions: {
      input: path.resolve(__dirname, 'vue/main.ts'),
    },
  },
  server: {
    port: 3000,
    strictPort: true,
    watch: {
      usePolling: true,
    }
  },
});