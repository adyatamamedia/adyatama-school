<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommentModel;

class Comments extends BaseController
{
    protected $commentModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Comments Moderation',
            'comments' => $this->commentModel
                ->select('comments.*, posts.title as post_title')
                ->join('posts', 'posts.id = comments.post_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->findAll()
        ];

        return view('admin/comments/index', $data);
    }

    public function approve($id)
    {
        $this->commentModel->update($id, ['is_approved' => 1, 'is_spam' => 0]);
        log_activity('approve_comment', 'comment', $id);
        return redirect()->back()->with('message', 'Comment approved.');
    }

    public function spam($id)
    {
        $this->commentModel->update($id, ['is_approved' => 0, 'is_spam' => 1]);
        log_activity('spam_comment', 'comment', $id);
        return redirect()->back()->with('message', 'Comment marked as spam.');
    }

    public function delete($id)
    {
        $this->commentModel->delete($id);
        log_activity('delete_comment', 'comment', $id);
        return redirect()->back()->with('message', 'Comment deleted.');
    }
}
