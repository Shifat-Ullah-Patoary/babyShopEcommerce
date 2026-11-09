@extends('layouts.app')
@section('content')
    <div class="grid gap-8 lg:grid-cols-[220px_1fr]">
        <aside class="rounded-2xl bg-slate-900 p-5 text-white lg:min-h-[750px]">
            <p class="mb-8 text-xs font-extrabold uppercase tracking-[.15em] text-rose-300">Admin panel</p>
            <nav class="flex flex-row gap-2 text-sm lg:flex-col">
                <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.dashboard') }}">Overview</a>
                <a class="rounded-lg bg-white/10 px-4 py-3" href="{{ route('admin.products') }}">Home page settings</a>
                <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.new') }}">New orders</a>
                <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.shipping') }}">Shipping
                    orders</a>
                <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.confirmed') }}">Confirmed
                    orders</a>
                <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.orders.cancelled') }}">Cancelled
                    orders</a>
                <a class="rounded-lg px-4 py-3 hover:bg-white/10" href="{{ route('admin.dashboard') }}#reports">Sales
                    reports</a>
            </nav>
            <form class="mt-8 border-t border-white/10 pt-5" method="post" action="{{ route('admin.logout') }}">
                @csrf<button class="text-sm text-slate-300 hover:text-white">Sign out</button></form>
        </aside>
        <div>
            <div class="mb-8">
                <p class="text-xs font-extrabold uppercase tracking-[.15em] text-rose-400">Home page settings</p>
                <h1 class="mt-1 text-4xl font-extrabold tracking-tighter">Manage products</h1>
                <p class="mt-2 text-slate-500">Products added here appear on your home page.</p>
            </div>
            <section class="my-8">
                <h2 class="mb-3 border-b border-orange-200 pb-2 text-2xl font-bold">Homepage header</h2>
                <form class="grid gap-4 rounded-2xl border border-orange-100 bg-white p-5" enctype="multipart/form-data"
                    method="post" action="{{ route('admin.settings.update') }}">@csrf @method('PUT')<div
                        class="grid gap-4 md:grid-cols-2"><label class="block font-bold">Company name<input
                                class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2"
                                name="company_name"
                                value="{{ old('company_name', $siteSettings?->company_name ?? 'Little Nest') }}"
                                ></label><label class="block font-bold">Header image<input
                                class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2"
                                type="file" name="hero_image" accept="image/jpeg,image/png,image/webp,image/gif"></label>
                    </div><label class="block font-bold">Header title<input
                            class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2"
                            name="hero_title" value="{{ old('hero_title', $siteSettings?->hero_title ?? '') }}"
                            ></label><label class="block font-bold">Header description
                        <textarea class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2" name="hero_description"
                            rows="3">{{ old('hero_description', $siteSettings?->hero_description ?? '') }}</textarea>
                    </label><label class="block font-bold">Delivery charge<input
                            class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2"
                            name="delivery_charge" type="number" min="0" step="0.01"
                            value="{{ old('delivery_charge', $siteSettings?->delivery_charge ?? 0) }}"><span
                            class="mt-1 block text-sm font-normal text-slate-500">This amount is added to every
                            order.</span></label><button
                        class="w-fit rounded-full bg-slate-800 px-5 py-3 font-bold text-white">Save homepage
                        settings</button></form>
            </section>
            <section class="my-8">
                <h2 class="mb-3 border-b border-orange-200 pb-2 text-2xl font-bold">Add a product</h2>
                <form
                    class="grid gap-3 rounded-2xl border border-orange-100 bg-white p-5 md:grid-cols-[1fr_2fr_.7fr_1.5fr_auto] md:items-end"
                    enctype="multipart/form-data" method="post" action="{{ route('admin.products.store') }}">@csrf
                    @include('admin.product-fields')<button class="rounded-full bg-slate-800 px-5 py-3 font-bold text-white">Add
                        product</button></form>
            </section>
            <section class="my-8">
                <h2 class="mb-3 border-b border-orange-200 pb-2 text-2xl font-bold">Current products</h2>
                @forelse($products as $product)
                    <div class="mb-3 flex flex-col gap-2 rounded-2xl border border-orange-100 bg-white p-5">
                        <form class="grid flex-1 gap-3 md:grid-cols-[1fr_2fr_.7fr_1.5fr_auto] md:items-end"
                            enctype="multipart/form-data" method="post"
                            action="{{ route('admin.products.update', $product) }}">@csrf @method('PUT')
                            @include('admin.product-fields', ['product' => $product])<button class="rounded-full bg-orange-100 px-5 py-3 font-bold">Save
                                changes</button></form>
                        <form method="post" action="{{ route('admin.products.delete', $product) }}"
                            onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button
                                class="rounded-full bg-rose-600 px-5 py-3 font-bold text-white">Delete product</button>
                        </form>
                </div>@empty<p>No products yet.</p>
                @endforelse
            </section>
        </div>
    </div>
@endsection
