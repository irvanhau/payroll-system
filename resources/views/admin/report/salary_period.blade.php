@extends('admin.layouts.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Employee Salary Period</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    {{-- FILTER BY MONTH --}}
                    <form class="mb-4" method="get" action="{{ route('salary-period-report.index') }}">
                        <div class="row">
                            <div class="col-2">
                                <input type="month" class="form-control" name="date" value="{{ $date }}">
                            </div>

                            <div class="col-2">
                                <input type="submit" class="btn btn-info" value="Search"></input>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table class="table text-center table-sm table-bordered dt-responsive nowrap"
                                style="border-collapse: separate; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Salary Amount</th>
                                        <th>Allowance</th>
                                        <th>Deduction</th>
                                        <th>Net Salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->employee->name }}</td>
                                            <td>@currency($item->total_salary)</td>
                                            <td>@currency($item->total_salary_allowance)</td>
                                            <td>@currency($item->total_salary_deduction)</td>
                                            <td>@currency($item->net_salary_amount)</td>
                                            <td>
                                                <button class="btn btn-info btn-sm w-100"
                                                    onclick="showJurnal('{{ $item->id }}','employee')"
                                                    title="View Jurnal">View
                                                    Jurnal</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>

    <div class="modal fade" id="modal-jurnal">
        <div class="modal-dialog modal-lg bs-example-modal-large">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="header"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h3><b>Jurnal<b></h3> <br>
                    <table id="tabelJurnal" class="table table-bordered dt-responsive nowrap font-weight-normal">
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
