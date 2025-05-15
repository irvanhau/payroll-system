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
                        <a onclick="addDeduction()" class="my-2 btn btn-success">Add Deduction</a>
                        <a href="{{ route('employee.index') }}" class="btn btn-danger"><i
                                class="glyphicon glyphicon-circle-arrow-right"></i> Back</a>
                        <div class="box-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="25%">Deduction</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee->employeeSalaryDeductions as $item)
                                        <tr>
                                            <td>{{ $item->deduction->name }}</td>
                                            <td>@currency($item->amount)</td>
                                            <td>
                                                <a onclick="deleteDeduction('{{ $item->deduction->id }}', '{{ $employee->id }}')"
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

    <div class="modal fade" id="modal-add-deduction">
        <div class="modal-dialog modal-lg bs-example-modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Deduction</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="javascript:void(0)" id="postFormAddDeduction">
                        <input type="hidden" id="employee_id" value="{{ $employee->id }}">

                        <div class="mb-3 row">
                            <label for="deduction_id" class="col-sm-3 col-form-label">Deduction</label>
                            <select class="col-sm-9 form-control" name="deduction_id" id="deduction_id">
                                <option value="0">Select Deduction</option>
                                @foreach ($deductions as $deduction)
                                    <option value="{{ $deduction->id }}">{{ $deduction->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success waves-effect waves-light">Add Deduction</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('addon-script')
        <script>
            function addDeduction() {
                $("#modal-add-deduction").modal("toggle");
            }

            $("#postFormAddDeduction").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this)
                var id = $("#employee_id").val()
                $.ajax({
                    data: formData,
                    url: `/employee/${id}/deduction`,
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

            function deleteDeduction(deduction_id, employee_id) {
                $.ajax({
                    url: `/employee/${employee_id}/deduction/${deduction_id}`,
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
