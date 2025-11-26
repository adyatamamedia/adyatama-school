<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class SummernoteUpload extends BaseController
{
    public function upload()
    {
        // Use CodeIgniter's request object to handle file
        // This ensures CI's internal state is synchronized when we move the file
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
             return $this->response->setJSON([
                'success' => false,
                'message' => 'No file uploaded or upload error occurred.'
            ]);
        }
        
        // Manual size check (10MB)
        if ($file->getSize() > 10240 * 1024) {
             return $this->response->setJSON([
                'success' => false,
                'message' => 'File too large. Max size is 10MB.'
            ]);
        }

        // Create upload directory if it doesn't exist
        $uploadPath = FCPATH . 'uploads/summernote';
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create upload directory'
                ]);
            }
        }

        // Generate unique filename
        $newName = $file->getRandomName();

        try {
            // Check using file extension (string based, no IO)
            $ext = $file->getClientExtension();
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (!in_array(strtolower($ext), $allowedExts)) {
                 return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid file extension. Only JPG, PNG, GIF, and WEBP are allowed.'
                ]);
            }
            
            // Use CI's move() method. 
            // This is critical because it updates the file state in CI, 
            // preventing Debug Toolbar from trying to read the deleted temp file.
            if (!$file->move($uploadPath, $newName)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to move uploaded file'
                ]);
            }

            // Path to the saved file
            $filePath = $uploadPath . '/' . $newName;
            
            // Validate real image type AFTER move using GD (safer than finfo on temp files)
            $imgInfo = @getimagesize($filePath);
            if (!$imgInfo) {
                // Not a valid image, delete it
                @unlink($filePath);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Uploaded file is not a valid image'
                ]);
            }
            
            $validMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($imgInfo['mime'], $validMimes)) {
                 @unlink($filePath);
                 return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid image mime type detected: ' . $imgInfo['mime']
                ]);
            }

            // Compress image if it's larger than 500KB
            if (!$imgInfo) {
                // Not a valid image, delete it
                @unlink($filePath);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Uploaded file is not a valid image'
                ]);
            }
            
            $validMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($imgInfo['mime'], $validMimes)) {
                 @unlink($filePath);
                 return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid image mime type detected: ' . $imgInfo['mime']
                ]);
            }

            // Compress image if it's larger than 500KB
            $this->compressImage($filePath, 500); // 500KB target

            // Build file URL
            $fileUrl = base_url('uploads/summernote/' . $newName);

            // Return success response with file URL
            return $this->response->setJSON([
                'success' => true,
                'message' => 'File uploaded successfully',
                'url' => $fileUrl,
                'filename' => $newName
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
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
                // Check if we need to convert PNG to JPG for better compression (optional)
                // For transparent PNGs, this might lose transparency if converted to JPG
                // Here we keep PNG but try to compress it
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($sourcePath);
                } else {
                    return false; // WEBP not supported by GD
                }
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Start compression loop
        // For JPEG/WEBP quality ranges 0-100. For PNG it's compression level 0-9.
        
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