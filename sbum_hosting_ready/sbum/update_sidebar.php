<?php

$files = glob(__DIR__ . '/resources/views/mahasiswa/*.blade.php');

$search = "<span>Pengembalian</span>\n                </a>";
$replace = "<span>Pengembalian</span>\n                </a>\n                <a href=\"{{ route('mahasiswa.riwayat') }}\" class=\"side-link {{ request()->routeIs('mahasiswa.riwayat') ? 'active' : '' }}\">\n                    <span class=\"side-icon\"></span>\n                    <span>Riwayat Peminjaman</span>\n                </a>";

foreach ($files as $file) {
    if (basename($file) == 'pdf_bukti.blade.php') continue;
    
    $content = file_get_contents($file);
    if (strpos($content, 'mahasiswa.riwayat') === false) {
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
        echo "Updated " . basename($file) . "\n";
    }
}
