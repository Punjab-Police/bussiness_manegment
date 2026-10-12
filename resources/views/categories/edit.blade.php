@extends('layouts.app')

@section('title', 'Update Category')

@section('header', 'Update Category')

@section('content')

    <div class="max-w-2xl">

        <div class="bg-white rounded-xl shadow-sm border p-6">

            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-5">

                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Category Name
                    </label>

                    <input type="text" name="name" value="{{ old('name', $category->name) }}"
                        class="w-full rounded-lg border-gray-300" required>

                    @error('name')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Description
                    </label>

                    <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300">{{ old('description', $category->description) }}</textarea>

                    @error('description')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Status
                    </label>

                    <select name="status" class="w-full rounded-lg border-gray-300">

                        <option value="1">Active</option>
                        <option value="0">Inactive</option>

                    </select>
                </div>

                <div class="flex gap-3">

                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-lg">
                        Update Category
                    </button>

                    <a href="{{ route('categories.index') }}" class="px-5 py-2 bg-gray-100 rounded-lg">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection
