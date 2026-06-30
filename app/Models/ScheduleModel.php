<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date', 'start_time', 'end_time', 'session_name', 'max_bookings', 'booked_count', 'is_available'];
    protected $useTimestamps = true;

    public function getAvailableSchedules($date = null)
    {
        $date = $date ?? date('Y-m-d');
        return $this->where('date', $date)
                    ->where('is_available', 1)
                    ->where('booked_count <', 'max_bookings', false)
                    ->orderBy('start_time', 'ASC')
                    ->findAll();
    }

    public function getScheduleByDate($date)
    {
        return $this->where('date', $date)
                    ->orderBy('start_time', 'ASC')
                    ->findAll();
    }

    public function getMonthlySchedules($month, $year)
    {
        return $this->where('MONTH(date)', $month)
                    ->where('YEAR(date)', $year)
                    ->findAll();
    }
}