@extends('admin.layouts.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Product</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Employee</h4>
                            <a href="{{ route('employee.create') }}" class="mb-2 btn btn-info sm" title="Tambah Data"><i
                                    class="fas fa-plus"></i> Add Employee</a>

                            <div class="table-responsive">
                                <table id="datatables" class="table text-center table-bordered nowrap"
                                    style="border-collapse: separate; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Birth Date</th>
                                            <th>Birth Place</th>
                                            <th>Religion</th>
                                            <th>Occupation</th>
                                            <th>Salary Amount</th>
                                            <th>Total Salary Allowance</th>
                                            <th>Total Salary Deduction</th>
                                            <th>Net Salary Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->gender }}</td>
                                                <td>{{ $item->birth_date }}</td>
                                                <td>{{ $item->birth_place }}</td>
                                                <td>{{ $item->religion }}</td>
                                                <td>{{ $item->occupation }}</td>
                                                <td>@currency($item->salary_amount)</td>
                                                <td>@currency($item->total_salary_allowance)</td>
                                                <td>@currency($item->total_salary_deduction)</td>
                                                <td>@currency($item->net_salary_amount)</td>
                                                <td>{{ $item->status }}</td>
                                                <td>
                                                    <a href="{{ route('employee.edit', $item->id) }}"
                                                        class="btn btn-info btn-sm"><i class="fa fa-pencil-alt"></i></a>
                                                    <a href="{{ route('employee.show', $item->id) }}"
                                                        class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                    @if ($item->status == 'Active')
                                                        <a href="{{ route('employee.setStatus', $item->id) }}"
                                                            class="btn btn-danger btn-sm" id = 'changeStatusNo'
                                                            title="Non Aktif Employee"><i
                                                                class=" ri-delete-bin-7-line"></i></a>
                                                    @else
                                                        <a href="{{ route('employee.setStatus', $item->id) }}"
                                                            class="btn btn-success btn-sm" id = 'changeStatusAc'
                                                            title="Aktif Employee"><i class="fas fa-check-square"></i></a>
                                                    @endif
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('employee-allowance.index', $item->id) }}">
                                                        Allowance
                                                    </a>
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('employee-deduction.index', $item->id) }}">
                                                        Deduction
                                                    </a>
                                                    <a class="btn btn-info btn-sm" id = 'generateNetSalary'
                                                        href="{{ route('employee.generateNetSalary', $item->id) }}">
                                                        Generate Net Salary
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>

    @push('addon-script')
        <script>
            $('.table-responsive').on('show.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "inherit");
            });

            $('.table-responsive').on('hide.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "auto");
            })
        </script>
    @endpush
@endsection
