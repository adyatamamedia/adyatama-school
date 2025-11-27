<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardNew extends BaseController
{
    public function index()
    {
        // Load models
        $postModel = new \App\Models\PostModel();
        $userModel = new \App\Models\UserModel();
        $activityModel = new \App\Models\ActivityLogModel();
        $pageModel = new \App\Models\PageModel();
        $galleryModel = new \App\Models\GalleryModel();
        $mediaModel = new \App\Models\MediaModel();
        $commentModel = new \App\Models\CommentModel();
        $settingModel = new \App\Models\SettingModel();

        // Get current statistics
        $data = [
            'title' => 'Dashboard Overview - Adyatama School CMS',
            'stats' => [
                // Posts statistics
                'posts' => $postModel->where('status', 'published')->countAll(),
                'published_posts' => $postModel->where('status', 'published')->countAll(),
                'draft_posts' => $postModel->where('status', 'draft')->countAll(),
                'posts_growth' => $this->calculateGrowth('posts', 30), // 30 days growth

                // Users statistics
                'users' => $userModel->where('status', 'active')->countAll(),
                'users_growth' => $this->calculateGrowth('users', 30),

                // Views statistics
                'views' => $this->getTotalViews(30), // Last 30 days
                'views_growth' => $this->calculateViewsGrowth(30),

                // Content statistics
                'total_pages' => $pageModel->countAll(),
                'total_galleries' => $galleryModel->countAll(),
                'total_media' => $mediaModel->countAll(),
                'pending_comments' => $commentModel->where('status', 'pending')->countAll(),

                // Visitor statistics (last 7 days)
                'visitor_labels' => $this->getLastSevenDays(),
                'visitor_data' => $this->getVisitorStats(7),
            ],
            'recent_activities' => $activityModel
                ->select('activity_logs.*, users.fullname as user_fullname')
                ->join('users', 'users.id = activity_logs.user_id', 'left')
                ->orderBy('activity_logs.created_at', 'DESC')
                ->limit(10)
                ->find()
        ];

        return view('admin/dashboard_new', $data);
    }

    /**
     * Calculate growth percentage for a given table
     */
    private function calculateGrowth($table, $days = 30)
    {
        $db = \Config\Database::connect();

        // Current period
        $currentStart = date('Y-m-d', strtotime("-$days days"));
        $currentEnd = date('Y-m-d');

        // Previous period (same duration)
        $previousStart = date('Y-m-d', strtotime("-" . ($days * 2) . " days"));
        $previousEnd = date('Y-m-d', strtotime("-$days days"));

        try {
            // Count current period records
            $currentQuery = $db->table($table)
                ->where('created_at >=', $currentStart)
                ->where('created_at <=', $currentEnd);

            // Add status condition for specific tables
            if ($table === 'posts') {
                $currentQuery->where('status', 'published');
            } elseif ($table === 'users') {
                $currentQuery->where('status', 'active');
            }

            $currentCount = $currentQuery->countAllResults();

            // Count previous period records
            $previousQuery = $db->table($table)
                ->where('created_at >=', $previousStart)
                ->where('created_at <=', $previousEnd);

            if ($table === 'posts') {
                $previousQuery->where('status', 'published');
            } elseif ($table === 'users') {
                $previousQuery->where('status', 'active');
            }

            $previousCount = $previousQuery->countAllResults();

            // Calculate growth percentage
            if ($previousCount == 0) {
                return $currentCount > 0 ? 100 : 0;
            }

            $growth = (($currentCount - $previousCount) / $previousCount) * 100;
            return round($growth, 1);

        } catch (\Exception $e) {
            log_message('error', 'Error calculating growth for ' . $table . ': ' . $e->getMessage());
            return 0; // Return 0 if there's an error
        }
    }

    /**
     * Get total views from post_views table
     */
    private function getTotalViews($days = 30)
    {
        $db = \Config\Database::connect();

        $startDate = date('Y-m-d', strtotime("-$days days"));

        try {
            $views = $db->table('post_views')
                ->where('view_date >=', $startDate)
                ->countAllResults();

            return $views ?? 0;
        } catch (\Exception $e) {
            log_message('error', 'Error getting total views: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate views growth percentage
     */
    private function calculateViewsGrowth($days = 30)
    {
        $db = \Config\Database::connect();

        // Current period
        $currentStart = date('Y-m-d', strtotime("-$days days"));
        $currentEnd = date('Y-m-d');

        // Previous period
        $previousStart = date('Y-m-d', strtotime("-" . ($days * 2) . " days"));
        $previousEnd = date('Y-m-d', strtotime("-$days days"));

        try {
            $currentViews = $db->table('post_views')
                ->where('view_date >=', $currentStart)
                ->where('view_date <=', $currentEnd)
                ->countAllResults();

            $previousViews = $db->table('post_views')
                ->where('view_date >=', $previousStart)
                ->where('view_date <=', $previousEnd)
                ->countAllResults();

            if ($previousViews == 0) {
                return $currentViews > 0 ? 100 : 0;
            }

            $growth = (($currentViews - $previousViews) / $previousViews) * 100;
            return round($growth, 1);

        } catch (\Exception $e) {
            log_message('error', 'Error calculating views growth: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get last seven days labels
     */
    private function getLastSevenDays()
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $days[] = date('D', strtotime("-$i days"));
        }
        return $days;
    }

    /**
     * Get visitor statistics for last N days
     */
    private function getVisitorStats($days = 7)
    {
        $db = \Config\Database::connect();
        $stats = [];

        try {
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));

                $count = $db->table('post_views')
                    ->where('view_date', $date)
                    ->countAllResults();

                $stats[] = $count ?? 0;
            }

            return $stats;

        } catch (\Exception $e) {
            log_message('error', 'Error getting visitor stats: ' . $e->getMessage());
            // Return mock data if there's an error
            return array_fill(0, $days, rand(50, 300));
        }
    }

    /**
     * Get mock data for development (if database doesn't have real data)
     */
    private function getMockStats()
    {
        return [
            'posts' => 45,
            'published_posts' => 38,
            'draft_posts' => 7,
            'posts_growth' => 12.5,
            'users' => 28,
            'users_growth' => 8.2,
            'views' => 12450,
            'views_growth' => 25.3,
            'total_pages' => 12,
            'total_galleries' => 15,
            'total_media' => 124,
            'pending_comments' => 3,
            'visitor_labels' => $this->getLastSevenDays(),
            'visitor_data' => [120, 150, 180, 200, 160, 240, 280]
        ];
    }

    /**
     * Alternative index method with mock data for development
     */
    public function indexDev()
    {
        $activityModel = new \App\Models\ActivityLogModel();

        $data = [
            'title' => 'Dashboard Overview - Adyatama School CMS',
            'stats' => $this->getMockStats(),
            'recent_activities' => $activityModel
                ->select('activity_logs.*, users.fullname as user_fullname')
                ->join('users', 'users.id = activity_logs.user_id', 'left')
                ->orderBy('activity_logs.created_at', 'DESC')
                ->limit(10)
                ->find()
        ];

        return view('admin/dashboard_new', $data);
    }
}