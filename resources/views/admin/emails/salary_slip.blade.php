<!DOCTYPE html>
<html>

<head>
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>SLIP GAJI</h2>
            <p>Periode: {{ $salaryDetails['month_year'] }}</p>
        </div>

        <div class="details">
            <p>Nama Karyawan: <strong>{{ $employee['name'] }}</strong></p>
            <p>Jabatan: {{ $employee['occupation'] }}</p>
        </div>

        <table>
            <tr>
                <th>Komponen</th>
                <th>Jumlah (Rp)</th>
            </tr>
            <tr>
                <td>Gaji Pokok</td>
                <td>{{ number_format($salaryDetails['basic_salary'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan</td>
                <td>{{ number_format($salaryDetails['allowance'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Potongan</td>
                <td>{{ number_format($salaryDetails['deduction'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Gaji</strong></td>
                <td><strong>{{ number_format($salaryDetails['net_salary'], 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        <div class="footer">
            <p>Slip gaji ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>Hubungi HRD jika ada pertanyaan atau koreksi.</p>
        </div>
    </div>
</body>

</html>
