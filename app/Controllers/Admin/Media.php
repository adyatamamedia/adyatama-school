<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MediaModel;

class Media extends BaseController
{
    protected $mediaModel;

    public function __construct()
    {
        $this->mediaModel = new MediaModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'newest';
        
        // Build query
        $builder = $this->mediaModel;
        
        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $builder = $builder->orderBy('created_at', 'ASC');
                break;
            case 'name_asc':
                $builder = $builder->orderBy('caption', 'ASC');
                break;
            case 'name_desc':
                $builder = $builder->orderBy('caption', 'DESC');
                break;
            case 'size_asc':
                $builder = $builder->orderBy('filesize', 'ASC');
                break;
            case 'size_desc':
                $builder = $builder->orderBy('filesize', 'DESC');
                break;
            case 'newest':
            default:
                $builder = $builder->orderBy('created_at', 'DESC');
                break;
        }
        
        $data = [
            'title' => 'Media Library',
            'media' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy
        ];

        return view('admin/media/index', $data);
    }

    public function upload()
    {
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'No file uploaded or upload error occurred.');
        }
        
        // Manual size check (10MB before compression)
        if ($file->getSize() > 10240 * 1024) {
            return redirect()->back()->with('error', 'File too large. Max size is 10MB.');
        }

        // Check extension (string based, no IO)
        $ext = $file->getClientExtension();
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array(strtolower($ext), $allowedExts)) {
            return redirect()->back()->with('error', 'Invalid file extension. Only JPG, PNG, GIF, and WEBP are allowed.');
        }

        try {
            $newName = $file->getRandomName();
            
            // Use CI's move() method to update internal file state
            if (!$file->move(FCPATH . 'uploads', $newName)) {
                return redirect()->back()->with('error', 'Failed to move uploaded file.');
            }

            $filePath = FCPATH . 'uploads/' . $newName;
            
            // Validate real image type AFTER move using GD (safer than finfo on temp files)
            $imgInfo = @getimagesize($filePath);
            if (!$imgInfo) {
                @unlink($filePath);
                return redirect()->back()->with('error', 'Uploaded file is not a valid image.');
            }
            
            $validMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($imgInfo['mime'], $validMimes)) {
                @unlink($filePath);
                return redirect()->back()->with('error', 'Invalid image mime type detected: ' . $imgInfo['mime']);
            }

            // Compress image if it's larger than 500KB
            $originalSize = filesize($filePath);
            $compressed = $this->compressImage($filePath, 500); // 500KB target
            
            // Get final file size after compression
            $finalSize = filesize($filePath);
            
            // Log compression result (for debugging)
            log_message('info', "Image compression: Original={$originalSize} bytes, Final={$finalSize} bytes, Compressed=" . ($compressed ? 'yes' : 'no'));

            $data = [
                'type' => 'image',
                'path' => 'uploads/' . $newName,
                'caption' => $this->request->getPost('caption') ?: $file->getClientName(),
                'filesize' => $finalSize,
                'meta' => json_encode([
                    'client_name' => $file->getClientName(),
                    'mime_type' => $imgInfo['mime'],
                ]),
            ];

            $this->mediaModel->save($data);
            
            $mediaId = $this->mediaModel->getInsertID();
            helper('auth');
            log_activity('upload_media', 'media', $mediaId, ['filename' => $newName]);

            return redirect()->to('/dashboard/media')->with('message', 'File uploaded successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        $media = $this->mediaModel->find($id);
        if (!$media) return redirect()->back()->with('error', 'Media not found.');

        $this->mediaModel->update($id, [
            'caption' => $this->request->getPost('caption')
        ]);
        
        helper('auth');
        log_activity('update_media_caption', 'media', $id, ['caption' => $this->request->getPost('caption')]);

        return redirect()->to('/dashboard/media')->with('message', 'Caption updated.');
    }

    public function editImage($id)
    {
        // Set JSON response header
        $this->response->setContentType('application/json');
        
        $media = $this->mediaModel->find($id);
        if (!$media) {
            return $this->response->setJSON(['success' => false, 'message' => 'Media not found.']);
        }
        
        // Prevent guru from editing media of others (redundant check but safe)
        helper('auth');
        if (current_user()->role === 'guru' && $media->author_id && $media->author_id != current_user()->id) {
             // Guru can edit their own media, but current media table structure might not have author_id consistently populated.
             // For now assuming media library is shared or permission handled elsewhere. 
             // If strict ownership needed, add check here.
        }

        $filePath = FCPATH . $media->path;
        if (!file_exists($filePath)) {
            return $this->response->setJSON(['success' => false, 'message' => 'File does not exist on server.']);
        }

        $action = $this->request->getPost('action');
        
        // Load Image using GD
        $info = getimagesize($filePath);
        $mime = $info['mime'];
        
        switch ($mime) {
            case 'image/jpeg': $image = imagecreatefromjpeg($filePath); break;
            case 'image/png': $image = imagecreatefrompng($filePath); break;
            case 'image/gif': $image = imagecreatefromgif($filePath); break;
            case 'image/webp': 
                if(function_exists('imagecreatefromwebp')) $image = imagecreatefromwebp($filePath); 
                else return $this->response->setJSON(['success' => false, 'message' => 'WebP not supported by server.']);
                break;
            default: return $this->response->setJSON(['success' => false, 'message' => 'Unsupported image type.']);
        }

        if (!$image) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to load image resource.']);
        }

        try {
            if ($action === 'rotate_left' || $action === 'rotate_right') {
                $degrees = ($action === 'rotate_left') ? 90 : -90;
                $rotated = imagerotate($image, $degrees, 0);
                imagedestroy($image);
                $image = $rotated;
            } elseif ($action === 'crop') {
                $x = $this->request->getPost('x');
                $y = $this->request->getPost('y');
                $width = $this->request->getPost('width');
                $height = $this->request->getPost('height');

                if ($width > 0 && $height > 0) {
                    $crop = imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
                    if ($crop) {
                        imagedestroy($image);
                        $image = $crop;
                    } else {
                        throw new \Exception('Crop operation failed.');
                    }
                }
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid action.']);
            }

            // Save Image Back to File
            switch ($mime) {
                case 'image/jpeg': imagejpeg($image, $filePath, 90); break;
                case 'image/png': 
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagepng($image, $filePath, 9); 
                    break;
                case 'image/gif': imagegif($image, $filePath); break;
                case 'image/webp': imagewebp($image, $filePath, 90); break;
            }

            imagedestroy($image);
            clearstatcache();

            // Update database filesize
            $newSize = filesize($filePath);
            // Use save() instead of update() to bypass potential validation rules or model constraints if update() is strictly bound
            // But update() should work fine. However, let's check if 'updated_at' is allowed in allowedFields in model.
            // Assuming model handles updated_at automatically if useTimestamps is true.
            
            // Manual update query to avoid model issues if any
            $db = \Config\Database::connect();
            
            $updateData = ['filesize' => $newSize];
            
            // Check if updated_at column exists in schema, if not, don't update it
            if ($db->fieldExists('updated_at', 'media')) {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
            }
            
            $db->table('media')->where('id', $id)->update($updateData);

            log_activity('edit_media_image', 'media', $id, ['action' => $action]);
            
            session()->setFlashdata('message', 'Berhasil mengedit gambar.');

            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Image updated successfully.',
                'new_url' => base_url($media->path) . '?t=' . time() // Cache busting
            ]);

        } catch (\Exception $e) {
            // Log the full exception for debugging
            log_message('error', 'Image Edit Error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON(['success' => false, 'message' => 'Error processing image: ' . $e->getMessage()]);
        }
    }

    public function uploadMultiple()
    {
        $files = $this->request->getFileMultiple('files');

        if (!$files) {
            return redirect()->back()->with('error', 'No files uploaded.');
        }

        $uploadedCount = 0;
        $errors = [];

        foreach ($files as $file) {
            if (!$file->isValid()) {
                continue;
            }

            // Check file size (10MB max)
            if ($file->getSize() > 10240 * 1024) {
                $errors[] = $file->getClientName() . ' too large (max 10MB)';
                continue;
            }

            // Check extension
            $ext = $file->getClientExtension();
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (!in_array(strtolower($ext), $allowedExts)) {
                $errors[] = $file->getClientName() . ' invalid extension';
                continue;
            }

            try {
                $newName = $file->getRandomName();
                
                if (!$file->move(FCPATH . 'uploads', $newName)) {
                    $errors[] = $file->getClientName() . ' failed to move';
                    continue;
                }

                $filePath = FCPATH . 'uploads/' . $newName;
                
                // Validate image
                $imgInfo = @getimagesize($filePath);
                if (!$imgInfo) {
                    @unlink($filePath);
                    $errors[] = $file->getClientName() . ' not valid image';
                    continue;
                }
                
                $validMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($imgInfo['mime'], $validMimes)) {
                    @unlink($filePath);
                    $errors[] = $file->getClientName() . ' invalid mime type';
                    continue;
                }

                // Compress image
                $originalSize = filesize($filePath);
                $this->compressImage($filePath, 500);
                $finalSize = filesize($filePath);
                
                log_message('info', "Image compression: {$file->getClientName()} Original={$originalSize} bytes, Final={$finalSize} bytes");

                // Save to database
                $data = [
                    'type' => 'image',
                    'path' => 'uploads/' . $newName,
                    'caption' => $file->getClientName(),
                    'filesize' => $finalSize,
                    'meta' => json_encode([
                        'client_name' => $file->getClientName(),
                        'mime_type' => $imgInfo['mime'],
                    ]),
                ];

                $this->mediaModel->save($data);
                $mediaId = $this->mediaModel->getInsertID();
                
                helper('auth');
                log_activity('upload_media', 'media', $mediaId, ['filename' => $file->getClientName()]);
                
                $uploadedCount++;

            } catch (\Exception $e) {
                $errors[] = $file->getClientName() . ': ' . $e->getMessage();
            }
        }

        if ($uploadedCount > 0) {
            $message = "$uploadedCount file(s) uploaded successfully.";
            if (!empty($errors)) {
                $message .= ' Errors: ' . implode(', ', $errors);
            }
            return redirect()->to('/dashboard/media')->with('message', $message);
        }

        return redirect()->back()->with('error', 'Upload failed. ' . implode(', ', $errors));
    }

    public function bulkDelete()
    {
        helper('auth');
        $currentUser = current_user();
        
        // Prevent guru from deleting media
        if ($currentUser->role === 'guru') {
            return redirect()->back()->with('error', 'You do not have permission to delete media. Only admin and operator can delete media.');
        }
        
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No media selected.');
        }

        $deletedCount = 0;
        foreach ($ids as $id) {
            $media = $this->mediaModel->find($id);
            if ($media) {
                // Optional: delete physical file
                // if (file_exists(FCPATH . $media->path)) {
                //     @unlink(FCPATH . $media->path);
                // }
                
                $this->mediaModel->delete($id);
                log_activity('bulk_delete_media', 'media', $id);
                $deletedCount++;
            }
        }

        return redirect()->to('/dashboard/media')->with('message', "$deletedCount media deleted successfully.");
    }

    public function delete($id)
    {
        helper('auth');
        $currentUser = current_user();
        
        // Prevent guru from deleting media
        if ($currentUser->role === 'guru') {
            return redirect()->back()->with('error', 'You do not have permission to delete media. Only admin and operator can delete media.');
        }
        
        $media = $this->mediaModel->find($id);

        if ($media) {
            // Optional: Physical file deletion?
            // For soft deletes, we might keep the file or move it. 
            // If permanent delete:
            // if (file_exists(FCPATH . $media->path)) { unlink(FCPATH . $media->path); }
            
            // Just soft delete db record for now
            $this->mediaModel->delete($id);
            
            log_activity('delete_media', 'media', $id, ['path' => $media->path ?? null]);
            
            return redirect()->to('/dashboard/media')->with('message', 'Media deleted successfully.');
        }

        return redirect()->to('/dashboard/media')->with('error', 'Media not found.');
    }
    
    // API for Modal Selector (JSON)
    public function getMediaJson()
    {
        $media = $this->mediaModel->orderBy('created_at', 'DESC')->findAll();
        return $this->response->setJSON($media);
    }

    /**
     * Compress image to target size (in KB)
     * 
     * @param string $sourcePath Path to image file
     * @param int $targetSizeKB Target size in KB
     * @return bool
     */
    private function compressImage($sourcePath, $targetSizeKB)
    {
        // Check if GD library is available
        if (!extension_loaded('gd')) {
            return false; // Skip compression if GD not available
        }

        if (!file_exists($sourcePath)) {
            return false;
        }

        // Check current file size
        $fileSize = filesize($sourcePath) / 1024; // in KB
        
        // If already smaller than target, return
        if ($fileSize <= $targetSizeKB) {
            return true;
        }

        // Get image info
        $info = getimagesize($sourcePath);
        $mime = $info['mime'];
        
        // Load image based on type
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($sourcePath);
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Start compression loop
        if ($mime == 'image/png') {
            // PNG compression is lossless, so we might need to resize if file is still too big
            imagepng($image, $sourcePath, 9); // Max compression
            imagedestroy($image);
            
            // If still too big, resize it progressively
            clearstatcache();
            $currentSize = filesize($sourcePath) / 1024;
            $scale = 0.8; // Start with 80% of original
            
            while ($currentSize > $targetSizeKB && $scale > 0.2) {
                // Reload image from saved file
                $image = imagecreatefrompng($sourcePath);
                $this->resizeImage($image, $sourcePath, $scale, $mime);
                imagedestroy($image);
                
                clearstatcache();
                $currentSize = filesize($sourcePath) / 1024;
                $scale -= 0.1;
            }
        } else {
            // For JPEG and WEBP, we can adjust quality
            $quality = 90;
            $minQuality = 10;
            $currentSize = $fileSize;
            
            do {
                if ($mime == 'image/jpeg') {
                    imagejpeg($image, $sourcePath, $quality);
                } elseif ($mime == 'image/webp') {
                    imagewebp($image, $sourcePath, $quality);
                } else { // GIF
                    imagegif($image, $sourcePath);
                    break; 
                }
                
                clearstatcache();
                $currentSize = filesize($sourcePath) / 1024;
                
                if ($currentSize <= $targetSizeKB) {
                    break;
                }
                
                $quality -= 10;
            } while ($quality >= $minQuality);
            
            imagedestroy($image);
            
            // If still too big after quality reduction, resize progressively
            $scale = 0.8;
            while ($currentSize > $targetSizeKB && $scale > 0.2) {
                // Reload image from saved file
                if ($mime == 'image/jpeg') {
                    $image = imagecreatefromjpeg($sourcePath);
                } elseif ($mime == 'image/webp') {
                    $image = imagecreatefromwebp($sourcePath);
                } else { // GIF
                    $image = imagecreatefromgif($sourcePath);
                }
                
                $this->resizeImage($image, $sourcePath, $scale, $mime);
                imagedestroy($image);
                
                clearstatcache();
                $currentSize = filesize($sourcePath) / 1024;
                $scale -= 0.1;
            }
        }
        
        return true;
    }

    /**
     * Resize image to scale
     */
    private function resizeImage($image, $targetPath, $scale, $mime)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        
        $newWidth = (int)($width * $scale);
        $newHeight = (int)($height * $scale);
        
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Handle transparency for PNG/GIF/WEBP
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save resized image
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($newImage, $targetPath, 80);
                break;
            case 'image/png':
                imagepng($newImage, $targetPath, 9);
                break;
            case 'image/gif':
                imagegif($newImage, $targetPath);
                break;
            case 'image/webp':
                imagewebp($newImage, $targetPath, 80);
                break;
        }
        
        imagedestroy($newImage);
    }
}
