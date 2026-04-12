/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./app/Views/**/*.php', './app/Controllers/**/*.php', './resources/**/*.{js,jsx,ts,tsx,vue}'],
  theme: {
    extend: {
      colors: {
        'neo-yellow': '#f2f4f7',
        'neo-black': '#101010',
        'neo-white': '#ffffff',
        'neo-paper': '#e7ebf0',
        'neo-cream': '#f8fafc',
        'neo-red': '#ff6f61',
        'neo-mint': '#65dcb0',
        'neo-blue': '#74b6ff',
      },
      fontFamily: {
        display: ['"Bricolage Grotesque"', '"Arial Black"', 'sans-serif'],
        body: ['Manrope', '"Trebuchet MS"', 'sans-serif'],
        mono: ['"JetBrains Mono"', '"Courier New"', 'monospace'],
      },
      boxShadow: {
        neo: '7px 7px 0 0 #101010',
        'neo-sm': '4px 4px 0 0 #101010',
        'neo-lg': '10px 10px 0 0 #101010',
      },
      borderWidth: {
        3: '3px',
      },
      letterSpacing: {
        tightest: '-0.04em',
      },
      keyframes: {
        'neo-enter': {
          '0%': {
            opacity: '0',
            transform: 'translateY(10px) translateX(-3px)',
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0) translateX(0)',
          },
        },
      },
      animation: {
        'neo-enter': 'neo-enter 0.35s cubic-bezier(0.2, 0.8, 0.2, 1) both',
      },
    },
  },
  plugins: [],
}
