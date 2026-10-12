@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <h2 class="mb-4">Edit Role: {{ $role->name }}</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                            rows="3">{{ old('description', $role->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required {{ $role->name === 'Administrator' ? 'disabled' : '' }}>
                            <option value="1" @selected(old('status', $role->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $role->status) == 0)>Inactive</option>
                        </select>

                        @if($role->name === 'Administrator')
                            <input type="hidden" name="status" value="1">
                            <small class="text-muted">
                                The Administrator role cannot be deactivated.
                            </small>
                        @endif
                    </div>

                    <h5 class="mb-3">Permissions</h5>

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
                                                    value="{{ $permission->id }}" id="p{{ $permission->id }}" @checked(
                                                        in_array(
                                                            $permission->id,
                                                            old('permissions', $role->permissions->pluck('id')->all())
                                                        )
                                                    )>
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

                    <button class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection