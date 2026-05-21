@props([
    'badge' => null,
    'title' => null,
])

<section {{ $attributes->merge(['class' => 'silatar-hero']) }}>
    <div class="silatar-hero-wrap">
        @if ($badge)
            <div class="silatar-hero-badge">
                <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                <span>{{ $badge }}</span>
            </div>
        @endif

        @if ($title)
            <h1 class="silatar-hero-title">
                {{ $title }}
            </h1>
        @endif

        {{ $slot }}
    </div>
</section>
