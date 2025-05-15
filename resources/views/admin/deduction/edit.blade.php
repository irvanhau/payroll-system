@extends('admin.layouts.master')
@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="javascript:void(0)" id="updateFormDeduction">
                        @method('PUT')
                        @csrf

                        <input type="hidden" id="id" value="{{ $data->id }}">

                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $data->name }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="rate" class="col-sm-2 col-form-label">Rate</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="rate" id="rate"
                                    value="{{ $data->rate }}">
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

                        <a href="{{ route('deduction.index') }}" class="my-3 btn btn-danger">Back</a>
                        <input type="submit" class="my-3 btn btn-success" value="Update">
                        {{-- END ROW --}}
                </div>

                </form>

            </div>
        </div>
    </div>

    @push('addon-script')
        <script>
            $("#updateFormDeduction").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this)
                var id = $("#id").val()
                $.ajax({
                    data: formData,
                    url: "/deduction/" + id,
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
