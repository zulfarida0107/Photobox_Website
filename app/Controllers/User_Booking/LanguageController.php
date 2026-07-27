<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;

class LanguageController extends BaseController
{
    public function change($lang)
    {
        // Daftar bahasa yang didukung
        $supportedLanguages = ['id', 'en', 'ja'];
        
        if (in_array($lang, $supportedLanguages)) {
            session()->set('language', $lang);
        }
        
        return redirect()->back();
    }
}