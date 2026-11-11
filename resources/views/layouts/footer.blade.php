{{-- =====================================================
         FOOTER
    ===================================================== --}}
    <footer class="mt-12 border-t border-rose-100/70 bg-white/60 px-6 py-10 backdrop-blur">
        <div class="mx-auto max-w-7xl text-center">
            <p class="text-lg font-extrabold tracking-tight text-slate-800">
                {{ $siteSettings?->company_name ?? 'Little Nest' }}
            </p>
            <p class="mt-1 text-sm text-slate-500">
                Made with <span class="text-rose-400">♥</span> for little ones.
            </p>
            <p class="mt-4 text-xs text-slate-400">
                &copy; {{ date('Y') }} All rights reserved.
            </p>
        </div>
    </footer>