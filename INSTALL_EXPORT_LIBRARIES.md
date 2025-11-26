# Install Export Libraries untuk Fitur Export Excel & DOC

## Untuk fitur Export Excel dan DOC pada halaman Pendaftaran, perlu install library berikut:

### Install via Composer:

```bash
cd dash
composer require phpoffice/phpspreadsheet
composer require phpoffice/phpword
```

### Atau install sekaligus:

```bash
cd dash
composer require phpoffice/phpspreadsheet phpoffice/phpword
```

## Jika instalasi berhasil, fitur export akan otomatis aktif:
- Export Excel: Export semua data pendaftaran ke file .xlsx
- Export DOC: Export data individual pendaftaran ke file .docx

## Jika library belum terinstall:
- Tombol export akan menampilkan pesan error dengan instruksi instalasi
- Semua fitur lain tetap berfungsi normal
