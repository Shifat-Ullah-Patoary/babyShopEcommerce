<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Little Nest Baby Shop' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-rose-50/40 via-white to-emerald-50/30 text-slate-800 antialiased">

    {{-- =====================================================
         HEADER
    ===================================================== --}}
    <header class="sticky top-0 z-30 border-b border-rose-100/70 bg-white/80 shadow-sm backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-4 lg:flex-row lg:items-center lg:justify-between">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br from-rose-300 to-rose-400 text-lg font-extrabold text-white shadow-sm transition group-hover:scale-105">
                    ♡
                </span>
                <span class="leading-tight">
                    <span class="block text-xl font-extrabold tracking-tight text-slate-800">
                        {{ $siteSettings?->company_name ?? 'Little Nest' }}
                    </span>
                    <span class="block text-[10px] font-bold uppercase tracking-[.2em] text-rose-400">
                        baby shop
                    </span>
                </span>
            </a>

            {{-- Search (home page only) --}}
            @if(request()->routeIs('home'))
                <form method="get" action="{{ route('home') }}"
                      class="order-3 flex w-full max-w-xl gap-2 lg:order-2">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search baby products..."
                            class="w-full rounded-full border border-rose-100 bg-white/90 py-2.5 pl-11 pr-5 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-100"
                        >
                    </div>
                    <button type="submit"
                            class="rounded-full bg-slate-800 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-700">
                        Search
                    </button>
                </form>
            @endif

            {{-- Nav --}}
            <nav class="order-2 flex flex-wrap items-center gap-2 text-sm font-semibold text-slate-600 lg:order-3">

                <a href="{{ route('home') }}"
                   class="rounded-full px-3 py-2 transition hover:bg-rose-50 hover:text-rose-500 {{ request()->routeIs('home') ? 'bg-rose-50 text-rose-500' : '' }}">
                    Shop
                </a>

                <a href="{{ route('cart') }}"
                   class="relative rounded-full px-3 py-2 transition hover:bg-rose-50 hover:text-rose-500 {{ request()->routeIs('cart') ? 'bg-rose-50 text-rose-500' : '' }}">
                    Cart
                    @php $cartCount = array_sum(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="ml-1 inline-grid h-5 min-w-5 place-items-center rounded-full bg-rose-400 px-1.5 text-[10px] font-extrabold text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @if(session('is_admin'))
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-full px-3 py-2 transition hover:bg-rose-50 hover:text-rose-500">
                        Dashboard
                    </a>
                @endif

                @auth
                    <span class="hidden items-center gap-2 rounded-full bg-emerald-50 px-3 py-2 text-emerald-700 sm:flex">
                        <span class="grid h-6 w-6 place-items-center rounded-full bg-emerald-200 text-xs font-extrabold text-emerald-700">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        {{ auth()->user()->name }}
                    </span>
                @endauth

                @if(auth()->check() || session('is_admin'))
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-full px-3 py-2 transition hover:bg-rose-50 hover:text-rose-500">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-full bg-rose-400 px-5 py-2 text-white shadow-sm transition hover:bg-rose-500">
                        Login
                    </a>
                @endif
            </nav>
        </div>
    </header>

    {{-- =====================================================
         MAIN
    ===================================================== --}}
    <main class="mx-auto max-w-7xl px-6 pb-20">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="my-4 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-5 py-3 text-sm font-semibold text-emerald-800 shadow-sm">
                <span class="grid h-6 w-6 place-items-center rounded-full bg-emerald-200 text-emerald-800">✓</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="my-4 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50/80 px-5 py-3 text-sm font-semibold text-rose-800 shadow-sm">
                <span class="grid h-6 w-6 place-items-center rounded-full bg-rose-200 text-rose-800">!</span>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="my-4 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50/80 px-5 py-3 text-sm font-semibold text-rose-800 shadow-sm">
                <span class="grid h-6 w-6 place-items-center rounded-full bg-rose-200 text-rose-800">!</span>
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>

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
        @stack('scripts')   

</body>

</html>