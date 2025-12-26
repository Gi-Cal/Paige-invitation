<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\RsvpConfirmation;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

        // Save to Excel
        $this->saveToExcel($validated);

        // Send confirmation email
        Mail::to($validated['email'])->send(new RsvpConfirmation($validated));

        return redirect()->back()->with('success', 'Thank you for your RSVP! A confirmation email has been sent.');
    }

    private function saveToExcel($data)
    {
        $filePath = storage_path('app/rsvps.xlsx');

        if (file_exists($filePath)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
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
}