@extends('admin.layouts.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Name</label>
                                <div class="col-9">
                                    <input type="text" readonly name="name" id="name"
                                        value="{{ $employee->name }}" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="occupation" class="col-sm-3 col-form-label">Occupation</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly name="occupation" id="occupation"
                                        value="{{ $employee->occupation }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="religion" class="col-sm-3 col-form-label">Religion</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly name="religion" id="religion"
                                        value="{{ $employee->religion }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3 row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $employee->email }}" readonly
                                        name="email" id="email">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="gender" class="col-sm-3 col-form-label">Gender</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly name="gender" id="gender"
                                        value="{{ $employee->gender }}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="phone_number" class="col-sm-3 col-form-label">Phone Number</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly name="phone_number"
                                        id="phone_number" value="{{ $employee->phone_number }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="box col-6" style="padding: 5px 30px">
                            <div class="box-body">
                                <h4>Allowance</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Allowance</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employee->employeeSalaryAllowances as $item)
                                            <tr>
                                                <td>{{ $item->allowance->name }}</td>
                                                <td>@currency($item->amount)</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="box col-6" style="padding: 5px 30px">
                            <div class="box-body">
                                <h4>Deduction</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Deduction</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employee->employeeSalaryDeductions as $item)
                                            <tr>
                                                <td>{{ $item->deduction->name }}</td>
                                                <td>@currency($item->amount)</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3 row">
                        <label for="salary_amount" class="col-sm-3 col-form-label">Salary Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly name="salary_amount" id="salary_amount"
                                value="@currency($employee->salary_amount)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="total_salary_allowance" class="col-sm-3 col-form-label">Total Salary Allowance</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly name="total_salary_allowance"
                                id="total_salary_allowance" value="@currency($employee->total_salary_allowance)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="gross_salary" class="col-sm-3 col-form-label">Gross Salary</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly name="gross_salary" id="gross_salary"
                                value="@currency($employee->total_salary_allowance + $employee->salary_amount)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="total_salary_deduction" class="col-sm-3 col-form-label">Total Salary Deduction</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly name="total_salary_deduction"
                                id="total_salary_deduction" value="(@currency($employee->total_salary_deduction))">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="net_salary_amount" class="col-sm-3 col-form-label">Net Salary</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly name="net_salary_amount"
                                id="net_salary_amount" value="@currency($employee->net_salary_amount)">
                        </div>
                    </div>

                    <a href="{{ route('employee.index') }}" class="btn btn-danger"><i
                            class="glyphicon glyphicon-circle-arrow-right"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
