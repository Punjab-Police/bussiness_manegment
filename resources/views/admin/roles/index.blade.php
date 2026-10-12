@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Roles & Permissions</h2>
                <p class="text-muted mb-0">
                    Manage access levels for your business system.
                </p>
            </div>

            @if(auth()->user()->hasPermission('roles.create'))
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    + Create Role
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Description</th>
                            <th>Users</th>
                            <th>Permissions</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td><strong>{{ $role->name }}</strong></td>
                                <td>{{ $role->description ?: '—' }}</td>
                                <td>{{ $role->users_count }}</td>
                                <td>{{ $role->permissions_count }}</td>
                                <td>
                                    <span class="badge {{ $role->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $role->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    @if(auth()->user()->hasPermission('roles.edit'))
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No roles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
@endsection