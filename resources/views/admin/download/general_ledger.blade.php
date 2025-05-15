<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>General Ledger {{ $month }}</title>

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
    <h1>General Ledger {{ $month }} {{ $year }}</h1>
    @foreach ($dataAccount as $dataAcc)
        <div class="card">
            <div class="card-body">

                <table width="100%" style="margin-bottom: 8px; border: 1px solid">
                    <tbody>
                        <tr style="background-color:lightgrey">
                            <th>Nama Account</th>
                            <th>{{ $dataAcc->account_no }}</th>
                            <th colspan="4">{{ $dataAcc->account_name }}</th>
                        </tr>

                        <tr>
                            <th>Date</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Total Debit</th>
                            <th>Total Credit</th>
                            <th>Balance</th>
                        </tr>

                        <?php
                        $totalBalance = 0;
                        $totalDebit = 0;
                        $totalCredit = 0;
                        ?>

                        @forelse ($dataBegin as $dataBeg)
                            @if ($dataBeg->account_name == $dataAcc->account_name)
                                <?php
                                $totalDebitOpening = $dataBeg->total_debit;
                                $totalCreditOpening = $dataBeg->total_credit;
                                $totalBalanceOpening = $totalDebitOpening - $totalCreditOpening;
                                $totalBalance += $totalDebitOpening - $totalCreditOpening;
                                ?>
                                <tr>
                                    <th colspan="3">Opening Balance</th>
                                    <th>@currency($totalDebitOpening)</th>
                                    <th>@currency($totalCreditOpening)</th>
                                    @if ($totalBalanceOpening < 0)
                                        <th>(@currency(-$totalBalanceOpening))</th>
                                    @else
                                        <th>@currency($totalBalanceOpening)</th>
                                    @endif
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <th colspan="3">Opening Balance</th>
                                <th>@currency(0)</th>
                                <th>@currency(0)</th>
                                <th>@currency(0)</th>
                            </tr>
                        @endforelse

                        @foreach ($dataReport as $dataRep)
                            @if ($dataRep->account_name == $dataAcc->account_name)
                                <?php
                                $totalDebit += $dataRep->debit;
                                $totalCredit += $dataRep->credit;
                                $totalBalance += $dataRep->debit - $dataRep->credit;
                                
                                ?>
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($dataRep->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td>@currency($dataRep->debit)</td>
                                    <td>@currency($dataRep->credit)</td>
                                    @if ($dataRep->debit > $dataRep->credit)
                                        <td>@currency($dataRep->debit - $dataRep->credit)</td>
                                    @else
                                        <td>@currency(0)</td>
                                    @endif
                                    @if ($dataRep->credit > $dataRep->debit)
                                        <td>@currency($dataRep->credit - $dataRep->debit)</td>
                                    @else
                                        <td>@currency(0)</td>
                                    @endif
                                    @if ($totalBalance < 0)
                                        <td>(@currency(-$totalBalance))</td>
                                    @else
                                        <td>@currency($totalBalance)</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach

                        <tr>
                            <th colspan="3">Closing Balance</th>
                            <th>@currency($totalDebit)</th>
                            <th>@currency($totalCredit)</th>
                            @if ($totalBalance < 0)
                                <th>(@currency(-$totalBalance))</th>
                            @else
                                <th>@currency($totalBalance)</th>
                            @endif
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    @endforeach

</body>

</html>
