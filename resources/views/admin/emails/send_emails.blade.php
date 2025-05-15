<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            border: 1px solid #dddddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777777;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SLIP GAJI BULANAN</h1>
        <p>Periode: {{ $salaryDetails['month_year'] }}</p>
    </div>

    <div class="content">
        <p>Halo {{ $employee['name'] }},</p>
        <p>Berikut adalah rincian gaji Anda untuk periode {{ $salaryDetails['month_year'] }}:</p>

        <table>
            <tr>
                <th>Keterangan</th>
                <th>Jumlah (IDR)</th>
            </tr>
            <tr>
                <td>Gaji Pokok</td>
                <td>@currency($salaryDetails['basic_salary'])</td>
            </tr>
            <tr>
                <td>Tunjangan</td>
                <td>@currency($salaryDetails['allowance'])</td>
            </tr>

            <tr class="total">
                <td>GAJI KOTOR</td>
                <td>@currency($salaryDetails['basic_salary'] + $salaryDetails['allowance'])</td>
            </tr>

            <tr class="total">
                <td>Potongan</td>
                <td>@currency($salaryDetails['deduction'])</td>
            </tr>
            <tr class="total" style="background-color: #e8f4fc;">
                <td>GAJI BERSIH</td>
                <td>@currency($salaryDetails['net_salary'])</td>
            </tr>
        </table>

        <p>Jika ada pertanyaan atau ketidaksesuaian, silakan hubungi bagian HRD.</p>
        <p>Terima kasih atas kerja keras Anda.</p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ $salaryDetails['month_year'] }} Kelompok 5. All rights reserved.</p>
    </div>
</body>

</html>
