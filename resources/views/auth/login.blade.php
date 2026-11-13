@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow p-8">
            <h1 class="text-2xl font-bold text-blue-700 text-center">Sign in</h1>
            <p class="text-sm text-gray-500 text-center mb-6">Purok Adan residents and officials</p>

            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2 rounded hover:bg-blue-800">
                    Sign in
                </button>
            </form>
        </div>
    </div>
@endsection
