<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProfitLossExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    protected $levelOneRevenues;
    protected $levelTwoRevenues;
    protected $sumBeginRevenues;
    protected $sumMoveRevenues;

    protected $levelOneCoses;
    protected $levelTwoCoses;
    protected $sumBeginCoses;
    protected $sumMoveCoses;

    protected $levelOneOtherRevenues;
    protected $levelTwoOtherRevenues;
    protected $sumBeginOtherRevenues;
    protected $sumMoveOtherRevenues;

    protected $levelOneExpenses;
    protected $levelTwoExpenses;
    protected $sumBeginExpenses;
    protected $sumMoveExpenses;

    public function __construct($month, $year)
    {
        $this->levelOneRevenues = ChartOfAccount::GetLevelOne('R', '', 'Other Income');
        $this->levelTwoRevenues = ChartOfAccount::GetLevelTwo('R', '', 'Other Income');
        $this->sumBeginRevenues = ChartOfAccount::GetSumBegin($month - 1, $year, 'R', '', 'Other Income');
        $this->sumMoveRevenues =  ChartOfAccount::GetSumMove($month, $year, 'R', '', 'Other Income');

        $this->levelOneOtherRevenues = ChartOfAccount::GetLevelOne('R', '', 'Income');
        $this->levelTwoOtherRevenues = ChartOfAccount::GetLevelTwo('R', '', 'Income');
        $this->sumBeginOtherRevenues = ChartOfAccount::GetSumBegin($month - 1, $year, 'R', '', 'Income');
        $this->sumMoveOtherRevenues =  ChartOfAccount::GetSumMove($month, $year, 'R', '', 'Income');

        $this->levelOneCoses = ChartOfAccount::GetLevelOne('C', '', 'Other Expense', 'Expenses');
        $this->levelTwoCoses = ChartOfAccount::GetLevelTwo('C', '', 'Other Expense', 'Expenses');
        $this->sumMoveCoses = ChartOfAccount::GetSumMove($month, $year, 'C', '', 'Other Expense', 'Expense');
        $this->sumBeginCoses = ChartOfAccount::GetSumBegin($month - 1, $year, 'C', '', 'Other Expense', 'Expense');

        $this->levelOneExpenses = ChartOfAccount::GetLevelOne('C', '', 'Other Expense', 'Cost of Sales');
        $this->levelTwoExpenses = ChartOfAccount::GetLevelTwo('C', '', 'Other Expenses', 'Cost of Sales');
        $this->sumMoveExpenses = ChartOfAccount::GetSumMove($month, $year, 'C', '', 'Other Expense', 'Cost of Sales');
        $this->sumBeginExpenses = ChartOfAccount::GetSumBegin($month - 1, $year, 'C', '', 'Other Expense', 'Cost of Sales');
    }

    public function headings(): array
    {
        $dataHeading = [];
        $dataHeading[] = [
            'Account No',
            'Account Name',
            'Saldo Akhir',
        ];

        $dataHeading[] = [
            'INCOME',
            '',
            '',
        ];


        return $dataHeading;
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

        $dataRevenue = [];

        $grandTotalBalanceRevenue = formatNumber(0);
        foreach ($this->levelOneRevenues as $levelOneRevenue) {
            $levelOneData = [];

            // Calculate level one totals
            $totalBeginLevelOneRevenue = formatNumber(0);
            $totalMoveLevelOneRevenue = formatNumber(0);
            $totalBalanceLevelOneRevenue = formatNumber(0);

            foreach ($this->levelTwoRevenues as $levelTwoRevenue) {
                if ($levelOneRevenue->name == $levelTwoRevenue->name) {
                    $levelTwoData = [];

                    // Calculate level two totals
                    $totalBeginLevelTwoRevenue = formatNumber(0);
                    $totalMoveLevelTwoRevenue = formatNumber(0);
                    $totalBalanceLevelTwoRevenue = formatNumber(0);

                    // Find corresponding beginning balance
                    if ($levelOneRevenue->name == $levelTwoRevenue->name) {
                        foreach ($this->sumBeginRevenues as $sumBeginRevenue) {

                            if ($sumBeginRevenue->account_no == $levelTwoRevenue->account_no) {
                                $totalBeginLevelTwoRevenue = $sumBeginRevenue->credit - $sumBeginRevenue->debit;
                                $totalBeginLevelOneRevenue += $totalBeginLevelTwoRevenue;
                            }
                        }

                        foreach ($this->sumMoveRevenues as $sumMoveRevenue) {
                            if ($sumMoveRevenue->account_no == $levelTwoRevenue->account_no) {
                                $totalMoveLevelTwoRevenue = $sumMoveRevenue->credit - $sumMoveRevenue->debit;
                                $totalMoveLevelOneRevenue += $totalMoveLevelTwoRevenue;
                            }
                        }
                    }

                    // Calculate level two balance
                    $totalBalanceLevelTwoRevenue = $totalBeginLevelTwoRevenue + $totalMoveLevelTwoRevenue;
                    $formattedTotalBalanceLevelTwo = $totalBalanceLevelTwoRevenue < 0 ? "(" . 0 - $totalBalanceLevelTwoRevenue . ")" : $totalBalanceLevelTwoRevenue;

                    $levelTwoData = [
                        $levelTwoRevenue->account_no,
                        $levelTwoRevenue->account_name,
                        formatNumber($totalBalanceLevelTwoRevenue),
                    ];

                    $levelOneData[] = $levelTwoData;

                    // Accumulate level one totals
                    $totalBeginLevelOneRevenue += $totalBeginLevelTwoRevenue;
                    $totalMoveLevelOneRevenue += $totalMoveLevelTwoRevenue;
                    $totalBalanceLevelOneRevenue += $totalBalanceLevelTwoRevenue;
                }
            }

            $dataRevenue[] = $levelOneData;

            // Accumulate grand totals
            $grandTotalBalanceRevenue += $totalBalanceLevelOneRevenue;
        }

        $dataCos = [];
        $dataCos[] = [
            "COST OF SALES",
            "",
            ""
        ];

        $grandTotalBalanceCos = formatNumber(0);
        foreach ($this->levelOneCoses as $levelOneCos) {
            $levelOneData = [];

            // Calculate level one totals
            $totalBeginLevelOneCos = formatNumber(0);
            $totalMoveLevelOneCos = formatNumber(0);
            $totalBalanceLevelOneCos = formatNumber(0);

            foreach ($this->levelTwoCoses as $levelTwoCos) {
                if ($levelOneCos->name == $levelTwoCos->name) {
                    $levelTwoData = [];

                    // Calculate level two totals
                    $totalBeginLevelTwoCos = formatNumber(0);
                    $totalMoveLevelTwoCos = formatNumber(0);
                    $totalBalanceLevelTwoCos = formatNumber(0);

                    // Find corresponding beginning balance
                    if ($levelOneCos->name == $levelTwoCos->name) {
                        foreach ($this->sumBeginCoses as $sumBeginCos) {

                            if ($sumBeginCos->account_no == $levelTwoCos->account_no) {
                                $totalBeginLevelTwoCos = $sumBeginCos->debit - $sumBeginCos->credit;
                                $totalBeginLevelOneCos += $totalBeginLevelTwoCos;
                            }
                        }

                        foreach ($this->sumMoveCoses as $sumMoveCos) {
                            if ($sumMoveCos->account_no == $levelTwoCos->account_no) {
                                $totalMoveLevelTwoCos = $sumMoveCos->debit - $sumMoveCos->credit;
                                $totalMoveLevelOneCos += $totalMoveLevelTwoCos;
                            }
                        }
                    }

                    // Calculate level two balance
                    $totalBalanceLevelTwoCos = $totalBeginLevelTwoCos + $totalMoveLevelTwoCos;

                    $levelTwoData = [
                        $levelTwoCos->account_no,
                        $levelTwoCos->account_name,
                        formatNumber($totalBalanceLevelTwoCos),
                    ];

                    $levelOneData[] = $levelTwoData;

                    // Accumulate level one totals
                    $totalBeginLevelOneCos += $totalBeginLevelTwoCos;
                    $totalMoveLevelOneCos += $totalMoveLevelTwoCos;
                    $totalBalanceLevelOneCos += $totalBalanceLevelTwoCos;
                }
            }

            $dataCos[] = $levelOneData;

            // Accumulate grand totals
            $grandTotalBalanceCos += $totalBalanceLevelOneCos;
        }

        $totalGrossProfit = $grandTotalBalanceRevenue - $grandTotalBalanceCos;

        $dataGrossProfit = [];
        $dataGrossProfit[] = [
            "GROSS PROFIT",
            "",
            formatNumber($totalGrossProfit)
        ];

        $dataOtherRevenue = [];
        $dataOtherRevenue[] = [
            "OTHER INCOME",
            "",
            ""
        ];

        $grandTotalBalanceOtherRevenue = formatNumber(0);
        foreach ($this->levelOneOtherRevenues as $levelOneOtherRevenue) {
            $levelOneData = [];

            // Calculate level one totals
            $totalBeginLevelOneOtherRevenue = formatNumber(0);
            $totalMoveLevelOneOtherRevenue = formatNumber(0);
            $totalBalanceLevelOneOtherRevenue = formatNumber(0);

            foreach ($this->levelTwoOtherRevenues as $levelTwoOtherRevenue) {
                if ($levelOneOtherRevenue->name == $levelTwoOtherRevenue->name) {
                    $levelTwoData = [];

                    // Calculate level two totals
                    $totalBeginLevelTwoOtherRevenue = formatNumber(0);
                    $totalMoveLevelTwoOtherRevenue = formatNumber(0);
                    $totalBalanceLevelTwoOtherRevenue = formatNumber(0);

                    // Find corresponding beginning balance
                    if ($levelOneOtherRevenue->name == $levelTwoOtherRevenue->name) {
                        foreach ($this->sumBeginOtherRevenues as $sumBeginOtherRevenue) {

                            if ($sumBeginOtherRevenue->account_no == $levelTwoOtherRevenue->account_no) {
                                $totalBeginLevelTwoOtherRevenue = $sumBeginOtherRevenue->credit - $sumBeginOtherRevenue->debit;
                                $totalBeginLevelOneOtherRevenue += $totalBeginLevelTwoOtherRevenue;
                            }
                        }

                        foreach ($this->sumMoveOtherRevenues as $sumMoveOtherRevenue) {
                            if ($sumMoveOtherRevenue->account_no == $levelTwoOtherRevenue->account_no) {
                                $totalMoveLevelTwoOtherRevenue = $sumMoveOtherRevenue->credit - $sumMoveOtherRevenue->debit;
                                $totalMoveLevelOneOtherRevenue += $totalMoveLevelTwoOtherRevenue;
                            }
                        }
                    }

                    // Calculate level two balance
                    $totalBalanceLevelTwoOtherRevenue = $totalBeginLevelTwoOtherRevenue + $totalMoveLevelTwoOtherRevenue;

                    $levelTwoData = [
                        $levelTwoOtherRevenue->account_no,
                        $levelTwoOtherRevenue->account_name,
                        formatNumber($totalBalanceLevelTwoOtherRevenue),
                    ];

                    $levelOneData[] = $levelTwoData;

                    // Accumulate level one totals
                    $totalBeginLevelOneOtherRevenue += $totalBeginLevelTwoOtherRevenue;
                    $totalMoveLevelOneOtherRevenue += $totalMoveLevelTwoOtherRevenue;
                    $totalBalanceLevelOneOtherRevenue += $totalBalanceLevelTwoOtherRevenue;
                }
            }

            $dataOtherRevenue[] = $levelOneData;

            // Accumulate grand totals
            $grandTotalBalanceOtherRevenue += $totalBalanceLevelOneOtherRevenue;
        }

        $dataExpense = [];
        $dataExpense[] = [
            "EXPENSES",
            "",
            ""
        ];

        $grandTotalBalanceExpense = formatNumber(0);
        foreach ($this->levelOneExpenses as $levelOneExpense) {
            $levelOneData = [];

            // Calculate level one totals
            $totalBeginLevelOneExpense = formatNumber(0);
            $totalMoveLevelOneExpense = formatNumber(0);
            $totalBalanceLevelOneExpense = formatNumber(0);

            foreach ($this->levelTwoExpenses as $levelTwoExpense) {
                if ($levelOneExpense->name == $levelTwoExpense->name) {
                    $levelTwoData = [];

                    // Calculate level two totals
                    $totalBeginLevelTwoExpense = formatNumber(0);
                    $totalMoveLevelTwoExpense = formatNumber(0);
                    $totalBalanceLevelTwoExpense = formatNumber(0);

                    // Find corresponding beginning balance
                    if ($levelOneExpense->name == $levelTwoExpense->name) {
                        foreach ($this->sumBeginExpenses as $sumBeginExpense) {

                            if ($sumBeginExpense->account_no == $levelTwoExpense->account_no) {
                                $totalBeginLevelTwoExpense = $sumBeginExpense->debit - $sumBeginExpense->credit;
                                $totalBeginLevelOneExpense += $totalBeginLevelTwoExpense;
                            }
                        }

                        foreach ($this->sumMoveExpenses as $sumMoveExpense) {
                            if ($sumMoveExpense->account_no == $levelTwoExpense->account_no) {
                                $totalMoveLevelTwoExpense = $sumMoveExpense->debit - $sumMoveExpense->credit;
                                $totalMoveLevelOneExpense += $totalMoveLevelTwoExpense;
                            }
                        }
                    }

                    // Calculate level two balance
                    $totalBalanceLevelTwoExpense = $totalBeginLevelTwoExpense + $totalMoveLevelTwoExpense;

                    $levelTwoData = [
                        $levelTwoExpense->account_no,
                        $levelTwoExpense->account_name,
                        formatNumber($totalBalanceLevelTwoExpense),
                    ];

                    $levelOneData[] = $levelTwoData;

                    // Accumulate level one totals
                    $totalBeginLevelOneExpense += $totalBeginLevelTwoExpense;
                    $totalMoveLevelOneExpense += $totalMoveLevelTwoExpense;
                    $totalBalanceLevelOneExpense += $totalBalanceLevelTwoExpense;
                }
            }

            $dataExpense[] = $levelOneData;

            // Accumulate grand totals
            $grandTotalBalanceExpense += $totalBalanceLevelOneExpense;
        }

        $totalOtherIncAndExp = $grandTotalBalanceOtherRevenue - $grandTotalBalanceExpense;

        $dataTotalOtherIncAndExp = [];
        $dataTotalOtherIncAndExp[] = [
            "OTHER INCOME AND EXPENSES",
            "",
            formatNumber($totalOtherIncAndExp)
        ];

        $totalNetProfitBeforeTax = $totalGrossProfit + $totalOtherIncAndExp;
        $dataNetProfitBeforeTax = [];
        $dataNetProfitBeforeTax[] = [
            "NET PROFIT",
            "",
            formatNumber($totalNetProfitBeforeTax)
        ];

        $data = [...$dataRevenue, ...$dataCos, ...$dataGrossProfit, ...$dataOtherRevenue, ...$dataExpense, ...$dataTotalOtherIncAndExp, ...$dataNetProfitBeforeTax];

        return collect($data);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
