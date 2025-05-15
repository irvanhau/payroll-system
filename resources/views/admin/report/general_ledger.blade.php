@extends('admin.layouts.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    {{-- FILTER BY MONTH --}}
                    <form method="GET" action="{{ route('general-ledger.index') }}" class="mb-4">
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

                    <a href="{{ route('general-ledger.pdf', ['date' => $date, 'category_id' => $category]) }}"
                        class="mb-3 btn btn-info"><i class="fas fa-file-pdf"></i>
                        Export
                        PDF</a>
                    <a href="{{ route('general-ledger.excel', ['date' => $date, 'category_id' => $category]) }}"
                        class="mb-3 btn btn-success"><i class="fas fa-file-excel"></i>
                        Export Excel</a>

                    @foreach ($dataAccount ?? [] as $dataAcc)
                        <div class="card">
                            <div class="card-body">

                                <table class="table mt-3 dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                    <td>{{ Carbon\Carbon::parse($dataRep->posted_date)->format('d/m/Y') }}
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
