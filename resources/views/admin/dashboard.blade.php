@extends('layouts.app')
@section('content')
<div class="grid gap-8 lg:grid-cols-[220px_1fr]">
    <aside class="rounded-2xl bg-slate-900 p-5 text-white lg:min-h-[750px]">
        <p class="mb-8 text-xs font-extrabold uppercase tracking-[.15em] text-rose-300">Admin panel</p>
        <nav class="flex flex-row gap-2 text-sm lg:flex-col">
            <a class="rounded-lg bg-white/10 px-4 py-3" href="{{ route('admin.dashboard') }}">Overview</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.products') }}">Home page settings</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.new') }}">New orders <span class="float-right rounded-full bg-rose-400 px-2 text-xs">{{ $pendingCount }}</span></a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.shipping') }}">Shipping orders</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.confirmed') }}">Confirmed orders</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.cancelled') }}">Cancelled orders</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="#reports">Sales reports</a>
        </nav>
        <form class="mt-8 border-t border-white/10 pt-5" method="post" action="{{ route('admin.logout') }}">@csrf<button class="text-sm text-slate-300 hover:text-white">Sign out</button></form>
    </aside>
    <div>
        <div class="mb-8 flex items-end justify-between"><div><p class="text-xs font-extrabold uppercase tracking-[.15em] text-rose-400">Store manager</p><h1 class="mt-1 text-4xl font-extrabold tracking-tighter">Overview</h1></div><span class="text-sm text-slate-500">{{ now()->format('d M Y') }}</span></div>
        <div class="mb-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-orange-100"><p class="text-sm text-slate-500">All orders</p><strong class="text-3xl">{{ $orderCount }}</strong></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-orange-100"><p class="text-sm text-slate-500">Awaiting confirmation</p><strong class="text-3xl text-rose-500">{{ $pendingCount }}</strong></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-orange-100"><p class="text-sm text-slate-500">Confirmed sales</p><strong class="text-3xl">${{ number_format($confirmedSales, 2) }}</strong></div>
        </div>
        <section id="reports" class="mb-10 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-orange-100"><div class="mb-6 flex items-center justify-between"><h2 class="text-xl font-bold">Sales overview</h2><span class="text-sm text-slate-500">Confirmed orders · 6 months</span></div><div class="flex h-48 items-end gap-3 border-b border-orange-100 pb-2">@php($maxSales = max(1, $monthlySales->max('total'))) @foreach($monthlySales as $month)<div class="flex flex-1 flex-col items-center gap-2"><div class="w-full rounded-t-lg bg-rose-300" style="height: {{ max(8, ($month['total'] / $maxSales) * 150) }}px" title="${{ number_format($month['total'], 2) }}"></div><span class="text-xs text-slate-500">{{ $month['label'] }}</span></div>@endforeach</div></section>
        <section class="my-8 rounded-2xl border border-orange-100 bg-white p-6"><h2 class="text-xl font-bold">Manage orders</h2><p class="mt-2 text-slate-500">Open a status page to review and update orders.</p><div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4"><a class="rounded-xl bg-rose-50 p-4 font-bold text-rose-700 hover:bg-rose-100" href="{{ route('admin.orders.new') }}">New orders <span class="mt-1 block text-sm font-normal">{{ $pendingCount }} awaiting confirmation</span></a><a class="rounded-xl bg-orange-50 p-4 font-bold text-orange-700 hover:bg-orange-100" href="{{ route('admin.orders.shipping') }}">Shipping orders</a><a class="rounded-xl bg-emerald-50 p-4 font-bold text-emerald-700 hover:bg-emerald-100" href="{{ route('admin.orders.confirmed') }}">Confirmed orders</a><a class="rounded-xl bg-slate-100 p-4 font-bold text-slate-700 hover:bg-slate-200" href="{{ route('admin.orders.cancelled') }}">Cancelled orders</a></div></section>
    </div>
</div>
@endsection
