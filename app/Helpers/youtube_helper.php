<?php

if (!function_exists('extract_youtube_id')) {
    /**
     * Extract YouTube video ID from various URL formats
     * 
     * @param string $url YouTube URL
     * @return string|null Video ID or null if not found
     */
    function extract_youtube_id($url)
    {
        if (empty($url)) {
            return null;
        }

        // Pattern 1: youtube.com/watch?v=VIDEO_ID
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtube\.com\/\?v=)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 2: youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 3: youtube.com/embed/VIDEO_ID
        if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 4: youtube.com/v/VIDEO_ID
        if (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}

if (!function_exists('get_youtube_thumbnail_url')) {
    /**
     * Get YouTube thumbnail URL
     * 
     * @param string $videoId YouTube video ID
     * @param string $quality Quality: maxresdefault, sddefault, hqdefault, mqdefault, default
     * @return string Thumbnail URL
     */
    function get_youtube_thumbnail_url($videoId, $quality = 'maxresdefault')
    {
        return "https://img.youtube.com/vi/{$videoId}/{$quality}.jpg";
    }
}

if (!function_exists('download_youtube_thumbnail')) {
    /**
     * Download YouTube thumbnail and save to media library
     * 
     * @param string $videoUrl YouTube video URL
     * @param string $title Post title for filename
     * @return array|null ['media_id' => int, 'path' => string] or null if failed
     */
    function download_youtube_thumbnail($videoUrl, $title = 'video')
    {
        // Extract video ID
        $videoId = extract_youtube_id($videoUrl);
        if (!$videoId) {
            log_message('error', 'Failed to extract YouTube video ID from: ' . $videoUrl);
            return null;
        }

        // Try different quality levels
        $qualities = ['maxresdefault', 'sddefault', 'hqdefault'];
        $imageData = null;
        $usedQuality = null;

        foreach ($qualities as $quality) {
            $thumbnailUrl = get_youtube_thumbnail_url($videoId, $quality);
            
            // Download image
            $imageData = @file_get_contents($thumbnailUrl);
            
            // Check if download successful and image is valid
            if ($imageData !== false && strlen($imageData) > 1000) {
                $usedQuality = $quality;
                break;
            }
        }

        if (!$imageData) {
            log_message('error', 'Failed to download YouTube thumbnail for video ID: ' . $videoId);
            return null;
        }

        // Create upload directory if not exists
        $uploadDir = FCPATH . 'uploads/youtube-thumbnails';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate filename
        $filename = 'yt-' . $videoId . '-' . time() . '.jpg';
        $filepath = $uploadDir . '/' . $filename;
        $relativePath = 'uploads/youtube-thumbnails/' . $filename;

        // Save image
        if (!file_put_contents($filepath, $imageData)) {
            log_message('error', 'Failed to save YouTube thumbnail to: ' . $filepath);
            return null;
        }

        // Get image dimensions
        $imageInfo = getimagesize($filepath);
        $width = $imageInfo[0] ?? 0;
        $height = $imageInfo[1] ?? 0;
        $filesize = filesize($filepath);

        // Save to media table
        $mediaModel = new \App\Models\MediaModel();
        $mediaData = [
            'filename' => $filename,
            'path' => $relativePath,
            'mime_type' => 'image/jpeg',
            'file_size' => $filesize,
            'width' => $width,
            'height' => $height,
            'alt_text' => $title,
            'caption' => 'YouTube video thumbnail',
            'uploaded_by' => session()->get('user_id') ?? 1,
        ];

        $mediaId = $mediaModel->insert($mediaData);

        if (!$mediaId) {
            log_message('error', 'Failed to insert media record for YouTube thumbnail');
            // Delete file if database insert failed
            @unlink($filepath);
            return null;
        }

        log_message('info', "YouTube thumbnail saved: Video ID={$videoId}, Quality={$usedQuality}, Media ID={$mediaId}");

        return [
            'media_id' => $mediaId,
            'path' => $relativePath,
            'video_id' => $videoId,
            'quality' => $usedQuality
        ];
    }
}
