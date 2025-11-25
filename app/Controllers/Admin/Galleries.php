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
        $data = [
            'title' => 'Galleries',
            'galleries' => $this->galleryModel
                ->select('galleries.*, extracurriculars.name as ekskul_name')
                ->join('extracurriculars', 'extracurriculars.id = galleries.extracurricular_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->findAll()
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
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);
        
        if ($this->galleryModel->where('slug', $slug)->first()) {
            $slug = $slug . '-' . time();
        }

        $this->galleryModel->save([
            'title' => $title,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'extracurricular_id' => $this->request->getPost('extracurricular_id') ?: null,
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/galleries')->with('message', 'Gallery created successfully.');
    }

    public function edit($id)
    {
        $gallery = $this->galleryModel->find($id);
        if (! $gallery) {
            return redirect()->to('/dashboard/galleries')->with('error', 'Gallery not found.');
        }

        $data = [
            'title' => 'Edit Gallery',
            'gallery' => $gallery,
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
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->galleryModel->update($id, [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'extracurricular_id' => $this->request->getPost('extracurricular_id') ?: null,
            'status' => $this->request->getPost('status'),
        ]);

        return redirect()->to('/dashboard/galleries')->with('message', 'Gallery updated successfully.');
    }

    public function delete($id)
    {
        $this->galleryModel->delete($id);
        return redirect()->to('/dashboard/galleries')->with('message', 'Gallery deleted successfully.');
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

        if($media) {
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
