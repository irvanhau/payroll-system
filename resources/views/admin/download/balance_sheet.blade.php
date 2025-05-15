<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balance Sheet {{ $month }}</title>

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
    <h1>Balance Sheet {{ $month }} {{ $year }}</h1>
    <table width="100%" border="1">
        {{-- ASSET TABLE BODY --}}
        <tbody>
            <tr>
                <th colspan="5" style="background-color:lightgrey">ASSETS</th>
            </tr>
            <tr>
                <td>Account No</td>
                <td>Account Name</td>
                <td>Saldo Awal</td>
                <td>Pergerakan</td>
                <td>Saldo Akhir</td>
            </tr>
            @php
                $grandTotalBeginAsset = 0;
                $grandTotalMoveAsset = 0;
                $grandTotalBalanceAsset = 0;
            @endphp
            @foreach ($levelOneAssets as $levelOneAsset)
                <?php
                $totalBeginLevelOneAsset = 0;
                $totalMoveLevelOneAsset = 0;
                $totalBalanceLevelOneAsset = 0;
                ?>
                <tr>
                    <th colspan="5" class="gray" style="padding-left: 3rem">
                        {{ $levelOneAsset->name }}
                    </th>
                </tr>
                @foreach ($levelTwoAssets as $levelTwoAsset)
                    <?php
                    $totalBeginLevelTwoAsset = 0;
                    $totalMoveLevelTwoAsset = 0;
                    $totalBalanceLevelTwoAsset = 0;
                    ?>
                    @if ($levelOneAsset->name == $levelTwoAsset->name)
                        <tr>
                            <td>
                                {{ $levelTwoAsset->account_no }}</td>
                            <td>{{ $levelTwoAsset->account_name }}</td>
                            <td>
                                @foreach ($sumBeginAssets as $sumBeginAsset)
                                    @if ($sumBeginAsset->account_no == $levelTwoAsset->account_no)
                                        @php
                                            $totalBeginLevelTwoAsset = $sumBeginAsset->debit - $sumBeginAsset->credit;
                                            $totalBeginLevelOneAsset += $totalBeginLevelTwoAsset;
                                        @endphp
                                        @if ($totalBeginLevelTwoAsset < 0)
                                            <?php $minusBeginLevelTwoAsset = 0 - $totalBeginLevelTwoAsset; ?>
                                            (@balance($minusBeginLevelTwoAsset))
                                        @else
                                            @balance($totalBeginLevelTwoAsset)
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($sumMoveAssets as $sumMoveAsset)
                                    @if ($sumMoveAsset->account_no == $levelTwoAsset->account_no)
                                        @php
                                            $totalMoveLevelTwoAsset = $sumMoveAsset->debit - $sumMoveAsset->credit;
                                            $totalMoveLevelOneAsset += $totalMoveLevelTwoAsset;
                                        @endphp
                                        @if ($totalMoveLevelTwoAsset < 0)
                                            <?php $minusMoveLevelTwoAsset = 0 - $totalMoveLevelTwoAsset; ?>
                                            (@balance($minusMoveLevelTwoAsset))
                                        @else
                                            @balance($totalMoveLevelTwoAsset)
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <?php
                                $totalBalanceLevelTwoAsset = $totalBeginLevelTwoAsset + $totalMoveLevelTwoAsset;
                                $totalBalanceLevelOneAsset += $totalBalanceLevelTwoAsset;
                                ?>
                                @if ($totalBalanceLevelTwoAsset < 0)
                                    <?php $minusBalanceLevelTwoAsset = 0 - $totalBalanceLevelTwoAsset; ?>
                                    (@balance($minusBalanceLevelTwoAsset))
                                @elseif ($totalBalanceLevelTwoAsset == 0)
                                @else
                                    @balance($totalBalanceLevelTwoAsset)
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th colspan="4" style="padding-left: 3rem">
                        Total {{ $levelOneAsset->name }}
                    </th>
                    <th>
                        @if ($totalBalanceLevelOneAsset < 0)
                            <?php $minusBalanceLevelOneAsset = 0 - $totalBalanceLevelOneAsset; ?>
                            (@balance($minusBalanceLevelOneAsset))
                        @else
                            @balance($totalBalanceLevelOneAsset)
                        @endif
                    </th>
                </tr>
                @php
                    $grandTotalBalanceAsset += $totalBalanceLevelOneAsset;
                @endphp
            @endforeach
            <tr style="background-color: lightgrey">
                <th colspan="4" style="padding-left: 3rem;background-color:lightgrey">
                    Total Assets
                </th>
                <th>@balance($grandTotalBalanceAsset)</th>
            </tr>
        </tbody>

        {{-- LIABILITIES AND EQUITY TABLE BODY --}}
        <tbody>
            <tr>
                <th colspan="5" style="background-color:lightgrey">LIABILITIES AND EQUITY
                </th>
            </tr>
            @php
                $grandTotalBeginLiaEqu = 0;
                $grandTotalMoveLiaEqu = 0;
                $grandTotalBalanceLiaEqu = 0;
            @endphp
            @foreach ($levelOneLiaEqus as $levelOneLiaEqu)
                <?php
                $totalBeginLevelOneLiaEqu = 0;
                $totalMoveLevelOneLiaEqu = 0;
                $totalBalanceLevelOneLiaEqu = 0;
                ?>
                <tr>
                    <th colspan="5" class="gray" style="padding-left: 3rem">
                        {{ $levelOneLiaEqu->name }}
                    </th>
                </tr>
                @foreach ($levelTwoLiaEqus as $levelTwoLiaEqu)
                    <?php
                    $totalBeginLevelTwoLiaEqu = 0;
                    $totalMoveLevelTwoLiaEqu = 0;
                    $totalBalanceLevelTwoLiaEqu = 0;
                    ?>
                    @if ($levelOneLiaEqu->name == $levelTwoLiaEqu->name)
                        <tr>
                            <td>
                                {{ $levelTwoLiaEqu->account_no }}</td>
                            <td>{{ $levelTwoLiaEqu->account_name }}</td>
                            <td>
                                @foreach ($sumBeginLiaEqus as $sumBeginLiaEqu)
                                    @if ($sumBeginLiaEqu->account_no == $levelTwoLiaEqu->account_no)
                                        @php
                                            $totalBeginLevelTwoLiaEqu =
                                                $sumBeginLiaEqu->credit - $sumBeginLiaEqu->debit;
                                            $totalBeginLevelOneLiaEqu += $totalBeginLevelTwoLiaEqu;
                                        @endphp
                                        @if ($totalBeginLevelTwoLiaEqu < 0)
                                            <?php $minusBeginLevelTwoLiaEqu = 0 - $totalBeginLevelTwoLiaEqu; ?>
                                            (@balance($minusBeginLevelTwoLiaEqu))
                                        @else
                                            @balance($totalBeginLevelTwoLiaEqu)
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($sumMoveLiaEqus as $sumMoveLiaEqu)
                                    @if ($sumMoveLiaEqu->account_no == $levelTwoLiaEqu->account_no)
                                        @php
                                            $totalMoveLevelTwoLiaEqu = $sumMoveLiaEqu->credit - $sumMoveLiaEqu->debit;
                                            $totalMoveLevelOneLiaEqu += $totalMoveLevelTwoLiaEqu;
                                        @endphp
                                        @if ($totalMoveLevelTwoLiaEqu < 0)
                                            <?php $minusMoveLevelTwoLiaEqu = 0 - $totalMoveLevelTwoLiaEqu; ?>
                                            (@balance($minusMoveLevelTwoLiaEqu))
                                        @else
                                            @balance($totalMoveLevelTwoLiaEqu)
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <?php
                                $totalBalanceLevelTwoLiaEqu = $totalBeginLevelTwoLiaEqu + $totalMoveLevelTwoLiaEqu;
                                $totalBalanceLevelOneLiaEqu += $totalBalanceLevelTwoLiaEqu;
                                ?>
                                @if ($totalBalanceLevelTwoLiaEqu < 0)
                                    <?php $minusBalanceLevelTwoLiaEqu = 0 - $totalBalanceLevelTwoLiaEqu; ?>
                                    (@balance($minusBalanceLevelTwoLiaEqu))
                                @elseif ($totalBalanceLevelTwoLiaEqu == 0)
                                @else
                                    @balance($totalBalanceLevelTwoLiaEqu)
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th colspan="4" style="padding-left: 3rem">
                        Total {{ $levelOneLiaEqu->name }}
                    </th>
                    <th>
                        @if ($totalBalanceLevelOneLiaEqu < 0)
                            <?php $minusBalanceLevelOneLiaEqu = 0 - $totalBalanceLevelOneLiaEqu; ?>
                            (@balance($minusBalanceLevelOneLiaEqu))
                        @else
                            @balance($totalBalanceLevelOneLiaEqu)
                        @endif
                    </th>
                </tr>
                @php
                    $grandTotalBalanceLiaEqu += $totalBalanceLevelOneLiaEqu;
                @endphp
            @endforeach
            <tr style="background-color: lightgrey">
                <th colspan="4" style="padding-left: 3rem;background-color:lightgrey">
                    Total Liabilites and Equity
                </th>
                <th>@balance($grandTotalBalanceLiaEqu)</th>
            </tr>
        </tbody>
    </table>

</body>

</html>
