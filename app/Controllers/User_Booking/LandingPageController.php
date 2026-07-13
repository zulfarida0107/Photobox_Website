<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;

class LandingPageController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'CuitBox Studio - Premium Photobox Experience'
        ];

        return view('User_Booking/landing_page', $data);
    }
}