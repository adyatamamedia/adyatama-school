# 🚀 DEPLOYMENT GUIDE: Update Server Setup

## Pilihan Deployment

Saya akan jelaskan **3 opsi deployment** dari yang paling mudah sampai yang paling advanced:

---

## 📦 OPTION 1: GitHub Releases (RECOMMENDED untuk Testing)

### Keuntungan:
- ✅ **GRATIS** (untuk public repo)
- ✅ Mudah setup (5 menit)
- ✅ Reliable (GitHub infrastructure)
- ✅ Built-in version control
- ✅ Automatic checksums

### Cara Setup:

#### 1. Buat Repository untuk Updates
```bash
# Di GitHub, buat repository baru
Repository name: adyatama-school-updates
Description: Update packages for Adyatama School CMS
Visibility: Private (atau Public jika mau gratis)
```

#### 2. Upload Update Package
```bash
# Buat update package (ZIP file)
cd /path/to/your/project
zip -r adyatama-school-v1.0.1.zip . -x "*.git*" "writable/*" ".env"

# Upload via GitHub Releases
# 1. Go to repository → Releases → Create new release
# 2. Tag: v1.0.1
# 3. Title: Version 1.0.1
# 4. Description: Changelog here
# 5. Attach ZIP file
# 6. Publish release
```

#### 3. Generate GitHub Token
```bash
# Settings → Developer settings → Personal access tokens → Generate new token
# Permissions needed: repo (full control)
# Copy token: ghp_xxxxxxxxxxxxxxxxxxxx
```

#### 4. Update Client Code

**File: `app/Controllers/Admin/UpdateController.php`**
```php
private function checkForLatestUpdate($currentVersion)
{
    $githubRepo = 'adyatama/adyatama-school-updates'; // Your repo
    $githubToken = env('GITHUB_TOKEN'); // Store in .env
    
    $client = \Config\Services::curlrequest();
    
    try {
        $response = $client->get(
            "https://api.github.com/repos/{$githubRepo}/releases/latest",
            [
                'headers' => [
                    'Authorization' => "Bearer {$githubToken}",
                    'Accept' => 'application/vnd.github.v3+json',
                    'User-Agent' => 'Adyatama-School-CMS'
                ]
            ]
        );
        
        if ($response->getStatusCode() === 200) {
            $release = json_decode($response->getBody(), true);
            $latestVersion = ltrim($release['tag_name'], 'v');
            
            if (version_compare($latestVersion, $currentVersion, '>')) {
                // Find ZIP asset
                $zipAsset = null;
                foreach ($release['assets'] as $asset) {
                    if (pathinfo($asset['name'], PATHINFO_EXTENSION) === 'zip') {
                        $zipAsset = $asset;
                        break;
                    }
                }
                
                if ($zipAsset) {
                    return [
                        'available' => true,
                        'version' => $latestVersion,
                        'description' => $release['name'],
                        'release_date' => date('Y-m-d', strtotime($release['published_at'])),
                        'download_url' => $zipAsset['browser_download_url'],
                        'file_size' => $zipAsset['size'],
                        'checksum' => $zipAsset['sha256'] ?? null,
                        'requirements' => 'PHP 8.0+, MySQL 5.7+',
                        'changelog' => $release['body']
                    ];
                }
            }
        }
        
        return ['available' => false];
        
    } catch (\Exception $e) {
        log_message('error', 'GitHub update check failed: ' . $e->getMessage());
        return ['available' => false];
    }
}
```

**File: `.env`**
```env
# Add this to your .env file
GITHUB_TOKEN=ghp_your_token_here
```

### Cara Menggunakan:

1. **Release Update Baru:**
   ```bash
   # 1. Buat ZIP package
   # 2. Go to GitHub → Releases → New release
   # 3. Tag: v1.0.2
   # 4. Upload ZIP
   # 5. Publish
   ```

2. **Client akan otomatis detect:**
   - Buka dashboard → System Update
   - Klik "Check for Updates"
   - Jika ada update, akan muncul download button

---

## ☁️ OPTION 2: Cloud Storage (DigitalOcean Spaces / AWS S3)

### Keuntungan:
- ✅ Simple setup
- ✅ Scalable
- ✅ CDN support
- ✅ Pay as you go

### Biaya:
- DigitalOcean Spaces: $5/month (250GB storage + 1TB transfer)
- AWS S3: ~$0.023/GB/month + transfer costs

### Cara Setup (DigitalOcean Spaces):

#### 1. Create Space
```bash
# Login to DigitalOcean
# Create → Spaces → Create Space
# Region: Singapore (SGP1)
# Name: adyatama-updates
# Enable CDN
```

#### 2. Upload Update Package
```bash
# Install s3cmd
sudo apt install s3cmd

# Configure
s3cmd --configure
# Access Key: Your DO Spaces key
# Secret Key: Your DO Spaces secret
# S3 Endpoint: sgp1.digitaloceanspaces.com

# Upload file
s3cmd put adyatama-school-v1.0.1.zip s3://adyatama-updates/releases/v1.0.1/
s3cmd setacl s3://adyatama-updates/releases/v1.0.1/adyatama-school-v1.0.1.zip --acl-public
```

#### 3. Create version.json
```json
{
  "latest_version": "1.0.1",
  "release_date": "2025-12-05",
  "download_url": "https://adyatama-updates.sgp1.cdn.digitaloceanspaces.com/releases/v1.0.1/adyatama-school-v1.0.1.zip",
  "file_size": 5242880,
  "checksum": "sha256_hash_here",
  "changelog": "- Bug fixes\n- Performance improvements",
  "requirements": "PHP 8.0+, MySQL 5.7+"
}
```

Upload version.json:
```bash
s3cmd put version.json s3://adyatama-updates/version.json --acl-public
```

#### 4. Update Client Code

```php
private function checkForLatestUpdate($currentVersion)
{
    $versionUrl = 'https://adyatama-updates.sgp1.cdn.digitaloceanspaces.com/version.json';
    
    try {
        $client = \Config\Services::curlrequest();
        $response = $client->get($versionUrl, ['timeout' => 10]);
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            
            if (version_compare($data['latest_version'], $currentVersion, '>')) {
                return [
                    'available' => true,
                    'version' => $data['latest_version'],
                    'description' => 'New version available',
                    'release_date' => $data['release_date'],
                    'download_url' => $data['download_url'],
                    'file_size' => $data['file_size'],
                    'checksum' => $data['checksum'],
                    'requirements' => $data['requirements'],
                    'changelog' => $data['changelog']
                ];
            }
        }
        
        return ['available' => false];
        
    } catch (\Exception $e) {
        log_message('error', 'Update check failed: ' . $e->getMessage());
        return ['available' => false];
    }
}
```

---

## 🖥️ OPTION 3: Self-Hosted Update Server (ADVANCED)

### Keuntungan:
- ✅ Full control
- ✅ Custom logic
- ✅ Private
- ✅ No rate limits

### Biaya:
- VPS: $5-10/month
- Domain: $10-15/year
- SSL: Free (Let's Encrypt)

### Cara Setup:

#### 1. Setup VPS

```bash
# Provision VPS (DigitalOcean, Vultr, Linode)
# OS: Ubuntu 22.04 LTS
# RAM: 1GB minimum
# Storage: 25GB minimum

# SSH to server
ssh root@your-server-ip

# Update system
apt update && apt upgrade -y

# Install LAMP stack
apt install apache2 php8.1 php8.1-mysql php8.1-curl php8.1-zip mysql-server -y

# Install Certbot for SSL
apt install certbot python3-certbot-apache -y
```

#### 2. Setup Domain & SSL

```bash
# Point domain to server IP
# updates.adyatama.com → your-server-ip

# Get SSL certificate
certbot --apache -d updates.adyatama.com

# Auto-renewal
certbot renew --dry-run
```

#### 3. Create Update Server

**File: `/var/www/updates/index.php`**
```php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database connection
$db = new PDO('mysql:host=localhost;dbname=updates', 'root', 'password');

// Get latest version
$action = $_GET['action'] ?? 'check';

if ($action === 'check') {
    $stmt = $db->query("SELECT * FROM versions ORDER BY created_at DESC LIMIT 1");
    $latest = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($latest) {
        echo json_encode([
            'success' => true,
            'latest_version' => $latest['version'],
            'release_date' => $latest['release_date'],
            'download_url' => "https://updates.adyatama.com/download.php?version=" . $latest['version'],
            'file_size' => $latest['file_size'],
            'checksum' => $latest['checksum'],
            'signature' => $latest['signature'],
            'changelog' => $latest['changelog'],
            'requirements' => $latest['requirements']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No versions available']);
    }
}
```

**File: `/var/www/updates/download.php`**
```php
<?php
$version = $_GET['version'] ?? null;

if (!$version) {
    http_response_code(400);
    die('Version required');
}

// Validate version
$db = new PDO('mysql:host=localhost;dbname=updates', 'root', 'password');
$stmt = $db->prepare("SELECT * FROM versions WHERE version = ?");
$stmt->execute([$version]);
$versionData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$versionData) {
    http_response_code(404);
    die('Version not found');
}

// Log download
$stmt = $db->prepare("INSERT INTO downloads (version, ip, user_agent, downloaded_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$version, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);

// Serve file
$filePath = "/var/www/updates/releases/{$version}/package.zip";

if (file_exists($filePath)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="adyatama-school-' . $version . '.zip"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
} else {
    http_response_code(404);
    die('File not found');
}
```

#### 4. Database Schema

```sql
CREATE DATABASE updates;
USE updates;

CREATE TABLE versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    version VARCHAR(20) NOT NULL UNIQUE,
    release_date DATE NOT NULL,
    file_size BIGINT NOT NULL,
    checksum VARCHAR(64) NOT NULL,
    signature TEXT,
    changelog TEXT,
    requirements TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    version VARCHAR(20) NOT NULL,
    ip VARCHAR(45),
    user_agent TEXT,
    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (version) REFERENCES versions(version)
);

-- Insert first version
INSERT INTO versions (version, release_date, file_size, checksum, changelog, requirements) 
VALUES ('1.0.1', '2025-12-05', 5242880, 'sha256_hash_here', 'Initial release', 'PHP 8.0+, MySQL 5.7+');
```

#### 5. Apache Configuration

```apache
<VirtualHost *:443>
    ServerName updates.adyatama.com
    DocumentRoot /var/www/updates
    
    <Directory /var/www/updates>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/updates-error.log
    CustomLog ${APACHE_LOG_DIR}/updates-access.log combined
    
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/updates.adyatama.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/updates.adyatama.com/privkey.pem
</VirtualHost>
```

---

## 🔐 Security Implementation

### 1. Generate GPG Keys

```bash
# On update server
gpg --full-generate-key
# Choose: RSA and RSA
# Key size: 4096
# Expiration: 0 (never)
# Name: Adyatama School Updates
# Email: updates@adyatama.com

# Export public key
gpg --armor --export updates@adyatama.com > public_key.pem

# Distribute public_key.pem to all clients
```

### 2. Sign Update Package

```bash
# Sign ZIP file
gpg --armor --detach-sign adyatama-school-v1.0.1.zip
# Creates: adyatama-school-v1.0.1.zip.asc

# Calculate checksum
sha256sum adyatama-school-v1.0.1.zip
```

### 3. Verify Signature (Client Side)

```php
// app/Libraries/UpdateService.php
public function verifySignature($filePath, $signaturePath, $publicKeyPath)
{
    $gpg = new gnupg();
    $gpg->import(file_get_contents($publicKeyPath));
    
    $fileContent = file_get_contents($filePath);
    $signature = file_get_contents($signaturePath);
    
    $info = $gpg->verify($fileContent, $signature);
    
    return !empty($info) && $info[0]['summary'] === 0;
}
```

---

## 📊 Comparison Table

| Feature | GitHub | Cloud Storage | Self-Hosted |
|---------|--------|---------------|-------------|
| **Setup Time** | 5 min | 15 min | 2-4 hours |
| **Cost** | Free/$4 | $5/month | $5-10/month |
| **Reliability** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| **Control** | ⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Scalability** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| **Privacy** | ⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Maintenance** | None | Low | Medium |

---

## 🎯 Recommendation

**For Testing/Development:**
→ Use **GitHub Releases** (Option 1)

**For Small-Medium Production:**
→ Use **Cloud Storage** (Option 2)

**For Enterprise/High Security:**
→ Use **Self-Hosted** (Option 3)

---

## 📝 Next Steps

1. Choose deployment option
2. Follow setup guide above
3. Test with staging environment
4. Deploy to production
5. Monitor and maintain
