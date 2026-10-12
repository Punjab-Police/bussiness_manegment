@extends('layouts.app')

@section('title', 'Audit Logs')
@section('header', 'Audit Logs')

@section('content')

    <div class="min-h-screen bg-gray-50 p-6">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Audit Logs</h1>
            <p class="mt-2 text-gray-500">Monitor users and track all application activities.</p>
        </div>

        {{-- Statistics --}}
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Activities</p>
                <h2 class="mt-2 text-3xl font-bold text-indigo-600">
                    {{ number_format($totalLogs) }}
                </h2>
            </div>

            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Search Results</p>
                <h2 class="mt-2 text-3xl font-bold text-green-600">
                    {{ number_format($logs->total()) }}
                </h2>
            </div>
        </div>

        {{-- Search Filters --}}
        <div class="mb-6 rounded-xl border bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-800">Search Activities</h2>

            <form action="{{ route('logs.index') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                    <div>
                        <label class="mb-2 block text-sm font-medium">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Name, email, IP, record ID..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">User</label>
                        <select name="user_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">Action</label>
                        <select name="action" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5">
                            <option value="">All Actions</option>
                            @foreach ($actions as $action)
                                <option value="{{ $action }}" @selected(request('action') === $action)>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">Module</label>
                        <select name="module" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5">
                            <option value="">All Modules</option>
                            @foreach ($modules as $module)
                                <option value="{{ $module }}" @selected(request('module') === $module)>
                                    {{ $module }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">From Date</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">To Date</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5">
                    </div>

                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 font-semibold text-white hover:bg-indigo-700">
                        Search Logs
                    </button>

                    <a href="{{ route('logs.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-100">
                        Reset Filters
                    </a>
                </div>
            </form>
        </div>

        {{-- Logs --}}
        <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
            <div class="border-b p-5">
                <h2 class="text-xl font-semibold text-gray-800">Activity History</h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $logs->total() }} activities found
                </p>
            </div>

            @forelse ($logs as $log)

                @php
                    $action = strtolower($log->action ?? '');

                    $badgeColors = [
                        'created' => 'bg-green-100 text-green-700',
                        'updated' => 'bg-blue-100 text-blue-700',
                        'deleted' => 'bg-red-100 text-red-700',
                    ];

                    $badge = $badgeColors[$action] ?? 'bg-gray-100 text-gray-700';

                    $oldValues = $log->old_values ?? [];
                    $newValues = $log->new_values ?? [];
                @endphp

                <div class="border-b p-5 last:border-0 hover:bg-gray-50">

                    {{-- Activity Heading --}}
                    <div class="flex flex-col justify-between gap-3 sm:flex-row">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-lg font-bold text-gray-800">
                                    {{ $log->module ?? 'General Activity' }}
                                </h3>

                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $badge }}">
                                    {{ ucfirst($action ?: 'unknown') }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-gray-600">
                                User:
                                <strong class="text-gray-900">
                                    {{ $log->user?->name ?? 'Unknown User' }}
                                </strong>

                                @if ($log->user?->email)
                                    <span class="text-gray-400">
                                        ({{ $log->user->email }})
                                    </span>
                                @endif
                            </p>

                            <p class="mt-1 text-sm text-gray-600">
                                Action:
                                <strong>{{ ucfirst($action ?: 'unknown') }}</strong>
                                &middot;
                                Record:
                                <strong>{{ class_basename($log->auditable_type ?? ($log->module ?? 'Record')) }}</strong>
                                #{{ $log->auditable_id ?? 'N/A' }}
                            </p>

                            <p class="mt-2 text-xs text-gray-500">
                                {{ $log->created_at?->format('d M Y, h:i:s A') }}
                                &middot; Log #{{ $log->id }}
                            </p>
                        </div>

                        <div class="text-sm text-gray-500">
                            User ID: {{ $log->user_id ?? 'N/A' }}
                        </div>
                    </div>

                    {{-- Values --}}
                    @if (count($oldValues) || count($newValues))
                        <details class="mt-5 rounded-lg border border-gray-200">
                            <summary
                                class="cursor-pointer bg-gray-50 px-4 py-3 font-semibold text-gray-700 hover:bg-gray-100">
                                View Details
                            </summary>

                            <div class="space-y-5 p-4">

                                @if (count($oldValues))
                                    <div>
                                        <h4 class="mb-3 font-semibold text-red-600">
                                            Previous Values
                                        </h4>

                                        <div class="overflow-hidden rounded-lg border border-red-100">
                                            @foreach ($oldValues as $key => $value)
                                                <div
                                                    class="grid grid-cols-1 gap-2 border-b border-red-50 p-3 last:border-0 sm:grid-cols-3">
                                                    <span class="text-sm text-gray-500">
                                                        {{ \Illuminate\Support\Str::headline($key) }}
                                                    </span>

                                                    <span class="break-words text-sm text-gray-800 sm:col-span-2">
                                                        @if (is_null($value))
                                                            <em>Empty</em>
                                                        @elseif (is_array($value))
                                                            {{ json_encode($value, JSON_PRETTY_PRINT) }}
                                                        @elseif (is_bool($value))
                                                            {{ $value ? 'Yes' : 'No' }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if (count($newValues))
                                    <div>
                                        <h4 class="mb-3 font-semibold text-green-700">
                                            {{ $action === 'created' ? 'Created Values' : 'New Values' }}
                                        </h4>

                                        <div class="overflow-hidden rounded-lg border border-green-100">
                                            @foreach ($newValues as $key => $value)
                                                <div
                                                    class="grid grid-cols-1 gap-2 border-b border-green-50 p-3 last:border-0 sm:grid-cols-3">
                                                    <span class="text-sm text-gray-500">
                                                        {{ \Illuminate\Support\Str::headline($key) }}
                                                    </span>

                                                    <span
                                                        class="break-words text-sm font-medium text-gray-800 sm:col-span-2">
                                                        @if (is_null($value))
                                                            <em>Empty</em>
                                                        @elseif (is_array($value))
                                                            {{ json_encode($value, JSON_PRETTY_PRINT) }}
                                                        @elseif (is_bool($value))
                                                            {{ $value ? 'Yes' : 'No' }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </details>
                    @else
                        <p class="mt-4 rounded-lg bg-gray-50 p-3 text-sm text-gray-500">
                            No field changes recorded.
                        </p>
                    @endif

                    {{-- Technical Information --}}
                    <div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-500">
                        <span>IP: {{ $log->ip_address ?: 'Not recorded' }}</span>
                        <span>Module: {{ $log->module ?: 'N/A' }}</span>
                    </div>

                </div>

            @empty
                <div class="p-12 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">No logs found</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Change your search filters or reset them.
                    </p>
                    <a href="{{ route('logs.index') }}"
                        class="mt-4 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                        Reset Filters
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="border-t p-5">
                    {{ $logs->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection
