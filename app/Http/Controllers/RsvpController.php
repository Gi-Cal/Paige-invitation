<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\RsvpConfirmation;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'attending' => 'required|in:yes,no',
            'additional_guests' => 'nullable|array',
            'additional_guests.*' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        // Check if this is admin login
        if (strtolower(trim($validated['name'])) === 'admin' 
            && $validated['attending'] === 'yes' 
            && trim($validated['message']) === 'Paige Birthday') {
            
            // Store admin session
            session(['admin_logged_in' => true]);
            
            // Redirect to admin page
            return redirect()->route('admin.rsvps');
        }

        // Regular RSVP - Save to Excel
        $this->saveToExcel($validated);

        // Send confirmation email
        Mail::to($validated['email'])->send(new RsvpConfirmation($validated));

        return redirect()->back()->with('success', 'Thank you for your RSVP! A confirmation email has been sent.');
    }

    private function saveToExcel($data)
    {
        $filePath = storage_path('app/rsvps.xlsx');

        if (file_exists($filePath)) {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $row = $sheet->getHighestRow() + 1;
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
    }

    public function adminView()
    {
        // Check if admin is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        $filePath = storage_path('app/rsvps.xlsx');
        $data = [];

        if (file_exists($filePath)) {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
        }

        return view('admin.rsvps', compact('data'));
    }

    public function downloadExcel()
    {
        // Check if admin is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        $filePath = storage_path('app/rsvps.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath, 'rsvps_' . date('Y-m-d') . '.xlsx');
        }

        return redirect()->back()->with('error', 'No RSVP data found.');
    }
}