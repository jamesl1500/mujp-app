@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <!-- <h4 class="alert-heading">Operation result:</h4> -->
        <div class="alert-body">
            {{ session('success') }}
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <!-- <h4 class="alert-heading">Operation result:</h4> -->
        <div class="alert-body">
            {{ session('error') }}
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif