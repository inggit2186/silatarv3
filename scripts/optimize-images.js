import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const args = process.argv.slice(2);
const options = {
    quality: 80,
    maxWidth: null,
    dryRun: false,
    replace: false,
    path: 'public/assets/img/template'
};

// Parse arguments
for (let i = 0; i < args.length; i++) {
    if (args[i] === '--quality' && args[i + 1]) {
        options.quality = parseInt(args[i + 1]);
        i++;
    } else if (args[i] === '--resize' && args[i + 1]) {
        options.maxWidth = parseInt(args[i + 1]);
        i++;
    } else if (args[i] === '--dry-run') {
        options.dryRun = true;
    } else if (args[i] === '--replace') {
        options.replace = true;
    } else if (args[i] === '--path' && args[i + 1]) {
        options.path = args[i + 1];
        i++;
    }
}

const basePath = path.resolve(process.cwd(), options.path);
const extensions = ['.png', '.jpg', '.jpeg', '.gif', '.webp'];

let stats = {
    scanned: 0,
    converted: 0,
    skipped: 0,
    failed: 0,
    sizeBefore: 0,
    sizeAfter: 0
};

function formatBytes(bytes) {
    const units = ['B', 'KB', 'MB', 'GB'];
    let i = 0;
    while (bytes >= 1024 && i < units.length - 1) {
        bytes /= 1024;
        i++;
    }
    return bytes.toFixed(1) + ' ' + units[i];
}

function processFile(filePath) {
    const ext = path.extname(filePath).toLowerCase();
    if (!extensions.includes(ext)) return;

    stats.scanned++;
    const currentSize = fs.statSync(filePath).size;
    stats.sizeBefore += currentSize;

    // Determine output path
    let outputPath = filePath;
    if (ext !== '.webp') {
        // Convert non-WebP to WebP
        outputPath = filePath.replace(/\.(png|jpg|jpeg|gif)$/i, '.webp');
    } else if (options.maxWidth) {
        // Resize WebP in place
        // outputPath stays as filePath
    } else {
        // Skip existing WebP files unless replacing or resizing
        if (!options.replace) {
            stats.skipped++;
            return;
        }
    }

    if (options.dryRun) {
        let action = 'convert';
        if (options.maxWidth) action = 'resize';
        console.log(`  [DRY-RUN] Would ${action}: ${path.basename(filePath)} (${formatBytes(currentSize)})`);
        stats.converted++;
        stats.sizeAfter += currentSize * 0.5;
        return;
    }

    // Read image to buffer (avoids input/output collision)
    return sharp(filePath)
        .resize(options.maxWidth ? { width: options.maxWidth, withoutEnlargement: true } : null)
        .webp({ quality: options.quality })
        .toBuffer()
        .then(buffer => {
            // Write to temp file first
            const tempPath = filePath + '.tmp';
            fs.writeFileSync(tempPath, buffer);

            // Replace original
            fs.unlinkSync(filePath);
            fs.renameSync(tempPath, filePath);

            stats.converted++;
            stats.sizeAfter += buffer.length;

            const reduction = Math.round((1 - buffer.length / currentSize) * 100, 1);
            const arrow = reduction > 0 ? '→' : '←';
            const icon = reduction > 0 ? '✓' : '!';
            console.log(`  ${icon} ${path.basename(filePath)}: ${formatBytes(currentSize)} ${arrow} ${formatBytes(buffer.length)} (${reduction}%)`);
        })
        .catch(err => {
            stats.failed++;
            console.error(`  ✗ Failed: ${path.basename(filePath)}: ${err.message}`);
        });
}

function walkDir(dir) {
    const files = fs.readdirSync(dir);

    for (const file of files) {
        const filePath = path.join(dir, file);
        const stat = fs.statSync(filePath);

        if (stat.isDirectory()) {
            walkDir(filePath);
        } else {
            processFile(filePath);
        }
    }
}

// Banner
console.log('═══════════════════════════════════════════');
console.log('     IMAGE OPTIMIZER (Sharp + WebP)        ');
console.log('═══════════════════════════════════════════');
console.log('');
console.log(`Path: ${basePath}`);
console.log(`Quality: ${options.quality}%`);
if (options.maxWidth) console.log(`Max width: ${options.maxWidth}px`);
console.log(`Mode: ${options.dryRun ? 'DRY-RUN' : (options.replace ? 'REPLACE' : 'CREATE copies')}`);
console.log('');
console.log('Converting...');
console.log('');

walkDir(basePath);

// Wait a bit for async operations to complete
setTimeout(() => {
    console.log('');
    console.log('═══════════════════════════════════════════');
    console.log('                 SUMMARY                   ');
    console.log('═══════════════════════════════════════════');
    console.log(`Scanned:  ${stats.scanned} files`);
    console.log(`Converted: ${stats.converted} files`);
    console.log(`Skipped:   ${stats.skipped} files`);
    console.log(`Failed:    ${stats.failed} files`);
    console.log('');

    if (stats.sizeBefore > 0) {
        const totalReduction = Math.round((1 - stats.sizeAfter / stats.sizeBefore) * 100, 1);
        console.log(`Size before: ${formatBytes(stats.sizeBefore)}`);
        console.log(`Size after:  ${formatBytes(stats.sizeAfter)}`);
        console.log(`Reduction:   ${totalReduction}%`);
    }
}, 1000);
