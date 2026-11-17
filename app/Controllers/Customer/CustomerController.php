<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\SlotModel;
use App\Models\BoothModel;
use App\Models\PrintOptionModel;

class CustomerController extends BaseController
{
    protected $bookingModel;
    protected $customerModel;
    protected $slotModel;
    protected $boothModel;
    protected $printOptionModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->slotModel = new SlotModel();
        $this->boothModel = new BoothModel();
        $this->printOptionModel = new PrintOptionModel();
    }

    public function index()
    {
        return redirect()->to('/customer/booking');
    }

    // ============================================================
    // BOOKING FORM
    // ============================================================

    public function bookingForm()
    {
        $data = [
            'title'    => 'CuitBox Studio - Booking Kiosk',
            'print_options' => $this->printOptionModel->findAll(),
            'slots'    => $this->slotModel->getAvailableSlots(date('Y-m-d')),
            'booths'   => $this->boothModel->getActiveBooths(),
        ];
        
        return view('customer/booking_form', $data);
    }

    public function createBooking()
    {
        $rules = [
            'email'      => 'required|valid_email',
            'print_option_id' => 'required',
            'slot_id'    => 'required',
            'booth_id'   => 'required',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }
        
        try {
            $customer = $this->customerModel->findOrCreateByEmail(
                $this->request->getPost('email'),
                $this->request->getPost('name'),
                $this->request->getPost('phone')
            );
            
            $queueNumber = $this->bookingModel->getNextQueueNumber($this->request->getPost('slot_id'));
            
            $bookingId = $this->bookingModel->generateId();
            $verificationCode = $this->bookingModel->generateVerificationCode();
            
            $bookingData = [
                'id'                => $bookingId,
                'booking_code'      => $bookingId,
                'verification_code' => $verificationCode,
                'customer_id'       => $customer['id'],
                'slot_id'           => $this->request->getPost('slot_id'),
                'booth_id'          => $this->request->getPost('booth_id'),
                'print_option_id'   => $this->request->getPost('print_option_id'),
                'queue_number'      => $queueNumber,
                'status'            => 'pending',
                'payment_status'    => 'pending',
                'notes'             => $this->request->getPost('notes'),
            ];
            
            $this->bookingModel->insert($bookingData);
            $booking = $this->bookingModel->find($bookingId);
            
            $this->slotModel->incrementBookedCount($this->request->getPost('slot_id'));
            $this->boothModel->incrementQueue($this->request->getPost('booth_id'));
            
            session()->set('pending_booking', $booking);
            
            return redirect()->to('/customer/verify')
                ->with('success', 'Booking created! Verification code: ' . $verificationCode);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ============================================================
    // VERIFICATION
    // ============================================================

    public function verifyPage()
    {
        $pendingBooking = session()->get('pending_booking');
        
        if (!$pendingBooking) {
            $sampleBooking = [
                'id'               => 'DEMO001',
                'booking_code'     => 'BK' . date('Ymd') . '001',
                'verification_code'=> '123456',
                'status'           => 'pending'
            ];
            $data = [
                'title'   => 'Verify Your Booking',
                'booking' => $sampleBooking,
            ];
        } else {
            $data = [
                'title'   => 'Verify Your Booking',
                'booking' => $pendingBooking,
            ];
        }
        
        return view('customer/verify_code', $data);
    }

    public function checkVerification()
    {
        $verificationCode = $this->request->getPost('verification_code');
        
        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Verification code is required');
        }
        
        $verifiedBooking = $this->bookingModel->verifyBooking($verificationCode);
        
        if ($verifiedBooking) {
            session()->remove('pending_booking');
            session()->set('verified_booking', $verifiedBooking);
            
            // Set active booking for the booth
            if (!empty($verifiedBooking['booth_id'])) {
                $this->boothModel->update($verifiedBooking['booth_id'], [
                    'active_booking_id' => $verifiedBooking['id']
                ]);
            }
            
            return redirect()->to('/customer/choose-frame')
                ->with('success', 'Booking verified! Please choose your frame.');
        }
        
        return redirect()->back()->with('error', 'Invalid verification code');
    }

    // ============================================================
    // CHOOSE FRAME
    // ============================================================

    public function chooseFrame()
    {
        $verifiedBooking = session()->get('verified_booking');
        
        if (!$verifiedBooking) {
            return redirect()->to('/customer/verify')
                ->with('error', 'Please verify your booking first');
        }
        
        $data = [
            'title'   => 'Choose Frame',
            'booking' => $verifiedBooking,
            'frames'  => [
                ['id' => 'clean_minimalist', 'name' => 'Clean Minimalist', 'icon' => '⬜', 'description' => 'Simple and elegant'],
                ['id' => 'cute_kawaii', 'name' => 'Cute & Kawaii Panel Style', 'icon' => '🌸', 'description' => 'Adorable and playful'],
                ['id' => 'retro_vintage', 'name' => 'Retro 80\'s Vintage Style', 'icon' => '📻', 'description' => 'Nostalgic classic'],
                ['id' => 'magazine_news', 'name' => 'Magazine News Liberty Notes', 'icon' => '📰', 'description' => 'Bold and editorial'],
            ]
        ];
        
        return view('customer/choose_frame', $data);
    }

    public function saveFrameChoice()
    {
        $frameChoice = $this->request->getPost('frame_choice');
        $bookingId = $this->request->getPost('booking_id');
        
        if (!$frameChoice || !$bookingId) {
            return redirect()->back()->with('error', 'Please select a frame');
        }
        
        $frameNames = [
            'clean_minimalist' => 'Clean Minimalist',
            'cute_kawaii' => 'Cute & Kawaii Panel Style',
            'retro_vintage' => 'Retro 80\'s Vintage Style',
            'magazine_news' => 'Magazine News Liberty Notes'
        ];
        
        $selectedFrame = $frameNames[$frameChoice] ?? $frameChoice;
        
        $updated = $this->bookingModel->updateFrameChoice($bookingId, $selectedFrame);
        
        if (!$updated) {
            return redirect()->back()->with('error', 'Failed to save frame choice');
        }
        
        // Clear active booking from the booth
        $booking = $this->bookingModel->find($bookingId);
        if ($booking && !empty($booking['booth_id'])) {
            $this->boothModel->update($booking['booth_id'], [
                'active_booking_id' => null
            ]);
        }
        
        session()->remove('verified_booking');
        
        return redirect()->to('/customer/bookings/' . $bookingId)
            ->with('success', 'Frame selected successfully!');
    }

    // ============================================================
    // BOOKING DETAILS
    // ============================================================

    public function showBooking($bookingId = null)
    {
        if (!$bookingId) {
            return redirect()->to('/customer/booking')->with('error', 'Invalid booking');
        }
        
        $booking = $this->bookingModel->getBookingWithDetails($bookingId);
        
        if (!$booking) {
            return redirect()->to('/customer/booking')->with('error', 'Booking not found');
        }
        
        $data = [
            'title'   => 'Booking Details',
            'booking' => $booking,
        ];
        
        return view('customer/booking_detail', $data);
    }

    public function myBookings($customerId = null)
    {
        $bookings = $this->bookingModel->where('customer_id', $customerId)->findAll();
        
        $data = [
            'title'    => 'My Bookings',
            'bookings' => $bookings,
        ];
        
        return view('customer/my_bookings', $data);
    }
}