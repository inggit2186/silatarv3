/**
 * PPID Size Presets Plugin for Jodit
 * Adds image size preset buttons (S, M, L, XL)
 */
(function() {
    'use strict';

    // Register Jodit plugin
    if (typeof Jodit !== 'undefined') {
        Jodit.plugins.add('ppidSizePresets', function(editor) {
            editor.events.on('afterInit', function() {
                // Add size preset buttons to toolbar
                const separator = document.createElement('div');
                separator.className = 'jodit-toolbar__separator';
                separator.style.cssText = 'width: 1px; background: var(--border); margin: 4px 8px;';

                const sizeContainer = document.createElement('div');
                sizeContainer.className = 'jodit-toolbar__group';
                sizeContainer.style.cssText = 'display: flex; gap: 2px;';

                // Size presets
                const sizes = [
                    { label: 'S', value: 300, title: 'Small (300px)' },
                    { label: 'M', value: 600, title: 'Medium (600px)' },
                    { label: 'L', value: 900, title: 'Large (900px)' },
                    { label: 'XL', value: 1200, title: 'Extra Large (1200px)' }
                ];

                sizes.forEach(function(size) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'jodit-toolbar-button';
                    btn.title = size.title;
                    btn.textContent = size.label;
                    btn.style.cssText = 'font-weight: 600; min-width: 28px;';
                    btn.onclick = function(e) {
                        e.preventDefault();
                        resizeImage(editor, size.value);
                    };
                    sizeContainer.appendChild(btn);
                });

                // Reset button
                const resetSeparator = document.createElement('div');
                resetSeparator.className = 'jodit-toolbar__separator';
                resetSeparator.style.cssText = 'width: 1px; background: var(--border); margin: 4px 8px;';

                const resetBtn = document.createElement('button');
                resetBtn.type = 'button';
                resetBtn.className = 'jodit-toolbar-button';
                resetBtn.title = 'Reset ukuran';
                resetBtn.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                `;
                resetBtn.onclick = function(e) {
                    e.preventDefault();
                    resetImageSize(editor);
                };

                // Find toolbar and add buttons
                const toolbar = editor.toolbar;
                if (toolbar) {
                    toolbar.appendChild(separator);
                    toolbar.appendChild(sizeContainer);
                    toolbar.appendChild(resetSeparator);
                    toolbar.appendChild(resetBtn);
                }

                // Add click handler for image selection
                editor.events.on('click', function(e) {
                    const img = e.target.closest('img');
                    if (img) {
                        // Deselect all
                        editor.editor.querySelectorAll('img.selected').forEach(function(el) {
                            el.classList.remove('selected');
                        });
                        // Select this image
                        img.classList.add('selected');
                    }
                });
            });

            // Function to resize selected image
            function resizeImage(editor, targetWidth) {
                const selectedImg = editor.editor.querySelector('img.selected');
                if (!selectedImg) {
                    // Try to get image at cursor
                    const range = editor.selection.range;
                    if (range) {
                        const node = range.startContainer;
                        const img = node.nodeType === 3 ? node.parentElement.querySelector('img') : node.querySelector('img');
                        if (img) {
                            applyResize(img, targetWidth);
                        }
                    }
                    return;
                }

                applyResize(selectedImg, targetWidth);
            }

            // Apply resize to image
            function applyResize(img, targetWidth) {
                // Store original dimensions if not already stored
                if (!img.dataset.originalWidth) {
                    img.dataset.originalWidth = img.naturalWidth || img.width;
                    img.dataset.originalHeight = img.naturalHeight || img.height;
                }

                const originalWidth = parseInt(img.dataset.originalWidth);
                const originalHeight = parseInt(img.dataset.originalHeight);
                const aspectRatio = originalWidth / originalHeight;

                // Calculate new height
                const newHeight = Math.round(targetWidth / aspectRatio);

                // Apply new dimensions
                img.style.width = targetWidth + 'px';
                img.style.height = newHeight + 'px';
                img.style.maxWidth = '100%';

                // Sync content
                editor.events.fire('change');
            }

            // Function to reset image size
            function resetImageSize(editor) {
                const selectedImg = editor.editor.querySelector('img.selected');
                if (!selectedImg) {
                    // Try to get image at cursor
                    const range = editor.selection.range;
                    if (range) {
                        const node = range.startContainer;
                        const img = node.nodeType === 3 ? node.parentElement.querySelector('img') : node.querySelector('img');
                        if (img) {
                            applyReset(img);
                        }
                    }
                    return;
                }

                applyReset(selectedImg);
            }

            // Apply reset to image
            function applyReset(img) {
                // Clear inline dimensions
                img.style.width = '';
                img.style.height = '';
                img.style.maxWidth = '100%';

                // Sync content
                editor.events.fire('change');
            }
        });

        console.log('PPID Size Presets plugin registered');
    }
})();
