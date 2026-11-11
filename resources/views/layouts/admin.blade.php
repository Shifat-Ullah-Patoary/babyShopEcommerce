@extends('layouts.app')

@section('content')

    <div class="grid min-h-0 flex-1 grid-rows-1 gap-6 lg:grid-cols-[260px_minmax(0,1fr)] lg:gap-8">

        {{-- ============================================================
         ADMIN SIDEBAR
    ============================================================ --}}
        @if (session('is_admin'))
            <button type="button" class="fixed inset-0 z-40 hidden bg-slate-950/50 lg:hidden" aria-label="Close admin menu"
                data-admin-menu-backdrop></button>

            <aside id="admin-sidebar"
                class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] -translate-x-full overflow-y-auto overscroll-contain rounded-r-2xl bg-slate-900 p-4 text-white shadow-2xl transition-transform duration-300 sm:p-5 lg:static lg:z-auto lg:h-full lg:min-h-0 lg:w-auto lg:max-w-none lg:translate-x-0 lg:rounded-2xl lg:shadow-none">
                <div class="mb-6 flex items-center justify-between lg:mb-8">
                    <p class="text-xs font-extrabold uppercase tracking-[.15em] text-rose-300">
                        Admin panel
                    </p>
                    <button type="button"
                        class="grid h-9 w-9 place-items-center rounded-lg text-slate-300 transition hover:bg-white/10 hover:text-white lg:hidden"
                        aria-label="Close admin menu" data-admin-menu-close>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
                        </svg>
                    </button>
                </div>

                <nav class="flex flex-col gap-2 text-sm">
                    <a class="rounded-lg px-4 py-3 transition hover:bg-white/10
                          {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        Overview
                    </a>

                    <a class="rounded-lg px-4 py-3 transition hover:bg-white/10
                          {{ request()->routeIs('admin.products') ? 'bg-white/10' : '' }}"
                        href="{{ route('admin.products') }}">
                        Home page settings
                    </a>

                    <a class="flex items-center justify-between gap-2 rounded-lg px-4 py-3 transition hover:bg-white/10
                          {{ request()->routeIs('admin.orders.new') ? 'bg-white/10' : '' }}"
                        href="{{ route('admin.orders.new') }}">
                        New orders
                        @if (isset($pendingCount) && $pendingCount > 0)
                            <span class="shrink-0 rounded-full bg-rose-400 px-2 text-xs">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a class="rounded-lg px-4 py-3 transition hover:bg-white/10
                          {{ request()->routeIs('admin.orders.shipping') ? 'bg-white/10' : '' }}"
                        href="{{ route('admin.orders.shipping') }}">
                        Shipping orders
                    </a>

                    <a class="rounded-lg px-4 py-3 transition hover:bg-white/10
                          {{ request()->routeIs('admin.orders.confirmed') ? 'bg-white/10' : '' }}"
                        href="{{ route('admin.orders.confirmed') }}">
                        Confirmed orders
                    </a>

                    <a class="rounded-lg px-4 py-3 transition hover:bg-white/10
                          {{ request()->routeIs('admin.orders.cancelled') ? 'bg-white/10' : '' }}"
                        href="{{ route('admin.orders.cancelled') }}">
                        Cancelled orders
                    </a>

                    <a class="rounded-lg px-4 py-3 transition hover:bg-white/10" href="#reports">
                        Sales reports
                    </a>
                </nav>

                <form class="mt-8 border-t border-white/10 pt-5" method="post" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="text-sm text-slate-300 hover:text-white">Sign out</button>
                </form>
            </aside>
        @endif


        {{-- ============================================================
         MAIN CONTENT — each admin page provides this
    ============================================================ --}}
        <div class="min-h-0 min-w-0 overflow-y-auto overscroll-contain">
            @yield('admin-content')
        </div>

    </div>

@endsection
