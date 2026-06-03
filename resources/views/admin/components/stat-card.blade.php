{{--
    Stat Card Component
    Used for displaying statistics on the admin dashboard

    Props:
    - icon: SVG icon path or HTML
    - value: The numeric value to display
    - label: Description label for the stat
    - trend: Optional trend direction ('up', 'down', 'neutral')
    - trendValue: The percentage or value of the trend
    - color: Color variant ('cyan', 'emerald', 'amber', 'rose', 'violet', 'blue', 'indigo', 'teal')
    - href: Optional link for the card
--}}

@props([
    'icon' => '',
    'value' => '0',
    'label' => '',
    'trend' => null,
    'trendValue' => '',
    'color' => 'cyan',
    'href' => null,
    'decoration' => true,
])

@php
$colorClasses = [
    'cyan' => 'bg-cyan-100 text-cyan-600',
    'emerald' => 'bg-emerald-100 text-emerald-600',
    'amber' => 'bg-amber-100 text-amber-600',
    'rose' => 'bg-rose-100 text-rose-600',
    'violet' => 'bg-violet-100 text-violet-600',
    'blue' => 'bg-blue-100 text-blue-600',
    'indigo' => 'bg-indigo-100 text-indigo-600',
    'teal' => 'bg-teal-100 text-teal-600',
];

$decorationColors = [
    'cyan' => 'bg-cyan-400',
    'emerald' => 'bg-emerald-400',
    'amber' => 'bg-amber-400',
    'rose' => 'bg-rose-400',
    'violet' => 'bg-violet-400',
    'blue' => 'bg-blue-400',
    'indigo' => 'bg-indigo-400',
    'teal' => 'bg-teal-400',
];

$trendClasses = [
    'up' => 'bg-emerald-50 text-emerald-700',
    'down' => 'bg-rose-50 text-rose-700',
    'neutral' => 'bg-slate-100 text-slate-600',
];

$iconColorClass = $colorClasses[$color] ?? $colorClasses['cyan'];
$decorationColorClass = $decorationColors[$color] ?? $decorationColors['cyan'];
$trendClass = $trendClasses[$trend] ?? $trendClasses['neutral'];
@endphp

@if($href)
    <a href="{{ $href }}" class="stat-card group block">
@else
    <div class="stat-card">
@endif

    @if($decoration)
        <div class="stat-card-decoration {{ $decorationColorClass }}"></div>
    @endif

    <div class="flex items-start justify-between">
        <div class="stat-card-icon {{ $iconColorClass }}">
            {!! $icon !!}
        </div>

        @if($trend)
            <div class="stat-card-trend {{ $trendClass }}">
                @if($trend === 'up')
                    <svg class="stat-card-trend-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                @elseif($trend === 'down')
                    <svg class="stat-card-trend-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                @endif
                <span>{{ $trendValue }}</span>
            </div>
        @endif
    </div>

    <div class="stat-card-value">{{ $value }}</div>
    <div class="stat-card-label">{{ $label }}</div>

@if($href)
    </a>
@else
    </div>
@endif