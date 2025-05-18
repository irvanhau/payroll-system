@extends('admin.layouts.master')
@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">
                    <h4>Change Password Page</h4><br><br>

                    @if (count($errors))
                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger alert-dismissable fade show">{{ $error }}</p>
                        @endforeach
                    @endif


                    <form method="post" action="{{ route('update.password') }}">
                        @csrf
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Old Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="oldpassword" id="oldpassword">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">New Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="newpassword" id="newpassword">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                            </div>
                        </div>

                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Change Password">
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
