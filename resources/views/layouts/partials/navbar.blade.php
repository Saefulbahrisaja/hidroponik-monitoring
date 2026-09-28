<header class="fixed top-0 left-0 w-full z-50 bg-emerald-800/85 backdrop-blur-md border-b border-white/10 text-white shadow-lg transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">
        <!-- Brand Logo & Title -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white shadow-md group-hover:scale-105 transition-transform">
                <i class="fas fa-leaf text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight leading-none group-hover:text-emerald-200 transition-colors">
                    Hijau Hydro
                </h1>
                <p class="text-xs text-emerald-200/80 font-medium">Smart Environment Hydroponic System</p>
            </div>
        </a>

        <!-- System Status Badge & Info -->
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs text-emerald-100">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="font-semibold">SIKECE System Active</span>
            </div>
        </div>
    </div>
</header>