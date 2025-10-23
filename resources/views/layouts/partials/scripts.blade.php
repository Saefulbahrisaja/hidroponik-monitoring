

<script>
    lucide.createIcons();

    // Custom animation untuk wiggle & spin slow
    tailwind.config = {
        theme: {
            extend: {
                animation: {
                    'spin-slow': 'spin 6s linear infinite',
                    'wiggle': 'wiggle 1s ease-in-out infinite',
                },
                keyframes: {
                    wiggle: {
                        '0%, 100%': { transform: 'rotate(-5deg)' },
                        '50%': { transform: 'rotate(5deg)' },
                    }
                }
            }
        }
    }
</script>
