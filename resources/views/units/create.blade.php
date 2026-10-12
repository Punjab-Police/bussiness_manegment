@extends('layouts.app')

@section('title', 'Add Unit')

@section('header')
    <h1 class="text-2xl font-bold text-slate-800">
        Add Unit
    </h1>
@endsection

@section('content')

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <form action="{{ route('units.store') }}" method="POST">

            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">
                    Unit Name
                </label>

                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Kilogram"
                    class="w-full border rounded-lg px-4 py-2">

                @error('name')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">
                    Short Name
                </label>

                <input type="text" name="short_name" value="{{ old('short_name') }}" placeholder="e.g. Kg"
                    class="w-full border rounded-lg px-4 py-2">

                @error('short_name')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">
                    Status
                </label>

                <select name="status" class="w-full border rounded-lg px-4 py-2">

                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                @error('status')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex gap-3">

                <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-lg">
                    Save Unit
                </button>

                <a href="{{ route('units.index') }}" class="px-5 py-2 bg-gray-100 rounded-lg">
                    Cancel
                </a>

            </div>

        </form>

    </div>

@endsection
