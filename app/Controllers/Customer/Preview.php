<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;

class Preview extends BaseController
{
    public function frame()
    {
        // Data dummy untuk preview UI
        $data = [
            'booking' => [
                'id' => 'DEMO001',
                'booking_code' => 'DEMO001',
                'verification_code' => '123456'
            ]
        ];
        
        return view('customer/choose_frame', $data);
    }
    
    public function verify()
    {
        $data = [
            'booking' => [
                'id' => 'DEMO001',
                'booking_code' => 'DEMO001',
                'verification_code' => '123456'
            ]
        ];
        
        return view('customer/verify_code', $data);
    }
}