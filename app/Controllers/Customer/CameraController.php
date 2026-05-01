<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;

class CameraController extends BaseController
{
    // ============================================================
    // CAMERA PAGE
    // ============================================================
    
    /**
     * Display camera page
     * GET: /customer/camera
     */
    public function index()
    {
        $data = [
            'title' => 'Photobox - Camera',
        ];
        
        return view('customer/camera_view', $data);
    }
    
    /**
     * Upload photo from camera
     * POST: /customer/camera/upload
     */
    public function upload()
    {
        $json = $this->request->getJSON();
        $imageData = $json->image ?? null;
        
        if (!$imageData) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No image data received'
            ])->setStatusCode(400);
        }
        
        try {
            // Remove base64 prefix
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageBinary = base64_decode($imageData);
            
            // Generate unique filename
            $filename = 'photo_' . date('Ymd_His') . '_' . uniqid() . '.jpg';
            $uploadPath = WRITEPATH . 'uploads/';
            
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Save file
            $filepath = $uploadPath . $filename;
            file_put_contents($filepath, $imageBinary);
            
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'path' => $filepath
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to upload photo: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Save photo to session for booking
     * POST: /customer/camera/save-photo
     */
    public function savePhoto()
    {
        $json = $this->request->getJSON();
        $imageData = $json->image ?? null;
        
        if (!$imageData) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No image data received'
            ])->setStatusCode(400);
        }
        
        // Store in session
        session()->set('captured_photo', $imageData);
        
        return $this->response->setJSON([
            'status' => true,
            'message' => 'Photo saved to session',
            'redirect' => '/customer/booking'
        ]);
    }
}