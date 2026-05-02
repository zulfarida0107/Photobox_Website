<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;

class frameController extends BaseController
{
    public function allFrame()
    {
        // Data frames
        $frames = [
            [
                'id' => 'pink_dream',
                'name' => 'Pink Dream',
                'description' => 'Classic Oriental & Stars',
                'sub_options' => ['Sky Bubble', 'Yibam Neez Blue'],
                'icon' => '🌸',
                'number' => '01'
            ],
            [
                'id' => 'sweet_lavender',
                'name' => 'Sweet Lavender',
                'description' => 'Pusat Hant Magic',
                'sub_options' => ['Sweet Lavender', 'Pusat Hant Magic'],
                'icon' => '🪻',
                'number' => '02'
            ],
            [
                'id' => 'rainbow_pop',
                'name' => 'Rainbow Pop',
                'description' => 'Multi-color/Splash',
                'sub_options' => ['Gold Center: Elegant Pony View'],
                'icon' => '🌈',
                'number' => '03'
            ],
            [
                'id' => 'retro_glitch',
                'name' => 'Retro Glitch',
                'description' => 'Cool Glitch Video',
                'sub_options' => ['Retro Glitch', 'Cool Glitch Video'],
                'icon' => '📺',
                'number' => '04'
            ]
        ];
        
        $data = [
            'title' => 'Pilih Frame',
            'frames' => $frames,
            'booking' => ['id' => 'DEMO001']
        ];
        
        // Panggil view dengan nama baru: all_frame.php
        return view('customer/all_frame', $data);
    }
    
    public function saveFrame()
    {
        $frameChoice = $this->request->getPost('frame_choice');
        $bookingId = $this->request->getPost('booking_id');
        
        return redirect()->to('/customer/all-frame')
            ->with('success', 'Frame "' . $frameChoice . '" berhasil dipilih!');
    }
}