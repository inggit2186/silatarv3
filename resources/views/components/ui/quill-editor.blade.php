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
            <span>Gunakan toolbar untuk formatting. Upload gambar: klik toolbar atau drag &amp; drop. Klik gambar untuk resize.</span>
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
    .ql-editor img { max-width: 100% !important; height: auto !important; border-radius: 8px !important; margin: 1rem 0 !important; cursor: pointer !important; }
    .ql-editor blockquote { border-left: 4px solid var(--cyan) !important; padding-left: 1rem !important; margin: 1rem 0 !important; font-style: italic !important; color: var(--ink-soft) !important; background: var(--paper-soft) !important; padding: 0.75rem 1rem !important; border-radius: 0 8px 8px 0 !important; }
    .ql-editor pre { background: var(--paper-soft) !important; border-radius: 8px !important; padding: 1rem !important; overflow-x: auto !important; margin: 1rem 0 !important; font-family: 'Courier New', monospace !important; }
    .ql-editor code { font-family: 'Courier New', monospace !important; background: var(--paper-soft) !important; padding: 0.2rem 0.4rem !important; border-radius: 4px !important; }
    .ql-editor a { color: var(--cyan) !important; text-decoration: underline !important; }
    .ql-editor table { width: 100% !important; border-collapse: collapse !important; margin: 1rem 0 !important; }
    .ql-editor th, .ql-editor td { border: 1px solid var(--line) !important; padding: 0.5rem 0.75rem !important; }
    .ql-editor th { background: var(--paper-soft) !important; font-weight: 600 !important; }
    .ql-editor hr { border: none !important; border-top: 1px solid var(--line) !important; margin: 1.5rem 0 !important; }

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

        // Upload image function
        function uploadImage(file, insertCallback) {
            const formData = new FormData();
            formData.append('image', file);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', uploadUrl, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

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
                } else {
                    alert('Upload failed: HTTP ' + xhr.status);
                }
            };

            xhr.onerror = function() {
                alert('Network error');
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
                    const quill = window.quillInstances[editorId];
                    const range = quill.getSelection(true);
                    // Insert placeholder
                    quill.insertEmbed(range.index, 'image', '{{ asset("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='100' viewBox='0 0 200 100'%3E%3Crect fill='%23f0f0f0' width='200' height='100'/%3E%3Ctext x='100' y='50' text-anchor='middle' fill='%23999' font-family='sans-serif' font-size='12'%3EUploading...%3C/text%3E%3C/svg%3E") }}');
                    // Upload and replace
                    uploadImage(file, function(url) {
                        const quillInstance = window.quillInstances[editorId];
                        const currentRange = quillInstance.getSelection();
                        if (currentRange) {
                            const img = quillInstance.root.querySelector(`img[src^="data:image/svg"]`);
                            if (img) {
                                img.src = url;
                                img.removeAttribute('style');
                            } else {
                                quillInstance.deleteText(currentRange.index, 1);
                                quillInstance.insertEmbed(currentRange.index, 'image', url);
                            }
                            initImageResize();
                            syncContent(quillInstance);
                        }
                    });
                }
            };
        }

        // Initialize Quill
        const quill = new Quill('#' + editorId, {
            theme: 'snow',
            placeholder: 'Tulis konten berita di sini...',
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': [1, 2, 3, 4, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link', 'image', 'video'],
                        ['blockquote', 'code-block'],
                        ['clean']
                    ],
                    handlers: { 'image': imageHandler }
                }
            },
            formats: ['header', 'bold', 'italic', 'underline', 'strike', 'color', 'background', 'align', 'list', 'bullet', 'indent', 'link', 'image', 'video', 'blockquote', 'code-block']
        });

        // Set initial content
        if (initialContent) {
            quill.clipboard.dangerouslyPasteHTML(initialContent);
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

                    // Add click handler for selection
                    img.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectImage(img);
                    });

                    // Add double-click to reset
                    img.addEventListener('dblclick', function(e) {
                        e.preventDefault();
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

            // Get image position relative to editor
            const editorRect = quill.root.getBoundingClientRect();
            const imgRect = img.getBoundingClientRect();

            const toolbar = document.createElement('div');
            toolbar.className = 'quick-resize-toolbar';
            toolbar.style.cssText = `
                position: fixed !important;
                display: flex !important;
                gap: 4px !important;
            `;

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
                    padding: 6px 12px !important;
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
                    // Update active state
                    toolbar.querySelectorAll('button').forEach(b => {
                        b.style.background = 'var(--paper)';
                        b.style.color = 'var(--ink)';
                    });
                    btn.style.background = 'var(--cyan)';
                    btn.style.color = 'white';
                };
                toolbar.appendChild(btn);
            });

            // Divider
            const div = document.createElement('div');
            div.style.cssText = 'width: 1px; background: var(--line); margin: 4px 2px;';
            toolbar.appendChild(div);

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
            dims.style.cssText = 'padding: 6px 8px !important; color: var(--ink-soft) !important; font-size: 11px !important;';
            dims.textContent = img.naturalWidth + '×' + img.naturalHeight;
            toolbar.appendChild(dims);

            // Position toolbar above the image
            document.body.appendChild(toolbar);
            const toolbarRect = toolbar.getBoundingClientRect();

            let top = imgRect.top - toolbarRect.height - 10;
            let left = imgRect.left + (imgRect.width / 2) - (toolbarRect.width / 2);

            // Keep within viewport
            if (top < 10) top = imgRect.bottom + 10;
            if (left < 10) left = 10;
            if (left + toolbarRect.width > window.innerWidth - 10) {
                left = window.innerWidth - toolbarRect.width - 10;
            }

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
