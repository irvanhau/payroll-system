@extends('admin.layouts.master')
@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="javascript:void(0)" id="updateFormEmployee">
                        @method('PUT')

                        <input type="hidden" id="id" value="{{ $data->id }}">

                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $data->name }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ $data->email }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="gender" id="gender">
                                    <option value="0" selected>Choose Gender:</option>
                                    <option {{ $data->gender == 'Male' ? 'selected' : '' }} value="L">Male</option>
                                    <option {{ $data->gender == 'Female' ? 'selected' : '' }} value="P">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="birth_date" class="col-sm-2 col-form-label">Birth Date</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="birth_date" id="birth_date"
                                    value="{{ Carbon\Carbon::parse($data->birth_date)->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="birth_place" class="col-sm-2 col-form-label">Birth Place</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="birth_place" id="birth_place"
                                    value="{{ $data->birth_place }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="religion" class="col-sm-2 col-form-label">Religion</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="religion" id="religion">
                                    <option value="0" selected>Choose Religion:</option>
                                    <option {{ $data->religion == 'Islam' ? 'selected' : '' }} value="Islam">Islam</option>
                                    <option {{ $data->religion == 'Kristen' ? 'selected' : '' }} value="Kristen">Kristen
                                    </option>
                                    <option {{ $data->religion == 'Katolik' ? 'selected' : '' }} value="Katolik">Katolik
                                    </option>
                                    <option {{ $data->religion == 'Buddha' ? 'selected' : '' }} value="Buddha">Buddha
                                    </option>
                                    <option {{ $data->religion == 'Hindu' ? 'selected' : '' }} value="Hindu">Hindu
                                    </option>
                                    <option {{ $data->religion == 'Konghucu' ? 'selected' : '' }} value="Konghucu">Konghucu
                                    </option>
                                    <option {{ $data->religion == 'Lainnya' ? 'selected' : '' }} value="Lainnya">Lainnya
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="address" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="address" id="address" cols="30" rows="10">{{ $data->address }}</textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="npwp" class="col-sm-2 col-form-label">NPWP</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="npwp" id="npwp"
                                    value="{{ $data->npwp }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="phone_number" class="col-sm-2 col-form-label">Phone Number</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="phone_number" id="phone_number"
                                    value="{{ $data->phone_number }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="occupation" class="col-sm-2 col-form-label">Occupation</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="occupation" id="occupation"
                                    value="{{ $data->occupation }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="salary_amount" class="col-sm-2 col-form-label">Salary Amount</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="salary_amount" id="salary_amount"
                                    value="{{ $data->salary_amount }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="coa_id" class="col-sm-2 col-form-label">Chart Of Account</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="coa_id" id="coa_id">
                                    <option value="0" selected>Choose COA:</option>
                                    @foreach ($coas as $coa)
                                        <option {{ $data->coa_id == $coa->id ? 'selected' : '' }}
                                            value="{{ $coa->id }}">{{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <a href="{{ route('employee.index') }}" class="my-3 btn btn-danger">Back</a>
                        <input type="submit" class="my-3 btn btn-success" value="Upodate">
                        {{-- END ROW --}}
                </div>

                </form>

            </div>
        </div>
    </div>

    @push('addon-script')
        <script>
            $("#updateFormEmployee").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this)
                var id = $("#id").val();
                $.ajax({
                    data: formData,
                    url: "/employee/" + id,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.errors) {
                            console.log(res.errors);

                            var data = [];
                            $.each(res.errors, function(ket, value) {
                                data.push(`<li>${value}</li>`)
                            })
                            toastr.info(data, {
                                timeout: 5000
                            });
                        } else {
                            location.reload()
                            toastr.info(res.message, {
                                timeOut: 10000
                            });
                        }
                    }
                })
            })
        </script>
    @endpush
@endsection
