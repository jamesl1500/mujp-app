@extends('layouts/contentLayoutMaster')

@section('title', 'MY DONATIONS')

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

        <a class="btn thm-btn  mb-1">DONATE</a>
        <table id="table-donations" class="table">
            <thead class="thead-light">
            <tr>
                <th>Philanthropist</th>
                <th>Institution/Foundtaion</th>
                <th>Donation Date</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Abraham Jones (1920-1970)</td>
                <td>Frier Aty Seh</td>
                <td>04/05/2021</td>
                <td>$300</td>
            </tr>
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
        $('#table-donations').DataTable({
            "drawCallback": function (settings) {
                feather.replace();
            }
        });
    </script>
@endsection