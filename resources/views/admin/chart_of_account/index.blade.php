@extends('admin.layouts.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Chart Of Account</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <a href="{{ route('chart-of-account.create') }}" class="mb-2 btn btn-info sm"
                                title="Tambah Data"><i class="fas fa-plus"></i> Add Chart Of Account</a>

                            <table id="datatables" class="table text-center table-sm table-bordered dt-responsive nowrap"
                                style="border-collapse: separate; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Account No</th>
                                        <th>Account Name</th>
                                        <th>Account Category Name</th>
                                        <th>Account Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chart_of_accounts as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->account_no }}</td>
                                            <td>{{ $item->account_name }}</td>
                                            <td>{{ $item->chartOfAccountCategory->name }}</td>
                                            <td>{{ $item->account_type }}</td>
                                            <td>{{ $item->status == 1 ? 'Active' : 'Non Active' }}</td>
                                            <td>
                                                <a href="{{ route('chart-of-account.edit', $item->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fa fa-pencil-alt"></i></a>
                                                @if ($item->status == 1)
                                                    <a href="{{ route('chart-of-account.setStatus', $item->id) }}"
                                                        class="btn btn-danger btn-sm" id = 'changeStatusNo'
                                                        title="Non Aktif Chart Of Account"><i
                                                            class=" ri-delete-bin-7-line"></i></a>
                                                @elseif($item->status == 0)
                                                    <a href="{{ route('chart-of-account.setStatus', $item->id) }}"
                                                        class="btn btn-success btn-sm" id = 'changeStatusAc'
                                                        title="Aktif Chart Of Account"><i
                                                            class="fas fa-check-square"></i></a>
                                                @endif
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
@endsection
