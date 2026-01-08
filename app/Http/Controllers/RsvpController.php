<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\RsvpConfirmation;
use App\Models\Rsvp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class RsvpController extends Controller
{
    public function store(Request $request)
    {
        // Check if this is admin login BEFORE validation
        if (strtolower(trim($request->input('name'))) === 'admin' 
            && $request->input('attending') === 'yes' 
            && trim($request->input('message')) === 'Paige Birthday') {
            
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.rsvps');
        }

        // Regular RSVP validation
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
            // Save to database
            Rsvp::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'attending' => $validated['attending'],
                'additional_guests' => isset($validated['additional_guests']) 
                    ? implode(', ', array_filter($validated['additional_guests'])) 
                    : null,
                'message' => $validated['message'] ?? null,
            ]);
            
            Log::info('Database save completed successfully');
        } catch (\Exception $e) {
            Log::error('Database save FAILED: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        try {
            // Send confirmation email
            Mail::to($validated['email'])->send(new RsvpConfirmation($validated));
            Log::info('Email sent successfully to: ' . $validated['email']);
        } catch (\Exception $e) {
            Log::error('Email send FAILED: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Check mail configuration
            Log::info('Mail Driver: ' . config('mail.default'));
            Log::info('Mail From Address: ' . config('mail.from.address'));
        }

        Log::info('=== RSVP SUBMISSION END ===');

        return redirect()->back()->with('success', 'Thank you for your RSVP! A confirmation email has been sent. Please check your SPAM folder if you don\'t see it.');
    }

    public function adminView()
    {
        // Check if admin is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        // Get all RSVPs from database
        $rsvps = Rsvp::orderBy('created_at', 'desc')->get();

        Log::info('Admin viewing RSVPs');
        Log::info('Total RSVPs in database: ' . $rsvps->count());

        return view('admin.rsvps', compact('rsvps'));
    }

    public function downloadExcel(Request $request)
    {
        // Check if admin is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('home');
        }

        // Get filter parameter
        $filter = $request->query('filter', 'all');

        // Build query
        $query = Rsvp::query();

        // Apply filter
        if ($filter === 'yes' || $filter === 'no') {
            $query->where('attending', $filter);
        }

        // Get RSVPs ordered by creation date
        $rsvps = $query->orderBy('created_at', 'asc')->get();

        if ($rsvps->isEmpty()) {
            return redirect()->route('admin.rsvps')->with('error', 'No RSVP data found for the selected filter.');
        }

        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(20);
        
        // Style headers
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4d683e'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        
        // Set headers
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Attending');
        $sheet->setCellValue('D1', 'Additional Guests');
        $sheet->setCellValue('E1', 'Message');
        $sheet->setCellValue('F1', 'Submitted At');
        
        // Add data
        $row = 2;
        foreach ($rsvps as $rsvp) {
            $sheet->setCellValue('A' . $row, $rsvp->name);
            $sheet->setCellValue('B' . $row, $rsvp->email);
            $sheet->setCellValue('C' . $row, ucfirst($rsvp->attending));
            $sheet->setCellValue('D' . $row, $rsvp->additional_guests ?: '-');
            $sheet->setCellValue('E' . $row, $rsvp->message ?: '-');
            $sheet->setCellValue('F' . $row, $rsvp->created_at->format('Y-m-d H:i:s'));
            
            // Add borders
            $sheet->getStyle('A' . $row . ':F' . $row)->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            $row++;
        }

        // Add borders to headers
        $sheet->getStyle('A1:F1')->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Save to temporary file
        $filterSuffix = $filter === 'all' ? 'all' : ($filter === 'yes' ? 'attending' : 'not-attending');
        $fileName = 'rsvps_' . $filterSuffix . '_' . date('Y-m-d_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        Log::info("Excel file generated: {$fileName} with filter: {$filter}, total rows: " . ($rsvps->count()));

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('home');
    }
}