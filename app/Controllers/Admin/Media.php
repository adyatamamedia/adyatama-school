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
        $data = [
            'title' => 'Media Library',
            'media' => $this->mediaModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/media/index', $data);
    }

    public function upload()
    {
        $validationRule = [
            'file' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[file]',
                    'is_image[file]',
                    'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[file,2048]', // 2MB max
                    // 'max_dims[file,1024,768]', // Optional
                ],
            ],
        ];

        if (! $this->validate($validationRule)) {
             return redirect()->back()->with('error', $this->validator->getErrors()['file']);
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            // Move to public/uploads
            $file->move(FCPATH . 'uploads', $newName);

            $data = [
                'type' => 'image', // Default to image for now
                'path' => 'uploads/' . $newName,
                'caption' => $this->request->getPost('caption') ?: $file->getClientName(),
                'filesize' => $file->getSize(),
                'meta' => json_encode([
                    'client_name' => $file->getClientName(),
                    'mime_type' => $file->getMimeType(),
                ]),
            ];

            $this->mediaModel->save($data);

            return redirect()->to('/dashboard/media')->with('message', 'File uploaded successfully.');
        }

        return redirect()->back()->with('error', 'The file has already been moved.');
    }

    public function update($id)
    {
        $media = $this->mediaModel->find($id);
        if (!$media) return redirect()->back()->with('error', 'Media not found.');

        $this->mediaModel->update($id, [
            'caption' => $this->request->getPost('caption')
        ]);

        return redirect()->to('/dashboard/media')->with('message', 'Caption updated.');
    }

    public function delete($id)
    {
        $media = $this->mediaModel->find($id);

        if ($media) {
            // Optional: Physical file deletion?
            // For soft deletes, we might keep the file or move it. 
            // If permanent delete:
            // if (file_exists(FCPATH . $media->path)) { unlink(FCPATH . $media->path); }
            
            // Just soft delete db record for now
            $this->mediaModel->delete($id);
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
}
