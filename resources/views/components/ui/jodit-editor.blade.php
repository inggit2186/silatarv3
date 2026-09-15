{{-- Jodit Editor Component with PPID Plugins --}}
@props([
    'name' => 'content',
    'id' => 'jodit-editor',
    'label' => 'Konten',
    'content' => '',
    'height' => 500,
])

<div class="mb-4">
    <label class="form-label flex items-center gap-2">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
        </svg>
        {{ $label }}
    </label>

    {{-- Jodit will replace this textarea --}}
    <textarea id="{{ $id }}" name="{{ $name }}" class="form-input" style="min-height: {{ $height }}px;">{!! $content !!}</textarea>

    <p class="text-xs mt-2" style="color: var(--text-muted);">
        <span class="flex items-center gap-1">
            <svg class="w-4 h-4" style="color: var(--info);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Upload gambar: drag & drop, paste, atau klik toolbar. Klik gambar untuk resize/alignment. Undo: Ctrl+Z</span>
        </span>
    </p>

    @error($name)
    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
    @enderror
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('js/jodit/jodit.min.css') }}">
<style>
    /* Jodit container styling */
    .jodit-container {
        border-radius: 0.5rem !important;
        border: 1px solid var(--border) !important;
    }

    .jodit-toolbar {
        background-color: var(--bg-secondary) !important;
        flex-wrap: wrap;
    }

    /* Image selection styling */
    .jodit-editor img.selected {
        outline: 3px solid var(--primary, #0891b2) !important;
        outline-offset: 2px !important;
    }

    /* Image alignment classes */
    .jodit-editor img.align-left {
        display: block !important;
        margin-left: 0 !important;
        margin-right: auto !important;
    }

    .jodit-editor img.align-center {
        display: block !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .jodit-editor img.align-right {
        display: block !important;
        margin-left: auto !important;
        margin-right: 0 !important;
    }

    /* Image sizing */
    .jodit-editor img {
        max-width: 100% !important;
        height: auto !important;
        cursor: pointer !important;
    }

    /* Toolbar button hover */
    .jodit-toolbar-button:hover {
        background: var(--secondary) !important;
    }

    .jodit-toolbar-button:active {
        background: var(--primary) !important;
        color: white !important;
    }

    /* Loading placeholder */
    .jodit-editor img[src*="data:image/svg"] {
        opacity: 0.7;
        border: 2px dashed var(--border) !important;
    }
</style>
@endpush

@push('scripts')
{{-- Jodit Core --}}
<script src="{{ asset('js/jodit/jodit.min.js') }}"></script>

{{-- Load PPID Plugins --}}
<script src="{{ asset('js/jodit/plugins/ppid-image-upload.js') }}"></script>
<script src="{{ asset('js/jodit/plugins/ppid-alignment.js') }}"></script>
<script src="{{ asset('js/jodit/plugins/ppid-size-presets.js') }}"></script>

{{-- Initialize Jodit --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Jodit is loaded
        if (typeof Jodit === 'undefined') {
            console.error('Jodit not loaded');
            document.getElementById('{{ $id }}').style.display = 'block';
            return;
        }

        // Initialize Jodit
        const editor = Jodit.make('#{{ $id }}', {
            height: {{ $height }},
            minHeight: {{ $height }},
            maxHeight: {{ $height + 200 }},

            // Toolbar configuration - use default (full toolbar)
            toolbar: true,
            toolbarButtonSize: 'middle',

            // Font families - Hybrid (Google Fonts + System Fonts)
            allowFonts: {
                'Arial': 'arial,helvetica,sans-serif',
                'Courier New': 'courier new,courier',
                'Georgia': 'georgia,palatino',
                'Times New Roman': 'times new roman,times',
                'Verdana': 'verdana,geneva',
                'Tahoma': 'tahoma,geneva',
                'Poppins': 'Poppins,sans-serif',
                'Inter': 'Inter,sans-serif',
                'Roboto': 'Roboto,sans-serif',
                'Open Sans': 'Open Sans,sans-serif',
                'Lato': 'Lato,sans-serif',
                'Montserrat': 'Montserrat,sans-serif',
                'Roboto Slab': 'Roboto Slab,serif',
                'Merriweather': 'Merriweather,serif',
                'Playfair Display': 'Playfair Display,serif',
            },

            // Font sizes
            allowSizes: ['8', '10', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48', '56', '72'],

            // Default font
            defaultFont: 'Inter',

            // Colors
            allowColorPicker: true,
            colors: [
                '#000000', '#434343', '#666666', '#999999', '#B7B7B7', '#CCCCCC', '#D9D9D9', '#EFEFEF', '#F3F3F3', '#FFFFFF',
                '#980000', '#FF0000', '#FF9900', '#FFFF00', '#00FF00', '#00FFFF', '#4A86E8', '#0000FF', '#9900FF', '#FF00FF',
                '#E6B8AF', '#F4CCCC', '#FCE5CD', '#FFF2CC', '#D9EAD3', '#D0E0E3', '#C9DAF8', '#CFE2F3', '#D9D2E9', '#EAD1DC',
                '#DD7E6B', '#EA9999', '#F9CB9C', '#FFE599', '#B6D7A8', '#A2C4C9', '#A4C2F4', '#9FC5E8', '#B4A7D6', '#D5A6BD',
                '#CC4125', '#E06666', '#F6B26B', '#FFD966', '#93C47D', '#76A5AF', '#6D9EEB', '#6FA8DC', '#8E7CC3', '#C27BA0',
                '#A61C00', '#CC0000', '#E69138', '#F1C232', '#6AA84F', '#45818E', '#3C78D8', '#3D85C6', '#674EA7', '#A64D79',
                '#85200C', '#990000', '#B45F06', '#BF9000', '#38761D', '#134F5C', '#1155CC', '#0B5394', '#351C75', '#741B47',
                '#5B0F00', '#660000', '#783F04', '#7F6000', '#274E13', '#0C343D', '#1C4587', '#073763', '#20124D', '#4C1130'
            ],

            // Image configuration
            imageProcessor: true,
            imageDefaultWidth: 0,
            imageDefaultHeight: 0,
            imageIncreaseSize: 10,

            // Link configuration
            linkDefaultTarget: '_blank',

            // Table configuration
            tableAllowCellBackgroundColor: true,
            tableAllowCellBorderColor: true,
            tableAllowCellWidth: true,

            // Spellcheck
            spellcheck: true,

            // Placeholder
            placeholder: 'Mulai menulis konten di sini...',

            // Events
            events: {
                change: function() {
                    // Sync content back to textarea
                    this.sync();
                },
                afterInit: function() {
                    console.log('Jodit editor initialized with PPID plugins');
                },
                // Handle image click for selection
                click: function(e) {
                    const img = e.target;
                    if (img.tagName === 'IMG') {
                        // Deselect all other images
                        this.editor.querySelectorAll('img.selected').forEach(function(el) {
                            el.classList.remove('selected');
                        });
                        // Select this image
                        img.classList.add('selected');
                    }
                }
            },

            // Misc settings
            askBeforePasteHTML: false,
            askBeforePasteFromWord: false,
            defaultActionOnPaste: 'insert_clear_html',
            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: false,

            // Disable default paste handling (we handle it in plugins)
            disablePlugins: 'paste,paste-storage',
        });

        // Store editor instance globally for plugins
        window.joditEditor = editor;
    });
</script>
@endpush
