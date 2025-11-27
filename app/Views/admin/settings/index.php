<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php if (!empty($groupedSettings)) : ?>
    <form action="<?= base_url('dashboard/settings/update') ?>" method="post" id="settingsForm" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?php
        // Define the order and labels for tabs
        $tabOrder = [
            'general' => 'Pengaturan Umum',
            'visual_identity' => 'Identitas Visual',
            'contact_info' => 'Informasi Kontak',
            'admission_info' => 'Info Pendaftaran',
            'social_media' => 'Media Sosial',
            'legal_documents' => 'Dokumen Legal',
            'seo_config' => 'Konfigurasi SEO',
            'email_config' => 'Konfigurasi Email',
            'security' => 'Keamanan',
            'academic_calendar' => 'Kalender Akademik',
            'website_behavior' => 'Perilaku Website',
            'payment_config' => 'Konfigurasi Pembayaran',
            'academic_info' => 'Informasi Akademik'
        ];
        ?>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="settingsTab" role="tablist" style="padding-top: 8px; padding-left: 16px;">
                    <?php $isActive = true; ?>
                    <?php foreach ($tabOrder as $groupKey => $groupLabel) : ?>
                        <?php if (isset($groupedSettings[$groupKey])) : ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $isActive ? 'active' : '' ?>" id="<?= $groupKey ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $groupKey ?>" type="button" role="tab" aria-controls="<?= $groupKey ?>" aria-selected="<?= $isActive ? 'true' : 'false' ?>">
                                    <?= $groupLabel ?>
                                </button>
                            </li>
                            <?php $isActive = false; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="settingsTabContent">
                    <?php $isActive = true; ?>
                    <?php foreach ($tabOrder as $groupKey => $groupLabel) : ?>
                        <?php if (isset($groupedSettings[$groupKey])) : ?>
                            <div class="tab-pane fade <?= $isActive ? 'show active' : '' ?>" id="<?= $groupKey ?>" role="tabpanel" aria-labelledby="<?= $groupKey ?>-tab">
                                <h5 class="mb-3 text-primary"><?= $groupLabel ?></h5>

                                <?php foreach ($groupedSettings[$groupKey] as $setting) : ?>
                                    <div class="mb-3 row">
                                        <label for="<?= $setting->key_name ?>" class="col-sm-3 col-form-label">
                                            <?= $setting->description ?>
                                            <br>
                                            <small class="text-muted code-text"><?= $setting->key_name ?></small>
                                        </label>
                                        <div class="col-sm-9">
                                            <?php if ($setting->type == 'textarea') : ?>
                                                <textarea class="form-control" name="<?= $setting->key_name ?>" rows="3" placeholder="<?= $setting->description ?>"><?= esc($setting->value) ?></textarea>
                                                <?php if ($setting->key_name == 'whatsapp_message') : ?>
                                                    <small class="form-text text-muted">Default message untuk WhatsApp customer service</small>
                                                <?php elseif ($setting->key_name == 'meta_description') : ?>
                                                    <small class="form-text text-muted">Maksimal 160 karakter untuk optimal SEO</small>
                                                <?php elseif ($setting->key_name == 'meta_keywords') : ?>
                                                    <small class="form-text text-muted">Pisahkan dengan koma: sekolah,pendidikan,islam</small>
                                                <?php elseif ($setting->key_name == 'google_analytics') : ?>
                                                    <small class="form-text text-muted">Paste Google Analytics tracking code (gtag.js atau analytics.js)</small>
                                                <?php elseif ($setting->key_name == 'hero_description') : ?>
                                                    <small class="form-text text-muted">Deskripsi untuk hero section di homepage</small>
                                                <?php elseif ($setting->key_name == 'holidays_schedule') : ?>
                                                    <small class="form-text text-muted">Format: tanggal deskripsi (setiap baris satu libur)</small>
                                                <?php elseif ($setting->key_name == 'exam_schedule') : ?>
                                                    <small class="form-text text-muted">Informasi jadwal ujian</small>
                                                <?php elseif ($setting->key_name == 'event_announcement') : ?>
                                                    <small class="form-text text-muted">Pengumuman event terkini</small>
                                                <?php elseif ($setting->key_name == 'upcoming_events') : ?>
                                                    <small class="form-text text-muted">Event mendatang (setiap baris satu event)</small>
                                                <?php elseif ($setting->key_name == 'admission_procedure') : ?>
                                                    <small class="form-text text-muted">Prosedur pendaftaran (setiap baris satu langkah)</small>
                                                <?php elseif ($setting->key_name == 'additional_documents') : ?>
                                                    <small class="form-text text-muted">Dokumen tambahan yang dibutuhkan</small>
                                                <?php elseif ($setting->key_name == 'privacy_policy') : ?>
                                                    <small class="form-text text-muted">Kebijakan privasi sekolah</small>
                                                <?php elseif ($setting->key_name == 'terms_of_service') : ?>
                                                    <small class="form-text text-muted">Terms of service</small>
                                                <?php elseif ($setting->key_name == 'robots_txt') : ?>
                                                    <small class="form-text text-muted">Konten robots.txt untuk SEO</small>
                                                <?php elseif ($setting->key_name == 'maintenance_message') : ?>
                                                    <small class="form-text text-muted">Pesan yang ditampilkan saat mode maintenance</small>
                                                <?php elseif ($setting->key_name == 'donation_message') : ?>
                                                    <small class="form-text text-muted">Pesan untuk fitur donasi</small>
                                                <?php elseif ($setting->key_name == 'curriculum') : ?>
                                                    <small class="form-text text-muted">Kurikulum yang digunakan di sekolah</small>
                                                <?php elseif ($setting->key_name == 'school_mission') : ?>
                                                    <small class="form-text text-muted">Misi sekolah</small>
                                                <?php elseif ($setting->key_name == 'school_vision') : ?>
                                                    <small class="form-text text-muted">Visi sekolah</small>
                                                <?php endif; ?>
                                            <?php elseif ($setting->type == 'boolean') : ?>
                                                <select class="form-select" name="<?= $setting->key_name ?>">
                                                    <option value="1" <?= $setting->value == '1' ? 'selected' : '' ?>>Yes / True</option>
                                                    <option value="0" <?= $setting->value == '0' ? 'selected' : '' ?>>No / False</option>
                                                </select>
                                                <?php if ($setting->key_name == 'show_branding') : ?>
                                                    <small class="form-text text-muted">Tampilkan logo/branding di seluruh halaman</small>
                                                <?php elseif ($setting->key_name == 'social_media_widget') : ?>
                                                    <small class="form-text text-muted">Tampilkan widget media sosial</small>
                                                <?php elseif ($setting->key_name == 'sitemap_enabled') : ?>
                                                    <small class="form-text text-muted">Aktifkan sitemap.xml</small>
                                                <?php elseif ($setting->key_name == 'cdn_enabled') : ?>
                                                    <small class="form-text text-muted">Gunakan CDN untuk assets</small>
                                                <?php elseif ($setting->key_name == 'minify_html') : ?>
                                                    <small class="form-text text-muted">Minify HTML output</small>
                                                <?php elseif ($setting->key_name == 'guest_comment') : ?>
                                                    <small class="form-text text-muted">Izinkan komentar dari pengunjung tanpa login</small>
                                                <?php elseif ($setting->key_name == 'auto_approve_comments') : ?>
                                                    <small class="form-text text-muted">Otomatis approve komentar (jika tidak aktif, perlu moderasi)</small>
                                                <?php elseif ($setting->key_name == 'comment_captcha') : ?>
                                                    <small class="form-text text-muted">Aktifkan captcha untuk komentar</small>
                                                <?php elseif ($setting->key_name == 'enable_reactions') : ?>
                                                    <small class="form-text text-muted">Aktifkan sistem reaksi emoji</small>
                                                <?php elseif ($setting->key_name == 'enable_sharing') : ?>
                                                    <small class="form-text text-muted">Tampilkan tombol share media sosial</small>
                                                <?php elseif ($setting->key_name == 'enable_search') : ?>
                                                    <small class="form-text text-muted">Aktifkan fitur pencarian</small>
                                                <?php elseif ($setting->key_name == 'email_notifications') : ?>
                                                    <small class="form-text text-muted">Kirim notifikasi email untuk aktivitas penting</small>
                                                <?php elseif ($setting->key_name == 'application_notification') : ?>
                                                    <small class="form-text text-muted">Notifikasi untuk pendaftaran siswa baru</small>
                                                <?php elseif ($setting->key_name == 'comment_notification') : ?>
                                                    <small class="form-text text-muted">Notifikasi untuk komentar baru</small>
                                                <?php elseif ($setting->key_name == 'recaptcha_enabled') : ?>
                                                    <small class="form-text text-muted">Aktifkan reCAPTCHA untuk formulir</small>
                                                <?php elseif ($setting->key_name == 'gdpr_compliance') : ?>
                                                    <small class="form-text text-muted">Aktifkan fitur kepatuhan GDPR</small>
                                                <?php elseif ($setting->key_name == 'cookie_consent') : ?>
                                                    <small class="form-text text-muted">Tampilkan notifikasi persetujuan cookie</small>
                                                <?php elseif ($setting->key_name == 'maintenance_mode') : ?>
                                                    <small class="form-text text-muted">Aktifkan mode perawatan website</small>
                                                <?php elseif ($setting->key_name == 'payment_enabled') : ?>
                                                    <small class="form-text text-muted">Aktifkan gateway pembayaran</small>
                                                <?php elseif ($setting->key_name == 'donation_enabled') : ?>
                                                    <small class="form-text text-muted">Aktifkan sistem donasi</small>
                                                <?php endif; ?>
                                            <?php elseif ($setting->type == 'color') : ?>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" name="<?= $setting->key_name ?>" value="<?= esc($setting->value ?: '#0ea5e9') ?>" id="<?= $setting->key_name ?>">
                                                    <input type="text" class="form-control" value="<?= esc($setting->value ?: '#0ea5e9') ?>" pattern="^#[0-9A-Fa-f]{6}$" placeholder="#000000" oninput="document.getElementById('<?= $setting->key_name ?>').value = this.value;">
                                                </div>
                                                <small class="form-text text-muted">Pilih warna primer website</small>
                                            <?php elseif ($setting->type == 'tel') : ?>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <?php if ($setting->key_name == 'whatsapp_number') : ?>
                                                            <i class="fab fa-whatsapp"></i>
                                                        <?php else : ?>
                                                            <i class="fas fa-phone"></i>
                                                        <?php endif; ?>
                                                    </span>
                                                    <input type="tel" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>"
                                                        <?php if ($setting->key_name == 'whatsapp_number') : ?>
                                                        placeholder="+628123456789"
                                                        <?php else : ?>
                                                        placeholder="(021) 123456789"
                                                        <?php endif; ?>>
                                                </div>
                                                <small class="form-text text-muted">
                                                    <?php if ($setting->key_name == 'whatsapp_number') : ?>
                                                        Format: +62xxx untuk WhatsApp
                                                    <?php else : ?>
                                                        Format: (021) untuk telepon
                                                    <?php endif; ?>
                                                </small>
                                            <?php elseif ($setting->type == 'email') : ?>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                                    <input type="email" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>" placeholder="email@example.com">
                                                </div>
                                                <small class="form-text text-muted">Alamat email untuk pengiriman</small>
                                            <?php elseif ($setting->type == 'url') : ?>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <?php if ($setting->key_name == 'instagram_url') : ?>
                                                            <i class="fab fa-instagram" style="color: #E4405F;"></i>
                                                        <?php elseif ($setting->key_name == 'facebook_url') : ?>
                                                            <i class="fab fa-facebook" style="color: #1877F2;"></i>
                                                        <?php elseif ($setting->key_name == 'youtube_url') : ?>
                                                            <i class="fab fa-youtube" style="color: #FF0000;"></i>
                                                        <?php elseif ($setting->key_name == 'tiktok_url') : ?>
                                                            <i class="fab fa-tiktok" style="color: #000000;"></i>
                                                        <?php elseif ($setting->key_name == 'maps_embed_url') : ?>
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        <?php else : ?>
                                                            <i class="fas fa-link"></i>
                                                        <?php endif; ?>
                                                    </span>
                                                    <input type="url" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>" placeholder="https://example.com">
                                                </div>
                                                <small class="form-text text-muted">URL lengkap dengan https://</small>
                                            <?php elseif ($setting->type == 'number') : ?>
                                                <input type="number" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>" placeholder="0">
                                                <?php if ($setting->key_name == 'min_age') : ?>
                                                    <small class="form-text text-muted">Minimal usia untuk pendaftaran</small>
                                                <?php elseif ($setting->key_name == 'max_age') : ?>
                                                    <small class="form-text text-muted">Maksimal usia untuk pendaftaran</small>
                                                <?php elseif ($setting->key_name == 'smtp_port') : ?>
                                                    <small class="form-text text-muted">Port untuk koneksi SMTP</small>
                                                <?php elseif ($setting->key_name == 'data_retention_days') : ?>
                                                    <small class="form-text text-muted">Jumlah hari untuk retensi data</small>
                                                <?php elseif ($setting->key_name == 'max_login_attempts') : ?>
                                                    <small class="form-text text-muted">Maksimal percobaan login sebelum lockout</small>
                                                <?php elseif ($setting->key_name == 'login_lockout_time') : ?>
                                                    <small class="form-text text-muted">Durasi lockout login dalam detik</small>
                                                <?php elseif ($setting->key_name == 'posts_per_page') : ?>
                                                    <small class="form-text text-muted">Jumlah post ditampilkan per halaman</small>
                                                <?php elseif ($setting->key_name == 'galleries_per_page') : ?>
                                                    <small class="form-text text-muted">Jumlah galeri ditampilkan per halaman</small>
                                                <?php elseif ($setting->key_name == 'search_results_per_page') : ?>
                                                    <small class="form-text text-muted">Jumlah hasil pencarian per halaman</small>
                                                <?php endif; ?>
                                            <?php elseif ($setting->type == 'date') : ?>
                                                <input type="date" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>">
                                                <?php if ($setting->key_name == 'admission_deadline') : ?>
                                                    <small class="form-text text-muted">Deadline terakhir pendaftaran siswa</small>
                                                <?php endif; ?>
                                            <?php elseif ($setting->type == 'file') : ?>
                                                <?php if (!empty($setting->value)) : ?>
                                                    <div class="mb-2">
                                                        <div class="card card-body bg-light">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                                <div>
                                                                    <strong><?= basename($setting->value) ?></strong>
                                                                    <br>
                                                                    <small class="text-muted">Current document</small>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2">
                                                                <a href="<?= base_url($setting->value) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                                <a href="<?= base_url('dashboard/settings/delete-image/' . $setting->id) ?>"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this document?')">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="<?= $setting->key_name ?>" accept=".pdf,.doc,.docx" id="<?= $setting->key_name ?>">
                                                <small class="form-text text-muted">Supported formats: PDF, DOC, DOCX. Max size: 5MB.</small>
                                                <?php if ($setting->key_name == 'legalitas_lain') : ?>
                                                    <small class="form-text text-info">Deskripsi legalitas lainnya (opsional)</small>
                                                <?php elseif ($setting->key_name == 'academic_calendar_url') : ?>
                                                    <small class="form-text text-muted">Upload kalender akademik dalam format PDF</small>
                                                <?php endif; ?>
                                            <?php elseif ($setting->type == 'image') : ?>
                                                <?php if (!empty($setting->value)) : ?>
                                                    <div class="mb-2">
                                                        <?php if (strpos($setting->key_name, 'favicon') !== false) : ?>
                                                            <img src="<?= base_url($setting->value) ?>" alt="<?= $setting->key_name ?>" style="max-height: 32px; border: 1px solid #ddd;">
                                                        <?php elseif (strpos($setting->key_name, 'logo') !== false) : ?>
                                                            <img src="<?= base_url($setting->value) ?>" alt="<?= $setting->key_name ?>" class="img-thumbnail" style="max-height: 80px;">
                                                        <?php elseif (strpos($setting->key_name, 'og_image') !== false) : ?>
                                                            <img src="<?= base_url($setting->value) ?>" alt="<?= $setting->key_name ?>" class="img-thumbnail" style="max-height: 150px;">
                                                        <?php elseif (strpos($setting->key_name, 'hero_bg_image') !== false) : ?>
                                                            <img src="<?= base_url($setting->value) ?>" alt="<?= $setting->key_name ?>" class="img-thumbnail" style="max-height: 150px;">
                                                        <?php else : ?>
                                                            <img src="<?= base_url($setting->value) ?>" alt="<?= $setting->key_name ?>" class="img-thumbnail" style="max-height: 150px;">
                                                        <?php endif; ?>
                                                        <div class="mt-2">
                                                            <a href="<?= base_url('dashboard/settings/delete-image/' . $setting->id) ?>"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this image?')">
                                                                <i class="fas fa-trash"></i> Delete Image
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="<?= $setting->key_name ?>" accept="image/*" id="<?= $setting->key_name ?>">
                                                <div class="mt-1">
                                                    <small class="text-info">
                                                        <?php if (strpos($setting->key_name, 'favicon') !== false) : ?>
                                                            Supported formats: ICO, PNG. Size: 32x32px recommended.
                                                        <?php elseif (strpos($setting->key_name, 'og_image') !== false) : ?>
                                                            Supported formats: JPG, PNG. Size: 1200x630px recommended.
                                                        <?php elseif (strpos($setting->key_name, 'hero_bg_image') !== false) : ?>
                                                            Supported formats: JPG, PNG, WebP. Size: 1920x1080px recommended.
                                                        <?php else : ?>
                                                            Supported formats: JPG, PNG, GIF, WebP. Max size: 5MB.
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            <?php else : ?>
                                                <input type="text" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>" placeholder="<?= $setting->description ?>">
                                                <?php if ($setting->key_name == 'hero_title') : ?>
                                                    <small class="form-text text-muted">Judul untuk hero section di homepage</small>
                                                <?php elseif ($setting->key_name == 'hero_cta_text') : ?>
                                                    <small class="form-text text-muted">Teks untuk tombol Call-to-Action di hero section</small>
                                                <?php elseif ($setting->key_name == 'hero_cta_url') : ?>
                                                    <small class="form-text text-muted">Link untuk tombol Call-to-Action di hero section</small>
                                                <?php elseif ($setting->key_name == 'registration_period') : ?>
                                                    <small class="form-text text-muted">Periode pendaftaran (contoh: Januari - Juli 2025)</small>
                                                <?php elseif ($setting->key_name == 'registration_status') : ?>
                                                    <small class="form-text text-muted">Status pendaftaran (open/closed)</small>
                                                <?php elseif ($setting->key_name == 'registration_fee') : ?>
                                                    <small class="form-text text-muted">Biaya pendaftaran</small>
                                                <?php elseif ($setting->key_name == 'annual_fee') : ?>
                                                    <small class="form-text text-muted">Biaya tahunan</small>
                                                <?php elseif ($setting->key_name == 'payment_method') : ?>
                                                    <small class="form-text text-muted">Metode pembayaran (contoh: manual_transfer, midtrans)</small>
                                                <?php elseif ($setting->key_name == 'bank_account_name') : ?>
                                                    <small class="form-text text-muted">Nama pemilik rekening</small>
                                                <?php elseif ($setting->key_name == 'bank_account_number') : ?>
                                                    <small class="form-text text-muted">Nomor rekening</small>
                                                <?php elseif ($setting->key_name == 'bank_name') : ?>
                                                    <small class="form-text text-muted">Nama bank</small>
                                                <?php elseif ($setting->key_name == 'smtp_host') : ?>
                                                    <small class="form-text text-muted">SMTP host (contoh: smtp.gmail.com)</small>
                                                <?php elseif ($setting->key_name == 'smtp_username') : ?>
                                                    <small class="form-text text-muted">Username SMTP</small>
                                                <?php elseif ($setting->key_name == 'npsn') : ?>
                                                    <small class="form-text text-muted">Nomor Pokok Sekolah Nasional</small>
                                                <?php elseif ($setting->key_name == 'nss') : ?>
                                                    <small class="form-text text-muted">Nomor Statistik Sekolah</small>
                                                <?php elseif ($setting->key_name == 'latitude') : ?>
                                                    <small class="form-text text-muted">Koordinat latitude lokasi sekolah</small>
                                                <?php elseif ($setting->key_name == 'longitude') : ?>
                                                    <small class="form-text text-muted">Koordinat longitude lokasi sekolah</small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $isActive = false; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="button" class="btn btn-info me-2 text-white" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-upload"></i> Import Settings
                </button>
                <a href="<?= base_url('dashboard/settings/export') ?>" class="btn btn-success me-2">
                    <i class="fas fa-download"></i> Export Settings
                </a>
                <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#resetModal">
                    <i class="fas fa-redo"></i> Reset to Default
                </button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
<?php else : ?>
    <div class="alert alert-info text-center">
        No settings found. Please click "Seed Default Settings" to initialize the configuration.
    </div>
<?php endif; ?>

<!-- Import Settings Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('dashboard/settings/import') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="json_file" class="form-label">Upload JSON File</label>
                        <input class="form-control" type="file" id="json_file" name="json_file" accept=".json" required>
                        <div class="form-text">Upload file JSON hasil export dari sistem ini.</div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Import akan menimpa nilai pengaturan yang ada jika key-nya sama.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetModalLabel">Konfirmasi Reset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mereset semua pengaturan ke default? 
                <br><br>
                <strong class="text-danger">Peringatan:</strong> Tindakan ini akan menghapus semua konfigurasi saat ini dan tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('dashboard/settings/reset-and-seed') ?>" class="btn btn-danger">Ya, Reset Sekarang</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>