/**
 * PPID Image Upload Plugin for Jodit
 * Custom image upload with toast notifications
 */
(function() {
    'use strict';

    // Toast notification system
    function showToast(message, type = 'info', duration = 3000) {
        const existingToast = document.getElementById('jodit-upload-toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.id = 'jodit-upload-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 12px 20px;
            background: var(--card, #fff);
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-primary, #1f2937);
        `;

        if (type === 'loading') {
            const spinner = document.createElement('div');
            spinner.style.cssText = `
                width: 18px;
                height: 18px;
                border: 2px solid var(--border, #e5e7eb);
                border-top-color: var(--primary, #0891b2);
                border-radius: 50%;
                animation: jodit-spin 0.8s linear infinite;
            `;
            toast.appendChild(spinner);
        } else if (type === 'success') {
            toast.innerHTML = `
                <svg style="width:18px;height:18px;color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            `;
            toast.style.borderColor = 'rgba(22, 163, 74, 0.3)';
        } else if (type === 'error') {
            toast.innerHTML = `
                <svg style="width:18px;height:18px;color:#dc2626" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
            toast.style.borderColor = 'rgba(220, 38, 38, 0.3)';
        }

        const text = document.createElement('span');
        text.textContent = message;
        toast.appendChild(text);

        document.body.appendChild(toast);

        if (duration > 0) {
            setTimeout(() => toast.remove(), duration);
        }

        return toast;
    }

    // Add CSS animations
    if (!document.getElementById('jodit-upload-styles')) {
        const style = document.createElement('style');
        style.id = 'jodit-upload-styles';
        style.textContent = `
            @keyframes jodit-spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }

    // Create custom image upload dialog
    function createImageUploadDialog(editor) {
        // Remove existing dialog
        const existing = document.getElementById('ppid-image-dialog');
        if (existing) existing.remove();

        const dialog = document.createElement('div');
        dialog.id = 'ppid-image-dialog';
        dialog.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--card, #fff);
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 12px;
            padding: 24px;
            z-index: 100000;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            min-width: 400px;
            max-width: 500px;
        `;

        dialog.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Upload Gambar</h3>
                <button id="ppid-dialog-close" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <div id="ppid-drop-zone" style="border: 2px dashed var(--border); border-radius: 8px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.2s;">
                <svg style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p style="margin: 0 0 8px; font-size: 14px; color: var(--text-primary);">Drag & drop gambar ke sini</p>
                <p style="margin: 0; font-size: 12px; color: var(--text-muted);">atau klik untuk memilih file</p>
                <input type="file" id="ppid-file-input" accept="image/*" style="display: none;">
            </div>
            <div style="margin-top: 16px; display: flex; gap: 8px; justify-content: flex-end;">
                <button id="ppid-cancel-btn" style="padding: 8px 16px; border: 1px solid var(--border); border-radius: 6px; background: var(--card); cursor: pointer; font-size: 14px;">Batal</button>
            </div>
        `;

        document.body.appendChild(dialog);

        const dropZone = document.getElementById('ppid-drop-zone');
        const fileInput = document.getElementById('ppid-file-input');
        const closeBtn = document.getElementById('ppid-dialog-close');
        const cancelBtn = document.getElementById('ppid-cancel-btn');

        // Close dialog
        function closeDialog() {
            dialog.remove();
        }

        closeBtn.onclick = closeDialog;
        cancelBtn.onclick = closeDialog;

        // Click to upload
        dropZone.onclick = () => fileInput.click();

        // Drag and drop
        dropZone.ondragover = (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--primary)';
            dropZone.style.background = 'var(--secondary-light, #f0f9ff)';
        };

        dropZone.ondragleave = () => {
            dropZone.style.borderColor = 'var(--border)';
            dropZone.style.background = 'transparent';
        };

        dropZone.ondrop = (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--border)';
            dropZone.style.background = 'transparent';

            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                handleImageUpload(files[0], editor, closeDialog);
            }
        };

        // File input change
        fileInput.onchange = () => {
            if (fileInput.files.length > 0) {
                handleImageUpload(fileInput.files[0], editor, closeDialog);
            }
        };
    }

    // Handle image upload
    function handleImageUpload(file, editor, closeDialog) {
        // Validate file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB.');
            return;
        }

        closeDialog();

        // Show uploading toast
        showToast('Mengupload dan memproses gambar...', 'loading', 0);

        // Create FormData
        const formData = new FormData();
        formData.append('image', file);

        // Upload via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/news/upload-image', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showToast('Gambar berhasil diupload!', 'success', 2000);
                        // Insert image into editor
                        editor.selection.insertHTML(`<img src="${response.url}" style="max-width: 100%; height: auto; border-radius: 8px;">`);
                        editor.events.fire('change');
                    } else {
                        showToast(response.error || 'Upload gagal', 'error', 3000);
                    }
                } catch (e) {
                    showToast('Respons server tidak valid', 'error', 3000);
                }
            } else {
                showToast('Upload gagal (HTTP ' + xhr.status + ')', 'error', 3000);
            }
        };

        xhr.onerror = function() {
            showToast('Kesalahan koneksi', 'error', 3000);
        };

        xhr.send(formData);
    }

    // Register Jodit plugin
    if (typeof Jodit !== 'undefined') {
        Jodit.plugins.add('ppidImageUpload', function(editor) {
            editor.events.on('afterInit', function() {
                // Override image button click
                editor.events.on('click', function(e) {
                    const imageBtn = e.target.closest('[data-name="image"]');
                    if (imageBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        createImageUploadDialog(editor);
                        return false;
                    }
                });

                // Handle paste images
                editor.events.on('paste', function(e) {
                    const clipboardData = e.clipboardData || window.clipboardData;
                    if (!clipboardData) return;

                    const items = clipboardData.items;
                    for (let i = 0; i < items.length; i++) {
                        if (items[i].type.indexOf('image') !== -1) {
                            e.preventDefault();
                            const file = items[i].getAsFile();
                            if (file) {
                                handleImageUpload(file, editor, function() {});
                            }
                            break;
                        }
                    }
                });

                // Handle drag and drop
                editor.events.on('drop', function(e) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0 && files[0].type.startsWith('image/')) {
                        e.preventDefault();
                        handleImageUpload(files[0], editor, function() {});
                    }
                });
            });
        });

        console.log('PPID Image Upload plugin registered');
    }
})();
