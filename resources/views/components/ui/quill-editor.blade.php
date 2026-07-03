<div class="neo-editor-wrapper">
    <label class="neo-form-label">
        <svg class="inline h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
        </svg>
        {{ $label ?? 'Konten Berita' }}
    </label>

    <div class="neo-editor-container">
        <div id="{{ $id ?? 'quill-editor' }}" class="quill-editor"></div>
        <textarea name="{{ $name ?? 'content' }}" id="{{ $name ?? 'content' }}_input" class="hidden-quill-input" style="display:none;">{!! $content ?? '' !!}</textarea>
    </div>

    <p class="neo-form-hint mt-2 flex items-center gap-2">
        <span class="inline-flex items-center gap-1">
            <svg class="h-4 w-4" style="color: var(--cyan);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Toolbar: Header, Bold, Italic, Link, Image, Code Block. Upload gambar: drag &amp; drop atau klik toolbar. Klik gambar untuk resize/align. Paste gambar langsung didukung.</span>
        </span>
    </p>

    @error($name ?? 'content')
    <p class="neo-form-error">{{ $message }}</p>
    @enderror
</div>

@push('styles')
{{-- Highlight.js CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">

<style>
    /* Quill Editor Container Styling */
    .neo-editor-container {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .quill-editor {
        min-height: 400px;
        max-height: 600px;
        overflow-y: auto;
        position: relative;
    }

    /* Prevent content from rendering outside editor */
    .quill-editor .ql-editor {
        overflow-wrap: break-word !important;
        word-wrap: break-word !important;
    }

    /* Hide Quill internal UI elements in editor */
    .quill-editor .ql-code-block-container {
        position: relative;
    }
    .quill-editor .ql-code-block-container select.ql-ui {
        position: absolute;
        top: 4px;
        right: 8px;
        opacity: 0.5;
        font-size: 0.65rem;
        padding: 2px 4px;
        background: var(--paper-deep);
        border: 1px solid var(--line);
        border-radius: 4px;
    }
    .quill-editor .ql-code-block-container [contenteditable="false"]:not(select) {
        display: none !important;
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
        overflow: hidden !important;
    }

    .ql-editor {
        min-height: 350px;
        max-height: 500px;
        padding: 16px 20px !important;
        font-family: inherit !important;
        font-size: 15px !important;
        line-height: 1.7 !important;
        color: var(--ink) !important;
        overflow-y: auto !important;
        box-sizing: border-box !important;
    }

    /* Ensure content stays inside the editor */
    .ql-editor > * {
        max-width: 100% !important;
        overflow-wrap: break-word !important;
    }

    /* Prevent any content from breaking out */
    .neo-editor-container {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .neo-editor-container .ql-editor.ql-blank::before {
        color: var(--ink-soft) !important;
        font-style: normal !important;
        left: 20px !important;
        right: 20px !important;
    }

    /* Ensure images don't overflow */
    .ql-editor img {
        max-width: 100% !important;
        height: auto !important;
    }

    /* Toolbar Button Styling */
    .ql-toolbar.ql-snow button {
        border-radius: 6px !important;
        transition: all 0.2s ease !important;
    }

    .ql-toolbar.ql-snow button:hover {
        background: var(--paper-hover) !important;
    }

    .ql-toolbar.ql-snow button.ql-active {
        background: var(--cyan-soft) !important;
        color: var(--cyan) !important;
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
    .quill-editor::-webkit-scrollbar {
        width: 8px;
    }

    .quill-editor::-webkit-scrollbar-track {
        background: var(--paper-soft);
        border-radius: 4px;
    }

    .quill-editor::-webkit-scrollbar-thumb {
        background: var(--line);
        border-radius: 4px;
    }

    .quill-editor::-webkit-scrollbar-thumb:hover {
        background: var(--ink-soft);
    }

    /* Content styling */
    .ql-editor h1 { font-size: 2rem !important; font-weight: 700 !important; margin: 1.5rem 0 1rem !important; }
    .ql-editor h2 { font-size: 1.5rem !important; font-weight: 600 !important; margin: 1.25rem 0 0.75rem !important; }
    .ql-editor h3 { font-size: 1.25rem !important; font-weight: 600 !important; margin: 1rem 0 0.5rem !important; }
    .ql-editor p { margin: 0.75rem 0 !important; }
    .ql-editor ul, .ql-editor ol { margin: 0.75rem 0 !important; padding-left: 1.5rem !important; }
    .ql-editor li { margin: 0.25rem 0 !important; }
    .ql-editor blockquote { border-left: 4px solid var(--cyan) !important; padding-left: 1rem !important; margin: 1rem 0 !important; font-style: italic !important; color: var(--ink-soft) !important; background: var(--paper-soft) !important; padding: 0.75rem 1rem !important; border-radius: 0 8px 8px 0 !important; }
    .ql-editor pre { background: var(--paper-soft) !important; border-radius: 8px !important; padding: 1rem !important; overflow-x: auto !important; margin: 1rem 0 !important; font-family: 'Courier New', monospace !important; }
    .ql-editor code { font-family: 'Courier New', monospace !important; background: var(--paper-soft) !important; padding: 0.2rem 0.4rem !important; border-radius: 4px !important; }
    .ql-editor a { color: var(--cyan) !important; text-decoration: underline !important; }
    .ql-editor table { width: 100% !important; border-collapse: collapse !important; margin: 1rem 0 !important; }
    .ql-editor th, .ql-editor td { border: 1px solid var(--line) !important; padding: 0.5rem 0.75rem !important; }
    .ql-editor th { background: var(--paper-soft) !important; font-weight: 600 !important; }
    .ql-editor hr { border: none !important; border-top: 1px solid var(--line) !important; margin: 1.5rem 0 !important; }

    /* Images - basic styles */
    .ql-editor img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px !important;
        margin: 1rem 0 !important;
        cursor: pointer !important;
        display: block !important;
    }

    /* Images in paragraphs with alignment */
    .ql-editor p[style*="text-align: center"] img,
    .ql-editor p[style*="TEXT-ALIGN: center"] img {
        margin-left: auto !important;
        margin-right: auto !important;
    }
    .ql-editor p[style*="text-align: right"],
    .ql-editor p[style*="TEXT-ALIGN: right"] {
        direction: rtl !important;
    }
    .ql-editor p[style*="text-align: right"] img,
    .ql-editor p[style*="TEXT-ALIGN: right"] img {
        margin-left: auto !important;
        direction: ltr !important;
    }

    /* Center/right alignment inline styles on images */
    .ql-editor img[style*="margin-left: auto"] {
        margin-left: auto !important;
    }
    .ql-editor img[style*="margin-right: auto"] {
        margin-right: auto !important;
    }

    /* ==================== */
    /* IMAGE RESIZE STYLES */
    /* ==================== */

    /* Selected image state */
    .ql-editor img.selected {
        outline: 3px solid var(--cyan) !important;
        outline-offset: 2px !important;
    }

    /* Resize container overlay */
    .image-resize-overlay {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        pointer-events: none !important;
        z-index: 5 !important;
    }

    .ql-editor img.selected + .image-resize-overlay,
    .ql-editor img.selected ~ .image-resize-overlay {
        pointer-events: auto !important;
    }

    /* Resize handles */
    .resize-handle {
        position: absolute !important;
        width: 14px !important;
        height: 14px !important;
        background: var(--cyan) !important;
        border: 2px solid white !important;
        border-radius: 3px !important;
        z-index: 10 !important;
        opacity: 0;
        transition: opacity 0.15s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .ql-editor img.selected ~ .resize-handle,
    .image-resize-container.selected .resize-handle {
        opacity: 1;
    }

    .resize-handle.nw { top: -7px; left: -7px; cursor: nw-resize; }
    .resize-handle.ne { top: -7px; right: -7px; cursor: ne-resize; }
    .resize-handle.sw { bottom: -7px; left: -7px; cursor: sw-resize; }
    .resize-handle.se { bottom: -7px; right: -7px; cursor: se-resize; }

    .resize-handle.n { top: -7px; left: 50%; transform: translateX(-50%); cursor: n-resize; width: 12px !important; height: 8px !important; }
    .resize-handle.s { bottom: -7px; left: 50%; transform: translateX(-50%); cursor: s-resize; width: 12px !important; height: 8px !important; }
    .resize-handle.w { left: -7px; top: 50%; transform: translateY(-50%); cursor: w-resize; width: 8px !important; height: 12px !important; }
    .resize-handle.e { right: -7px; top: 50%; transform: translateY(-50%); cursor: e-resize; width: 8px !important; height: 12px !important; }

    /* Quick resize toolbar - minimal styles, most is inline */
    .quick-resize-toolbar {
        position: fixed !important;
        background: var(--paper) !important;
        border: 1px solid var(--line) !important;
        border-radius: 8px !important;
        padding: 4px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        z-index: 9999 !important;
    }

    .quick-resize-toolbar button {
        border-radius: 6px !important;
        background: var(--paper) !important;
        color: var(--ink) !important;
        cursor: pointer !important;
    }

    .quick-resize-toolbar button:hover {
        background: var(--paper-hover) !important;
    }

    /* Resize guide line */
    .resize-guide {
        position: absolute !important;
        background: var(--cyan) !important;
        pointer-events: none !important;
        z-index: 8 !important;
    }

    .resize-guide.horizontal {
        height: 1px !important;
        left: 0 !important;
        right: 0 !important;
    }

    .resize-guide.vertical {
        width: 1px !important;
        top: 0 !important;
        bottom: 0 !important;
    }

    /* Upload toast animations */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes slideInToast {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Loading placeholder animation */
    .quill-loading-placeholder {
        background: var(--paper-soft, #faf8f4);
        border: 2px dashed var(--line, #c5c0b8);
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        color: var(--ink-soft, #4a4540);
    }

    /* ==================== */
    /* ADDITIONAL TOOLBAR STYLES */
    /* ==================== */

    /* Table styles */
    .ql-editor table {
        border-collapse: collapse !important;
        width: 100% !important;
        margin: 1rem 0 !important;
    }
    .ql-editor table td, .ql-editor table th {
        border: 1px solid var(--line) !important;
        padding: 0.5rem 0.75rem !important;
        min-width: 80px;
    }
    .ql-editor table th {
        background: var(--paper-deep) !important;
        font-weight: 600 !important;
    }
    .ql-editor table caption {
        font-style: italic !important;
        color: var(--ink-soft) !important;
        margin-bottom: 0.5rem !important;
    }
    /* Table selection */
    .ql-editor .quill-cursor-flag {
        display: none !important;
    }

    /* Code Block with syntax highlighting */
    .ql-editor pre.ql-syntax {
        background: #1e1e1e !important;
        color: #d4d4d4 !important;
        border-radius: 8px !important;
        padding: 1rem !important;
        margin: 1rem 0 !important;
        overflow-x: auto !important;
        font-family: 'Fira Code', 'Monaco', 'Consolas', monospace !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
    }
    .ql-editor pre.ql-syntax .hljs-keyword { color: #569cd6 !important; }
    .ql-editor pre.ql-syntax .hljs-string { color: #ce9178 !important; }
    .ql-editor pre.ql-syntax .hljs-number { color: #b5cea8 !important; }
    .ql-editor pre.ql-syntax .hljs-function { color: #dcdcaa !important; }
    .ql-editor pre.ql-syntax .hljs-comment { color: #6a9955 !important; }
    .ql-editor pre.ql-syntax .hljs-variable { color: #9cdcfe !important; }
    .ql-editor pre.ql-syntax .hljs-built_in { color: #4ec9b0 !important; }
    .ql-editor pre.ql-syntax .hljs-class { color: #4ec9b0 !important; }
    .ql-editor pre.ql-syntax .hljs-attr { color: #9cdcfe !important; }

    /* Inline code */
    .ql-editor code {
        background: var(--paper-deep) !important;
        padding: 0.2em 0.4em !important;
        border-radius: 4px !important;
        font-family: 'Fira Code', 'Monaco', monospace !important;
        font-size: 0.9em !important;
        color: var(--sun-deep) !important;
    }

    /* Horizontal Rule */
    .ql-editor hr {
        border: none !important;
        height: 2px !important;
        background: linear-gradient(90deg, transparent, var(--line), transparent) !important;
        margin: 2rem 0 !important;
    }

    /* Video embed */
    .ql-editor video, .ql-editor iframe {
        max-width: 100% !important;
        border-radius: 8px !important;
        margin: 1rem 0 !important;
    }

    /* Mention placeholder */
    .ql-editor .mention {
        background: rgba(201, 165, 90, 0.15) !important;
        color: var(--gold) !important;
        padding: 0.1em 0.3em !important;
        border-radius: 4px !important;
        font-weight: 500 !important;
    }
</style>
@endpush

@push('scripts')
{{-- Highlight.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

<script>
(function() {
    const editorId = '{{ $id ?? "quill-editor" }}';
    const inputId = '{{ $name ?? "content" }}_input';
    const uploadUrl = '{{ route("admin.news.upload-image") }}';

    function initQuill() {
        const editorEl = document.getElementById(editorId);
        if (!editorEl) {
            console.error('Quill editor element not found:', editorId);
            return;
        }

        if (window.quillInstances && window.quillInstances[editorId]) {
            return;
        }

        if (typeof Quill === 'undefined') {
            console.error('Quill not loaded');
            return;
        }

        // Sync content to textarea
        function syncContent(quill) {
            const content = quill.root.innerHTML;
            const textarea = document.getElementById(inputId);
            if (textarea) {
                textarea.value = content;
            }
        }

        // Get initial content from textarea - content is now raw HTML (not escaped)
        function getInitialContent() {
            const textarea = document.getElementById(inputId);
            if (!textarea) return '';

            let content = textarea.value || '';

            // Clean up Quill internal HTML artifacts
            // 1. Remove data attributes added by our resize system from images
            content = content.replace(/\s*data-resize-init="[^"]*"/gi, '');
            content = content.replace(/\s*data-original-width="[^"]*"/gi, '');
            content = content.replace(/\s*data-original-height="[^"]*"/gi, '');

            // 2. Remove orphaned span.ql-ui elements inside list items
            content = content.replace(/<li[^>]*>\s*<span class="ql-ui" contenteditable="false"><\/span>\s*/gi, '<li>');

            // 3. Clean up code block containers - keep the content but remove the select dropdown
            // Replace select.ql-ui inside code-block-container with empty
            content = content.replace(/<div class="ql-code-block-container"[^>]*>[\s\S]*?<select class="ql-ui"[^>]*>[\s\S]*?<\/select>/gi, '<div class="ql-code-block-container">');

            return content;
        }

        const initialContent = getInitialContent();

        // Upload image function
        // Show upload progress toast
        let uploadToast = null;
        function showUploadToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.getElementById('quill-upload-toast');
            if (existingToast) existingToast.remove();

            const toast = document.createElement('div');
            toast.id = 'quill-upload-toast';
            toast.style.cssText = `
                position: fixed;
                bottom: 24px;
                right: 24px;
                padding: 12px 20px;
                background: var(--paper, #f5f0e6);
                border: 1px solid var(--line, #c5c0b8);
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 99999;
                display: flex;
                align-items: center;
                gap: 12px;
                font-family: inherit;
                font-size: 14px;
                color: var(--ink, #2a2520);
                animation: slideInToast 0.3s ease;
            `;

            const spinner = document.createElement('div');
            spinner.style.cssText = `
                width: 18px;
                height: 18px;
                border: 2px solid var(--line, #c5c0b8);
                border-top-color: var(--gold, #c9a55a);
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
            `;

            const text = document.createElement('span');
            text.textContent = message;

            toast.appendChild(spinner);
            toast.appendChild(text);
            document.body.appendChild(toast);
            uploadToast = toast;

            return toast;
        }

        function hideUploadToast(success = false, message = '') {
            const toast = document.getElementById('quill-upload-toast');
            if (toast) {
                if (success) {
                    toast.innerHTML = `
                        <svg style="width:18px;height:18px;color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span style="color:#16a34a">${message || 'Berhasil!'}</span>
                    `;
                    toast.style.borderColor = 'rgba(22, 163, 74, 0.3)';
                    setTimeout(() => toast.remove(), 2000);
                } else {
                    toast.innerHTML = `
                        <svg style="width:18px;height:18px;color:#dc2626" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span style="color:#dc2626">${message || 'Gagal!'}</span>
                    `;
                    toast.style.borderColor = 'rgba(220, 38, 38, 0.3)';
                    setTimeout(() => toast.remove(), 3000);
                }
            }
        }

        function uploadImage(file, insertCallback) {
            const formData = new FormData();
            formData.append('image', file);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', uploadUrl, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            // Show uploading toast
            showUploadToast('Mengupload dan memproses gambar...');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            hideUploadToast(true, 'Gambar berhasil diupload!');
                            // Show compression stats if available
                            if (response.meta && response.meta.saved_percent && parseFloat(response.meta.saved_percent) > 0) {
                                console.log(`Image compressed: ${response.meta.original_size} → ${response.meta.compressed_size} (saved ${response.meta.saved_percent})`);
                            }
                            insertCallback(response.url);
                        } else {
                            hideUploadToast(false, response.error || 'Upload gagal');
                        }
                    } catch (e) {
                        hideUploadToast(false, 'Respons server tidak valid');
                    }
                } else {
                    hideUploadToast(false, 'Upload gagal (HTTP ' + xhr.status + ')');
                }
            };

            xhr.onerror = function() {
                hideUploadToast(false, 'Kesalahan koneksi');
            };

            xhr.send(formData);
        }

        // Image handler
        function imageHandler() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.click();
            input.onchange = function() {
                const file = input.files[0];
                if (file) {
                    // Validate file size (max 10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 10MB.');
                        return;
                    }

                    const quill = window.quillInstances[editorId];
                    const range = quill.getSelection(true);

                    // Create loading placeholder with animation
                    const loadingSvg = `data:image/svg+xml,${encodeURIComponent(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">
                            <rect fill="#f5f0e6" width="400" height="300" rx="8"/>
                            <rect x="175" y="115" width="50" height="50" rx="25" fill="none" stroke="#c9a55a" stroke-width="3">
                                <animateTransform attributeName="transform" type="rotate" from="0 200 140" to="360 200 140" dur="1s" repeatCount="indefinite"/>
                            </rect>
                            <text x="200" y="190" text-anchor="middle" fill="#4a4540" font-family="system-ui" font-size="13">Memproses gambar...</text>
                            <text x="200" y="210" text-anchor="middle" fill="#8a8580" font-family="system-ui" font-size="11">Mohon tunggu sebentar</text>
                        </svg>
                    `)}`;

                    // Insert loading placeholder
                    quill.insertEmbed(range.index, 'image', loadingSvg);

                    // Upload and replace
                    uploadImage(file, function(url) {
                        const quillInstance = window.quillInstances[editorId];
                        const currentRange = quillInstance.getSelection();
                        if (currentRange) {
                            // Find and replace the loading placeholder
                            const loadingImg = quillInstance.root.querySelector(`img[src*="Memproses"]`);
                            if (loadingImg) {
                                loadingImg.src = url;
                                loadingImg.removeAttribute('style');
                                loadingImg.style.maxWidth = '100%';
                                loadingImg.style.height = 'auto';
                                loadingImg.style.borderRadius = '8px';
                            } else {
                                // Fallback: delete loading and insert new image
                                const allImgs = quillInstance.root.querySelectorAll('img');
                                for (let img of allImgs) {
                                    if (img.src.includes('data:image/svg')) {
                                        img.src = url;
                                        img.removeAttribute('style');
                                        img.style.maxWidth = '100%';
                                        img.style.height = 'auto';
                                        img.style.borderRadius = '8px';
                                        break;
                                    }
                                }
                            }
                            initImageResize();
                            syncContent(quillInstance);
                        }
                    });
                }
            };
        }

        // Wrap image with alignment by setting CSS on parent
        function wrapImageWithAlignment(img, align) {
            // Get the immediate parent of the image
            const parent = img.parentElement;

            // Set text-align on parent element for alignment effect
            if (parent) {
                parent.style.textAlign = align || 'left';
            }

            // Ensure image is block display for proper alignment
            img.style.display = 'block';
            img.style.marginLeft = align === 'right' ? 'auto' : (align === 'center' ? 'auto' : '0');
            img.style.marginRight = align === 'center' ? 'auto' : '0';

            // Force sync to save the content
            syncContent(quill);

            // Re-initialize image resize to rebind events
            setTimeout(() => initImageResize(), 100);
        }

        // Remove alignment wrapper from image
        function unwrapImage(img) {
            const wrapper = img.parentElement;
            if (wrapper && wrapper.classList.contains('quill-image-wrapper')) {
                const parent = wrapper.parentNode;
                while (wrapper.firstChild) {
                    parent.insertBefore(wrapper.firstChild, wrapper);
                }
                parent.removeChild(wrapper);
            }
        }

        // Initialize Quill
        const quill = new Quill('#' + editorId, {
            theme: 'snow',
            placeholder: 'Tulis konten berita di sini...',
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [false, 'center', 'right', 'justify'] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link', 'image'],
                        ['blockquote', 'code-block'],
                        ['clean']
                    ],
                    handlers: {
                        'image': imageHandler
                    }
                },
                syntax: {
                    interval: 250
                },
                keyboard: {
                    bindings: {
                        // Ctrl+Shift+S for superscript
                        'superscript': {
                            key: 'S',
                            ctrlKey: true,
                            shiftKey: true,
                            handler: function() {}
                        }
                    }
                }
            },
            formats: ['header', 'bold', 'italic', 'underline', 'strike', 'color', 'background', 'align', 'list', 'indent', 'link', 'image', 'blockquote', 'code-block', 'code']
        });

        // Apply syntax highlighting after text changes
        quill.on('text-change', function() {
            syncContent(quill);
            initImageResize();
            // Apply syntax highlighting to code blocks
            setTimeout(highlightCodeBlocks, 100);
        });

        // Initial highlighting
        setTimeout(highlightCodeBlocks, 100);

        // Function to highlight code blocks
        function highlightCodeBlocks() {
            const codeBlocks = quill.root.querySelectorAll('pre.ql-syntax');
            codeBlocks.forEach(block => {
                if (!block.dataset.highlighted) {
                    block.dataset.highlighted = 'true';
                }
            });
        }

        // Set initial content
        if (initialContent && initialContent.trim()) {
            try {
                quill.clipboard.dangerouslyPasteHTML(initialContent);
            } catch (e) {
                console.warn('Error loading initial content:', e);
                quill.setText(initialContent);
            }
        }

        // Store instance
        if (!window.quillInstances) window.quillInstances = {};
        window.quillInstances[editorId] = quill;

        // Sync content
        quill.on('text-change', function() {
            syncContent(quill);
            initImageResize();
        });

        quill.on('selection-change', function(range) {
            if (range) syncContent(quill);
        });

        // ====================
        // IMAGE RESIZE SYSTEM
        // ====================

        let isResizing = false;
        let selectedImg = null;
        let resizeStartX, resizeStartY, resizeStartWidth, resizeStartHeight;
        let aspectRatio = 1;

        function initImageResize() {
            const images = quill.root.querySelectorAll('img');
            images.forEach(img => {
                if (!img.dataset.resizeInit) {
                    img.dataset.resizeInit = 'true';
                    img.dataset.originalWidth = img.naturalWidth;
                    img.dataset.originalHeight = img.naturalHeight;

                    // Prevent Quill from selecting the image as text
                    img.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    });

                    // Add click handler for selection
                    img.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        selectImage(img);
                    });

                    // Add double-click to reset
                    img.addEventListener('dblclick', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        resetImageSize(img);
                    });
                }
            });
        }

        function selectImage(img) {
            // Deselect all
            quill.root.querySelectorAll('img.selected').forEach(el => el.classList.remove('selected'));
            // Remove existing toolbars
            document.querySelectorAll('.quick-resize-toolbar').forEach(el => el.remove());

            // Select this image
            img.classList.add('selected');
            selectedImg = img;

            // Calculate aspect ratio
            aspectRatio = parseInt(img.dataset.originalWidth) / parseInt(img.dataset.originalHeight);

            // Show quick toolbar
            showQuickToolbar(img);

            console.log('Image selected:', img.src);
        }

        function showQuickToolbar(img) {
            // Remove existing toolbar
            document.querySelectorAll('.quick-resize-toolbar').forEach(el => el.remove());

            // Get image position - scroll into view first to get accurate position
            img.scrollIntoView({ behavior: 'instant', block: 'nearest' });
            const imgRect = img.getBoundingClientRect();
            const editorRect = quill.root.getBoundingClientRect();

            const toolbar = document.createElement('div');
            toolbar.className = 'quick-resize-toolbar';
            toolbar.id = 'image-toolbar-' + Date.now();
            toolbar.style.cssText = `
                position: fixed !important;
                display: flex !important;
                gap: 4px !important;
                flex-wrap: wrap !important;
                max-width: 320px !important;
                z-index: 10000 !important;
            `;

            // Alignment buttons
            const aligns = [
                { label: '◀', value: 'left', title: 'Rata Kiri' },
                { label: '◫', value: 'center', title: 'Tengah' },
                { label: '▶', value: 'right', title: 'Rata Kanan' }
            ];

            // Check current alignment
            const currentWrapper = img.parentElement;
            const currentAlign = currentWrapper && currentWrapper.classList.contains('quill-image-wrapper')
                ? currentWrapper.getAttribute('data-align') || 'left'
                : 'left';

            aligns.forEach(align => {
                const btn = document.createElement('button');
                btn.innerHTML = align.label;
                btn.title = align.title;
                btn.style.cssText = `
                    padding: 6px 10px !important;
                    border: 1px solid ${align.value === currentAlign ? 'var(--gold)' : 'var(--line)'} !important;
                    border-radius: 6px !important;
                    background: ${align.value === currentAlign ? 'var(--gold)' : 'var(--paper)'} !important;
                    color: ${align.value === currentAlign ? 'var(--night)' : 'var(--ink)'} !important;
                    cursor: pointer !important;
                    font-size: 12px !important;
                `;
                btn.onclick = function(e) {
                    e.stopPropagation();
                    wrapImageWithAlignment(img, align.value);
                    syncContent(quill);
                    // Refresh toolbar to update button states
                    setTimeout(() => showQuickToolbar(img), 50);
                };
                toolbar.appendChild(btn);
            });

            // Divider
            const div1 = document.createElement('div');
            div1.style.cssText = 'width: 1px; background: var(--line); margin: 4px 4px;';
            toolbar.appendChild(div1);

            // Size buttons
            const sizes = [
                { label: 'S', value: 300 },
                { label: 'M', value: 600 },
                { label: 'L', value: 900 },
                { label: 'XL', value: 1200 }
            ];

            sizes.forEach(size => {
                const btn = document.createElement('button');
                btn.textContent = size.label;
                btn.style.cssText = `
                    padding: 6px 10px !important;
                    border: 1px solid var(--line) !important;
                    border-radius: 6px !important;
                    background: var(--paper) !important;
                    color: var(--ink) !important;
                    cursor: pointer !important;
                    font-size: 12px !important;
                    font-weight: 600 !important;
                `;
                btn.onclick = function(e) {
                    e.stopPropagation();
                    resizeToWidth(img, size.value);
                };
                toolbar.appendChild(btn);
            });

            // Divider
            const div2 = document.createElement('div');
            div2.style.cssText = 'width: 1px; background: var(--line); margin: 4px 4px;';
            toolbar.appendChild(div2);

            // Reset button
            const resetBtn = document.createElement('button');
            resetBtn.innerHTML = '↺';
            resetBtn.title = 'Reset ukuran';
            resetBtn.style.cssText = `
                padding: 6px 10px !important;
                border: 1px solid var(--line) !important;
                border-radius: 6px !important;
                background: var(--paper) !important;
                color: var(--ink) !important;
                cursor: pointer !important;
                font-size: 14px !important;
            `;
            resetBtn.onclick = function(e) {
                e.stopPropagation();
                resetImageSize(img);
            };
            toolbar.appendChild(resetBtn);

            // Dimensions display
            const dims = document.createElement('div');
            dims.style.cssText = 'padding: 6px 8px !important; color: var(--ink-soft) !important; font-size: 11px !important; white-space: nowrap !important;';
            dims.textContent = img.naturalWidth + '×' + img.naturalHeight;
            toolbar.appendChild(dims);

            // Position toolbar - place below the image, centered
            document.body.appendChild(toolbar);
            const toolbarRect = toolbar.getBoundingClientRect();

            // Calculate position below the image
            let top = imgRect.bottom + 10;
            let left = imgRect.left + (imgRect.width / 2) - (toolbarRect.width / 2);

            // Keep within viewport
            if (left < 10) left = 10;
            if (left + toolbarRect.width > window.innerWidth - 10) {
                left = window.innerWidth - toolbarRect.width - 10;
            }
            // If would go off bottom, place above image
            if (top + toolbarRect.height > window.innerHeight - 10) {
                top = imgRect.top - toolbarRect.height - 10;
            }
            if (top < 10) top = 10;

            toolbar.style.position = 'fixed';
            toolbar.style.top = top + 'px';
            toolbar.style.left = left + 'px';

            // Store reference to target image
            toolbar.dataset.targetImg = img.src;

            console.log('Toolbar shown at:', left, top);
        }

        function updateToolbarButtons(toolbar, currentWidth) {
            const buttons = toolbar.querySelectorAll('button:not(.divider + button)');
            buttons.forEach(btn => {
                // Update active state based on current width
            });
        }

        function resizeToWidth(img, targetWidth) {
            const newHeight = Math.round(targetWidth / aspectRatio);
            img.style.width = targetWidth + 'px';
            img.style.height = newHeight + 'px';
            syncContent(quill);
        }

        function resetImageSize(img) {
            img.style.width = '';
            img.style.height = '';
            syncContent(quill);
        }

        // Click outside to deselect
        document.addEventListener('click', function(e) {
            if (!e.target.closest('img') && !e.target.closest('.quick-resize-toolbar')) {
                quill.root.querySelectorAll('img.selected').forEach(el => el.classList.remove('selected'));
                document.querySelectorAll('.quick-resize-toolbar').forEach(el => el.remove());
                selectedImg = null;
            }
        });

        // Mouse move/up for global resize handling
        document.addEventListener('mousemove', function(e) {
            if (!isResizing || !selectedImg) return;

            const dx = e.clientX - resizeStartX;
            const dy = e.clientY - resizeStartY;

            // Calculate new width based on aspect ratio
            let newWidth = resizeStartWidth + dx;

            // Constrain
            newWidth = Math.max(100, Math.min(newWidth, 1200));
            const newHeight = Math.round(newWidth / aspectRatio);

            selectedImg.style.width = newWidth + 'px';
            selectedImg.style.height = newHeight + 'px';
        });

        document.addEventListener('mouseup', function(e) {
            if (isResizing) {
                isResizing = false;
                document.body.style.cursor = '';
                document.body.style.userSelect = '';
                syncContent(quill);
                console.log('Resize complete');
            }
        });

        // Initialize
        setTimeout(initImageResize, 100);

        console.log('Quill initialized:', editorId);
    }

    // Handle paste images
    document.addEventListener('paste', function(e) {
        const items = e.clipboardData.items;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                const quill = window.quillInstances[editorId];
                if (quill && quill.hasFocus()) {
                    e.preventDefault();
                    const file = items[i].getAsFile();
                    if (file) {
                        const range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', '{{ asset("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='100' viewBox='0 0 200 100'%3E%3Crect fill='%23f0f0f0' width='200' height='100'/%3E%3Ctext x='100' y='50' text-anchor='middle' fill='%23999' font-family='sans-serif' font-size='12'%3EUploading...%3C/text%3E%3C/svg%3E") }}');

                        const formData = new FormData();
                        formData.append('image', file);
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route("admin.news.upload-image") }}', true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    const img = quill.root.querySelector(`img[src^="data:image/svg"]`);
                                    if (img) {
                                        img.src = response.url;
                                    }
                                }
                            }
                        };
                        xhr.send(formData);
                    }
                }
                break;
            }
        }
    });

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQuill);
    } else {
        initQuill();
    }
})();
</script>
@endpush
