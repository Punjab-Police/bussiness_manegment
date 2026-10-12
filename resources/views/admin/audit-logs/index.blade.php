@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="mb-4">
            <h2>Audit Logs</h2>
            <p class="text-muted mb-0">
                Review user activity and changes recorded across the system.
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.audit-logs.index') }}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="Module, action, record ID, IP">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select">
                                <option value="">All actions</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" @selected(request('action') === $action)>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Module</label>
                            <select name="module" class="form-select">
                                <option value="">All modules</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module }}" @selected(request('module') === $module)>
                                        {{ $module }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">From</label>
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">To</label>
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Activity History</strong>
                <span class="text-muted">
                    {{ number_format($logs->total()) }} records
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Record</th>
                            <th>IP Address</th>
                            <th>Details</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-nowrap">
                                    {{ $log->created_at?->format('d M Y, h:i A') }}
                                </td>
                                <td>
                                    {{ $log->user?->name ?? 'System / Deleted User' }}
                                    <div class="text-muted small">
                                        {{ $log->user?->email ?? '' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>{{ $log->module ?: '—' }}</td>
                                <td>
                                    {{ class_basename($log->auditable_type ?? '') ?: '—' }}
                                    @if($log->auditable_id)
                                        #{{ $log->auditable_id }}
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?: '—' }}</td>
                                <td>
                                    <details>
                                        <summary>View changes</summary>

                                        <div class="mt-2">
                                            <strong>Previous values</strong>
                                            <pre
                                                class="small text-wrap">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '—' }}</pre>

                                            <strong>New values</strong>
                                            <pre
                                                class="small text-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '—' }}</pre>

                                            <strong>Browser / Client</strong>
                                            <p class="small text-break">
                                                {{ $log->user_agent ?: '—' }}
                                            </p>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    No audit records match your filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection