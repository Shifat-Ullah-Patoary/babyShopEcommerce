@extends('layouts.app')
@section('content')
<div class="grid gap-8 lg:grid-cols-[220px_1fr]">
    <aside class="rounded-2xl bg-slate-900 p-5 text-white lg:min-h-[650px]">
        <p class="mb-8 text-xs font-extrabold uppercase tracking-[.15em] text-rose-300">Admin panel</p>
        <nav class="flex flex-row gap-2 text-sm lg:flex-col">
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.dashboard') }}">Overview</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.new') }}">New orders</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.shipping') }}">Shipping orders</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.confirmed') }}">Confirmed orders</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.cancelled') }}">Cancelled orders</a>
            <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.products') }}">Home page settings</a>
        </nav>
        <form class="mt-8 border-t border-white/10 pt-5" method="post" action="{{ route('admin.logout') }}">@csrf<button class="text-sm text-slate-300 hover:text-white">Sign out</button></form>
    </aside>
    <div>
        <div class="mb-8"><p class="text-xs font-extrabold uppercase tracking-[.15em] text-rose-400">Store manager</p><h1 class="mt-1 text-4xl font-extrabold tracking-tighter">{{ $title }}</h1><p class="mt-2 text-slate-500">{{ $orders->count() }} order(s) in this status.</p></div>
        @forelse($orders as $order)
            <div class="my-2 rounded-2xl border border-orange-100 bg-white p-5">
                <div class="flex flex-col justify-between gap-4 sm:flex-row"><div><strong>#{{ $order->id }} · {{ $order->customer_name }}</strong><p class="text-slate-500">{{ $order->mobile }} · {{ $order->address }}</p><p class="text-sm text-slate-500">{{ $order->items->map(fn ($item) => $item->product_name.' × '.$item->quantity)->join(', ') }}</p></div><div class="text-left sm:text-right"><strong>${{ number_format($order->total, 2) }}</strong><p class="text-sm font-bold uppercase text-rose-500">{{ $order->status }}</p></div></div>
                <form class="mt-4 flex gap-2 border-t border-orange-100 pt-4" method="post" action="{{ route('admin.orders.status', $order) }}">@csrf @method('PUT')<select class="rounded-lg border border-orange-100 bg-orange-50 px-3 py-2" name="status">@foreach(['new','confirmed','shipped','cancelled'] as $orderStatus)<option value="{{ $orderStatus }}" @selected($order->status === $orderStatus)>{{ ucfirst($orderStatus) }}</option>@endforeach</select><button class="rounded-full bg-slate-800 px-4 py-2 text-sm font-bold text-white">Update order</button></form>
            </div>
        @empty
            <p class="rounded-2xl bg-white p-8 text-slate-500">No {{ strtolower($title) }} found.</p>
        @endforelse
    </div>
</div>
@endsection
