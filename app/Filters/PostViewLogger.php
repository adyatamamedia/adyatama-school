<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PostModel;

class PostViewLogger implements FilterInterface
{
    /**
     * Logs post views for analytics.
     * Expects route to be like /post/{slug} or /article/{slug}
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Only track GET requests
        if ($request->getMethod() !== 'GET') {
            return;
        }

        // Basic bot detection (simple check)
        $agent = $request->getUserAgent();
        if ($agent->isRobot()) {
            return;
        }

        // Extract slug from URI
        $uri = $request->getUri();
        $segments = $uri->getSegments();
        
        // Assumption: URL structure is /post/slug-of-the-post
        // Adjust based on actual frontend route later
        if (count($segments) >= 2 && ($segments[0] == 'post' || $segments[0] == 'article')) {
            $slug = $segments[1];
            
            $postModel = new PostModel();
            $post = $postModel->where('slug', $slug)->first();

            if ($post) {
                $db = \Config\Database::connect();
                $builder = $db->table('post_views');

                // Optional: Session based unique check to prevent spamming refresh
                // $sessionKey = 'viewed_post_' . $post->id;
                // if (!session()->has($sessionKey)) { ... }

                $data = [
                    'post_id' => $post->id,
                    'user_id' => session('user_id') ?? null,
                    'ip_address' => $request->getIPAddress(),
                    'user_agent' => $agent->getAgentString(),
                    'viewed_at' => date('Y-m-d H:i:s')
                ];

                $builder->insert($data);

                // Increment total view count on post table
                $postModel->where('id', $post->id)->increment('view_count');
                
                // session()->set($sessionKey, true);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
