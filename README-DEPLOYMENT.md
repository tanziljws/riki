# Deployment Guide

## Konfigurasi Database MySQL Railway

Database sudah dikonfigurasi untuk menggunakan MySQL Railway dengan kredensial berikut:

- **Host**: trolley.proxy.rlwy.net
- **Port**: 49593
- **Username**: root
- **Password**: BUNIgCsnyeQPwCuZpxLXrBPNYAJoolki
- **Database**: railway

Konfigurasi ini sudah ada di file `.env`.

## Import Database

### Cara 1: Menggunakan Script (Recommended)

```bash
# Pastikan file SQL ada di root project
./import-sql.sh
```

### Cara 2: Manual Import

```bash
mysql -h trolley.proxy.rlwy.net -P 49593 -u root -pBUNIgCsnyeQPwCuZpxLXrBPNYAJoolki --protocol=TCP railway < "galeriweb (3).sql"
```

### Cara 3: Menggunakan MySQL Client

```bash
mysql -h trolley.proxy.rlwy.net -P 49593 -u root -p --protocol=TCP railway
# Masukkan password: BUNIgCsnyeQPwCuZpxLXrBPNYAJoolki
# Kemudian:
source galeriweb\ \(3\).sql;
```

## Git Push ke GitHub

Repository sudah dikonfigurasi ke: `https://github.com/tanziljws/riki.git`

### Push Manual

```bash
git add .
git commit -m "Your commit message"
git push origin main
```

### Auto-push (Watch Mode)

Untuk auto-push setiap ada perubahan, Anda bisa menggunakan tools seperti:
- `gitwatch` - Git file watcher
- `inotifywait` (Linux) atau `fswatch` (macOS)

Contoh dengan fswatch (macOS):
```bash
# Install fswatch: brew install fswatch
fswatch -o . | xargs -n1 -I{} git add -A && git commit -m "Auto commit" && git push
```

## Catatan Penting

1. **Folder Public**: Semua file di folder `public/` (images, videos, css, dll) sudah dikonfigurasi untuk di-track oleh Git, kecuali `public/storage` (symlink).

2. **File .env**: File `.env` tidak di-commit ke Git (sudah di-ignore). Pastikan untuk mengatur variabel environment di server production.

3. **Database**: Pastikan koneksi ke MySQL Railway berfungsi sebelum menjalankan aplikasi.

## Troubleshooting

### Error 500 saat Git Push
- Coba lagi setelah beberapa saat
- Pastikan repository sudah dibuat di GitHub
- Pastikan Anda memiliki akses ke repository

### Error Koneksi Database
- Pastikan kredensial database benar
- Pastikan port dan host dapat diakses
- Cek firewall/network settings

