@extends('layouts/contentLayoutMaster')

@section('title', 'MY SUBMISSIONS')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
    @include('shared.alert')

    <section id="summary">

        <table id="table-submissions" class="table">
            <thead class="thead-light">
            <tr>
                <th>Philanthropist</th>
                <th>Status</th>
                <th>Submission Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($submissions as $submission)
                <tr>
                    <td>{{$submission->firstname. ' '. $submission->lastnamek}}
                        ({{$submission->year_of_birth ?? 'Unknown'}} - {{$submission->year_of_death ?? 'Unknown'}})
                    </td>
                    <td>{{$submission->status == 'active' ? 'approved' : ucfirst($submission->status)}}</td>
                    <td>{{\Carbon\Carbon::parse($submission->created_at)->format('Y/m/d H:i:s')}}</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   href="{{route('member.submissions.view', $submission->id )}}">
                                    <svg data-feather="eye" class="font-small-4 mr-50"></svg>
                                    View</a>
                                <!--<a class="dropdown-item delete-record"
                                   onclick="">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>-->
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </section>

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        $('#table-submissions').DataTable({
            "drawCallback": function (settings) {
                feather.replace();
            },
            "order": [],
        });
    </script>
@endsection