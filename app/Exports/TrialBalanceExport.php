<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TrialBalanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    public $category, $month, $year;

    public function __construct($category, $month, $year)
    {
        $this->category = $category;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        function formatNumber($value)
        {
            return number_format((float)$value, 2, '.', '');
        }
        $dataGL = [];
        $dataAccount = ChartOfAccount::GetDataAccount($this->category);
        $dataReport = ChartOfAccount::GetReport($this->category, $this->month, $this->year);
        $dataBegin = ChartOfAccount::GetDataBegin($this->category, $this->month, $this->year);

        $grandTotalDebit = formatNumber(0);
        $grandTotalCredit = formatNumber(0);

        $dataGL[] = [
            "Nama Account",
            "Balance Debit",
            "Balance Credit",
        ];

        foreach ($dataAccount as $dataAcc) {

            $totalBalance = formatNumber(0);

            foreach ($dataBegin as $dataBeg) {
                if ($dataBeg->account_name == $dataAcc->account_name) {
                    $totalBalance += $dataBeg->total_debit - $dataBeg->total_credit;
                }
            }

            foreach ($dataReport as $dataRep) {
                if ($dataRep->account_name == $dataAcc->account_name) {
                    $totalBalance += $dataRep->debit - $dataRep->credit;
                }
            }

            if ($totalBalance < 0) {
                $totalCredit = -$totalBalance;
                $totalDebit = formatNumber(0);
                $grandTotalCredit += $totalCredit;
            } else {
                $totalCredit = formatNumber(0);
                $totalDebit = $totalBalance;
                $grandTotalDebit += $totalDebit;
            }

            $dataGL[] = [
                $dataAcc->account_no . ": " . $dataAcc->account_name,
                formatNumber($totalDebit),
                formatNumber($totalCredit),
            ];
        }
        $dataGL[] = [
            "Total",
            formatNumber($grandTotalDebit),
            formatNumber($grandTotalCredit)
        ];

        return collect($dataGL);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function headings(): array
    {
        return [];
    }
}
