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
                    <form class="mb-4" method="post" action="javascript:void(0)" id="postFormEmployeeSalaryPeriod">
                        <div class="row">
                            <div class="col-2">
                                <input type="month" class="form-control" name="date"
                                    value="{{ Carbon\Carbon::now()->format('Y-m') }}">
                            </div>

                            <div class="col-2">
                                <select class="form-control" name="coa_cash_id" id="coa_cash_id">
                                    @foreach ($coaCash as $item)
                                        <option value="{{ $item->id }}">{{ $item->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-2">
                                <input type="submit" class="btn btn-info" value="Generate Salary"></input>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>@currency($item->salary_amount)</td>
                                            <td>@currency($item->total_salary_allowance)</td>
                                            <td>@currency($item->total_salary_deduction)</td>
                                            <td>@currency($item->net_salary_amount)</td>
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

    @push('addon-script')
        <script>
            $("#postFormEmployeeSalaryPeriod").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this)
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Generate Net Salary?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Generate!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            data: formData,
                            url: "{{ route('employee-salary-period.store') }}",
                            type: 'POST',
                            contentType: false,
                            processData: false,
                            cache: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                if (res.errors) {
                                    Swal.fire(
                                        "Warning!",
                                        res.errors,
                                        "error"
                                    );
                                } else {
                                    Swal.fire(
                                        "Success!",
                                        res.message,
                                        "success"
                                    )
                                }
                            }
                        })
                    }
                });
            })
        </script>
    @endpush
@endsection
