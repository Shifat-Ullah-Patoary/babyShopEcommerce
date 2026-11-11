<!doctype html>
<html lang="en">

@php($isAdminPage = session('is_admin') && request()->routeIs('admin.*'))

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Little Nest Baby Shop' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="{{ $isAdminPage ? 'flex h-dvh flex-col overflow-hidden' : 'min-h-screen' }} bg-gradient-to-b from-rose-50/40 via-white to-emerald-50/30 text-slate-800 antialiased">

   @include('layouts.header')

    {{-- =====================================================
         MAIN
    ===================================================== --}}
  
<main class="{{ $isAdminPage
    ? 'flex min-h-0 w-full max-w-none flex-1 flex-col overflow-hidden px-2 py-4 sm:px-6 lg:py-6'
    : 'w-full max-w-none px-2 pb-20' }}">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mx-4 my-4 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-5 py-3 text-sm font-semibold text-emerald-800 shadow-sm">
            <span class="grid h-6 w-6 place-items-center rounded-full bg-emerald-200 text-emerald-800">✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mx-4 my-4 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50/80 px-5 py-3 text-sm font-semibold text-rose-800 shadow-sm">
            <span class="grid h-6 w-6 place-items-center rounded-full bg-rose-200 text-rose-800">!</span>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mx-4 my-4 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50/80 px-5 py-3 text-sm font-semibold text-rose-800 shadow-sm">
            <span class="grid h-6 w-6 place-items-center rounded-full bg-rose-200 text-rose-800">!</span>
            {{ $errors->first() }}
        </div>
    @endif

    @yield('content')
</main>

    @unless($isAdminPage)
        @include('layouts.footer')
    @endunless

    @stack('scripts')

</body>

</html>