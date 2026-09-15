{{-- TinyMCE Editor Component --}}
@props([
    'name' => 'content',
    'id' => 'tinymce-editor',
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

    {{-- TinyMCE will replace this textarea --}}
    <textarea id="{{ $id }}" name="{{ $name }}" class="form-input" style="min-height: {{ $height }}px;">{!! $content !!}</textarea>

    <p class="text-xs mt-2" style="color: var(--text-muted);">
        <span class="flex items-center gap-1">
            <svg class="w-4 h-4" style="color: var(--info);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Gunakan toolbar untuk formatting. Upload gambar: drag & drop atau klik tombol gambar. Undo: Ctrl+Z</span>
        </span>
    </p>

    @error($name)
    <p class="text-sm mt-1" style="color: var(--danger);">{{ $message }}</p>
    @enderror
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('js/tinymce/skins/content/default/content.min.css') }}">
<style>
    /* TinyMCE container styling */
    .tox-tinymce {
        border-radius: 0.5rem !important;
        border: 1px solid var(--border) !important;
    }

    .tox .tox-menubar + .tox-toolbar-overlord {
        background-color: var(--bg-secondary) !important;
    }
</style>
@endpush

@push('scripts')
{{-- TinyMCE Core --}}
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

{{-- Initialize TinyMCE --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if TinyMCE is loaded
        if (typeof tinymce === 'undefined') {
            console.error('TinyMCE not loaded');
            document.getElementById('{{ $id }}').style.display = 'block';
            return;
        }

        // Initialize TinyMCE
        tinymce.init({
            selector: '#{{ $id }}',
            height: {{ $height }},
            menubar: 'file edit view insert format tools table help',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | fontfamily fontsize | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat | help',

            // Font families - Hybrid (Google Fonts + System Fonts)
            font_family_formats: 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Times New Roman=times new roman,times;Verdana=verdana,geneva;Tahoma=tahoma,geneva;Trebuchet MS=trebuchet ms,geneva;Poppins=Poppins,sans-serif;Inter=Inter,sans-serif;Roboto=Roboto,sans-serif;Open Sans=Open Sans,sans-serif;Lato=Lato,sans-serif;Montserrat=Montserrat,sans-serif;Roboto Slab=Roboto Slab,serif;Merriweather=Merriweather,serif;Playfair Display=Playfair Display,serif;Source Code Pro=Source Code Pro,monospace;',

            // Font sizes
            font_size_formats: '8px 10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 48px 56px 72px',

            // Default styles
            content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.6; padding: 1rem; } img { max-width: 100%; height: auto; }',

            // Image upload configuration
            automatic_uploads: true,

            // Link settings
            link_default_target: '_blank',

            // Table settings
            table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },

            // Setup callback
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            },

            // Misc settings
            branding: false,
            promotion: false,
            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,
            statusbar: true,
            resize: true,
            paste_data_images: true,
        });
    });
</script>
@endpush
