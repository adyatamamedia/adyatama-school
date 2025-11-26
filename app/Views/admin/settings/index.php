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

        <div class="card shadow mb-4">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="settingsTab" role="tablist" style="padding-top: 8px; padding-left: 16px;">
                    <?php $isActive = true; ?>
                    <?php foreach ($groupedSettings as $group => $items) : ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $isActive ? 'active' : '' ?>" id="<?= $group ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $group ?>" type="button" role="tab" aria-controls="<?= $group ?>" aria-selected="<?= $isActive ? 'true' : 'false' ?>">
                                <?= ucfirst($group) ?>
                            </button>
                        </li>
                        <?php $isActive = false; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="settingsTabContent">
                    <?php $isActive = true; ?>
                    <?php foreach ($groupedSettings as $group => $items) : ?>
                        <div class="tab-pane fade <?= $isActive ? 'show active' : '' ?>" id="<?= $group ?>" role="tabpanel" aria-labelledby="<?= $group ?>-tab">
                            <h5 class="mb-3 text-primary text-capitalize"><?= $group ?> Configuration</h5>

                            <?php foreach ($items as $setting) : ?>
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
                                            <?php endif; ?>
                                        <?php elseif ($setting->type == 'boolean') : ?>
                                            <select class="form-select" name="<?= $setting->key_name ?>">
                                                <option value="1" <?= $setting->value == '1' ? 'selected' : '' ?>>Yes / True</option>
                                                <option value="0" <?= $setting->value == '0' ? 'selected' : '' ?>>No / False</option>
                                            </select>
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
                                                    <?php else : ?>
                                                        <i class="fas fa-link"></i>
                                                    <?php endif; ?>
                                                </span>
                                                <input type="url" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>" placeholder="https://example.com">
                                            </div>
                                            <small class="form-text text-muted">URL lengkap dengan https://</small>
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
                                                    <?php else : ?>
                                                        Supported formats: JPG, PNG, GIF, WebP. Max size: 5MB.
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        <?php else : ?>
                                            <input type="text" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>" placeholder="<?= $setting->description ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php $isActive = false; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
<?php else : ?>
    <div class="alert alert-info text-center">
        No settings found. Please click "Seed Default Settings" to initialize the configuration.
    </div>
<?php endif; ?>
<?= $this->endSection() ?>