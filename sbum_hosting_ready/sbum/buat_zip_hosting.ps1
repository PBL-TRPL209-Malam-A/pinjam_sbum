$projectDir = "C:\laragon\www\pinjam_sbum"
$hostingDir = "C:\laragon\www\pinjam_sbum_hosting"
$sbumDir = "$hostingDir\sbum"
$htdocsDir = "$hostingDir\htdocs"

# 1. Bersihkan folder tujuan jika sudah ada
if (Test-Path $hostingDir) {
    Remove-Item -Path $hostingDir -Recurse -Force
}

# 2. Buat folder struktur InfinityFree
New-Item -ItemType Directory -Path $hostingDir | Out-Null
New-Item -ItemType Directory -Path $sbumDir | Out-Null
New-Item -ItemType Directory -Path $htdocsDir | Out-Null

# 3. Copy semua file project kecuali node_modules dan .git
Write-Host "Menyalin file... (Ini mungkin memakan waktu sebentar)"
robocopy $projectDir $sbumDir /E /XD node_modules .git .vscode /NFL /NDL /NJH /NJS /nc /ns /np

# 4. Pindahkan isi folder public ke htdocs
Write-Host "Mengatur folder public (htdocs)..."
Move-Item -Path "$sbumDir\public\*" -Destination $htdocsDir -Force

# 5. Modifikasi index.php di htdocs
$indexPath = "$htdocsDir\index.php"
$indexContent = Get-Content $indexPath
$indexContent = $indexContent -replace "require __DIR__.'/../vendor/autoload.php';", "require __DIR__.'/../sbum/vendor/autoload.php';"
$indexContent = $indexContent -replace "\`$app = require_once __DIR__.'/../bootstrap/app.php';", "`$app = require_once __DIR__.'/../sbum/bootstrap/app.php';"
Set-Content -Path $indexPath -Value $indexContent

# 6. Jadikan file ZIP
Write-Host "Mengompresi menjadi ZIP (sbum_hosting_ready.zip)..."
$zipPath = "$projectDir\sbum_hosting_ready.zip"
if (Test-Path $zipPath) {
    Remove-Item -Path $zipPath -Force
}
Compress-Archive -Path "$hostingDir\*" -DestinationPath $zipPath -Force

# 7. Bersihkan folder sementara
Remove-Item -Path $hostingDir -Recurse -Force

Write-Host "=================================================="
Write-Host "SELESAI! File ZIP siap di-upload ke InfinityFree."
Write-Host "Lokasi file: $zipPath"
Write-Host "=================================================="
