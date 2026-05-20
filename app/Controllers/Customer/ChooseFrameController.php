<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;

class ChooseFrameController extends BaseController
{
    /**
     * Display frame selection page (4 cards)
     * GET: /customer/choose-frame
     */
    public function index()
    {
        // Data untuk view
        $data = [
            'title' => 'Pilih Frame Terbaik untuk Memori Anda',
            'booking' => [
                'id' => 'DEMO001',
                'booking_code' => 'BK20240501001',
                'customer_id' => 'CUST001',
                'status' => 'verified'
            ],
            'frames' => [
                [
                    'id' => 'clean_minimalist',
                    'name' => 'Clean Minimalist',
                    'subtitle' => 'White & Simple',
                    'icon' => '⬜',
                    'image' => 'https://placehold.co/300x400/f8f9fa/008393?text=Clean+Minimalist'
                ],
                [
                    'id' => 'cute_kawaii',
                    'name' => 'Cute & Kawaii',
                    'subtitle' => 'Pastel Style',
                    'icon' => '🌸',
                    'image' => 'https://placehold.co/300x400/fce7f3/008393?text=Cute+Kawaii'
                ],
                [
                    'id' => 'retro_vintage',
                    'name' => 'Retro 80\'s',
                    'subtitle' => 'Vintage Style',
                    'icon' => '📻',
                    'image' => 'https://placehold.co/300x400/4a4a4a/ffffff?text=Retro+80%27s'
                ],
                [
                    'id' => 'magazine_news',
                    'name' => 'Magazine News',
                    'subtitle' => 'Lifestyle News',
                    'icon' => '📰',
                    'image' => 'https://placehold.co/300x400/ff6b6b/ffffff?text=Magazine+News'
                ]
            ]
        ];
        
        return view('customer/choose_frame', $data);
    }
    
    /**
     * Save selected frame
     * POST: /customer/save-frame
     */
    public function save()
    {
        $frameChoice = $this->request->getPost('frame_choice');
        $bookingId = $this->request->getPost('booking_id');
        
        // Validasi
        if (!$frameChoice) {
            return redirect()->back()->with('error', 'Silakan pilih frame terlebih dahulu');
        }
        
        // Simpan ke session
        session()->set('selected_frame', $frameChoice);
        
        // Redirect ke halaman sukses
        return redirect()->to('/customer/choose-frame')
            ->with('success', 'Frame "' . $frameChoice . '" berhasil dipilih!');
    }
}