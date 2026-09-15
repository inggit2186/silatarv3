/**
 * PPID Alignment Plugin for Jodit
 * Adds image alignment buttons (Left, Center, Right)
 */
(function() {
    'use strict';

    // Register Jodit plugin
    if (typeof Jodit !== 'undefined') {
        Jodit.plugins.add('ppidAlignment', function(editor) {
            editor.events.on('afterInit', function() {
                // Add alignment buttons to toolbar
                const separator = document.createElement('div');
                separator.className = 'jodit-toolbar__separator';
                separator.style.cssText = 'width: 1px; background: var(--border); margin: 4px 8px;';

                const alignContainer = document.createElement('div');
                alignContainer.className = 'jodit-toolbar__group';
                alignContainer.style.cssText = 'display: flex; gap: 2px;';

                // Left align button
                const leftBtn = document.createElement('button');
                leftBtn.type = 'button';
                leftBtn.className = 'jodit-toolbar-button';
                leftBtn.title = 'Rata Kiri';
                leftBtn.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="17" y1="10" x2="3" y2="10"/>
                        <line x1="21" y1="6" x2="3" y2="6"/>
                        <line x1="21" y1="14" x2="3" y2="14"/>
                        <line x1="17" y1="18" x2="3" y2="18"/>
                    </svg>
                `;
                leftBtn.onclick = function(e) {
                    e.preventDefault();
                    alignImage(editor, 'left');
                };

                // Center align button
                const centerBtn = document.createElement('button');
                centerBtn.type = 'button';
                centerBtn.className = 'jodit-toolbar-button';
                centerBtn.title = 'Tengah';
                centerBtn.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="10" x2="6" y2="10"/>
                        <line x1="21" y1="6" x2="3" y2="6"/>
                        <line x1="21" y1="14" x2="3" y2="14"/>
                        <line x1="18" y1="18" x2="6" y2="18"/>
                    </svg>
                `;
                centerBtn.onclick = function(e) {
                    e.preventDefault();
                    alignImage(editor, 'center');
                };

                // Right align button
                const rightBtn = document.createElement('button');
                rightBtn.type = 'button';
                rightBtn.className = 'jodit-toolbar-button';
                rightBtn.title = 'Rata Kanan';
                rightBtn.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="21" y1="10" x2="7" y2="10"/>
                        <line x1="21" y1="6" x2="3" y2="6"/>
                        <line x1="21" y1="14" x2="3" y2="14"/>
                        <line x1="21" y1="18" x2="7" y2="18"/>
                    </svg>
                `;
                rightBtn.onclick = function(e) {
                    e.preventDefault();
                    alignImage(editor, 'right');
                };

                alignContainer.appendChild(leftBtn);
                alignContainer.appendChild(centerBtn);
                alignContainer.appendChild(rightBtn);

                // Find toolbar and add buttons
                const toolbar = editor.toolbar;
                if (toolbar) {
                    toolbar.appendChild(separator);
                    toolbar.appendChild(alignContainer);
                }

                // Click outside to deselect
                editor.events.on('click', function(e) {
                    if (!e.target.closest('img')) {
                        editor.editor.querySelectorAll('img.selected').forEach(function(el) {
                            el.classList.remove('selected');
                        });
                    }
                });
            });

            // Function to align selected image
            function alignImage(editor, alignment) {
                const selectedImg = editor.editor.querySelector('img.selected');
                if (!selectedImg) {
                    // Try to get image at cursor
                    const range = editor.selection.range;
                    if (range) {
                        const node = range.startContainer;
                        const img = node.nodeType === 3 ? node.parentElement.querySelector('img') : node.querySelector('img');
                        if (img) {
                            applyAlignment(img, alignment);
                        }
                    }
                    return;
                }

                applyAlignment(selectedImg, alignment);
            }

            // Apply alignment to image
            function applyAlignment(img, alignment) {
                // Remove existing alignment classes
                img.classList.remove('align-left', 'align-center', 'align-right');

                // Add new alignment class
                img.classList.add('align-' + alignment);

                // Apply inline styles
                img.style.display = 'block';
                img.style.marginLeft = alignment === 'center' || alignment === 'right' ? 'auto' : '0';
                img.style.marginRight = alignment === 'center' ? 'auto' : (alignment === 'right' ? '0' : 'auto');

                // Sync content
                editor.events.fire('change');
            }
        });

        console.log('PPID Alignment plugin registered');
    }
})();
