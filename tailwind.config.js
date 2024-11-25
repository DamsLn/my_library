/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    fontSize: {
      "xl": "18.72px",
      "2xl": "24px",
      "3xl": "32px",
    },
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

