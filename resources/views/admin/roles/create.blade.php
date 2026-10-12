@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <h2 class="mb-4">Create Role</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input name="name" class="form-control" value="{{ old('name') }}" required maxlength="100">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <h5 class="mb-3">Assign Permissions</h5>

                    <div class="row">
                        @foreach($permissions->groupBy(fn($p) => explode('.', $p->name)[0]) as $module => $items)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <strong>{{ ucfirst($module) }}</strong>
                                    </div>
                                    <div class="card-body">
                                        @foreach($items as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}" id="p{{ $permission->id }}"
                                                    @checked(in_array($permission->id, old('permissions', [])))>
                                                <label class="form-check-label" for="p{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="btn btn-primary">Create Role</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection