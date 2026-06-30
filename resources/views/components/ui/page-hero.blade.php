@props([
    'badge' => null,
    'title' => null,
])

<section class="hero-page">
    <div class="hero-page-content" data-reveal="">
        @if($badge)
            <p class="section-label">{{ $badge }}</p>
        @endif
        @if($title)
            <h1 class="hero-page-title">{{ $title }}</h1>
        @endif
        {{ $slot }}
    </div>
</section>
