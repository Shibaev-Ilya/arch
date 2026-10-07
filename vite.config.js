import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  plugins: [tailwindcss()],
  publicDir: false,
  css: {
    devSourcemap: true,
  },
  build: {
    outDir: "assets",
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: "src/js/main.js",
    },
  },
});
