@extends('redwine::layouts.dashboard')

@section('title', 'უფლებები')

@section('navbar-title', 'უფლებები')

@section('content')
    
    @if(Redwine::bladePermissionFail('add_permission'))
        <a href="/redwine/permission/add" class="btn btn-primary btn-round pull-right" style="margin:-10px 0 -6px 0">
            <i class="material-icons">add</i> ახლის დამატება
        </a>
    @endif

    <div class="card" data-border="purple" style="">
        <div class="card-header card-icon" data-background-color="purple">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </div>
        <div class="card-content table-responsive">
            <table class="table table-hover">
                <thead class="text-pink">
                    <th>ID</th>
                    <th>სახელი</th>
                    <th>სრული დასახელება</th>
                    <th>შექმნის დრო</th>
                    <th>ღილაკები</th>
                </thead>
                <tbody>
                    @if(Redwine::bladePermissionFail('read_permission'))
                        @foreach($roles as $key => $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->created_at }}</td>
                                <td>
                                    @if(Redwine::bladePermissionFail('edit_permission'))
                                        <a href="/redwine/permission/edit/{{ $role->id }}" rel="tooltip" class="btn btn-primary btn-simple btn-xs">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    @endif
                                    @if(Redwine::bladePermissionFail('delete_permission'))
                                        <button type="button" rel="tooltip" class="btn btn-danger btn-simple btn-xs" @click="deleteTable({{ $role->id }})">
                                            <i class="material-icons">close</i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table> <!-- .table -->

            {{ $roles->links() }}
        </div> <!-- .card-content -->
    </div> <!-- .card -->

@endsection

@push('scripts')
    <script>
        vue = new Vue({
            el: ".vue",
            http: {
                emulateJSON: true,
                emulateHTTP: true
            },
            methods: {
                @if(Redwine::bladePermissionFail('delete_permission'))
                    deleteTable: function (id) {
                        swal({
                            title: 'დარწმუნებული ხარ?',
                            text: 'ამ მოქმედებას უკან ვერ დააბრუნებ!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#4caf50',
                            cancelButtonColor: '#f44336',
                            confirmButtonText: 'თანხმობა',
                            cancelButtonText: 'გაუქმება',
                            showCloseButton: true,
                        }).then((result) => {
                            if (result.value) {
                                data = {
                                    _token: '{{ csrf_token() }}'
                                };

                                this.$http.post('/redwine/permission/delete/' + id, data).then(function (data) {
                                    swal({
                                        type: 'success',
                                        showConfirmButton: false,
                                        timer: 2000
                                    }).then((result) => {
                                        location.reload();
                                    });
                                });
                            }
                        });
                    }
                @endif
            }
        });
    </script>
@endpush