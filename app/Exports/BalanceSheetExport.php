<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BalanceSheetExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $levelOneAssets;
    protected $levelTwoAssets;
    protected $sumBeginAssets;
    protected $sumMoveAssets;

    protected $levelOneLiaEqus;
    protected $levelTwoLiaEqus;
    protected $sumBeginLiaEqus;
    protected $sumMoveLiaEqus;

    public function __construct($month, $year)
    {
        $this->levelOneAssets = ChartOfAccount::GetLevelOne('A');
        $this->levelTwoAssets = ChartOfAccount::GetLevelTwo('A');
        $this->sumBeginAssets = ChartOfAccount::GetSumBegin($month - 1, $year, 'A');
        $this->sumMoveAssets =  ChartOfAccount::GetSumMove($month, $year, 'A');

        $this->levelOneLiaEqus = ChartOfAccount::GetLevelOne('L', 'E');
        $this->levelTwoLiaEqus = ChartOfAccount::GetLevelTwo('L', 'E');
        $this->sumBeginLiaEqus = ChartOfAccount::GetSumBegin($month - 1, $year, 'L', 'E');
        $this->sumMoveLiaEqus =  ChartOfAccount::GetSumMove($month, $year, 'L', 'E');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $dataAsset = [];

        $grandTotalBalanceAsset = 0;
        foreach ($this->levelOneAssets as $levelOneAsset) {
            $levelOneData = [];

            // Calculate level one totals
            $totalBeginLevelOneAsset = 0;
            $totalMoveLevelOneAsset = 0;
            $totalBalanceLevelOneAsset = 0;

            foreach ($this->levelTwoAssets as $levelTwoAsset) {
                if ($levelOneAsset->name == $levelTwoAsset->name) {
                    $levelTwoData = [];

                    // Calculate level two totals
                    $totalBeginLevelTwoAsset = 0;
                    $totalMoveLevelTwoAsset = 0;
                    $totalBalanceLevelTwoAsset = 0;

                    // Find corresponding beginning balance
                    if ($levelOneAsset->name == $levelTwoAsset->name) {
                        foreach ($this->sumBeginAssets as $sumBeginAsset) {

                            if ($sumBeginAsset->account_no == $levelTwoAsset->account_no) {
                                $totalBeginLevelTwoAsset = $sumBeginAsset->debit - $sumBeginAsset->credit;
                                $totalBeginLevelOneAsset += $totalBeginLevelTwoAsset;
                            }
                        }

                        foreach ($this->sumMoveAssets as $sumMoveAsset) {
                            if ($sumMoveAsset->account_no == $levelTwoAsset->account_no) {
                                $totalMoveLevelTwoAsset = $sumMoveAsset->debit - $sumMoveAsset->credit;
                                $totalMoveLevelOneAsset += $totalMoveLevelTwoAsset;
                            }
                        }
                    }

                    // Calculate level two balance
                    $totalBalanceLevelTwoAsset = $totalBeginLevelTwoAsset + $totalMoveLevelTwoAsset;
                    $formattedTotalBeginLevelTwo = $totalBeginLevelTwoAsset < 0 ? "(" . 0 - $totalBeginLevelTwoAsset . ")" : $totalBeginLevelTwoAsset;
                    $formattedTotalMoveLevelTwo = $totalMoveLevelTwoAsset < 0 ? "(" . 0 - $totalMoveLevelTwoAsset . ")" : $totalMoveLevelTwoAsset;
                    $formattedTotalBalanceLevelTwo = $totalBalanceLevelTwoAsset < 0 ? "(" . 0 - $totalBalanceLevelTwoAsset . ")" : $totalBalanceLevelTwoAsset;

                    $levelTwoData = [
                        $levelTwoAsset->account_no,
                        $levelTwoAsset->account_name,
                        $formattedTotalBeginLevelTwo,
                        $formattedTotalMoveLevelTwo,
                        $formattedTotalBalanceLevelTwo,
                    ];

                    $levelOneData[] = $levelTwoData;

                    // Accumulate level one totals
                    $totalBeginLevelOneAsset += $totalBeginLevelTwoAsset;
                    $totalMoveLevelOneAsset += $totalMoveLevelTwoAsset;
                    $totalBalanceLevelOneAsset += $totalBalanceLevelTwoAsset;
                }
            }

            $formattedTotalBalanceLevelOne = $totalBalanceLevelOneAsset < 0 ? "(" . 0 - $totalBalanceLevelOneAsset . ")" : $totalBalanceLevelOneAsset;

            $levelOneData[] = [
                '', // Empty for Account No and Account Name in total row
                'Total ' . $levelOneAsset->name,
                '',
                '',
                $formattedTotalBalanceLevelOne,
            ];

            $dataAsset[] = $levelOneData;

            // Accumulate grand totals
            $grandTotalBalanceAsset += $totalBalanceLevelOneAsset;
        }

        $formattedGrandTotalBalance = $grandTotalBalanceAsset < 0 ? "(" . 0 - $grandTotalBalanceAsset . ")" : $grandTotalBalanceAsset;

        $dataAsset[] = [
            '',
            'Total Assets',
            '',
            '',
            $formattedGrandTotalBalance,
        ];

        $dataLiaEqu = [];
        $dataLiaEqu[] = [
            "LIABILITIES AND EQUITY",
            "",
            "",
            "",
            ""
        ];

        $grandTotalBalanceLiaEqu = 0;
        foreach ($this->levelOneLiaEqus as $levelOneLiaEqu) {
            $levelOneData = [];

            // Calculate level one totals
            $totalBeginLevelOneLiaEqu = 0;
            $totalMoveLevelOneLiaEqu = 0;
            $totalBalanceLevelOneLiaEqu = 0;

            foreach ($this->levelTwoLiaEqus as $levelTwoLiaEqu) {
                if ($levelOneLiaEqu->name == $levelTwoLiaEqu->name) {
                    $levelTwoData = [];

                    // Calculate level two totals
                    $totalBeginLevelTwoLiaEqu = 0;
                    $totalMoveLevelTwoLiaEqu = 0;
                    $totalBalanceLevelTwoLiaEqu = 0;

                    // Find corresponding beginning balance
                    if ($levelOneLiaEqu->name == $levelTwoLiaEqu->name) {
                        foreach ($this->sumBeginLiaEqus as $sumBeginLiaEqu) {

                            if ($sumBeginLiaEqu->account_no == $levelTwoLiaEqu->account_no) {
                                $totalBeginLevelTwoLiaEqu = $sumBeginLiaEqu->debit - $sumBeginLiaEqu->credit;
                                $totalBeginLevelOneLiaEqu += $totalBeginLevelTwoLiaEqu;
                            }
                        }

                        foreach ($this->sumMoveLiaEqus as $sumMoveLiaEqu) {
                            if ($sumMoveLiaEqu->account_no == $levelTwoLiaEqu->account_no) {
                                $totalMoveLevelTwoLiaEqu = $sumMoveLiaEqu->debit - $sumMoveLiaEqu->credit;
                                $totalMoveLevelOneLiaEqu += $totalMoveLevelTwoLiaEqu;
                            }
                        }
                    }

                    // Calculate level two balance
                    $totalBalanceLevelTwoLiaEqu = $totalBeginLevelTwoLiaEqu + $totalMoveLevelTwoLiaEqu;
                    $formattedTotalBeginLevelTwo = $totalBeginLevelTwoLiaEqu < 0 ? "(" . 0 - $totalBeginLevelTwoLiaEqu . ")" : $totalBeginLevelTwoLiaEqu;
                    $formattedTotalMoveLevelTwo = $totalMoveLevelTwoLiaEqu < 0 ? "(" . 0 - $totalMoveLevelTwoLiaEqu . ")" : $totalMoveLevelTwoLiaEqu;
                    $formattedTotalBalanceLevelTwo = $totalBalanceLevelTwoLiaEqu < 0 ? "(" . 0 - $totalBalanceLevelTwoLiaEqu . ")" : $totalBalanceLevelTwoLiaEqu;

                    $levelTwoData = [
                        $levelTwoLiaEqu->account_no,
                        $levelTwoLiaEqu->account_name,
                        $formattedTotalBeginLevelTwo,
                        $formattedTotalMoveLevelTwo,
                        $formattedTotalBalanceLevelTwo,
                    ];

                    $levelOneData[] = $levelTwoData;

                    // Accumulate level one totals
                    $totalBeginLevelOneLiaEqu += $totalBeginLevelTwoLiaEqu;
                    $totalMoveLevelOneLiaEqu += $totalMoveLevelTwoLiaEqu;
                    $totalBalanceLevelOneLiaEqu += $totalBalanceLevelTwoLiaEqu;
                }
            }

            $formattedTotalBalanceLevelOne = $totalBalanceLevelOneLiaEqu < 0 ? "(" . 0 - $totalBalanceLevelOneLiaEqu . ")" : $totalBalanceLevelOneLiaEqu;

            $levelOneData[] = [
                '', // Empty for Account No and Account Name in total row
                'Total ' . $levelOneLiaEqu->name,
                '',
                '',
                $formattedTotalBalanceLevelOne,
            ];

            $dataLiaEqu[] = $levelOneData;

            // Accumulate grand totals
            $grandTotalBalanceLiaEqu += $totalBalanceLevelOneLiaEqu;
        }

        $formattedGrandTotalBalance = $grandTotalBalanceLiaEqu < 0 ? "(" . 0 - $grandTotalBalanceLiaEqu . ")" : $grandTotalBalanceLiaEqu;

        $dataLiaEqu[] = [
            '',
            'Total Liabilities and Equity',
            '',
            '',
            $formattedGrandTotalBalance,
        ];

        $data = [...$dataAsset, ...$dataLiaEqu];

        return collect($data);
    }

    public function headings(): array
    {
        $dataHeading = [];
        $dataHeading[] = [
            'ASSETS',
            '',
            '',
            '',
            '',
        ];

        $dataHeading[] = [
            'Account No',
            'Account Name',
            'Saldo Awal',
            'Pergerakan',
            'Saldo Akhir',
        ];

        return $dataHeading;
    }

    private $styles = [
        'heading' => [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ],
        'header' => [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'DDDDDD'],
            ],
        ],
        'levelOne' => [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ],
        'total' => [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ],
    ];
}
