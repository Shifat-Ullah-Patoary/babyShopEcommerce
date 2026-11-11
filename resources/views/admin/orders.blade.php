@extends('layouts.admin')

@section('admin-content')

{{-- =====================================================
     PAGE HEADER
===================================================== --}}
<div class="mb-8 flex flex-wrap items-end justify-between gap-3">
    <div>
        <div class="mb-3 flex items-center gap-2.5">
            <span class="h-1 w-8 rounded-full bg-rose-400"></span>
            <p class="text-[11px] font-bold uppercase tracking-[.15em] text-rose-400">Store manager</p>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tighter text-slate-800 sm:text-4xl">
            {{ $title }}
        </h1>
        <p class="mt-2 text-sm text-slate-500">
            {{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} in this status.
        </p>
    </div>
</div>

{{-- =====================================================
     ORDER CARDS
===================================================== --}}
@forelse($orders as $order)
    <article class="mb-4 overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm transition hover:shadow-md">

        {{-- Customer info + total --}}
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">

            <div class="flex items-start gap-3 min-w-0">
                <div class="grid h-11 w-11 flex-shrink-0 place-items-center rounded-xl bg-gradient-to-br from-rose-100 to-rose-200 text-sm font-bold text-rose-600">
                    {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <strong class="text-base font-bold text-slate-800">
                            #{{ $order->id }} · {{ $order->customer_name }}
                        </strong>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide
                            @switch($order->status)
                                @case('new')        bg-rose-100 text-rose-700        @break
                                @case('confirmed')  bg-emerald-100 text-emerald-700  @break
                                @case('shipped')    bg-orange-100 text-orange-700    @break
                                @case('cancelled')  bg-slate-200 text-slate-600      @break
                                @default            bg-slate-100 text-slate-600
                            @endswitch">
                            {{ $order->status }}
                        </span>
                    </div>

                    <p class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-500">
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $order->mobile }}
                        </span>
                        <span class="inline-flex items-start gap-1">
                            <svg class="mt-0.5 h-3.5 w-3.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="line-clamp-2">{{ $order->address }}</span>
                        </span>
                    </p>

                    <p class="mt-2 line-clamp-2 text-sm text-slate-500">
                        {{ $order->items->map(fn ($item) => $item->product_name . ' × ' . $item->quantity)->join(', ') }}
                    </p>
                </div>
            </div>

            <div class="flex-shrink-0 text-left sm:text-right">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</p>
                <strong class="block text-2xl font-extrabold text-slate-800">
                    ৳{{ number_format($order->total, 2) }}
                </strong>
            </div>
        </div>

        {{-- Status update form --}}
        <form method="post" action="{{ route('admin.orders.status', $order) }}"
              class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/60 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3">
                <label for="status-{{ $order->id }}" class="text-sm font-semibold text-slate-600 whitespace-nowrap">
                    Change status
                </label>
                <select id="status-{{ $order->id }}" name="status"
                        class="flex-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-800 transition focus:border-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-100 sm:flex-none">
                    @foreach(['new', 'confirmed', 'shipped', 'cancelled'] as $orderStatus)
                        <option value="{{ $orderStatus }}" @selected($order->status === $orderStatus)>
                            {{ ucfirst($orderStatus) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 rounded-full bg-slate-800 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Update order
            </button>
        </form>
    </article>
@empty
    <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-12 text-center">
        <div class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-full bg-slate-100 text-slate-400">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-lg font-bold text-slate-800">No {{ strtolower($title) }} found</p>
        <p class="mt-1 text-sm text-slate-500">Orders with this status will appear here.</p>
    </div>
@endforelse

@endsection