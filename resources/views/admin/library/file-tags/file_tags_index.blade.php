@extends('layouts/contentLayoutMaster')

@section('title', 'FILE TAGS')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection


@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">

    <style>
        .dropdown-searchbox {
            overflow: auto;
            max-height: 15rem;
        }

        .dropdown-searchbox hr {
            margin: 0px !important;
        }

        .content-header-title {
            border-right: none !important;
        }
    </style>
@endsection

@section('content')
    @include('shared.alert')

    <section id="fileTags">
        <a onclick="showAddModal()" class="btn thm-btn mb-1">ADD</a>

        <!-- FileTag List -->
        <table id="table-fileTags" class="table">
            <thead class="thead-light">
            <tr>
                <th>FILE TAG</th>
                <th>DESCRIPTION</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($fileTags as $fileTag)
                <tr class="tr-{{$fileTag->id}}">
                    <td>{{ $fileTag->name }}</td>
                    <td class="td-desc">{{ $fileTag->description }}</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <svg data-feather="more-vertical" class="font-small-4"></svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   onclick="showUpdateModal({id: {{ $fileTag->id }}, name: '{{ $fileTag->name }}'})">
                                    <svg data-feather="archive" class="font-small-4 mr-50"></svg>
                                    Edit</a>
                                <a class="dropdown-item delete-record"
                                   onclick="showDeleteModal({ id: {{ $fileTag->id}} , name: '{{$fileTag->name}}'})">
                                    <svg data-feather="trash-2" class="font-small-4 mr-50"></svg>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- FileTag List END -->

        <div id="addModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ADD FILE TAG</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- Add FileTag -->
                            <section id="add-fileTag">
                                <div class="row">
                                    <!-- FileTag name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fileTag_name">File Tag</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('fileTag_name') is-invalid @enderror"
                                                        placeholder="File Tag"
                                                        value="{{old('fileTag_name')}}"
                                                        name="fileTag_name"
                                                        id="fileTag_name"
                                                        oninput="onAddFileTagNameInputChanged(event)"
                                                        required
                                                />

                                                <span id="spinner-check-fileTag"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-already-exists" class="error mt-1 d-none" role="alert">
                                                File tag already exists!
                                            </span>
                                            <div id="add-fileTag-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-fileTag-search-result">
                                            </div>
                                            @error('fileTag_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>

                                    <!-- FileTag Description -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fileTag_name">Description</label>
                                            <div class="d-flex">
                                                <textarea id="fileTag_description" name="fileTag_description" rows="3"
                                                          class="form-control"
                                                          placeholder="Description">{{old('fileTag_name')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Add Industries END -->
                        </div>

                        <input type="hidden" name="id" id="input-save-fileTag-id"/>
                        <div class="modal-footer">
                            <button id="button-fileTag-save" type="submit" class="btn thm-btn" disabled>SAVE
                            </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Add Modal END -->


        <!-- Delete Modal -->
        <div id="deleteModal" class="modal fade modal-danger">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">DELETE FILE TAG</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="font-weight-bold" id="deletingFileTagName"></span>?
                        All tagged files will be changed to <span
                                class="font-weight-bold">{{\App\Models\FileTag::emptyRecordName()}}</span>.
                    </div>
                    <form method="post" id="form-delete-fileTag">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="input-delete-fileTag-id"/>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">REMOVE</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete Modal END -->

        <!-- Edit Modal -->
        <div id="editModal" class="modal fade modal-primary">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">EDIT FILE TAG</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="form-update-fileTag" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" id="input-edit-fileTag-id"/>
                        <div class="modal-body">
                            <!-- Edit Industries -->
                            <section id="edit-industries">
                                <div class="row">
                                    <!-- FileTag name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-fileTag-name">File Tag</label>
                                            <div class="d-flex">
                                                <input
                                                        type="text"
                                                        class="form-control @error('input-edit-fileTag-name') is-invalid @enderror"
                                                        placeholder="File Tag"
                                                        value=""
                                                        name="input-edit-fileTag-name"
                                                        id="input-edit-fileTag-name"
                                                        oninput="onUpdateFileTagNameInputChanged(event)"
                                                        required
                                                />
                                                <span id="edit-spinner-check-fileTag"
                                                      class="spinner-border text-primary d-none"
                                                      style="position: absolute; top:28px; right: 20px;"></span>
                                            </div>
                                            <span id="span-edit-already-exists" class="error mt-1 d-none" role="alert">
                                                File tag already exists!
                                            </span>
                                            <div id="edit-fileTag-search-result"
                                                 class="add-dropdown-menu overflow-auto dropdown-searchbox"
                                                 aria-labelledby="add-fileTag-search-result">
                                            </div>
                                            @error('input-edit-fileTag-name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- FileTag Description -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="input-edit-fileTag-description">Description</label>
                                            <div class="d-flex">
                                                <textarea id="input-edit-fileTag-description"
                                                          name="input-edit-fileTag-description"
                                                          rows="3" class="form-control" placeholder="Description"
                                                          oninput="onUpdateFileTagDescription(event)"
                                                >{{old('fileTag_name')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Edit Industries END -->
                        </div>

                        <div class="modal-footer">
                            <button id="button-fileTag-update" type="submit" class="btn thm-btn" disabled>SAVE
                                CHANGES
                            </button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit Modal END -->
    </section>

    <script>
        let lastTimeOut = null;

        const fileTagNameCheckerUrl = "{{ route('admin.file-tags.search') }}",
            token = "{{ csrf_token() }}",
            fileTagsDeleteUrl = "{{route('admin.file-tags.destroy','')}}",
            fileTagsUpdateUrl = "{{route('admin.file-tags.update','')}}";

        const showDeleteModal = (fileTag) => {
            let deleteForm = document.querySelector('#form-delete-fileTag');
            deleteForm.action = `${fileTagsDeleteUrl}/${fileTag.id}`;
            $('#deletingFileTagName').text(fileTag.name);
            $('#input-delete-fileTag-id').val(fileTag.id);
            $('#deleteModal').modal();
        }

        const showUpdateModal = (fileTag) => {
            let updateForm = document.querySelector('#form-update-fileTag');
            updateForm.action = `${fileTagsUpdateUrl}/${fileTag.id}`;
            $('#input-edit-fileTag-name').val(fileTag.name);
            $('#input-edit-fileTag-id').val(fileTag.id);
            $('#edit-fileTag-search-result').html('');
            $('#span-edit-already-exists').addClass('d-none');

            const description = $(`.tr-${fileTag.id}`).find('.td-desc').text();
            $('#input-edit-fileTag-description').val(description);

            $('#editModal').modal();
        }

        const showAddModal = () => {
            $('#fileTag_name').val('');
            $('#fileTag_description').val('');
            $('#add-fileTag-search-result').html('');
            $('#span-already-exists').addClass('d-none');
            $('#addModal').modal();
        }

        const onAddFileTagNameInputChanged = (event) => {
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }

            $('#button-fileTag-save').prop('disabled', true);

            let spinnerCheckFileTag = $('#spinner-check-fileTag');
            spinnerCheckFileTag.removeClass('d-none');

            if (!event.target.value.length) {
                $('#add-fileTag-search-result').html('');
                spinnerCheckFileTag.addClass('d-none');
            }

            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: fileTagNameCheckerUrl,
                    spinnerId: 'spinner-check-fileTag',
                    actionButtonId: 'button-fileTag-save',
                    spanId: 'span-already-exists',
                    searchResultItemId: 'add-fileTag-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        fileTag_name: event.target.value,
                        searchSimilarRecords: true
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateFileTagNameInputChanged = (event) => {
            $('#button-fileTag-update').prop('disabled', true);
            $('#edit-spinner-check-fileTag').removeClass('d-none');
            if (lastTimeOut) {
                clearTimeout(lastTimeOut);
            }
            lastTimeOut = setTimeout(() => {
                const checkerConfig = {
                    url: fileTagNameCheckerUrl,
                    spinnerId: 'edit-spinner-check-fileTag',
                    actionButtonId: 'button-fileTag-update',
                    spanId: 'span-edit-already-exists',
                    searchResultItemId: 'edit-fileTag-search-result',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        fileTag_name: event.target.value,
                        searchSimilarRecords: true,
                        itemId: $('#input-edit-fileTag-id').val()
                    }
                }
                checkItemExists(checkerConfig);
            }, 500);
        }

        const onUpdateFileTagDescription = (event) => {
            $('#input-edit-fileTag-name').trigger('input');
        }

        const checkItemExists = (checkerConfig) => {
            const searchResultItem = $('#' + checkerConfig.searchResultItemId);
            const actionButton = $('#' + checkerConfig.actionButtonId);
            const loadingSpinner = $('#' + checkerConfig.spinnerId);
            const existsSpan = $('#' + checkerConfig.spanId);

            loadingSpinner.addClass('d-none');

            $.ajax({
                url: checkerConfig.url,
                type: checkerConfig.method ? checkerConfig.method : 'POST',
                data: checkerConfig.data,
                success: function (response) {
                    switch (response.status) {
                        case 'not-exists' : {
                            actionButton.prop('disabled', false)
                            break;
                        }
                        case 'exists' : {
                            actionButton.prop('disabled', true);
                            existsSpan.removeClass('d-none');
                            break;
                        }
                        default : {
                            actionButton.prop('disabled', false);
                            existsSpan.addClass('d-none');
                        }
                    }

                    loadingSpinner.addClass('d-none');

                    if (response.similarRecords && checkerConfig.data.fileTag_name.length >= 3) {
                        searchResultItem.html(response.similarRecords);
                    } else {
                        searchResultItem.html('');
                    }
                },
                error: function (response) {
                    console.log('error', response);
                },
            });
        }
    </script>

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
        $(document).ready(function () {
            $('#table-fileTags').DataTable({
                "drawCallback": function (settings) {
                    feather.replace();
                }
            });
        });
    </script>
@endsection