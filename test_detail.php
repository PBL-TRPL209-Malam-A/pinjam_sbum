<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "Peminjaman Records:\n";
print_r(App\Models\Peminjaman::with(['ruangan', 'barang'])->take(5)->get()->toArray());
