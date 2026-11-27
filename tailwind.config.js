/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/View/Components/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                // Public Luxury Botanical Palette
                moss: '#2C3424',
                mossDark: '#1a1f15',
                mossLight: '#4a573d',
                mossAccent: '#48573D',
                warmwhite: '#e8e6d9',
                ink: '#1A1A1A',
                cream: '#EFEFEA',
                cedar: '#4A3B32',
                aloe: '#8D9F87',
                paper: '#dcd8c8',
                'olive-light': '#8D9F87',

                // Admin Dashboard Botanical CMS Palette
                adminBg: '#F6F5EE',
                adminDark: '#1E2519',
                adminMossLight: '#3D4933',
                sidebarBg: '#262E20',
                sidebarActive: '#37442F',
                cardBg: '#FFFFFF',
                cardMuted: '#FAF9F4',
                inkAdmin: '#22251E',
                inkMuted: '#6B7264',
                accentGold: '#9A8555',
                borderSoft: '#E8E6DC',
                badgeGreen: '#E2EBE0',
                textGreen: '#2D5427',
                badgeRed: '#FBE8E7',
                textRed: '#8C322D',
                badgeAmber: '#FBF2DE',
                textAmber: '#87621E',
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                display: ['"Cormorant Garamond"', 'serif'],
                script: ['Caveat', 'cursive'],
            },
            letterSpacing: {
                editorial: '0.32em',
            },
        },
    },
    plugins: [],
};
