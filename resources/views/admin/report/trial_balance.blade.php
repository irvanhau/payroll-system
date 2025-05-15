@extends('admin.layouts.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    {{-- FILTER BY MONTH --}}
                    <form method="GET" action="{{ route('trial-balance.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="row">

                                    <div class="col-6">
                                        <input type="month" class="form-control" name="date"
                                            value="{{ $date }}">
                                    </div>

                                    <div style="padding: 0px" class="col-6">
                                        <select name="category_id" class="form-control">

                                            @foreach ($dataCategory as $item)
                                                <option {{ $category == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                            <option {{ $category == 14 ? 'selected' : '' }} value="14">All</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-8">
                                <input type="submit" class="btn btn-info" value="Filter"></input>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('trial-balance.pdf', ['date' => $date, 'category_id' => $category]) }}"
                        class="mb-3 btn btn-info"><i class="fas fa-file-pdf"></i>
                        Export
                        PDF</a>
                    <a href="{{ route('trial-balance.excel', ['date' => $date, 'category_id' => $category]) }}"
                        class="mb-3 btn btn-success"><i class="fas fa-file-excel"></i>
                        Export Excel</a>

                    <div class="card">
                        <div class="card-body">

                            <table class="table dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
    </div>

    @push('addon-script')
        <script>
            function generatePdf() {
                @this.dispatch('generate-pdf')
            }

            function exportExcel() {
                @this.dispatch('export-excel')
            }
        </script>
    @endpush

    @push('addon-style')
        <style>
            table.table.table-bordered.dt-responsive.nowrap {
                font-size: 13px
            }
        </style>
    @endpush
@endsection
