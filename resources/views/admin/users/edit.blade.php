
@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <h2 class="mb-4">Edit User</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password"
                           class="form-control" minlength="8">
                    <small class="text-muted">Leave blank to keep the current password.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" minlength="8">
                </div>

                <div class="mb-3">
                    <label class="form-label">Account Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active"
                            @selected(old('status', $user->status) === 'active')>Active</option>
                        <option value="inactive"
                            @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Roles</label>
                    @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="roles[]" value="{{ $role->id }}"
                                   id="role{{ $role->id }}"
                                   @checked(in_array($role->id, old('roles', $user->roles->pluck('id')->all())))>
                            <label class="form-check-label" for="role{{ $role->id }}">
                                {{ $role->name }}
                                <small class="text-muted">{{ $role->description }}</small>
                            </label>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-primary">Save Changes</button>
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
