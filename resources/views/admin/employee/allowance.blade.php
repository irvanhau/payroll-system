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

                    <div class="box" style="padding: 5px 30px">
                        <a onclick="addAllowance()" class="my-2 btn btn-success">Add Allowance</a>
                        <a href="{{ route('employee.index') }}" class="btn btn-danger"><i
                                class="glyphicon glyphicon-circle-arrow-right"></i> Back</a>
                        <div class="box-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="25%">Allowance</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee->employeeSalaryAllowances as $item)
                                        <tr>
                                            <td>{{ $item->allowance->name }}</td>
                                            <td>@currency($item->amount)</td>
                                            <td>
                                                <a onclick="deleteAllowance('{{ $item->allowance->id }}', '{{ $employee->id }}')"
                                                    class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add-allowance">
        <div class="modal-dialog modal-lg bs-example-modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Allowance</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="javascript:void(0)" id="postFormAddAllowance">
                        <input type="hidden" id="employee_id" value="{{ $employee->id }}">

                        <div class="mb-3 row">
                            <label for="allowance_id" class="col-sm-3 col-form-label">Allowance</label>
                            <select class="col-sm-9 form-control" name="allowance_id" id="allowance_id">
                                <option value="0">Select Allowance</option>
                                @foreach ($allowances as $allowance)
                                    <option value="{{ $allowance->id }}">{{ $allowance->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 row">
                            <label for="amount" class="col-sm-3 col-form-label">Amount</label>
                            <input type="text" class="col-sm-9 form-control" onkeypress="return hanyaAngka(event)"
                                name="amount" id="amount">
                        </div>

                        <button type="submit" class="btn btn-success waves-effect waves-light">Add Allowance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('addon-script')
        <script>
            function addAllowance() {
                $("#modal-add-allowance").modal("toggle");
            }

            $("#postFormAddAllowance").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this)
                var id = $("#employee_id").val()
                $.ajax({
                    data: formData,
                    url: `/employee/${id}/allowance`,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.errors) {
                            var data = [];
                            $.each(res.errors, function(ket, value) {
                                data.push(`<li>${value}</li>`)
                            })
                            toastr.info(data, {
                                timeout: 5000
                            });
                        } else {
                            $('#ajaxModal').modal("hide");
                            location.reload()
                            toastr.info(res.message, {
                                timeOut: 10000
                            });
                        }
                    }
                })
            });

            function deleteAllowance(allowance_id, employee_id) {
                $.ajax({
                    url: `/employee/${employee_id}/allowance/${allowance_id}`,
                    type: 'DELETE',
                    contentType: false,
                    processData: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.errors) {
                            var data = [];
                            $.each(res.errors, function(ket, value) {
                                data.push(`<li>${value}</li>`)
                            })
                            toastr.info(data, {
                                timeout: 5000
                            });
                        } else {
                            $('#ajaxModal').modal("hide");
                            location.reload()
                            toastr.info(res.message, {
                                timeOut: 10000
                            });
                        }
                    }
                })
            }
        </script>
    @endpush
@endsection
