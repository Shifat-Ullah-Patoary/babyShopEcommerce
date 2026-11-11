@extends('layouts.app')

@section('content')




{{-- =====================================================
     HERO IMAGE SLIDER
===================================================== --}}

<section id="baby-hero" class="relative w-full overflow-hidden bg-rose-50">

    @if($heroSlides->isNotEmpty())

        <div id="hero-slider"
             class="flex w-full transition-transform duration-700 ease-in-out">

            @foreach($heroSlides as $slide)
                <div class="w-full min-w-full shrink-0">
                    <img
                        src="{{ asset('storage/' . $slide->image) }}"
                        alt="Little Nest baby shop banner {{ $loop->iteration }}"
                        class="block h-auto w-full"
                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                    >
                </div>
            @endforeach

        </div>

        {{-- Slider navigation dots --}}
        @if($heroSlides->count() > 1)
            <div class="absolute inset-x-0 bottom-3 flex justify-center gap-2 sm:bottom-5">

                @foreach($heroSlides as $index => $slide)
                    <button
                        type="button"
                        class="hero-dot h-2.5 w-2.5 rounded-full transition-all duration-300"
                        data-slide="{{ $index }}"
                        aria-label="Go to banner {{ $index + 1 }}"
                    ></button>
                @endforeach

            </div>
        @endif

    @elseif($siteSettings?->hero_image)

        {{-- Old single-image fallback --}}
        <img
            src="{{ asset('storage/' . $siteSettings->hero_image) }}"
            alt="Little Nest baby shop"
            class="block h-auto w-full"
        >

    @else

        <div class="grid min-h-64 place-items-center text-rose-400">
            <p class="font-semibold">Welcome to Little Nest</p>
        </div>

    @endif

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

/* ---------- HERO IMAGE SLIDER ---------- */

const slider = document.querySelector('#hero-slider');

if (slider) {
    const dots = document.querySelectorAll('.hero-dot');
    const totalSlides = slider.children.length;

    let currentSlide = 0;
    let autoSlide;

    function showSlide(index) {
        currentSlide = index;

        slider.style.transform =
            `translateX(-${currentSlide * 100}%)`;

        dots.forEach((dot, i) => {
            dot.classList.toggle(
                'bg-rose-500',
                i === currentSlide
            );

            dot.classList.toggle(
                'bg-white',
                i !== currentSlide
            );

            dot.classList.toggle(
                'ring-2',
                i === currentSlide
            );

            dot.classList.toggle(
                'ring-rose-300',
                i === currentSlide
            );
        });
    }

    function startAutoSlide() {
        if (totalSlides <= 1) return;

        autoSlide = setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }, 4000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlide);
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });

    showSlide(0);

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        startAutoSlide();
    }
}

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