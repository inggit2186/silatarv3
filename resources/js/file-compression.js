/**
 * File Compression Utility
 * Handles PDF compression and image compression
 */
import { PDFDocument } from 'pdf-lib';

const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
const MAX_IMAGE_SIZE = 1 * 1024 * 1024; // 1MB
const COMPRESSION_QUALITY = 0.8; // 80% quality for images

/**
 * Format file size to human readable
 */
export function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

/**
 * Compress PDF using pdf-lib
 * Returns compressed blob or original if compression fails
 */
export async function compressPdf(file) {
    if (!file || file.type !== 'application/pdf') {
        return file;
    }

    try {
        const arrayBuffer = await file.arrayBuffer();
        const pdfDoc = await PDFDocument.load(arrayBuffer);

        // Save with compression options
        const compressedPdf = await pdfDoc.save({
            useObjectStreams: true,
            addDefaultPage: false,
            preserveEditability: false,
        });

        // Create new file from compressed data
        const compressedBlob = new Blob([compressedPdf], { type: 'application/pdf' });

        // Only return compressed if it's actually smaller
        if (compressedBlob.size < file.size) {
            console.log(`PDF compressed: ${formatFileSize(file.size)} -> ${formatFileSize(compressedBlob.size)}`);
            return new File([compressedBlob], file.name, { type: 'application/pdf' });
        }

        return file; // Return original if compression didn't help
    } catch (error) {
        console.warn('PDF compression failed, using original:', error.message);
        return file;
    }
}

/**
 * Compress image using canvas
 */
export async function compressImage(file) {
    if (!file.type.startsWith('image/')) {
        return file;
    }

    try {
        const img = new Image();
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Load image
        const url = URL.createObjectURL(file);
        await new Promise((resolve, reject) => {
            img.onload = resolve;
            img.onerror = reject;
            img.src = url;
        });

        URL.revokeObjectURL(url);

        // Calculate new dimensions (max 1920px)
        let { width, height } = img;
        const maxDim = 1920;

        if (width > maxDim || height > maxDim) {
            if (width > height) {
                height = Math.round((height * maxDim) / width);
                width = maxDim;
            } else {
                width = Math.round((width * maxDim) / height);
                height = maxDim;
            }
        }

        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0, width, height);

        // Convert to blob with quality
        const blob = await new Promise((resolve) => {
            canvas.toBlob(resolve, 'image/jpeg', COMPRESSION_QUALITY);
        });

        if (blob.size < file.size) {
            const ext = file.name.split('.').pop();
            const baseName = file.name.replace(/\.[^.]+$/, '');
            const newName = `${baseName}.jpg`;

            console.log(`Image compressed: ${formatFileSize(file.size)} -> ${formatFileSize(blob.size)}`);
            return new File([blob], newName, { type: 'image/jpeg' });
        }

        return file;
    } catch (error) {
        console.warn('Image compression failed, using original:', error.message);
        return file;
    }
}

/**
 * Process file - validate size and compress if needed
 */
export async function processFile(file) {
    if (!file) return null;

    // Validate file size
    const maxSize = file.type === 'application/pdf' ? MAX_FILE_SIZE : MAX_IMAGE_SIZE;

    if (file.size > maxSize) {
        throw new Error(`File terlalu besar. Maksimal ${formatFileSize(maxSize)}`);
    }

    // Compress based on type
    if (file.type === 'application/pdf') {
        return await compressPdf(file);
    } else if (file.type.startsWith('image/')) {
        return await compressImage(file);
    }

    return file;
}

/**
 * Validate file size without compression
 */
export function validateFileSize(file) {
    if (!file) return { valid: true };

    const maxSize = file.type === 'application/pdf' ? MAX_FILE_SIZE : MAX_IMAGE_SIZE;
    const valid = file.size <= maxSize;

    return {
        valid,
        message: valid ? null : `File terlalu besar. Maksimal ${formatFileSize(maxSize)}`,
        maxSize,
        currentSize: file.size,
    };
}
