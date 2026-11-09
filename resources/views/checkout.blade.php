@extends('layouts.app')

@section('content')

@if(isset($placedOrder) && $placedOrder)

    {{-- =====================================================
         SUCCESS STATE — order just placed
    ===================================================== --}}
    <div class="mx-auto max-w-md py-16 text-center">

        <div class="mx-auto mb-6 grid h-20 w-20 place-items-center rounded-full bg-gradient-to-br from-emerald-200 to-emerald-300 text-4xl text-emerald-700 shadow-inner">
            ✓
        </div>

        <h1 class="text-3xl font-extrabold tracking-tight text-slate-800">
            Order placed successfully
        </h1>
        <p class="mt-3 text-slate-500">
            Order <span class="font-bold text-rose-500">#{{ $placedOrder->id }}</span>
            for <span class="font-bold text-slate-800">৳{{ number_format($placedOrder->total, 2) }}</span>
        </p>
        <p class="mx-auto mt-4 max-w-sm text-sm text-slate-500">
            We'll contact you shortly to confirm delivery.
        </p>

        {{-- Order items --}}
        @if($placedOrder->items->isNotEmpty())
            <div class="mt-8 overflow-hidden rounded-2xl border border-rose-100 bg-white text-left">
                <div class="border-b border-rose-100 bg-rose-50/60 px-5 py-3">
                    <p class="text-sm font-bold text-slate-800">Your items</p>
                </div>
                <ul class="divide-y divide-rose-100">
                    @foreach($placedOrder->items as $item)
                        <li class="flex items-center justify-between gap-3 px-5 py-3 text-sm">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-800">{{ $item->product_name }}</p>
                                <p class="text-xs text-slate-500">Qty {{ $item->quantity }}</p>
                            </div>
                            <span class="font-bold text-slate-800">
                                ৳{{ number_format($item->price * $item->quantity, 2) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-10">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 rounded-full bg-rose-400 px-7 py-3.5 font-bold text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:bg-rose-500 hover:shadow-rose-300">
                Back to shop <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>

@else

    {{-- =====================================================
         NORMAL CHECKOUT — form + summary
    ===================================================== --}}

    {{-- HEADER --}}
    <div id="checkout-header" class="my-10 sm:my-14">
        <div class="mb-3 flex items-center gap-3">
            <span class="h-1 w-10 rounded-full bg-rose-400"></span>
            <p class="text-xs font-extrabold uppercase tracking-[.18em] text-rose-500">
                Almost there
            </p>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl">
            Place your order
        </h1>
        <p class="mt-3 max-w-xl text-slate-500">
            We'll contact you to confirm delivery. After submitting, you can edit these details and place another order.
        </p>
    </div>

    <div class="grid gap-6 lg:grid-cols-5">

        {{-- FORM --}}
        <form id="checkout-form"
              method="post"
              action="{{ route('checkout.place') }}"
              class="lg:col-span-3 rounded-3xl border border-rose-100 bg-white p-7 shadow-sm sm:p-8">

            @csrf

            <h2 class="mb-6 flex items-center gap-2 text-lg font-bold text-slate-800">
                <span class="grid h-7 w-7 place-items-center rounded-full bg-rose-100 text-sm text-rose-500">1</span>
                Delivery details
            </h2>

            {{-- Name --}}
            <label class="mb-5 block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Your name</span>
                <input
                    type="text"
                    name="customer_name"
                    value="{{ old('customer_name') }}"
                    required
                    placeholder="e.g. Ayesha Rahman"
                    class="w-full rounded-xl border border-rose-100 bg-rose-50/50 px-4 py-3 text-slate-800 placeholder:text-slate-400 transition focus:border-rose-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-100"
                >
                @error('customer_name')
                    <span class="mt-1.5 block text-xs font-semibold text-rose-500">{{ $message }}</span>
                @enderror
            </label>

            {{-- Mobile --}}
            <label class="mb-5 block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Mobile number</span>
                <input
                    type="tel"
                    name="mobile"
                    value="{{ old('mobile') }}"
                    required
                    placeholder="e.g. +880 1XXX XXXXXX"
                    class="w-full rounded-xl border border-rose-100 bg-rose-50/50 px-4 py-3 text-slate-800 placeholder:text-slate-400 transition focus:border-rose-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-100"
                >
                @error('mobile')
                    <span class="mt-1.5 block text-xs font-semibold text-rose-500">{{ $message }}</span>
                @enderror
            </label>

            {{-- Address --}}
            <label class="mb-5 block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Delivery address</span>
                <textarea
                    name="address"
                    rows="4"
                    required
                    placeholder="House, road, area, city..."
                    class="w-full rounded-xl border border-rose-100 bg-rose-50/50 px-4 py-3 text-slate-800 placeholder:text-slate-400 transition focus:border-rose-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-100"
                >{{ old('address') }}</textarea>
                @error('address')
                    <span class="mt-1.5 block text-xs font-semibold text-rose-500">{{ $message }}</span>
                @enderror
            </label>

            {{-- Submit --}}
            <button type="submit"
                    class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-full bg-rose-400 px-7 py-3.5 font-bold text-white shadow-lg shadow-rose-200 transition hover:-translate-y-0.5 hover:bg-rose-500 hover:shadow-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-2 sm:w-auto">
                Place order
                <span aria-hidden="true">→</span>
            </button>
        </form>

        {{-- SUMMARY --}}
        <aside class="lg:col-span-2 space-y-4">

            <div class="overflow-hidden rounded-3xl border border-rose-100 bg-white shadow-sm">
                <div class="border-b border-rose-100 bg-gradient-to-r from-rose-50/60 to-emerald-50/40 px-6 py-5">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-slate-800">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-rose-100 text-sm text-rose-500">2</span>
                        Order summary
                    </h2>
                </div>

                <div class="space-y-3 px-6 py-6 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-800">
                            {{ number_format($subtotal ?? 0, 2) }}৳
                        </span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Delivery charge</span>
                        <span class="font-semibold text-slate-800">
                            {{ number_format($deliveryCharge, 2) }}৳
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-rose-100 pt-4">
                        <span class="text-base font-semibold text-slate-800">Total</span>
                        <span class="text-2xl font-extrabold text-rose-500">
                            {{ number_format($total ?? 0, 2) }}৳
                        </span>
                    </div>
                </div>
            </div>

            {{-- Trust badges --}}
            <div class="rounded-3xl border border-emerald-100 bg-emerald-50/40 p-5">
                <p class="flex items-center gap-2 text-sm font-semibold text-emerald-800">
                    <span class="grid h-6 w-6 place-items-center rounded-full bg-emerald-200 text-emerald-700">✓</span>
                    Safe & secure checkout
                </p>
                <p class="mt-2 text-xs leading-relaxed text-emerald-700">
                    Your details are only used to deliver your order. We'll call you before dispatch to confirm.
                </p>
            </div>

            <a href="{{ route('cart') }}"
               class="flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">
                <span aria-hidden="true">←</span> Back to cart
            </a>

        </aside>
    </div>

@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.14.2/dist/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const hasGsap = typeof gsap !== 'undefined';

    /* ============================================================
       SWEETALERT CONFIRMATION — only on the actual form
    ============================================================ */
    const checkoutForm = document.getElementById('checkout-form');

    if (checkoutForm && typeof Swal !== 'undefined') {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!checkoutForm.reportValidity()) {
                return;
            }

            Swal.fire({
                title: 'Place your order?',
                html: '<p class="text-slate-500">We\'ll contact you shortly to confirm delivery.</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, place order',
                cancelButtonText: 'Review again',
                confirmButtonColor: '#fb7185',
                cancelButtonColor: '#94a3b8',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-3xl',
                    title: 'text-2xl font-extrabold text-slate-800',
                    html: 'text-sm',
                    confirmButton: 'rounded-full px-6 py-3 font-bold shadow-lg shadow-rose-200',
                    cancelButton: 'rounded-full px-6 py-3 font-semibold',
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Placing your order...',
                        text: 'Just a moment.',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-3xl',
                            title: 'text-xl font-bold text-slate-800',
                        },
                    });

                    checkoutForm.submit();
                }
            });
        });
    }

    /* ============================================================
       ENTRANCE ANIMATIONS — only on the form page
    ============================================================ */
    if (hasGsap && !reduceMotion) {
        const header = document.getElementById('checkout-header');
        const form = document.getElementById('checkout-form');

        if (header) {
            gsap.from(header.children, {
                y: 25, opacity: 0, duration: 0.7, stagger: 0.1, ease: 'power3.out'
            });
        }
        if (form) {
            gsap.from(form, {
                y: 30, opacity: 0, duration: 0.8, delay: 0.2, ease: 'power3.out'
            });
            gsap.from(form.querySelectorAll('label'), {
                y: 20, opacity: 0, duration: 0.6, stagger: 0.08, delay: 0.35, ease: 'power2.out'
            });
        }

        const summary = document.querySelector('aside');
        if (summary) {
            gsap.from(summary.children, {
                y: 30, opacity: 0, duration: 0.7, stagger: 0.1, delay: 0.3, ease: 'power3.out'
            });
        }
    }
});
</script>
@endpush