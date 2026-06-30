const fs = require('fs');
const path = 'resources/views/laporan-madrasah.blade.php';

// Minimal test first
const testContent = '<x-layouts.app title="Laporan Madrasah - SILATAR">\n</x-layouts.app>';
fs.writeFileSync(path, testContent);
console.log('File created');
