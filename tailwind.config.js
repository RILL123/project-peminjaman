/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./view/**/*.php",
        "./public/**/*.php",
        "./src/**/*.html"
    ],
    theme: {
        fontFamily: {
            sans: ['Poppins', 'sans-serif'],
            poppins: ['Poppins', 'sans-serif'],
        },
        colors: {
            perpusku1: '#1A3263',
            perpusku2: '#547792',
            perpusku3: '#FAB95B',
            perpusku4: '#E8E2DB',
            transparent: 'transparent',
            white: '#ffffff',
            black: '#000000',
            red: '#ef4444',
            blue: '#3b82f6',
            gray: '#6b7280',
        }
    },
    plugins: [],
}
