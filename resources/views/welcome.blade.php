@extends('layouts.app')

@section('content')

{{-- =====================================================
     HERO
===================================================== --}}

<section
    id="baby-hero"
    class="relative isolate flex w-full flex-col overflow-hidden
           bg-gradient-to-br from-rose-50 via-emerald-50 to-amber-50
           sm:min-h-[420px] sm:flex-row sm:items-center sm:px-16 sm:py-20"
    @if($siteSettings?->hero_image)
        style="
            background-image:
                linear-gradient(90deg, rgba(253,242,248,.25), rgba(236,253,245,.10)),
                url('{{ asset('storage/'.$siteSettings->hero_image) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        "
    @endif
>
    {{-- Mobile image: displays the complete image --}}
    @if($siteSettings?->hero_image)
        <img
            src="{{ asset('storage/'.$siteSettings->hero_image) }}"
            alt="Little Nest baby shop"
            class="block h-auto w-full sm:hidden"
        >
    @endif

    {{-- Hero content --}}
    {{-- <div class="relative flex w-full items-center justify-between gap-8 px-5 py-10 sm:px-0 sm:py-0">
        <div class="max-w-2xl">
            <div class="hero-badge mb-4 inline-flex items-center gap-2 rounded-full bg-white/70 px-4 py-1.5 shadow-sm backdrop-blur">
                <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                <p class="text-[11px] font-extrabold uppercase tracking-[.18em] text-rose-500">
                    Soft things for small people
                </p>
            </div>

            <h1 class="hero-title my-4 max-w-xl text-4xl font-extrabold leading-tight tracking-tighter text-slate-800 sm:text-7xl">
                {{ $siteSettings?->hero_title }}
            </h1>

            <p class="hero-description max-w-md text-base leading-relaxed text-slate-500 sm:text-lg">
                {{ $siteSettings?->hero_description }}
            </p>

            <div class="hero-cta mt-6 flex flex-wrap gap-3">
                <a href="#products"
                   class="rounded-full bg-rose-400 px-6 py-3 font-bold text-white shadow-lg">
                    Shop the collection →
                </a>

                <a href="#products"
                   class="rounded-full bg-white/80 px-6 py-3 font-semibold text-slate-700 shadow-sm">
                    Browse new arrivals
                </a>
            </div>
        </div>

        <div class="hero-heart hidden select-none text-[14rem] leading-none text-rose-300/70 sm:block" aria-hidden="true">
            ♡
        </div>
    </div> --}}
</section>


{{-- =====================================================
     SECTION HEADER
===================================================== --}}
<div id="products" class="my-16 flex flex-wrap items-end justify-between gap-4">
    <div>
        <div class="mb-3 flex items-center gap-3">
            <span class="h-1 w-10 rounded-full bg-rose-400"></span>
            <p class="text-xs font-extrabold uppercase tracking-[.18em] text-rose-500">
                Our collection
            </p>
        </div>
        <h2 class="text-4xl font-extrabold tracking-tighter text-slate-800 sm:text-5xl">
            Shop the nest
        </h2>
        <p class="mt-3 max-w-md text-slate-500">
            Hand-picked pieces for tiny hands and tired parents.
        </p>
    </div>

    <span class="rounded-full bg-rose-50 px-4 py-1.5 text-sm font-semibold text-rose-500">
        {{ $products->count() }} lovely finds
    </span>
</div>

{{-- =====================================================
     PRODUCT GRID
===================================================== --}}
<div class="grid grid-cols-2 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @forelse($products as $product)
        <article
            data-product-card
            class="group relative flex flex-col overflow-hidden rounded-3xl border border-rose-100/70 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-rose-100/70"
        >
            {{-- Image --}}
            <div class="relative h-60 overflow-hidden bg-gradient-to-br from-rose-50 to-amber-50">
                @if($product->image_url)
                    <img
                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                        src="{{ filter_var($product->image_url, FILTER_VALIDATE_URL) ? $product->image_url : asset('storage/' . $product->image_url) }}"
                        alt="{{ $product->name }}"
                        loading="lazy"
                    >
                @else
                    <div class="grid h-full w-full place-items-center text-6xl text-rose-300">✦</div>
                @endif

                {{-- Soft gradient at bottom of image --}}
                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-white/80 to-transparent"></div>
            </div>

            {{-- Content --}}
            <div class="flex flex-1 flex-col p-5">
                <h3 class="text-lg font-bold text-slate-800 transition group-hover:text-rose-500">
                    {{ $product->name }}
                </h3>
                <p class="my-2 min-h-12 text-sm leading-relaxed text-slate-500 line-clamp-3">
                    {{ $product->description }}
                </p>

                <div class="mt-auto pt-3">
                    <strong class="mb-4 block text-xl font-extrabold text-slate-800">
                        {{ number_format($product->price, 2) }}৳
                    </strong>

                    <div class="grid grid-cols-2 gap-2">
                        <form method="post" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full rounded-full border border-rose-200 bg-rose-50 px-3 py-2.5 text-sm font-bold text-rose-500 transition hover:border-rose-300 hover:bg-rose-100"
                            >
                                Add to Cart
                            </button>
                        </form>
                        <form method="post" action="{{ route('buy.now', $product) }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full rounded-full bg-slate-800 px-3 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700"
                            >
                                Buy Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="col-span-full rounded-2xl border border-dashed border-rose-200 bg-rose-50/50 p-12 text-center">
            <p class="text-lg font-semibold text-slate-600">No products yet. Check back soon 🌸</p>
            <p class="mt-2 text-sm text-slate-400">We're curating beautiful new arrivals just for you.</p>
        </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/gsap@3.14.2/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.14.2/dist/ScrollTrigger.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    /* ---------- HERO ---------- */
    const hero = document.querySelector('#baby-hero');
    if (hero) {
        const blobs = hero.querySelectorAll('.hero-blob');
        const badge = hero.querySelector('.hero-badge');
        const title = hero.querySelector('.hero-title');
        const desc  = hero.querySelector('.hero-description');
        const cta   = hero.querySelector('.hero-cta');
        const heart = hero.querySelector('.hero-heart');

        // Floating blobs — continuous gentle drift
        blobs.forEach((blob, i) => {
            gsap.to(blob, {
                x: 'random(-40, 40)',
                y: 'random(-40, 40)',
                duration: 6 + i * 2,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });
        });

        // Heart gently rotates back and forth forever
        if (heart) {
            gsap.to(heart, {
                rotate: -8,
                duration: 3,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
                transformOrigin: 'center'
            });
        }

        // Entrance timeline — everything else
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

        if (badge) tl.from(badge, { y: 20, opacity: 0, duration: 0.6 });
        if (title) tl.from(title, { y: 40, opacity: 0, duration: 0.9 }, '-=0.3');
        if (desc)  tl.from(desc,  { y: 25, opacity: 0, duration: 0.7 }, '-=0.5');
        if (cta)   tl.from(cta.children, { y: 20, opacity: 0, stagger: 0.12, duration: 0.6 }, '-=0.4');
    }

    /* ---------- PRODUCT CARDS — staggered scroll reveal ---------- */
    const cards = document.querySelectorAll('[data-product-card]');
    cards.forEach((card, i) => {
        gsap.from(card, {
            y: 50,
            opacity: 0,
            duration: 0.8,
            delay: (i % 4) * 0.08,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: card,
                start: 'top 90%',
                toggleActions: 'play none none reverse'
            }
        });
    });

    ScrollTrigger.refresh();
    window.addEventListener('load', () => ScrollTrigger.refresh());
});
</script>
@endpush