<?php

namespace App\Http\Controllers\Other;

use App\Exports\BarcodeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImport;
use App\Exports\QrCodeExport;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class BarcodeController extends Controller
{
    public function index()
    {
        return view('barcode-form');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'column' => 'required|string'
        ]);

        // Baca file Excel
        $import = new DataImport();
        Excel::import($import, $request->file('file'));

        $data = $import->getData();
        $column = $request->column;

        // Buat direktori untuk menyimpan QR Code
        $directory = 'qrcodes/' . uniqid();
        Storage::makeDirectory($directory);

        // Generate QR Code untuk setiap row
        $qrData = [];
        foreach ($data as $index => $row) {
            if (isset($row[$column])) {
                $content = $row[$column];
                $filename = 'qr_' . $index . '.png';
                $path = $directory . '/' . $filename;

                // Generate QR Code sebagai PNG
                QrCode::format('png')
                    ->size(200)
                    ->generate($content, storage_path('app/public/' . $path));

                // Simpan path QR Code
                $row['qr_code'] = $path;
                $qrData[] = $row;
            }
        }

        // Export ke Excel dengan QR Code
        $export = new BarcodeExport($qrData, $directory);
        return Excel::download($export, 'output_with_qrcodes.xlsx');
    }
}
