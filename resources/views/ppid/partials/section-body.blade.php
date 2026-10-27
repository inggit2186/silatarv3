{{-- Dispatcher: renders section body based on section_type --}}
{{-- Each type has its own hardcoded style (CSS classes already defined in neo-mirai-home.css) --}}
@php
    $type = $section->section_type ?? 'text';
    $meta = is_string($section->metadata)
        ? (json_decode($section->metadata, true) ?? [])
        : ($section->metadata ?? []);

    // Feather icon SVG map for card_grid icons
    $iconMap = [
        'shield'       => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'database'     => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
        'users'        => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'globe'        => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        'user'         => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'phone'        => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'mail'         => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
        'map-pin'      => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
        'clock'        => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'zap'          => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        'calendar'     => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'building'     => '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M8 10h.01M16 10h.01M12 10h.01M8 14h.01M16 14h.01M12 14h.01"/>',
        'clipboard'    => '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>',
        'bar-chart'    => '<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>',
        'list'         => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
        'refresh-cw'  => '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>',
        'file'         => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
        'folder'       => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>',
    ];
    $defaultIconPath = '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>';
@endphp

@switch($type)
    @case('text')
        {!! $section->content ?? ($fallback ?? '') !!}
        @break

    @case('list')
        @if(!empty($meta['items']))
            <ul class="ppid-list">
                @foreach($meta['items'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @endif
        @break

    @case('card_grid')
        @if(!empty($meta['cards']))
            <div class="ppid-grid">
                @foreach($meta['cards'] as $card)
                    @php
                        $href = '#';
                        $hasLink = !empty($card['link']) && $card['link'] !== '#';
                        if ($hasLink) {
                            $link = trim($card['link']);
                            if (str_starts_with($link, 'http://') || str_starts_with($link, 'https://')) {
                                $href = $link;
                            } elseif (str_starts_with($link, '/')) {
                                // internal path — try resolve to named route
                                $cleanLink = ltrim(str_replace('/ppid/', '/', $link), '/');
                                if ($cleanLink === '' || $cleanLink === 'ppid') {
                                    $href = route('ppid');
                                } else {
                                    try {
                                        $href = route('ppid.' . $cleanLink);
                                    } catch (\Exception $e) {
                                        $href = $link;
                                    }
                                }
                            } else {
                                try {
                                    $href = route('ppid.' . $link);
                                } catch (\Exception $e) {
                                    $href = '#';
                                }
                            }
                        }
                    @endphp
                    @if($hasLink)
                        <a href="{{ $href }}" class="ppid-card">
                            <div class="ppid-card-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {!! $iconMap[$card['icon'] ?? ''] ?? $defaultIconPath !!}
                                </svg>
                            </div>
                            <h3 class="ppid-card-title">{{ $card['title'] ?? '' }}</h3>
                            <p class="ppid-card-text">{{ $card['description'] ?? '' }}</p>
                        </a>
                    @else
                        <div class="ppid-card">
                            <div class="ppid-card-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {!! $iconMap[$card['icon'] ?? ''] ?? $defaultIconPath !!}
                                </svg>
                            </div>
                            <h3 class="ppid-card-title">{{ $card['title'] ?? '' }}</h3>
                            <p class="ppid-card-text">{{ $card['description'] ?? '' }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        @break

    @case('timeline')
        @if(!empty($meta['steps']))
            <div class="ppid-timeline">
                @foreach($meta['steps'] as $step)
                    <div class="ppid-timeline-item">
                        <span class="ppid-timeline-number">
                            Langkah {{ $step['number'] ?? $loop->iteration }}
                        </span>
                        <h3 class="ppid-timeline-title">{{ $step['title'] ?? '' }}</h3>
                        <p class="ppid-timeline-text">{{ $step['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
        @break

    @case('stats')
        @if(!empty($meta['stats']))
            <div class="ppid-stats">
                @foreach($meta['stats'] as $stat)
                    <div class="ppid-stat">
                        @if(!empty($stat['icon']))
                            <div style="display: flex; justify-content: center; margin-bottom: 0.75rem; color: var(--ppid-primary);">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {!! $iconMap[$stat['icon']] ?? $defaultIconPath !!}
                                </svg>
                            </div>
                        @endif
                        <div class="ppid-stat-value">{{ $stat['value'] ?? '' }}</div>
                        <div class="ppid-stat-label">{{ $stat['label'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif
        @break

    @case('table')
        @if(!empty($meta['headers']) && !empty($meta['rows']))
            <div style="background: white; border-radius: 1.5rem; overflow: hidden; border: 1px solid oklch(58% 0.06 76 / 0.15);">
                <table class="ppid-table" style="border-radius: 0;">
                    <thead>
                        <tr>
                            @foreach($meta['headers'] as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meta['rows'] as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @break

    @case('form_fields')
        @if(!empty($meta['fields']))
            @if(empty($asFields))
                <form>
            @endif
                @foreach($meta['fields'] as $field)
                    <div class="ppid-form-group">
                        <label class="ppid-form-label">
                            {{ $field['label'] ?? '' }}{{ !empty($field['required']) ? ' *' : '' }}
                        </label>
                        @if(($field['type'] ?? 'text') === 'textarea')
                            <textarea
                                name="{{ $field['name'] ?? '' }}"
                                class="ppid-form-input"
                                rows="5"
                                placeholder="Masukkan {{ strtolower($field['label'] ?? '') }}"
                                {{ !empty($field['required']) ? 'required' : '' }}
                            ></textarea>
                        @elseif(($field['type'] ?? 'text') === 'select')
                            <select
                                name="{{ $field['name'] ?? '' }}"
                                class="ppid-form-input"
                                {{ !empty($field['required']) ? 'required' : '' }}
                            >
                                <option value="">Pilih {{ strtolower($field['label'] ?? '') }}</option>
                                @foreach(($field['options'] ?? []) as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @else
                            <input
                                type="{{ $field['type'] ?? 'text' }}"
                                name="{{ $field['name'] ?? '' }}"
                                class="ppid-form-input"
                                placeholder="Masukkan {{ strtolower($field['label'] ?? '') }}"
                                {{ !empty($field['required']) ? 'required' : '' }}
                            >
                        @endif
                    </div>
                @endforeach
            @if(empty($asFields))
                </form>
            @endif
        @endif
        @break

    @case('image')
        @if(!empty($meta['image_path']))
            <figure style="margin: 0;">
                <img
                    src="{{ asset('storage/' . $meta['image_path']) }}"
                    alt="{{ $meta['alt'] ?? $section->title ?? '' }}"
                    style="width: 100%; border-radius: 0.75rem;"
                >
                @if(!empty($meta['alt']))
                    <figcaption style="text-align: center; font-size: 0.85rem; color: oklch(50% 0 0 / 0.6); margin-top: 0.5rem;">
                        {{ $meta['alt'] }}
                    </figcaption>
                @endif
            </figure>
        @endif
        @break

    @default
        {{-- Unknown type: silently ignore --}}
@endswitch
