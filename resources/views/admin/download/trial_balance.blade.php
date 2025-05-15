<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trial Balance {{ $month }}</title>

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
    <h1>Trial Balance {{ $month }} {{ $year }}</h1>
    <div class="card">
        <div class="card-body">

            <table border="1" width="100%">
                <thead>
                    <tr>
                        <th>Nama Account</th>
                        <th>Balance Debit</th>
                        <th>Balance Credit</th>
                    </tr>
                </thead>
                <?php
                $grandTotalDebit = 0;
                $grandTotalCredit = 0;
                ?>
                @foreach ($dataAccount ?? [] as $dataAcc)
                    <?php
                    $totalBalance = 0;
                    ?>

                    @foreach ($dataBegin as $dataBeg)
                        @if ($dataBeg->account_name == $dataAcc->account_name)
                            <?php
                            $totalBalance += $dataBeg->total_debit - $dataBeg->total_credit;
                            ?>
                        @endif
                    @endforeach

                    @foreach ($dataReport as $dataRep)
                        @if ($dataRep->account_name == $dataAcc->account_name)
                            <?php
                            $totalBalance += $dataRep->debit - $dataRep->credit;
                            ?>
                        @endif
                    @endforeach

                    <?php
                    if ($totalBalance < 0) {
                        $totalCredit = -$totalBalance;
                        $totalDebit = 0;
                        $grandTotalCredit += $totalCredit;
                    } else {
                        $totalCredit = 0;
                        $totalDebit = $totalBalance;
                        $grandTotalDebit += $totalDebit;
                    }
                    ?>
                    <tr>
                        <td>{{ $dataAcc->account_no . ': ' . $dataAcc->account_name }}</td>
                        <td>@currency($totalDebit)</td>
                        <td>@currency($totalCredit)</td>
                    </tr>
                @endforeach
                <tr>
                    <th>Total</th>
                    <th>@currency($grandTotalDebit)</th>
                    <th>@currency($grandTotalCredit)</th>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
