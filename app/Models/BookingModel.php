<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'booking_code',
        'user_id',
        'booth_id',
        'schedule_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'payment_status',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Generate unique booking code
     * Format: FF-YYYY-XXXXXX
     */
    public function generateBookingCode()
    {
        return 'FF-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Get booking with all details (join with users, booths, schedules)
     */
    public function getBookingWithDetails($bookingId)
    {
        return $this->select('bookings.*, 
                              users.name as user_name, 
                              users.email as user_email,
                              users.phone as user_phone,
                              booths.name as booth_name, 
                              booths.price as booth_price,
                              booths.description as booth_description,
                              schedules.date as schedule_date, 
                              schedules.start_time as schedule_start,
                              schedules.end_time as schedule_end,
                              schedules.session_name')
                    ->join('users', 'users.id = bookings.user_id', 'left')
                    ->join('booths', 'booths.id = bookings.booth_id', 'left')
                    ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
                    ->where('bookings.id', $bookingId)
                    ->first();
    }

    /**
     * Get booking by booking code
     */
    public function getBookingByCode($code)
    {
        return $this->where('booking_code', $code)->first();
    }

    /**
     * Get all bookings for a user
     */
    public function getUserBookings($userId)
    {
        return $this->select('bookings.*, 
                              booths.name as booth_name,
                              schedules.date as schedule_date,
                              schedules.start_time,
                              schedules.end_time')
                    ->join('booths', 'booths.id = bookings.booth_id', 'left')
                    ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get bookings by status
     */
    public function getBookingsByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get bookings by date range
     */
    public function getBookingsByDateRange($startDate, $endDate)
    {
        return $this->where('booking_date >=', $startDate)
                    ->where('booking_date <=', $endDate)
                    ->orderBy('booking_date', 'ASC')
                    ->findAll();
    }

    /**
     * Check if booth is available for a specific date and time
     */
    public function isBoothAvailable($boothId, $date, $startTime, $endTime)
    {
        $count = $this->where('booth_id', $boothId)
                      ->where('booking_date', $date)
                      ->where('start_time <', $endTime)
                      ->where('end_time >', $startTime)
                      ->whereNotIn('status', ['cancelled'])
                      ->countAllResults();
        
        return $count == 0;
    }

    /**
     * Update booking status
     */
    public function updateStatus($bookingId, $status)
    {
        return $this->update($bookingId, ['status' => $status]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($bookingId, $paymentStatus)
    {
        return $this->update($bookingId, ['payment_status' => $paymentStatus]);
    }

    /**
     * Get booking statistics for a user
     */
    public function getUserStats($userId)
    {
        $totalBookings = $this->where('user_id', $userId)->countAllResults();
        $totalSpent = $this->selectSum('total_price')
                           ->where('user_id', $userId)
                           ->where('payment_status', 'paid')
                           ->get()
                           ->getRow()
                           ->total_price ?? 0;
        
        $completed = $this->where('user_id', $userId)
                          ->where('status', 'completed')
                          ->countAllResults();
        
        return [
            'total_bookings' => $totalBookings,
            'total_spent' => $totalSpent,
            'completed' => $completed,
            'pending' => $this->where('user_id', $userId)->where('status', 'pending')->countAllResults(),
        ];
    }

    /**
     * Get upcoming bookings for a user
     */
    public function getUpcomingBookings($userId)
    {
        return $this->select('bookings.*, 
                              booths.name as booth_name,
                              schedules.date as schedule_date,
                              schedules.start_time,
                              schedules.end_time')
                    ->join('booths', 'booths.id = bookings.booth_id', 'left')
                    ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
                    ->where('user_id', $userId)
                    ->where('booking_date >=', date('Y-m-d'))
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->orderBy('booking_date', 'ASC')
                    ->findAll();
    }
}