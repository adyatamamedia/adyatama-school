# GitHub Update System - Setup Guide

## ✅ Yang Sudah Dilakukan

1. ✅ Repository GitHub dibuat: `adyatamamedia/adyatama-school-update`
2. ✅ UpdateController diupdate dengan GitHub API integration
3. ✅ Sistem sekarang bisa check update dari GitHub Releases

---

## 📝 Langkah Selanjutnya

### 1. Generate GitHub Personal Access Token (Optional tapi Recommended)

**Kenapa perlu?**
- Tanpa token: 60 requests/hour
- Dengan token: 5000 requests/hour

**Cara membuat:**

1. Login ke GitHub
2. Settings → Developer settings → Personal access tokens → Tokens (classic)
3. Generate new token (classic)
4. Note: `Adyatama School Update Access`
5. Expiration: No expiration (atau sesuai kebutuhan)
6. Select scopes: **Hanya centang `public_repo`** (untuk public repository)
7. Generate token
8. **COPY TOKEN** (hanya muncul sekali!): `ghp_xxxxxxxxxxxxxxxxxxxx`

### 2. Tambahkan Token ke .env

Buka file `.env` di folder `dash` dan tambahkan:

```env
# GitHub Update System
GITHUB_TOKEN=ghp_your_token_here
```

**Note:** Token ini OPSIONAL. Sistem tetap bisa berjalan tanpa token, tapi dengan rate limit lebih rendah.

---

### 3. Buat Release Pertama di GitHub

#### A. Siapkan Update Package

```bash
# Di folder dash, buat ZIP file
# EXCLUDE: .git, writable, .env, vendor (jika ada)

# Windows (PowerShell):
Compress-Archive -Path * -DestinationPath adyatama-school-v1.0.1.zip -Force

# Atau manual:
# 1. Select semua file KECUALI: .git, writable, .env
# 2. Right-click → Send to → Compressed (zipped) folder
# 3. Rename: adyatama-school-v1.0.1.zip
```

#### B. Upload ke GitHub Releases

1. Buka: https://github.com/adyatamamedia/adyatama-school-update
2. Klik **Releases** (di sidebar kanan)
3. Klik **Create a new release**
4. Isi form:
   - **Choose a tag:** `v1.0.1` (ketik dan klik "Create new tag")
   - **Release title:** `Version 1.0.1 - Initial Release`
   - **Describe this release:**
     ```markdown
     ## What's New
     - Initial release of Adyatama School CMS
     - Dashboard admin interface
     - Content management system
     - User management
     
     ## Requirements
     - PHP 8.0 or higher
     - MySQL 5.7 or higher
     - 50MB free disk space
     
     ## Installation
     1. Download the ZIP file below
     2. Extract to your web server
     3. Configure database in .env
     4. Run migrations
     ```
   - **Attach files:** Upload `adyatama-school-v1.0.1.zip`
5. Klik **Publish release**

---

### 4. Test Update System

1. Buka dashboard Anda
2. Login sebagai admin
3. Go to: **Dashboard → System Update**
4. Klik **"Check for Updates"**
5. Seharusnya muncul:
   - Current Version: 1.0.0
   - Available Update: 1.0.1 (jika version.json Anda masih 1.0.0)

---

## 🔄 Cara Release Update Baru (Untuk Masa Depan)

### Skenario: Anda sudah fix bugs dan ingin release v1.0.2

1. **Update version.json:**
   ```json
   {
     "current_version": "1.0.2",
     "last_check": null,
     "update_url": null
   }
   ```

2. **Buat ZIP package:**
   ```bash
   # Compress semua file (exclude .git, writable, .env)
   Compress-Archive -Path * -DestinationPath adyatama-school-v1.0.2.zip -Force
   ```

3. **Create GitHub Release:**
   - Tag: `v1.0.2`
   - Title: `Version 1.0.2 - Bug Fixes`
   - Description: List semua changes
   - Upload ZIP file

4. **Semua client dashboard akan otomatis detect update!**

---

## 🎯 Cara Kerja System

```
Client Dashboard (v1.0.0)
    ↓
    Check for updates
    ↓
GitHub API: /repos/adyatamamedia/adyatama-school-update/releases/latest
    ↓
    Response: v1.0.2 available
    ↓
Client Dashboard menampilkan:
    - Update available: v1.0.2
    - Download button
    - Changelog
    ↓
User klik Download
    ↓
Download ZIP dari GitHub
    ↓
Install update
    ↓
Dashboard updated to v1.0.2
```

---

## 🔐 Security Notes

**Current Implementation:**
- ✅ HTTPS connection to GitHub
- ✅ Version comparison
- ⚠️ No checksum verification (GitHub doesn't provide SHA256 by default)
- ⚠️ No signature verification

**Untuk Production (Future Enhancement):**
1. Generate checksums manually dan include di release notes
2. Implement GPG signature verification
3. Add backup before update
4. Add rollback mechanism

---

## 📊 Testing Checklist

- [ ] GitHub token added to .env (optional)
- [ ] First release created (v1.0.1)
- [ ] ZIP file uploaded to release
- [ ] Test "Check for Updates" button
- [ ] Verify update info displays correctly
- [ ] Test download button (will be implemented next)

---

## ❓ Troubleshooting

**Problem:** "Failed to check for updates"
- **Solution:** Check internet connection, verify repository is public

**Problem:** "No ZIP file found in GitHub release"
- **Solution:** Make sure you uploaded a .zip file to the release

**Problem:** Rate limit exceeded
- **Solution:** Add GitHub token to .env file

**Problem:** "Version not found"
- **Solution:** Make sure tag format is correct (v1.0.1, not 1.0.1)

---

## 🎉 Status

**Current Status:**
- ✅ GitHub integration: DONE
- ✅ Check for updates: WORKING
- ⏳ Download update: NEXT STEP
- ⏳ Install update: FUTURE
- ⏳ Backup/Rollback: FUTURE

**Next Steps:**
1. Create first GitHub release
2. Test update check
3. Implement real download functionality
4. Implement installation process
