@extends('layouts/contentLayoutMaster')

@section('title', 'FAVORITES')

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

        <table id="table-favorites" class="table">
            <thead class="thead-light">
            <tr>
                <th>Philanthropist</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach(Auth::user()->favoritePhilanthropists->all() as $favorite)
                <tr>
                    <td>{{ $favorite->philanthropist->firstname.' '.$favorite->philanthropist->lastname }}</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="">
                                    <svg data-feather="eye" class="font-small-4 mr-50"></svg>
                                    View</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $favorite->id}} , name: '{{$favorite->philanthropist->firstname.' '.$favorite->philanthropist->lastname}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Delete Modal -->
        <div id="deleteModal" class="modal fade modal-danger">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">DELETE FAVORITE</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold"
                                                              id="deletingFavorite"></span>?
                    </div>
                    <form method="post" id="form-delete-favorite">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-favorite-id"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">REMOVE</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete Modal END -->

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
        $('#table-favorites').DataTable({
            "drawCallback": function (settings) {
                feather.replace();
            }
        });

        const token = "{{ csrf_token() }}",
            favoritesDeleteUrl = "{{route('member.favorites.destroy','')}}";

        const showDeleteModal = (favorite) => {
            let deleteForm = document.querySelector('#form-delete-favorite');
            deleteForm.action = `${favoritesDeleteUrl}/${favorite.id}`;
            $('#deletingFavorite').text(favorite.name);
            $('#input-delete-favorite-id').val(favorite.id);
            $('#deleteModal').modal();
        }
    </script>
@endsection