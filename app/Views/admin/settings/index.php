<?= $this->extend('layout/admin_base') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-end align-items-center mb-3">
    <?php if(empty($groupedSettings)): ?>
        <a href="<?= base_url('dashboard/settings/seed') ?>" class="btn btn-warning shadow-sm">
            <i class="fas fa-seedling"></i> Seed Default Settings
        </a>
    <?php else: ?>
        <button type="submit" form="settingsForm" class="btn btn-primary shadow-sm">
            <i class="fas fa-save"></i> Save Changes
        </button>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php if (!empty($groupedSettings)) : ?>
    <form action="<?= base_url('dashboard/settings/update') ?>" method="post" id="settingsForm">
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
                                            <textarea class="form-control" name="<?= $setting->key_name ?>" rows="3"><?= esc($setting->value) ?></textarea>
                                        <?php elseif ($setting->type == 'boolean') : ?>
                                            <select class="form-select" name="<?= $setting->key_name ?>">
                                                <option value="1" <?= $setting->value == '1' ? 'selected' : '' ?>>Yes / True</option>
                                                <option value="0" <?= $setting->value == '0' ? 'selected' : '' ?>>No / False</option>
                                            </select>
                                        <?php else : ?>
                                            <input type="text" class="form-control" name="<?= $setting->key_name ?>" value="<?= esc($setting->value) ?>">
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
