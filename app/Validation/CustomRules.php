<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Custom validation untuk password strength
     * Password harus mengandung:
     * - Huruf Kapital (A-Z)
     * - Huruf Kecil (a-z)
     * - Angka (0-9)
     * - Karakter Khusus (!@#$%^&*(),.?":{}|<>)
     * - Tidak boleh mengandung titik (.)
     * - Minimal 8 karakter
     */
    public function custom_password_strength($str): bool
    {
        // Cek tidak boleh ada titik
        if (strpos($str, '.') !== false) {
            return false;
        }

        // Cek minimal 1 huruf kapital
        if (!preg_match('/[A-Z]/', $str)) {
            return false;
        }

        // Cek minimal 1 huruf kecil
        if (!preg_match('/[a-z]/', $str)) {
            return false;
        }

        // Cek minimal 1 angka
        if (!preg_match('/\d/', $str)) {
            return false;
        }

        // Cek minimal 1 karakter khusus
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $str)) {
            return false;
        }

        // Cek minimal 8 karakter
        if (strlen($str) < 8) {
            return false;
        }

        return true;
    }

    /**
     * Custom validation untuk cek password tidak boleh ada titik
     */
    public function password_no_dot($str): bool
    {
        return strpos($str, '.') === false;
    }
}