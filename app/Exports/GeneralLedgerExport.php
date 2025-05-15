<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GeneralLedgerExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
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

        foreach ($dataAccount as $dataAcc) {
            $dataGL[] = [
                "Nama Account",
                $dataAcc->account_no,
                $dataAcc->account_name,
                "",
                "",
                "",
            ];

            $dataGL[] = [
                "Date",
                "Debit",
                "Credit",
                "Total Debit",
                "Total Credit",
                "Balance"
            ];

            $totalBalance = formatNumber(0);
            $totalDebit = formatNumber(0);
            $totalCredit = formatNumber(0);

            if (sizeof($dataBegin) == 0) {
                $dataGL[] = [
                    "Opening Balance",
                    "",
                    "",
                    formatNumber(0),
                    formatNumber(0),
                    formatNumber(0),
                ];
            } else {
                foreach ($dataBegin as $dataBeg) {
                    if ($dataBeg->account_name == $dataAcc->account_name) {
                        $totalDebitOpening = $dataBeg->total_debit;
                        $totalCreditOpening = $dataBeg->total_credit;
                        $totalBalanceOpening = $totalDebitOpening - $totalCreditOpening;
                        $totalBalance += $totalDebitOpening - $totalCreditOpening;
                        $dataGL[] = [
                            "Opening Balance",
                            "",
                            "",
                            $totalDebitOpening,
                            $totalCreditOpening,
                            $totalBalanceOpening,
                        ];
                    } else {
                    }
                }
            }

            foreach ($dataReport as $dataRep) {

                if ($dataRep->account_name == $dataAcc->account_name) {
                    $totalDebit += $dataRep->debit;
                    $totalCredit += $dataRep->credit;
                    $totalBalance += $dataRep->debit - $dataRep->credit;
                    if ($dataRep->debit > $dataRep->credit) {
                        $totalDebitReport = $dataRep->debit - $dataRep->credit;
                        $totalCreditReport = formatNumber(0);
                    } else {
                        $totalDebitReport = formatNumber(0);
                        $totalCreditReport = $dataRep->credit - $dataRep->debit;
                    }
                    $dataGL[] = [
                        Carbon::parse($dataRep->created_at)->format('d/m/Y'),
                        $dataRep->debit,
                        $dataRep->credit,
                        $totalDebitReport,
                        $totalCreditReport,
                        $totalBalance
                    ];
                }
            }

            $dataGL[] = [
                "Closing Balance",
                "",
                "",
                $totalDebit ?? formatNumber(0),
                $totalCredit ?? formatNumber(0),
                $totalBalance
            ];

            $dataGL[] = [
                "",
            ];
        }
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
