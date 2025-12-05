<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UpdateController extends BaseController
{
    protected $versionFile;
    
    public function __construct()
    {
        $this->versionFile = ROOTPATH . 'version.json';
        helper('auth');
    }
    
    /**
     * Display update page
     */
    public function index()
    {
        // Check if user is admin
        if (current_user()->role !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to access this page.');
        }
        
        $data = [
            'title' => 'System Update - Dashboard'
        ];
        
        return view('admin/update', $data);
    }
    
    /**
     * Check for updates
     */
    public function checkUpdates()
    {
        // Check if user is admin
        if (current_user()->role !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have permission to access this feature.'
            ]);
        }
        
        try {
            $versionInfo = $this->getVersionInfo();
            $latestUpdate = $this->checkForLatestUpdate($versionInfo['current_version']);
            
            // Update last check time
            $this->updateVersionFile([
                'current_version' => $versionInfo['current_version'],
                'last_check' => date('Y-m-d H:i:s')
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'current' => $versionInfo,
                'update_available' => $latestUpdate['available'],
                'update_info' => $latestUpdate['available'] ? $latestUpdate : null
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Update check failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to check for updates'
            ]);
        }
    }
    
    /**
     * Download update file
     */
    public function downloadUpdate($version = null)
    {
        // Check if user is admin
        if (current_user()->role !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have permission to access this feature.'
            ]);
        }
        
        // For demo purposes, simulate update download
        // In production, this would download actual update files
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Update download started',
            'download_url' => '#', // In production, this would be actual file URL
            'version' => $version
        ]);
    }
    
    /**
     * Get current version info
     */
    private function getVersionInfo()
    {
        if (file_exists($this->versionFile)) {
            $versionData = json_decode(file_get_contents($this->versionFile), true);
            
            return [
                'current_version' => $versionData['current_version'] ?? '1.0.0',
                'last_check' => $versionData['last_check'] ?? null,
                'update_url' => $versionData['update_url'] ?? null
            ];
        }
        
        return [
            'current_version' => '1.0.0',
            'last_check' => null,
            'update_url' => null
        ];
    }
    
    /**
     * Update version file
     */
    private function updateVersionFile($data)
    {
        $currentData = $this->getVersionInfo();
        $updatedData = array_merge($currentData, $data);
        
        file_put_contents($this->versionFile, json_encode($updatedData, JSON_PRETTY_PRINT));
    }
    
    /**
     * Check for latest update from GitHub Releases
     */
    private function checkForLatestUpdate($currentVersion)
    {
        $githubRepo = 'adyatamamedia/adyatama-school';
        $githubToken = env('GITHUB_TOKEN'); // Optional, but recommended for higher rate limits
        
        $client = \Config\Services::curlrequest();
        
        try {
            $headers = [
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Adyatama-School-CMS'
            ];
            
            // Add token if available (increases rate limit from 60 to 5000 requests/hour)
            if ($githubToken) {
                $headers['Authorization'] = "Bearer {$githubToken}";
            }
            
            $response = $client->get(
                "https://api.github.com/repos/{$githubRepo}/releases/latest",
                [
                    'headers' => $headers,
                    'timeout' => 10
                ]
            );
            
            if ($response->getStatusCode() === 200) {
                $release = json_decode($response->getBody(), true);
                $latestVersion = ltrim($release['tag_name'], 'v'); // Remove 'v' prefix if exists
                
                // Check if update is available
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
                            'description' => $release['name'] ?? 'New version available',
                            'release_date' => date('Y-m-d', strtotime($release['published_at'])),
                            'download_url' => $zipAsset['browser_download_url'],
                            'file_size' => $zipAsset['size'],
                            'checksum' => null, // GitHub doesn't provide SHA256 by default
                            'requirements' => 'PHP 8.0+, MySQL 5.7+, 50MB free space',
                            'changelog' => $release['body'] ?? 'No changelog provided'
                        ];
                    } else {
                        log_message('warning', 'No ZIP file found in GitHub release');
                    }
                }
            } else {
                log_message('error', 'GitHub API returned status: ' . $response->getStatusCode());
            }
            
            return ['available' => false];
            
        } catch (\Exception $e) {
            log_message('error', 'GitHub update check failed: ' . $e->getMessage());
            return ['available' => false];
        }
    }
}
