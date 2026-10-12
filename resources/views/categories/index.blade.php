@extends('layouts.app')

@section('title', 'Categories')

@section('header', 'Categories')

@section('content')

    <div class="space-y-6">

        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-2xl font-bold">
                    Categories
                </h1>

                <p class="text-gray-500">
                    Manage your product categories.
                </p>
            </div>

            @if (auth()->user()->hasPermission('products.create'))
                <a href="{{ route('categories.create') }}"
                    class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                    + Add Category
                </a>
            @endif

        </div>

        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">
                            Name
                        </th>

                        <th class="px-6 py-3 text-left text-sm font-semibold">
                            Description
                        </th>

                        <th class="px-6 py-3 text-left text-sm font-semibold">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-sm font-semibold">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">
                                {{ $category->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $category->description ?? '-' }}
                            </td>

                            <td class="px-6 py-4">

                                @if ($category->status)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-right space-x-2">

                                @if (auth()->user()->hasPermission('products.edit'))
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="text-blue-600 hover:underline">
                                        Edit
                                    </a>
                                @endif

                                @if (auth()->user()->hasPermission('products.delete'))
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" onclick="return confirm('Delete this category?')"
                                            class="text-red-600 hover:underline">
                                            Delete
                                        </button>

                                    </form>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div>
            {{ $categories->links() }}
        </div>

    </div>

@endsection
