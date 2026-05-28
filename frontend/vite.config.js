import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { VitePWA } from 'vite-plugin-pwa';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    react(),
    VitePWA({
      registerType: 'autoUpdate', // Met à jour l'application dès qu'un changement est détecté
      includeAssets: ['pwa-192x192.png', 'pwa-512x512.png', 'favicon.svg', 'icons.svg'],
      manifest: {
        name: 'AgriTrack',
        short_name: 'AgriTrack',
        description: 'Application de gestion connecté pour exploitation agricole',
        theme_color: '#2e7d32',
        background_color: '#f4f7f6',
        display: 'standalone', // pour ouvrir l'application sans la barre d'adresse du navigateur
        orientation: 'portrait',
        icons: [
          {
            src: 'pwa-192x192.png',
            sizes: '192x192',
            type: 'image/png'
          },
          {
            src: 'pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png'
          }
        ]
      }
    })
  ]
});