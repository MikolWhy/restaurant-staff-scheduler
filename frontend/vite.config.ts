import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],

  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000', // sends /api requests to the correct laravel backend port 
        changeOrigin: true,   // set Host header to target so server sees it as intended and avoids rejection
      },

    }
  }
})
