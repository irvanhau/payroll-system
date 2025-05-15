<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profit Loss {{ $month }}</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: small;
        }

        tbody tr th {

            text-align: left;
        }

        tbody tr td {
            text-align: left;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: small;
        }

        .gray {
            background-color: lightgray
        }
    </style>
</head>

<body>
    <h1>Profit Loss {{ $month }} {{ $year }}</h1>
    <table width="100%" border="1">
        {{-- REVENUE TABLE BODY --}}
        <tbody>
            <tr>
                <td>Account No</td>
                <td>Account Name</td>
                <td>Saldo Akhir</td>
            </tr>
            <tr>
                <th colspan="3">INCOME</th>
            </tr>
            @php
                $grandTotalBeginRevenue = 0;
                $grandTotalMoveRevenue = 0;
                $grandTotalBalanceRevenue = 0;
            @endphp
            @foreach ($levelOneRevenues as $levelOneRevenue)
                <?php
                $totalBeginLevelOneRevenue = 0;
                $totalMoveLevelOneRevenue = 0;
                $totalBalanceLevelOneRevenue = 0;
                ?>
                @foreach ($levelTwoRevenues as $levelTwoRevenue)
                    <?php
                    $totalBeginLevelTwoRevenue = 0;
                    $totalMoveLevelTwoRevenue = 0;
                    $totalBalanceLevelTwoRevenue = 0;
                    ?>
                    @if ($levelOneRevenue->name == $levelTwoRevenue->name)
                        <tr>
                            <td style="padding-left: 3rem">
                                {{ $levelTwoRevenue->account_no }}</td>
                            <td>{{ $levelTwoRevenue->account_name }}</td>
                            @foreach ($sumBeginRevenues as $sumBeginRevenue)
                                @if ($sumBeginRevenue->account_no == $levelTwoRevenue->account_no)
                                    @php
                                        $totalBeginLevelTwoRevenue = $sumBeginRevenue->credit - $sumBeginRevenue->debit;
                                        $totalBeginLevelOneRevenue += $totalBeginLevelTwoRevenue;
                                    @endphp
                                @endif
                            @endforeach
                            @foreach ($sumMoveRevenues as $sumMoveRevenue)
                                @if ($sumMoveRevenue->account_no == $levelTwoRevenue->account_no)
                                    @php
                                        $totalMoveLevelTwoRevenue = $sumMoveRevenue->credit - $sumMoveRevenue->debit;
                                        $totalMoveLevelOneRevenue += $totalMoveLevelTwoRevenue;
                                    @endphp
                                @endif
                            @endforeach
                            <td>
                                <?php
                                $totalBalanceLevelTwoRevenue = $totalBeginLevelTwoRevenue + $totalMoveLevelTwoRevenue;
                                $totalBalanceLevelOneRevenue += $totalBalanceLevelTwoRevenue;
                                ?>
                                @if ($totalBalanceLevelTwoRevenue < 0)
                                    <?php $minusBalanceLevelTwoRevenue = 0 - $totalBalanceLevelTwoRevenue; ?>
                                    (@balance($minusBalanceLevelTwoRevenue))
                                @elseif ($totalBalanceLevelTwoRevenue == 0)
                                @else
                                    @balance($totalBalanceLevelTwoRevenue)
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                @php
                    $grandTotalBalanceRevenue += $totalBalanceLevelOneRevenue;
                @endphp
            @endforeach
        </tbody>

        {{-- COST OF SALES TABLE BODY --}}
        <tbody>
            <tr>
                <th colspan="3">COST OF SALES
                </th>
            </tr>
            @php
                $grandTotalBeginCos = 0;
                $grandTotalMoveCos = 0;
                $grandTotalBalanceCos = 0;
            @endphp
            @foreach ($levelOneCoses as $levelOneCos)
                <?php
                $totalBeginLevelOneCos = 0;
                $totalMoveLevelOneCos = 0;
                $totalBalanceLevelOneCos = 0;
                ?>
                @foreach ($levelTwoCoses as $levelTwoCos)
                    <?php
                    $totalBeginLevelTwoCos = 0;
                    $totalMoveLevelTwoCos = 0;
                    $totalBalanceLevelTwoCos = 0;
                    ?>
                    @if ($levelOneCos->name == $levelTwoCos->name)
                        <tr>
                            <td style="padding-left: 3rem">
                                {{ $levelTwoCos->account_no }}</td>
                            <td>{{ $levelTwoCos->account_name }}</td>
                            @foreach ($sumBeginCoses as $sumBeginCos)
                                @if ($sumBeginCos->account_no == $levelTwoCos->account_no)
                                    @php
                                        $totalBeginLevelTwoCos = $sumBeginCos->debit - $sumBeginCos->credit;
                                        $totalBeginLevelOneCos += $totalBeginLevelTwoCos;
                                    @endphp
                                @endif
                            @endforeach
                            @foreach ($sumMoveCoses as $sumMoveCos)
                                @if ($sumMoveCos->account_no == $levelTwoCos->account_no)
                                    @php
                                        $totalMoveLevelTwoCos = $sumMoveCos->debit - $sumMoveCos->credit;
                                        $totalMoveLevelOneCos += $totalMoveLevelTwoCos;
                                    @endphp
                                @endif
                            @endforeach
                            <td>
                                <?php
                                $totalBalanceLevelTwoCos = $totalBeginLevelTwoCos + $totalMoveLevelTwoCos;
                                $totalBalanceLevelOneCos += $totalBalanceLevelTwoCos;
                                ?>
                                @if ($totalBalanceLevelTwoCos < 0)
                                    <?php $minusBalanceLevelTwoCos = 0 - $totalBalanceLevelTwoCos; ?>
                                    (@balance($minusBalanceLevelTwoCos))
                                @elseif ($totalBalanceLevelTwoCos == 0)
                                @else
                                    @balance($totalBalanceLevelTwoCos)
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                @php
                    $grandTotalBalanceCos += $totalBalanceLevelOneCos;
                @endphp
            @endforeach
        </tbody>

        {{-- GROSS PROFIT BODY --}}
        <tbody>
            <tr style="background-color: lightgray">
                <th colspan="2" style="padding-left: 3rem">
                    GROSS PROFIT
                </th>
                <?php
                $grossProfit = $grandTotalBalanceRevenue - $grandTotalBalanceCos;
                ?>
                <th>
                    @if ($grossProfit < 0)
                        <?php $minusGrossProfit = 0 - $grossProfit; ?>
                        (@balance($minusGrossProfit))
                    @else
                        @balance($grossProfit)
                    @endif
                </th>
            </tr>
        </tbody>

        {{-- OTHER INCOME TABLE BODY --}}
        <tbody>
            <tr>
                <th colspan="3">OTHER INCOME</th>
            </tr>
            @php
                $grandTotalBeginOtherRevenue = 0;
                $grandTotalMoveOtherRevenue = 0;
                $grandTotalBalanceOtherRevenue = 0;
            @endphp
            @foreach ($levelOneOtherRevenues as $levelOneOtherRevenue)
                <?php
                $totalBeginLevelOneOtherRevenue = 0;
                $totalMoveLevelOneOtherRevenue = 0;
                $totalBalanceLevelOneOtherRevenue = 0;
                ?>
                @foreach ($levelTwoOtherRevenues as $levelTwoOtherRevenue)
                    <?php
                    $totalBeginLevelTwoOtherRevenue = 0;
                    $totalMoveLevelTwoOtherRevenue = 0;
                    $totalBalanceLevelTwoOtherRevenue = 0;
                    ?>
                    @if ($levelOneOtherRevenue->name == $levelTwoOtherRevenue->name)
                        <tr>
                            <td style="padding-left: 3rem">
                                {{ $levelTwoOtherRevenue->account_no }}</td>
                            <td>{{ $levelTwoOtherRevenue->account_name }}</td>
                            @foreach ($sumBeginOtherRevenues as $sumBeginOtherRevenue)
                                @if ($sumBeginOtherRevenue->account_no == $levelTwoOtherRevenue->account_no)
                                    @php
                                        $totalBeginLevelTwoOtherRevenue =
                                            $sumBeginOtherRevenue->credit - $sumBeginOtherRevenue->debit;
                                        $totalBeginLevelOneOtherRevenue += $totalBeginLevelTwoOtherRevenue;
                                    @endphp
                                @endif
                            @endforeach
                            @foreach ($sumMoveOtherRevenues as $sumMoveOtherRevenue)
                                @if ($sumMoveOtherRevenue->account_no == $levelTwoOtherRevenue->account_no)
                                    @php
                                        $totalMoveLevelTwoOtherRevenue =
                                            $sumMoveOtherRevenue->credit - $sumMoveOtherRevenue->debit;
                                        $totalMoveLevelOneOtherRevenue += $totalMoveLevelTwoOtherRevenue;
                                    @endphp
                                @endif
                            @endforeach
                            <td>
                                <?php
                                $totalBalanceLevelTwoOtherRevenue = $totalBeginLevelTwoOtherRevenue + $totalMoveLevelTwoOtherRevenue;
                                $totalBalanceLevelOneOtherRevenue += $totalBalanceLevelTwoOtherRevenue;
                                ?>
                                @if ($totalBalanceLevelTwoOtherRevenue < 0)
                                    <?php $minusBalanceLevelTwoOtherRevenue = 0 - $totalBalanceLevelTwoOtherRevenue; ?>
                                    (@balance($minusBalanceLevelTwoOtherRevenue))
                                @elseif ($totalBalanceLevelTwoOtherRevenue == 0)
                                @else
                                    @balance($totalBalanceLevelTwoOtherRevenue)
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                @php
                    $grandTotalBalanceOtherRevenue += $totalBalanceLevelOneOtherRevenue;
                @endphp
            @endforeach
        </tbody>

        {{-- EXPENSE TABLE BODY --}}
        <tbody>
            <tr>
                <th colspan="3">EXPENSES</th>
            </tr>
            @php
                $grandTotalBeginExpense = 0;
                $grandTotalMoveExpense = 0;
                $grandTotalBalanceExpense = 0;
            @endphp
            @foreach ($levelOneExpenses as $levelOneExpense)
                <?php
                $totalBeginLevelOneExpense = 0;
                $totalMoveLevelOneExpense = 0;
                $totalBalanceLevelOneExpense = 0;
                ?>
                @foreach ($levelTwoExpenses as $levelTwoExpense)
                    <?php
                    $totalBeginLevelTwoExpense = 0;
                    $totalMoveLevelTwoExpense = 0;
                    $totalBalanceLevelTwoExpense = 0;
                    ?>
                    @if ($levelOneExpense->name == $levelTwoExpense->name)
                        <tr>
                            <td style="padding-left: 3rem">
                                {{ $levelTwoExpense->account_no }}</td>
                            <td>{{ $levelTwoExpense->account_name }}</td>
                            @foreach ($sumBeginExpenses as $sumBeginExpense)
                                @if ($sumBeginExpense->account_no == $levelTwoExpense->account_no)
                                    @php
                                        $totalBeginLevelTwoExpense = $sumBeginExpense->debit - $sumBeginExpense->credit;
                                        $totalBeginLevelOneExpense += $totalBeginLevelTwoExpense;
                                    @endphp
                                @endif
                            @endforeach
                            @foreach ($sumMoveExpenses as $sumMoveExpense)
                                @if ($sumMoveExpense->account_no == $levelTwoExpense->account_no)
                                    @php
                                        $totalMoveLevelTwoExpense = $sumMoveExpense->debit - $sumMoveExpense->credit;
                                        $totalMoveLevelOneExpense += $totalMoveLevelTwoExpense;
                                    @endphp
                                @endif
                            @endforeach
                            <td>
                                <?php
                                $totalBalanceLevelTwoExpense = $totalBeginLevelTwoExpense + $totalMoveLevelTwoExpense;
                                $totalBalanceLevelOneExpense += $totalBalanceLevelTwoExpense;
                                ?>
                                @if ($totalBalanceLevelTwoExpense < 0)
                                    <?php $minusBalanceLevelTwoExpense = 0 - $totalBalanceLevelTwoExpense; ?>
                                    (@balance($minusBalanceLevelTwoExpense))
                                @elseif ($totalBalanceLevelTwoExpense == 0)
                                @else
                                    @balance($totalBalanceLevelTwoExpense)
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                @php
                    $grandTotalBalanceExpense += $totalBalanceLevelOneExpense;
                @endphp
            @endforeach
        </tbody>

        {{-- OTHER INCOME AND EXPENSES BODY --}}
        <tbody>
            <tr style="background-color: lightgrey">
                <th colspan="2" style="padding-left: 3rem">
                    OTHER INCOME AND EXPENSES
                </th>
                <?php
                $totalOtherIncAndExpenses = $grandTotalBalanceOtherRevenue - $grandTotalBalanceExpense;
                ?>
                <th>
                    @if ($totalOtherIncAndExpenses < 0)
                        <?php $minusNetProfit = 0 - $totalOtherIncAndExpenses; ?>
                        (@balance($minusNetProfit))
                    @else
                        @balance($totalOtherIncAndExpenses)
                    @endif
                </th>
            </tr>
        </tbody>

        {{-- NET PROFIT BEFORE TAX BODY --}}
        <tbody>
            <tr style="background-color: lightgrey">
                <th colspan="2" style="padding-left: 3rem">
                    NET PROFIT
                </th>
                <?php
                $netProfitBeforeTax = $grossProfit + $totalOtherIncAndExpenses;
                ?>
                <th>
                    @if ($netProfitBeforeTax < 0)
                        <?php $minusNetProfit = 0 - $netProfitBeforeTax; ?>
                        (@balance($minusNetProfit))
                    @else
                        @balance($netProfitBeforeTax)
                    @endif
                </th>
            </tr>
        </tbody>

    </table>

</body>

</html>
