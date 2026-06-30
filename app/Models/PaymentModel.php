<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_id', 'user_id', 'amount', 'payment_method',
        'payment_status', 'transaction_id', 'payment_date',
        'qr_code', 'expiry_time'
    ];
    protected $useTimestamps = true;

    public function getPaymentWithBooking($paymentId)
    {
        return $this->select('payments.*, 
                              bookings.booking_code,
                              users.name as user_name, users.email')
                    ->join('bookings', 'bookings.id = payments.booking_id')
                    ->join('users', 'users.id = payments.user_id')
                    ->where('payments.id', $paymentId)
                    ->first();
    }

    public function getPaymentByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->first();
    }
}