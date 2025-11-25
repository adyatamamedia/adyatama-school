<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PostModel;

class PublishScheduled extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Adyatama';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'publish:scheduled';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Publishes scheduled posts that have passed their publish date.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'publish:scheduled';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $postModel = new PostModel();
        $db = \Config\Database::connect();

        // Find posts that are 'draft' BUT have 'published_at' <= NOW()
        // Logic: If a post was scheduled, its status might be 'draft' initially
        // but we rely on a specific flow. Usually, scheduled posts are set to 'draft' or a 'scheduled' status.
        // Since our enum is only 'draft' or 'published', we assume 'draft' + future date = scheduled.
        
        $now = date('Y-m-d H:i:s');

        $builder = $postModel->builder();
        $query = $builder->where('status', 'draft')
                         ->where('published_at IS NOT NULL')
                         ->where('published_at <=', $now)
                         ->get();

        $posts = $query->getResult();
        $count = 0;

        foreach ($posts as $post) {
            $postModel->update($post->id, ['status' => 'published']);
            
            // Optional: Log activity system-wide?
            // log_activity('auto_publish', 'post', $post->id); // Helper might not be available in CLI without loading
            
            CLI::write("Published Post ID: {$post->id} - {$post->title}", 'green');
            $count++;
        }

        if ($count > 0) {
            CLI::write("Successfully published {$count} scheduled posts.", 'green');
        } else {
            CLI::write("No scheduled posts found to publish.", 'yellow');
        }
    }
}
