@extends('layouts.app')

@section('content')

<div id="cart-page" class="mx-auto max-w-5xl px-4 py-10 sm:py-14">

    {{-- =====================================================
         HEADER
    ===================================================== --}}
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <div class="mb-3 flex items-center gap-3">
                <span class="h-1 w-10 rounded-full bg-rose-400"></span>
                <p class="text-xs font-extrabold uppercase tracking-[.18em] text-rose-500">
                    Your bag
                </p>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl">
                Shopping Cart
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                @if($products->isEmpty())
                    A cosy nest is just a click away.
                @else
                    {{ $products->count() }} lovely {{ Str::plural('find', $products->count()) }} ready to come home.
                @endif
            </p>
        </div>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-5 py-2.5 text-sm font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-100">
            <span aria-hidden="true">←</span> Continue shopping
        </a>
    </div>

    @if($products->isEmpty())

        {{-- =====================================================
             EMPTY STATE
        ===================================================== --}}
        <div class="rounded-3xl border border-rose-100 bg-gradient-to-br from-rose-50/60 via-white to-emerald-50/40 p-12 text-center shadow-sm">
            <div class="mx-auto mb-6 grid h-24 w-24 place-items-center rounded-full bg-rose-100 text-5xl text-rose-300">
                ♡
            </div>
            <h2 class="text-xl font-bold text-slate-800">Your cart is currently empty.</h2>
            <p class="mx-auto mt-2 max-w-md text-slate-500">
                When you find something you love, it will appear here. In the meantime, take a peek at our collection.
            </p>
            <a href="{{ route('home') }}"
               class="mt-8 inline-flex items-center gap-2 rounded-full bg-rose-400 px-7 py-3.5 font-bold text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:bg-rose-500 hover:shadow-rose-300">
                Browse the collection <span aria-hidden="true">→</span>
            </a>
        </div>

    @else

        <form method="post" action="{{ route('cart.update') }}" id="cart-form">
            @csrf
            @method('PUT')

            {{-- =====================================================
                 CART ITEMS
            ===================================================== --}}
            <div class="space-y-4">
                @foreach($cart as $id => $quantity)
                    @if($products->has($id))
                        @php($product = $products[$id])

                        <div data-cart-item
                             data-price="{{ $product->price }}"
                             data-quantity="{{ $quantity }}"
                             class="group relative flex flex-col gap-5 rounded-2xl border border-rose-100 bg-white p-5 shadow-sm transition duration-300 hover:shadow-md sm:flex-row sm:items-center sm:p-6">

                            {{-- Image --}}
                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl border border-rose-100 bg-gradient-to-br from-rose-50 to-amber-50">
                                @if($product->image_url)
                                    <img
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        src="{{ filter_var($product->image_url, FILTER_VALIDATE_URL) ? $product->image_url : asset('storage/' . $product->image_url) }}"
                                        alt="{{ $product->name }}"
                                    >
                                @else
                                    <div class="grid h-full w-full place-items-center text-2xl text-rose-300">✦</div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-bold text-slate-800">
                                    {{ $product->name }}
                                </h3>
                                <p class="mt-1 text-sm text-rose-500">
                                    {{ number_format($product->price, 2) }}৳ each
                                </p>
                            </div>

                            {{-- Quantity --}}
                            <div class="flex items-center justify-between gap-3 sm:justify-start">
                                <span class="text-sm text-slate-500 sm:hidden">Quantity</span>
                                <div class="inline-flex items-center overflow-hidden rounded-full border border-rose-200 bg-rose-50/50">
                                    <button type="button" data-qty-decrease
                                            class="grid h-9 w-9 place-items-center text-rose-500 transition hover:bg-rose-100"
                                            aria-label="Decrease quantity">−</button>
                                    <input
                                        class="cart-quantity w-14 border-0 bg-transparent text-center text-sm font-semibold text-slate-800 focus:outline-none focus:ring-0"
                                        data-price="{{ $product->price }}"
                                        type="number" min="1" max="99"
                                        name="quantities[{{ $id }}]"
                                        value="{{ $quantity }}"
                                    >
                                    <button type="button" data-qty-increase
                                            class="grid h-9 w-9 place-items-center text-rose-500 transition hover:bg-rose-100"
                                            aria-label="Increase quantity">+</button>
                                </div>
                            </div>

                            {{-- Line total + remove --}}
                            <div class="flex items-center justify-between gap-4 sm:flex-col sm:items-end sm:justify-center">
                                <strong class="cart-line-total text-xl font-extrabold text-slate-800" data-line-total>
                                    {{ number_format($product->price * $quantity, 2) }}৳
                                </strong>

                                <button
                                    type="submit"
                                    name="remove_product"
                                    value="{{ $id }}"
                                    data-remove-cart-item="{{ $product->name }}"
                                    class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-500 transition hover:border-rose-300 hover:bg-rose-50">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- =====================================================
                 SUMMARY
            ===================================================== --}}
            <div class="mt-8 overflow-hidden rounded-3xl border border-rose-100 bg-white shadow-sm">

                <div class="border-b border-rose-100 bg-gradient-to-r from-rose-50/60 to-emerald-50/40 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-800">Order Summary</h2>
                </div>

                <div class="space-y-3 px-6 py-6 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-800" data-cart-subtotal>
                            {{ number_format($subtotal, 2) }}৳
                        </span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Delivery charge</span>
                        <span class="font-semibold text-slate-800">
                            {{ number_format($deliveryCharge, 2) }}৳
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-rose-100 pt-4">
                        <span class="text-base font-semibold text-slate-800">Total Amount</span>
                        <span
                            class="text-2xl font-extrabold text-rose-500"
                            data-cart-total
                            data-delivery-charge="{{ $deliveryCharge }}">
                            {{ number_format($total, 2) }}৳
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3 border-t border-rose-100 bg-slate-50/40 px-6 py-5 sm:flex-row sm:justify-end">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                        Update cart
                    </button>
                    <a href="{{ route('checkout') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-rose-400 px-7 py-3 font-bold text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:bg-rose-500 hover:shadow-rose-300">
                        Proceed to checkout <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

        </form>

    @endif
</div>

{{-- =====================================================
     REMOVE CONFIRMATION MODAL
===================================================== --}}
<div id="remove-cart-confirmation"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-labelledby="remove-cart-title">
    <div class="w-full max-w-sm overflow-hidden rounded-3xl bg-white p-6 shadow-2xl">
        <div class="mb-4 flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-full bg-rose-100 text-rose-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h2 id="remove-cart-title" class="text-lg font-bold text-slate-800">Remove product?</h2>
        </div>

        <p class="text-sm leading-relaxed text-slate-500">
            Do you want to remove <span data-remove-name class="font-semibold text-slate-800">this item</span> from your cart?
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" data-cancel-remove
                    class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                No, keep it
            </button>
            <button type="button" data-confirm-remove
                    class="rounded-full bg-rose-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-600">
                Yes, remove
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/gsap@3.14.2/dist/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const hasGsap = typeof gsap !== 'undefined';

    /* ============================================================
       1. QUANTITY +/- BUTTONS
    ============================================================ */
    document.querySelectorAll('[data-cart-item]').forEach((item) => {
        const input     = item.querySelector('.cart-quantity');
        const price     = parseFloat(item.dataset.price) || 0;
        const lineTotal = item.querySelector('[data-line-total]');

        const updateLine = () => {
            const qty = Math.max(1, Math.min(99, parseInt(input.value, 10) || 1));
            input.value = qty;
            lineTotal.textContent = '$' + (price * qty).toFixed(2);

            // Brief "pulse" on the total for tactile feedback
            if (hasGsap && !reduceMotion) {
                gsap.fromTo(lineTotal,
                    { scale: 1 },
                    { scale: 1.08, duration: 0.15, yoyo: true, repeat: 1, ease: 'power2.out' }
                );
            }

            updateSummary();
        };

        item.querySelector('[data-qty-decrease]')?.addEventListener('click', () => {
            input.value = Math.max(1, parseInt(input.value, 10) - 1);
            updateLine();
        });

        item.querySelector('[data-qty-increase]')?.addEventListener('click', () => {
            input.value = Math.min(99, parseInt(input.value, 10) + 1);
            updateLine();
        });

        input.addEventListener('input', updateLine);
    });

    /* ============================================================
       2. LIVE TOTALS
    ============================================================ */
    const subtotalEl  = document.querySelector('[data-cart-subtotal]');
    const totalEl     = document.querySelector('[data-cart-total]');
    const deliveryFee = parseFloat(totalEl?.dataset.deliveryCharge || 0);

    function updateSummary() {
        let subtotal = 0;
        document.querySelectorAll('[data-cart-item]').forEach((item) => {
            const qty   = parseInt(item.querySelector('.cart-quantity').value, 10) || 0;
            const price = parseFloat(item.dataset.price) || 0;
            subtotal += qty * price;
        });

        if (subtotalEl) subtotalEl.textContent = '$' + subtotal.toFixed(2);
        if (totalEl)    totalEl.textContent    = '$' + (subtotal + deliveryFee).toFixed(2);
    }

    /* ============================================================
       3. REMOVE CONFIRMATION MODAL
    ============================================================ */
    const modal       = document.getElementById('remove-cart-confirmation');
    const confirmBtn  = modal?.querySelector('[data-confirm-remove]');
    const cancelBtn   = modal?.querySelector('[data-cancel-remove]');
    const removeNameEl = modal?.querySelector('[data-remove-name]');
    let pendingButton = null;

    document.querySelectorAll('[data-remove-cart-item]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            pendingButton = btn;

            if (removeNameEl) {
                removeNameEl.textContent = btn.dataset.removeCartItem || 'this item';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animate modal in
            if (hasGsap && !reduceMotion) {
                gsap.fromTo(modal.firstElementChild,
                    { y: 20, opacity: 0, scale: 0.96 },
                    { y: 0, opacity: 1, scale: 1, duration: 0.35, ease: 'back.out(1.4)' }
                );
            }
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        pendingButton = null;
    }

    cancelBtn?.addEventListener('click', closeModal);

    confirmBtn?.addEventListener('click', () => {
        if (!pendingButton) { closeModal(); return; }

        const form = document.getElementById('cart-form');
        const hidden = document.createElement('input');
        hidden.type  = 'hidden';
        hidden.name  = 'remove_product';
        hidden.value = pendingButton.value;
        form.appendChild(hidden);
        form.submit();
    });

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('flex')) closeModal();
    });

    /* ============================================================
       4. ENTRANCE ANIMATIONS
    ============================================================ */
    if (hasGsap && !reduceMotion) {
        const items = document.querySelectorAll('[data-cart-item]');
        if (items.length) {
            gsap.from(items, {
                y: 30,
                opacity: 0,
                duration: 0.6,
                stagger: 0.08,
                ease: 'power3.out',
            });
        }

        const summary = document.querySelector('.mt-8.overflow-hidden');
        if (summary) {
            gsap.from(summary, {
                y: 24,
                opacity: 0,
                duration: 0.7,
                delay: 0.4,
                ease: 'power3.out',
            });
        }
    }
});
</script>
@endpush