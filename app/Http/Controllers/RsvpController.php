<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\RsvpConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        // Check if this is admin login BEFORE validation
        if (strtolower(trim($request->input('name'))) === 'admin' 
            && $request->input('attending') === 'yes' 
            && trim($request->input('message')) === 'Paige Birthday') {
            
            // Store admin session
            session(['admin_logged_in' => true]);
            
            // Redirect to admin page
            return redirect()->route('admin.rsvps');
        }

        // Regular RSVP validation (email is required for non-admin)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'attending' => 'required|in:yes,no',
            'additional_guests' => 'nullable|array',
            'additional_guests.*' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        Log::info('=== RSVP SUBMISSION START ===');
        Log::info('Name: ' . $validated['name']);
        Log::info('Email: ' . $validated['email']);

        try {
            // Regular RSVP - Save to Excel
            $this->saveToExcel($validated);
            Log::info('Excel save completed successfully');
        } catch (\Exception $e) {
            Log::error('Excel save FAILED: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            // Don't stop - still try to send email
        }

        try {
            // Send confirmation email
            Mail::to($validated['email'])->send(new RsvpConfirmation($validated));
            Log::info('Email sent successfully');
        } catch (\Exception $e) {
            Log::error('Email send FAILED: ' . $e->getMessage());
        }

        Log::info('=== RSVP SUBMISSION END ===');

        return redirect()->back()->with('success', 'Thank you for your RSVP! A confirmation email has been sent. Please check your SPAM folder if you don\'t see it.');
    }

    private function saveToExcel($data)
    {
        $filePath = storage_path('app/rsvps.xlsx');
        
        Log::info('Excel file path: ' . $filePath);
        Log::info('File exists: ' . (file_exists($filePath) ? 'YES' : 'NO'));
        Log::info('Directory writable: ' . (is_writable(storage_path('app')) ? 'YES' : 'NO'));

        if (file_exists($filePath)) {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $row = $sheet->getHighestRow() + 1;
            Log::info('Appending to existing file at row: ' . $row);
        } else {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set headers
            $sheet->setCellValue('A1', 'Name');
            $sheet->setCellValue('B1', 'Email');
            $sheet->setCellValue('C1', 'Attending');
            $sheet->setCellValue('D1', 'Additional Guests');
            $sheet->setCellValue('E1', 'Message');
            $sheet->setCellValue('F1', 'Submitted At');
            
            $row = 2;
            Log::info('Creating new Excel file, starting at row 2');
        }

        // Add data
        $sheet->setCellValue('A' . $row, $data['name']);
        $sheet->setCellValue('B' . $row, $data['email']);
        $sheet->setCellValue('C' . $row, $data['attending']);
        $sheet->setCellValue('D' . $row, isset($data['additional_guests']) ? implode(', ', array_filter($data['additional_guests'])) : '');
        $sheet->setCellValue('E' . $row, $data['message'] ?? '');
        $sheet->setCellValue('F' . $row, now()->format('Y-m-d H:i:s'));

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        
        Log::info('Excel file saved. Total rows now: ' . $sheet->getHighestRow());
        Log::info('File size: ' . filesize($filePath) . ' bytes');
    }

    public function adminView()
    {
        // Check if admin is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        $filePath = storage_path('app/rsvps.xlsx');
        $data = [];

        Log::info('Admin viewing RSVPs');
        Log::info('Excel file exists: ' . (file_exists($filePath) ? 'YES' : 'NO'));

        if (file_exists($filePath)) {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            Log::info('Loaded ' . count($data) . ' rows from Excel');
        } else {
            Log::warning('Excel file does not exist when admin tried to view');
        }

        return view('admin.rsvps', compact('data'));
    }

    public function downloadExcel()
    {
        // Check if admin is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('home');
        }

        $filePath = storage_path('app/rsvps.xlsx');

        // Check if file exists
        if (!file_exists($filePath)) {
            return redirect()->route('admin.rsvps')->with('error', 'No RSVP data found.');
        }

        // Return the file for download
        return response()->download($filePath, 'rsvps_' . date('Y-m-d') . '.xlsx');
    }

    public function logout()
    {
        // Clear admin session
        session()->forget('admin_logged_in');
        
        // Redirect to home
        return redirect()->route('home');
    }
}