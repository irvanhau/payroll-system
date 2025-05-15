<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BarcodeExport implements FromCollection, WithHeadings, WithDrawings
{
    private $data;
    private $directory;
    private $headings;

    public function __construct($data, $directory)
    {
        $this->data = $data;
        $this->directory = $directory;

        if (count($data) > 0) {
            $this->headings = array_keys($data[0]);
            $this->headings[] = 'QR Code';
        }
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2; // Mulai dari row 2 (setelah header)

        foreach ($this->data as $item) {
            if (isset($item['qr_code']) && file_exists(storage_path('app/public/' . $item['qr_code']))) {
                $drawing = new Drawing();
                $drawing->setName('QR Code');
                $drawing->setDescription('QR Code');
                $drawing->setPath(storage_path('app/public/' . $item['qr_code']));
                $drawing->setHeight(50);
                $drawing->setCoordinates('Z' . $row); // Sesuaikan kolom untuk QR Code

                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}
