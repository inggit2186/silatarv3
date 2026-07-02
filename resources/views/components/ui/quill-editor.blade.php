<div class="neo-editor-wrapper">
    <label class="neo-form-label">
        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
        </svg>
        {{ $label ?? 'Konten Berita' }}
    </label>

    <div class="neo-editor-container">
        <div id="{{ $id ?? 'quill-editor' }}" class="quill-editor"></div>
        <input type="hidden" name="{{ $name ?? 'content' }}" id="{{ $name ?? 'content' }}_input" value="{!! $content ?? '' !!}">
    </div>

    <p class="neo-form-hint mt-2 flex items-center gap-2">
        <span class="inline-flex items-center gap-1">
            <svg class="h-4 w-4" style="color: var(--cyan);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Gunakan toolbar untuk formatting. Upload gambar: klik toolbar atau drag &amp; drop. Atur alignment dengan ikon kiri/tengah/kanan.</span>
        </span>
    </p>

    @error($name ?? 'content')
    <p class="neo-form-error">{{ $message }}</p>
    @enderror
</div>

@push('styles')
{{-- Quill.js CSS --}}
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
    /* Quill Editor Container Styling */
    .neo-editor-container {
        border-radius: 12px;
        overflow: hidden;
    }

    .quill-editor {
        min-height: 400px;
        max-height: 600px;
        overflow-y: auto;
    }

    /* Quill Theme Overrides - Neo Mirai Style */
    .ql-toolbar.ql-snow {
        border: 1px solid var(--line) !important;
        border-bottom: none !important;
        border-radius: 12px 12px 0 0 !important;
        background: var(--paper-soft) !important;
        padding: 8px 12px !important;
        font-family: inherit !important;
    }

    .ql-container.ql-snow {
        border: 1px solid var(--line) !important;
        border-radius: 0 0 12px 12px !important;
        background: var(--paper) !important;
        font-family: inherit !important;
        font-size: 15px !important;
    }

    .ql-editor {
        min-height: 350px;
        max-height: 500px;
        padding: 16px 20px !important;
        font-family: inherit !important;
        font-size: 15px !important;
        line-height: 1.7 !important;
        color: var(--ink) !important;
    }

    .ql-editor.ql-blank::before {
        color: var(--ink-soft) !important;
        font-style: normal !important;
        left: 20px !important;
        right: 20px !important;
    }

    /* Toolbar Button Styling */
    .ql-toolbar.ql-snow .ql-btn,
    .ql-toolbar.ql-snow button {
        border-radius: 6px !important;
        transition: all 0.2s ease !important;
    }

    .ql-toolbar.ql-snow .ql-btn:hover,
    .ql-toolbar.ql-snow button:hover {
        background: var(--paper-hover) !important;
    }

    .ql-toolbar.ql-snow .ql-btn.ql-active,
    .ql-toolbar.ql-snow button.ql-active {
        background: var(--cyan-soft) !important;
        color: var(--cyan) !important;
    }

    /* Format buttons */
    .ql-toolbar.ql-snow .ql-formats button:hover,
    .ql-toolbar.ql-snow .ql-formats button.ql-active {
        color: var(--cyan) !important;
    }

    .ql-toolbar.ql-snow .ql-formats button:hover .ql-stroke,
    .ql-toolbar.ql-snow .ql-formats button.ql-active .ql-stroke {
        stroke: var(--cyan) !important;
    }

    .ql-toolbar.ql-snow .ql-formats button:hover .ql-fill,
    .ql-toolbar.ql-snow .ql-formats button.ql-active .ql-fill {
        fill: var(--cyan) !important;
    }

    /* Dropdown styling */
    .ql-toolbar.ql-snow .ql-picker {
        border-radius: 6px !important;
    }

    .ql-toolbar.ql-snow .ql-picker-options {
        background: var(--paper) !important;
        border: 1px solid var(--line) !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .ql-toolbar.ql-snow .ql-picker-item:hover {
        background: var(--paper-hover) !important;
    }

    .ql-toolbar.ql-snow .ql-picker-label:hover {
        background: var(--paper-hover) !important;
    }

    /* Image Alignment Toolbar Buttons */
    .ql-toolbar.ql-snow .image-alignment {
        display: flex !important;
        gap: 2px !important;
    }

    .ql-toolbar.ql-snow .image-alignment button {
        width: 28px !important;
        height: 28px !important;
        padding: 2px !important;
    }

    .ql-toolbar.ql-snow .image-alignment button svg {
        width: 18px !important;
        height: 18px !important;
    }

    /* Tooltip styling */
    .ql-tooltip {
        background: var(--paper) !important;
        border: 1px solid var(--line) !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        color: var(--ink) !important;
        font-family: inherit !important;
    }

    .ql-tooltip input[type="text"] {
        border: 1px solid var(--line) !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        background: var(--paper-soft) !important;
        color: var(--ink) !important;
    }

    .ql-tooltip input[type="text"]:focus {
        border-color: var(--cyan) !important;
        outline: none !important;
    }

    .ql-tooltip a {
        color: var(--cyan) !important;
    }

    /* Snow theme overrides */
    .ql-snow .ql-stroke {
        stroke: var(--ink) !important;
    }

    .ql-snow .ql-fill {
        fill: var(--ink) !important;
    }

    .ql-snow .ql-picker {
        color: var(--ink) !important;
    }

    /* Scrollbar styling */
    .quill-editor::-webkit-scrollbar,
    .ql-editor::-webkit-scrollbar {
        width: 8px;
    }

    .quill-editor::-webkit-scrollbar-track,
    .ql-editor::-webkit-scrollbar-track {
        background: var(--paper-soft);
        border-radius: 4px;
    }

    .quill-editor::-webkit-scrollbar-thumb,
    .ql-editor::-webkit-scrollbar-thumb {
        background: var(--line);
        border-radius: 4px;
    }

    .quill-editor::-webkit-scrollbar-thumb:hover,
    .ql-editor::-webkit-scrollbar-thumb:hover {
        background: var(--ink-soft);
    }

    /* Content styling when rendered */
    .ql-editor h1 {
        font-size: 2rem !important;
        font-weight: 700 !important;
        margin: 1.5rem 0 1rem !important;
        color: var(--ink) !important;
    }

    .ql-editor h2 {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        margin: 1.25rem 0 0.75rem !important;
        color: var(--ink) !important;
    }

    .ql-editor h3 {
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        margin: 1rem 0 0.5rem !important;
        color: var(--ink) !important;
    }

    .ql-editor p {
        margin: 0.75rem 0 !important;
    }

    .ql-editor ul, .ql-editor ol {
        margin: 0.75rem 0 !important;
        padding-left: 1.5rem !important;
    }

    .ql-editor li {
        margin: 0.25rem 0 !important;
    }

    /* Image styling with alignment */
    .ql-editor img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px !important;
        margin: 1rem 0 !important;
        display: block !important;
    }

    /* Image alignment classes */
    .ql-editor img.image-align-left {
        float: left !important;
        margin-right: 1rem !important;
        margin-bottom: 0.5rem !important;
        max-width: 50% !important;
    }

    .ql-editor img.image-align-center {
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .ql-editor img.image-align-right {
        float: right !important;
        margin-left: 1rem !important;
        margin-bottom: 0.5rem !important;
        max-width: 50% !important;
    }

    /* Clear floats after images */
    .ql-editor .image-clear {
        clear: both !important;
    }

    .ql-editor blockquote {
        border-left: 4px solid var(--cyan) !important;
        padding-left: 1rem !important;
        margin: 1rem 0 !important;
        font-style: italic !important;
        color: var(--ink-soft) !important;
        background: var(--paper-soft) !important;
        padding: 0.75rem 1rem !important;
        border-radius: 0 8px 8px 0 !important;
    }

    .ql-editor pre {
        background: var(--paper-soft) !important;
        border-radius: 8px !important;
        padding: 1rem !important;
        overflow-x: auto !important;
        margin: 1rem 0 !important;
        font-family: 'Courier New', monospace !important;
    }

    .ql-editor code {
        font-family: 'Courier New', monospace !important;
        background: var(--paper-soft) !important;
        padding: 0.2rem 0.4rem !important;
        border-radius: 4px !important;
        font-size: 0.9em !important;
    }

    .ql-editor a {
        color: var(--cyan) !important;
        text-decoration: underline !important;
    }

    .ql-editor table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 1rem 0 !important;
    }

    .ql-editor th, .ql-editor td {
        border: 1px solid var(--line) !important;
        padding: 0.5rem 0.75rem !important;
        text-align: left !important;
    }

    .ql-editor th {
        background: var(--paper-soft) !important;
        font-weight: 600 !important;
    }

    .ql-editor hr {
        border: none !important;
        border-top: 1px solid var(--line) !important;
        margin: 1.5rem 0 !important;
    }

    /* Placeholder styling */
    .ql-editor.ql-blank::before {
        color: var(--ink-soft) !important;
        opacity: 0.7 !important;
    }

    /* Image resize handle */
    .ql-editor img:hover {
        outline: 2px solid var(--cyan) !important;
        outline-offset: 2px !important;
    }

    /* Upload progress indicator */
    .quill-upload-progress {
        position: relative;
        display: inline-block;
        margin: 1rem 0;
    }

    .quill-upload-progress img {
        opacity: 0.5;
    }

    .quill-upload-progress::after {
        content: 'Uploading...';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: var(--paper);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
{{-- Quill.js --}}
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>

<script>
(function() {
    const editorId = '{{ $id ?? "quill-editor" }}';
    const inputId = '{{ $name ?? "content" }}_input';
    const uploadUrl = '{{ route("admin.news.upload-image") }}';

    // Wait for Quill to be available
    function initQuill() {
        const editorEl = document.getElementById(editorId);
        if (!editorEl) {
            console.error('Quill editor element not found: ' + editorId);
            return;
        }

        // Check if already initialized
        if (window.quillInstances && window.quillInstances[editorId]) {
            return;
        }

        if (typeof Quill === 'undefined') {
            console.error('Quill not loaded');
            return;
        }

        // Image alignment handler
        function setImageAlignment(align) {
            const quill = window.quillInstances[editorId];
            if (!quill) return;

            const range = quill.getSelection();
            if (!range) return;

            // Find the image in the current selection
            const [leaf, offset] = quill.getLeaf(range.index);
            if (leaf && leaf.domNode && leaf.domNode.tagName === 'IMG') {
                // Remove all alignment classes
                leaf.domNode.classList.remove('image-align-left', 'image-align-center', 'image-align-right');
                // Add new alignment class
                if (align !== 'none') {
                    leaf.domNode.classList.add('image-align-' + align);
                }
                // Sync to hidden input
                syncContent(quill);
            } else {
                // Try to find the closest image before the cursor
                let index = range.index;
                while (index > 0) {
                    const [prevLeaf] = quill.getLeaf(index - 1);
                    if (prevLeaf && prevLeaf.domNode && prevLeaf.domNode.tagName === 'IMG') {
                        prevLeaf.domNode.classList.remove('image-align-left', 'image-align-center', 'image-align-right');
                        if (align !== 'none') {
                            prevLeaf.domNode.classList.add('image-align-' + align);
                        }
                        syncContent(quill);
                        return;
                    }
                    index--;
                }
            }
        }

        // Upload image function
        function uploadImage(file, insertCallback) {
            const formData = new FormData();
            formData.append('image', file);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', uploadUrl, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            // Show progress (optional)
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    console.log('Upload progress: ' + percent + '%');
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            insertCallback(response.url);
                        } else {
                            alert('Upload gagal: ' + (response.error || 'Unknown error'));
                        }
                    } catch (e) {
                        alert('Invalid response from server');
                    }
                } else if (xhr.status === 403) {
                    alert('Unauthorized. Please login again.');
                } else {
                    alert('Upload failed: HTTP ' + xhr.status);
                }
            };

            xhr.onerror = function() {
                alert('Network error. Check your connection.');
            };

            xhr.send(formData);
        }

        // Image handler for toolbar
        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = function() {
                const file = input.files[0];
                if (file) {
                    const quill = window.quillInstances[editorId];
                    const range = quill.getSelection(true);

                    // Insert placeholder
                    const index = range.index;
                    quill.insertEmbed(index, 'image', '{{ asset("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='100' viewBox='0 0 200 100'%3E%3Crect fill='%23f0f0f0' width='200' height='100'/%3E%3Ctext x='100' y='50' text-anchor='middle' fill='%23999' font-family='sans-serif' font-size='12'%3EUploading...%3C/text%3E%3C/svg%3E") }}');

                    // Upload and replace
                    uploadImage(file, function(url) {
                        const quillInstance = window.quillInstances[editorId];
                        const currentRange = quillInstance.getSelection();
                        if (currentRange) {
                            // Delete placeholder
                            quillInstance.deleteText(currentRange.index, 1);
                            // Insert actual image
                            quillInstance.insertEmbed(currentRange.index, 'image', url);
                            // Move cursor after image
                            quillInstance.setSelection(currentRange.index + 1, 0);
                        }
                        syncContent(quillInstance);
                    });
                }
            };
        }

        // Sync content to hidden input
        function syncContent(quill) {
            const content = quill.root.innerHTML;
            const input = document.getElementById(inputId);
            if (input) {
                input.value = content;
            }
        }

        // Get initial content
        const initialContent = document.getElementById(inputId)?.value || '';

        // Custom toolbar with image alignment
        const toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            ['link', 'image', 'video'],
            ['blockquote', 'code-block'],
            ['clean']
        ];

        // Initialize Quill
        const quill = new Quill('#' + editorId, {
            theme: 'snow',
            placeholder: 'Tulis konten berita di sini...',
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        'image': imageHandler
                    }
                }
            },
            formats: [
                'header',
                'bold', 'italic', 'underline', 'strike',
                'color', 'background',
                'align',
                'list', 'bullet',
                'indent',
                'link', 'image', 'video',
                'blockquote', 'code-block'
            ]
        });

        // Set initial content
        if (initialContent) {
            quill.clipboard.dangerouslyPasteHTML(initialContent);
        }

        // Store instance
        if (!window.quillInstances) {
            window.quillInstances = {};
        }
        window.quillInstances[editorId] = quill;

        // Sync content on text change
        quill.on('text-change', function() {
            syncContent(quill);
        });

        // Also sync on selection change
        quill.on('selection-change', function(range) {
            if (range) {
                syncContent(quill);
            }
        });

        // Handle paste for images
        quill.root.addEventListener('paste', function(e) {
            const items = e.clipboardData.items;
            for (let i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    e.preventDefault();
                    const file = items[i].getAsFile();
                    if (file) {
                        const range = quill.getSelection(true);
                        uploadImage(file, function(url) {
                            quill.insertEmbed(range.index, 'image', url);
                            quill.setSelection(range.index + 1, 0);
                            syncContent(quill);
                        });
                    }
                    break;
                }
            }
        });

        // Handle drag and drop for images
        quill.root.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.indexOf('image') !== -1) {
                    e.preventDefault();
                    const file = files[i];
                    const range = quill.getSelection(true);
                    uploadImage(file, function(url) {
                        quill.insertEmbed(range.index, 'image', url);
                        quill.setSelection(range.index + 1, 0);
                        syncContent(quill);
                    });
                    break;
                }
            }
        });

        // Prevent default drag behavior
        quill.root.addEventListener('dragover', function(e) {
            if (e.dataTransfer.types.includes('Files')) {
                e.preventDefault();
            }
        });

        console.log('Quill initialized: ' + editorId);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQuill);
    } else {
        initQuill();
    }
})();
</script>
@endpush
