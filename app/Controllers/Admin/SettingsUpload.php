<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class SettingsUpload extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $settings = $this->settingModel->findAll();

        $groupedSettings = [];
        foreach ($settings as $s) {
            $groupedSettings[$s->group_name][] = $s;
        }

        $data = [
            'title' => 'Settings Upload',
            'groupedSettings' => $groupedSettings
        ];

        return view('admin/settings_upload', $data);
    }

    public function update()
    {
        $errors = [];
        $successes = [];
        $warnings = [];

        // Handle text fields first
        $postData = $this->request->getPost();
        unset($postData[csrf_token()]);

        foreach ($postData as $key => $value) {
            $setting = $this->settingModel->where('key_name', $key)->first();
            if ($setting && $setting->type !== 'image') {
                $this->settingModel->update($setting->id, ['value' => $value]);
            }
        }

        // HANDLE IMAGE UPLOADS - COMPREHENSIVE VALIDATION VERSION
        $imageKeys = ['site_logo', 'seo_image', 'hero_bg_image'];

        foreach ($imageKeys as $key) {
            $file = $this->request->getFile($key);

            // Check if file was uploaded
            if ($file === null) {
                // No file uploaded, skip
                continue;
            }

            // DEBUG: Log file info
            log_message('info', "Upload attempt for {$key}: " . json_encode([
                'name' => $file->getName(),
                'clientName' => $file->getClientName(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'isValid' => $file->isValid(),
                'hasMoved' => $file->hasMoved()
            ]));

            // Check if file is valid
            if (!$file->isValid()) {
                $error_code = $file->getError();
                $error_message = $file->getErrorString();

                // Specific error handling
                switch ($error_code) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errors[] = "{$key}: File terlalu besar. Maksimal " . ini_get('upload_max_filesize') . " (PHP.ini limit)";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $errors[] = "{$key}: File melebihi batas form";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errors[] = "{$key}: File hanya ter-upload sebagian. Coba lagi";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        // Check if this is a real "no file" or configuration issue
                        if ($file->getSize() === 0 && $file->getClientName() === '') {
                            // Real no file selected - skip silently
                            continue 2;
                        } else {
                            // This is a configuration issue
                            $errors[] = "{$key}: Upload konfigurasi bermasalah. Periksa: file_uploads=On, upload_tmp_dir, dan restart XAMPP";
                        }
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errors[] = "{$key}: Temporary directory tidak ada. Periksa upload_tmp_dir di php.ini";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errors[] = "{$key}: Gagal menulis file ke disk. Periksa permissions temp directory";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errors[] = "{$key}: Upload dihentikan oleh PHP extension";
                        break;
                    default:
                        $errors[] = "{$key}: Upload gagal - {$error_message} (Error Code: {$error_code})";
                }
                continue;
            }

            // Check file size (max 5MB)
            $max_size = 5 * 1024 * 1024; // 5MB
            if ($file->getSize() > $max_size) {
                $errors[] = "{$key}: File terlalu besar. Maksimal 5MB (File Anda: " . round($file->getSize() / 1024 / 1024, 2) . "MB)";
                continue;
            }

            // Check file size minimum (avoid empty files)
            if ($file->getSize() == 0) {
                $errors[] = "{$key}: File kosong. Pilih file yang valid";
                continue;
            }

            // Check file type/MIME
            $allowed_types = [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp'
            ];

            $mime_type = $file->getMimeType();
            if (!in_array($mime_type, $allowed_types)) {
                $errors[] = "{$key}: Tipe file tidak diizinkan. Hanya: JPG, PNG, GIF, WebP (File Anda: {$mime_type})";
                continue;
            }

            // Check file extension
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower($file->getExtension());
            if (!in_array($extension, $allowed_extensions)) {
                $errors[] = "{$key}: Ekstensi file tidak diizinkan. Hanya: jpg, jpeg, png, gif, webp (File Anda: {$extension})";
                continue;
            }

            // Validate image dimensions (optional)
            try {
                $image_info = getimagesize($file->getTempName());
                if ($image_info === false) {
                    $errors[] = "{$key}: File tidak valid atau bukan image yang benar";
                    continue;
                }

                // Check image dimensions (max 4000x4000)
                $max_width = 4000;
                $max_height = 4000;
                if ($image_info[0] > $max_width || $image_info[1] > $max_height) {
                    $warnings[] = "{$key}: Image terlalu besar ({$image_info[0]}x{$image_info[1]}). Maksimal {$max_width}x{$max_height}";
                }

                // Check minimum dimensions (50x50)
                $min_width = 50;
                $min_height = 50;
                if ($image_info[0] < $min_width || $image_info[1] < $min_height) {
                    $errors[] = "{$key}: Image terlalu kecil. Minimum {$min_width}x{$min_height} pixel";
                    continue;
                }

            } catch (Exception $e) {
                $errors[] = "{$key}: Gagal memvalidasi image - " . $e->getMessage();
                continue;
            }

            // Create upload directory if not exists
            $uploadPath = FCPATH . 'uploads/settings';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    $errors[] = "{$key}: Gagal membuat directory upload";
                    continue;
                }
            }

            // Check if directory is writable
            if (!is_writable($uploadPath)) {
                $errors[] = "{$key}: Directory upload tidak writable";
                continue;
            }

            // Generate safe filename
            $original_name = $file->getClientName();
            $original_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $original_name);
            $newName = $key . '_' . time() . '_' . substr(md5($original_name), 0, 8) . '.' . $extension;

            // Move file with error handling
            try {
                if (!$file->move($uploadPath, $newName)) {
                    $errors[] = "{$key}: Gagal memindahkan file ke directory upload";
                    continue;
                }
            } catch (Exception $e) {
                $errors[] = "{$key}: Exception saat memindahkan file - " . $e->getMessage();
                continue;
            }

            // Verify file was moved successfully
            if (!file_exists($uploadPath . '/' . $newName)) {
                $errors[] = "{$key}: File tidak ditemukan setelah dipindahkan";
                continue;
            }

            // Update database with transaction-like behavior
            try {
                $setting = $this->settingModel->where('key_name', $key)->first();
                if (!$setting) {
                    $errors[] = "{$key}: Setting tidak ditemukan di database";
                    // Clean up uploaded file
                    if (file_exists($uploadPath . '/' . $newName)) {
                        unlink($uploadPath . '/' . $newName);
                    }
                    continue;
                }

                // Delete old file if exists
                if (!empty($setting->value)) {
                    $old_file_path = FCPATH . $setting->value;
                    if (file_exists($old_file_path)) {
                        if (!unlink($old_file_path)) {
                            $warnings[] = "{$key}: Gagal menghapus file lama: {$setting->value}";
                        }
                    }
                }

                // Update database
                $update_data = ['value' => 'uploads/settings/' . $newName];
                if (!$this->settingModel->update($setting->id, $update_data)) {
                    $errors[] = "{$key}: Gagal update database";
                    // Clean up uploaded file
                    if (file_exists($uploadPath . '/' . $newName)) {
                        unlink($uploadPath . '/' . $newName);
                    }
                    continue;
                }

                // Success!
                $successes[] = "{$key}: Berhasil upload ({$file->getClientName()}, " . round($file->getSize() / 1024, 1) . "KB)";

            } catch (Exception $e) {
                $errors[] = "{$key}: Database error - " . $e->getMessage();
                // Clean up uploaded file
                if (file_exists($uploadPath . '/' . $newName)) {
                    unlink($uploadPath . '/' . $newName);
                }
                continue;
            }
        }

        // Log results for debugging
        log_message('info', "Upload Results: " . json_encode([
            'successes' => $successes,
            'errors' => $errors,
            'warnings' => $warnings
        ]));

        // Prepare response message
        $message_parts = [];

        if (!empty($successes)) {
            $message_parts[] = "✅ " . count($successes) . " file berhasil diupload";
        }

        if (!empty($warnings)) {
            $message_parts[] = "⚠️ " . count($warnings) . " warning: " . implode('; ', $warnings);
        }

        if (!empty($errors)) {
            $message_parts[] = "❌ " . count($errors) . " error: " . implode('; ', $errors);
        }

        $message = implode(' | ', $message_parts);

        // Determine message type and redirect
        if (!empty($errors)) {
            if (!empty($successes)) {
                return redirect()->to('/dashboard/settings-upload')
                    ->with('warning', $message)
                    ->with('successes', $successes)
                    ->with('errors', $errors)
                    ->with('warnings', $warnings);
            } else {
                return redirect()->to('/dashboard/settings-upload')
                    ->with('error', $message)
                    ->with('errors', $errors);
            }
        } elseif (!empty($warnings)) {
            return redirect()->to('/dashboard/settings-upload')
                ->with('warning', $message)
                ->with('successes', $successes)
                ->with('warnings', $warnings);
        } else {
            $success_message = !empty($successes) ?
                "Berhasil upload " . count($successes) . " file: " . implode(', ', array_map(function($s) {
                    $parts = explode(':', $s, 2);
                    return trim($parts[0]);
                }, $successes)) :
                'Settings updated successfully';

            return redirect()->to('/dashboard/settings-upload')
                ->with('message', $success_message)
                ->with('successes', $successes);
        }
    }

    public function deleteImage($key)
    {
        $setting = $this->settingModel->where('key_name', $key)->first();

        if ($setting && !empty($setting->value)) {
            if (file_exists(FCPATH . $setting->value)) {
                unlink(FCPATH . $setting->value);
            }

            $this->settingModel->update($setting->id, ['value' => '']);
        }

        return redirect()->to('/dashboard/settings-upload')->with('message', 'Image deleted!');
    }
}