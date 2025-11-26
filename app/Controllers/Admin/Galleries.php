<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GalleryModel;
use App\Models\GalleryItemModel;
use App\Models\ExtracurricularModel;
use App\Models\MediaModel;

class Galleries extends BaseController
{
    protected $galleryModel;
    protected $galleryItemModel;
    protected $extracurricularModel;

    public function __construct()
    {
        $this->galleryModel = new GalleryModel();
        $this->galleryItemModel = new GalleryItemModel();
        $this->extracurricularModel = new ExtracurricularModel();
    }

    public function index()
    {
        // Get query parameters
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'newest';
        $search = $this->request->getGet('search') ?? '';

        // Build query
        $builder = $this->galleryModel
            ->select('galleries.*, extracurriculars.name as ekskul_name')
            ->join('extracurriculars', 'extracurriculars.id = galleries.extracurricular_id', 'left');

        // Apply search
        if ($search) {
            $builder->groupStart()
                ->like('galleries.title', $search)
                ->orLike('galleries.description', $search)
                ->orLike('extracurriculars.name', $search)
                ->groupEnd();
        }

        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $builder->orderBy('galleries.created_at', 'ASC');
                break;
            case 'title_asc':
                $builder->orderBy('galleries.title', 'ASC');
                break;
            case 'title_desc':
                $builder->orderBy('galleries.title', 'DESC');
                break;
            case 'ekskul':
                $builder->orderBy('extracurriculars.name', 'ASC');
                break;
            case 'newest':
            default:
                $builder->orderBy('galleries.created_at', 'DESC');
                break;
        }

        $data = [
            'title' => 'Galleries',
            'galleries' => $builder->paginate($perPage, 'default'),
            'pager' => $builder->pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'sortOptions' => [
                'newest' => 'Terbaru',
                'oldest' => 'Terlama',
                'title_asc' => 'Judul A-Z',
                'title_desc' => 'Judul Z-A',
                'ekskul' => 'Ekstrakurikuler'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus galeri terpilih?']
            ],
            'createButton' => [
                'url' => base_url('dashboard/galleries/new'),
                'label' => 'Buat Galeri'
            ]
        ];

        return view('admin/galleries/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create New Gallery',
            'ekskuls' => $this->extracurricularModel->findAll()
        ];
        return view('admin/galleries/create', $data);
    }

    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'status' => 'required|in_list[draft,published]',
            'featured_image' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);

        if ($this->galleryModel->where('slug', $slug)->first()) {
            $slug = $slug . '-' . time();
        }

        // Receive featured_image (form field name), save to featured_img (database column)
        $featuredImage = $this->request->getPost('featured_image');

        // Debug log
        log_message('debug', 'Featured Image received: ' . ($featuredImage ?: 'EMPTY'));
        log_message('debug', 'All POST data: ' . json_encode($this->request->getPost()));

        $galleryId = $this->galleryModel->insert([
            'title' => $title,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'extracurricular_id' => $this->request->getPost('extracurricular_id') ?: null,
            'featured_img' => $featuredImage ?: null, // Save to featured_img column
            'status' => $this->request->getPost('status'),
        ]);

        // Handle gallery items (multiple images)
        $galleryItems = $this->request->getPost('gallery_items');
        if ($galleryItems && is_array($galleryItems)) {
            $galleryItemModel = new \App\Models\GalleryItemModel();
            $order = 1;
            foreach ($galleryItems as $mediaId) {
                // Get media info
                $mediaModel = new \App\Models\MediaModel();
                $media = $mediaModel->find($mediaId);

                if ($media) {
                    $galleryItemModel->save([
                        'gallery_id' => $galleryId,
                        'media_id' => $mediaId,
                        'path' => $media->path,
                        'type' => $media->type ?? 'image',
                        'caption' => $media->caption,
                        'order_num' => $order++,
                    ]);
                }
            }
        }

        return redirect()->to('/dashboard/galleries')->with('message', 'Gallery created successfully with ' . count($galleryItems ?? []) . ' items.');
    }

    public function edit($id)
    {
        $gallery = $this->galleryModel->find($id);
        if (! $gallery) {
            return redirect()->to('/dashboard/galleries')->with('error', 'Gallery not found.');
        }

        // Load existing gallery items
        $galleryItemModel = new \App\Models\GalleryItemModel();
        $items = $galleryItemModel->where('gallery_id', $id)->orderBy('order_num', 'ASC')->findAll();

        $data = [
            'title' => 'Edit Gallery',
            'gallery' => $gallery,
            'items' => $items,
            'ekskuls' => $this->extracurricularModel->findAll()
        ];

        return view('admin/galleries/edit', $data);
    }

    public function update($id)
    {
        $gallery = $this->galleryModel->find($id);
        if (! $gallery) {
            return redirect()->to('/dashboard/galleries')->with('error', 'Gallery not found.');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'status' => 'required|in_list[draft,published]',
            'featured_image' => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Receive featured_image (form field name), save to featured_img (database column)
        $featuredImage = $this->request->getPost('featured_image');

        $this->galleryModel->update($id, [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'extracurricular_id' => $this->request->getPost('extracurricular_id') ?: null,
            'featured_img' => $featuredImage ?: null,
            'status' => $this->request->getPost('status'),
        ]);

        // Handle gallery items (delete old, insert new)
        $galleryItems = $this->request->getPost('gallery_items');
        $galleryItemModel = new \App\Models\GalleryItemModel();

        // Delete existing items for this gallery
        $galleryItemModel->where('gallery_id', $id)->delete();

        // Insert new items
        if ($galleryItems && is_array($galleryItems)) {
            $mediaModel = new \App\Models\MediaModel();
            $order = 1;
            foreach ($galleryItems as $mediaId) {
                $media = $mediaModel->find($mediaId);

                if ($media) {
                    $galleryItemModel->save([
                        'gallery_id' => $id,
                        'media_id' => $mediaId,
                        'path' => $media->path,
                        'type' => $media->type ?? 'image',
                        'caption' => $media->caption,
                        'order_num' => $order++,
                    ]);
                }
            }
        }

        return redirect()->to('/dashboard/galleries')->with('message', 'Gallery updated successfully with ' . count($galleryItems ?? []) . ' items.');
    }

    public function delete($id)
    {
        $this->galleryModel->delete($id);
        return redirect()->to('/dashboard/galleries')->with('message', 'Gallery deleted successfully.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No galleries selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            if ($this->galleryModel->delete($id)) {
                $count++;
            }
        }

        return redirect()->to('/dashboard/galleries')->with('message', "$count galler(ies) deleted successfully.");
    }

    // Manage Items (Photos/Videos in Gallery)
    public function items($id)
    {
        $gallery = $this->galleryModel->find($id);
        if (! $gallery) {
            return redirect()->to('/dashboard/galleries')->with('error', 'Gallery not found.');
        }

        $items = $this->galleryItemModel->where('gallery_id', $id)->orderBy('order_num', 'ASC')->findAll();

        $data = [
            'title' => 'Manage Gallery Items: ' . $gallery->title,
            'gallery' => $gallery,
            'items' => $items
        ];

        return view('admin/galleries/items', $data);
    }

    public function addItem($galleryId)
    {
        $mediaId = $this->request->getPost('media_id');

        $mediaModel = new MediaModel();
        $media = $mediaModel->find($mediaId);

        if ($media) {
            $this->galleryItemModel->save([
                'gallery_id' => $galleryId,
                'media_id' => $media->id,
                'type' => $media->type == 'video' ? 'video' : 'image', // Map types
                'path' => $media->path,
                'caption' => $media->caption,
                'order_num' => 0 // Default order
            ]);
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Media not found']);
    }

    public function deleteItem($itemId)
    {
        $this->galleryItemModel->delete($itemId);
        return redirect()->back()->with('message', 'Item removed.');
    }
}
