<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <label for="meta_title" class="form-label">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="seo[meta_title]" value="<?= isset($seo) ? esc($seo->meta_title) : '' ?>" placeholder="Custom title for search engines">
            <div class="form-text">If empty, the content title will be used.</div>
        </div>

        <div class="mb-3">
            <label for="meta_description" class="form-label">Meta Description</label>
            <textarea class="form-control" id="meta_description" name="seo[meta_description]" rows="3" placeholder="Brief summary for search results"><?= isset($seo) ? esc($seo->meta_description) : '' ?></textarea>
            <div class="form-text">Recommended length: 150-160 characters.</div>
        </div>

        <div class="mb-3">
            <label for="meta_keywords" class="form-label">Meta Keywords</label>
            <input type="text" class="form-control" id="meta_keywords" name="seo[meta_keywords]" value="<?= isset($seo) ? esc($seo->meta_keywords) : '' ?>" placeholder="keyword1, keyword2, keyword3">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="canonical" class="form-label">Canonical URL</label>
                    <input type="text" class="form-control" id="canonical" name="seo[canonical]" value="<?= isset($seo) ? esc($seo->canonical) : '' ?>" placeholder="https://example.com/original-content">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="robots" class="form-label">Robots Meta</label>
                    <select class="form-select" id="robots" name="seo[robots]">
                        <option value="index,follow" <?= (isset($seo) && $seo->robots == 'index,follow') ? 'selected' : '' ?>>Index, Follow (Default)</option>
                        <option value="noindex,follow" <?= (isset($seo) && $seo->robots == 'noindex,follow') ? 'selected' : '' ?>>No Index, Follow</option>
                        <option value="index,nofollow" <?= (isset($seo) && $seo->robots == 'index,nofollow') ? 'selected' : '' ?>>Index, No Follow</option>
                        <option value="noindex,nofollow" <?= (isset($seo) && $seo->robots == 'noindex,nofollow') ? 'selected' : '' ?>>No Index, No Follow</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
