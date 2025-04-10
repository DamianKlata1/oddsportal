import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";

/* if you're using React */
// import react from '@vitejs/plugin-react';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        /* react(), // if you're using React */
        symfonyPlugin(),
        vue(),
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/app.js",
                main: "./assets/main.js",
            },
        }
    },
    server: {
        host: "0.0.0.0", // Umożliwia dostęp z zewnątrz
        port: 5173, // Upewnij się, że ten port jest przekierowany w Dockerze
        strictPort: true,
        hmr: {
            host: "localhost", // Przeglądarka łączy się z hostem
            clientPort: 5173, // Port używany przez przeglądarkę
        },
        watch: {
            usePolling: true, // Pomaga w wykrywaniu zmian w Dockerze
        },
    },
});
