
@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <h2 class="mb-4">Create User</h2>

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
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input name="name" class="form-control"
                           value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-control" minlength="8" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" minlength="8" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Assign Roles</label>
                    @forelse($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="roles[]" value="{{ $role->id }}"
                                   id="role{{ $role->id }}"
                                   @checked(in_array($role->id, old('roles', [])))>
                            <label class="form-check-label" for="role{{ $role->id }}">
                                {{ $role->name }}
                                <small class="text-muted">{{ $role->description }}</small>
                            </label>
                        </div>
                    @empty
                        <p class="text-muted">No active roles are available.</p>
                    @endforelse
                </div>

                <button class="btn btn-primary">Create User</button>
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
