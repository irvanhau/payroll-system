@extends('admin.layouts.master')
@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="javascript:void(0)" id="postFormChartOfAccount">

                        @csrf

                        <div class="mb-3 row">
                            <label for="account_no" class="col-sm-2 col-form-label">Account No</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="account_no" id="account_no">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="account_name" class="col-sm-2 col-form-label">Account Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="account_name" id="account_name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="coa_category_id" class="col-sm-2 col-form-label">Chart Of Account Category</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="coa_category_id" id="coa_category_id">
                                    <option value="0" selected>Choose COA Category:</option>
                                    @foreach ($coa_categories as $coa_category)
                                        <option value="{{ $coa_category->id }}">{{ $coa_category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <a href="{{ route('chart-of-account.index') }}" class="my-3 btn btn-danger">Back</a>
                        <input type="submit" class="my-3 btn btn-success" value="Save">
                        {{-- END ROW --}}
                </div>

                </form>

            </div>
        </div>
    </div>

    @push('addon-script')
        <script>
            $("#postFormChartOfAccount").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this)
                $.ajax({
                    data: formData,
                    url: "{{ route('chart-of-account.store') }}",
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
