/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './resources/views/layouts/pearl.blade.php',
    './resources/views/welcome.blade.php',
    './resources/views/projects/**/*.blade.php',
    './resources/views/pages/**/*.blade.php',
    './resources/views/blog/**/*.blade.php',
    './resources/views/components/radial-orbit-wheel.blade.php',
    './app/Support/**/*.php',
    './resources/js/lucide-pearl.js',
    './resources/js/welcome-pearl.js',
    './resources/js/radial-orbit-wheel.js',
    './resources/js/brand-svgs.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'Inter Fallback', 'system-ui', 'sans-serif'],
        display: ['Space Grotesk', 'Space Grotesk Fallback', 'Inter', 'Inter Fallback', 'system-ui', 'sans-serif'],
        mono: ['JetBrains Mono', 'JetBrains Mono Fallback', 'monospace'],
      },
      animation: {
        blob: 'blob 10s infinite alternate',
        float: 'floatSlow 4.5s ease-in-out infinite',
        'spin-slow': 'spin 75s linear infinite',
      },
      keyframes: {
        blob: {
          '0%': { transform: 'translate(0px, 0px) scale(1)' },
          '33%': { transform: 'translate(35px, -45px) scale(1.08)' },
          '66%': { transform: 'translate(-25px, 25px) scale(0.94)' },
          '100%': { transform: 'translate(0px, 0px) scale(1)' },
        },
        floatSlow: {
          '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
          '50%': { transform: 'translateY(-10px) rotate(2deg)' },
        },
      },
    },
  },
  plugins: [],
};
