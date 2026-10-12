@extends('layouts.app')

@section('title', 'Units')



@section('header', 'Units')



@section('content')


    <div class="space-y-6  m-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Units
                </h1>
                <p class="text-sm text-slate-500">
                    Manage product measurement units.
                </p>
            </div>

            @if (auth()->user()->hasPermission('products.create'))
                <a href="{{ route('units.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                    + Add Unit
                </a>
            @endif
        </div>








        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Short Name</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($units as $unit)
                        <tr class="border-t">

                            <td class="px-6 py-4">
                                {{ $unit->id }}
                            </td>

                            <td class="px-6 py-4 font-medium">
                                {{ $unit->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $unit->short_name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">

                                @if ($unit->status)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                @if (auth()->user()->hasPermission('products.edit'))
                                    <a href="{{ route('units.edit', $unit) }}" class="text-blue-600 hover:underline mr-3">
                                        Edit
                                    </a>
                                @endif

                                @if (auth()->user()->hasPermission('products.delete'))
                                    <form action="{{ route('units.destroy', $unit) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this unit?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-red-600 hover:underline">
                                            Delete
                                        </button>

                                    </form>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                No units found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

            <div class="p-4">
                {{ $units->links() }}
            </div>

        </div>
    </div>

@endsection
