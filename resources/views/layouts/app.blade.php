<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Bulletin – Purok Adan</title>
    {{-- loads tailwind css and js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-blue-700 text-white">
        <div class="max-w-5xl mx-auto px-4 py-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <a href="{{ route('posts.index') }}" class="text-xl font-bold">Barangay Bulletin – Purok Adan</a>

            <nav class="flex items-center gap-4 text-sm">
                @guest
                    <a href="{{ route('login') }}" class="bg-white text-blue-700 font-semibold px-3 py-1 rounded hover:bg-blue-50">Login</a>
                @endguest

                @auth
                    {{-- only users linked to an author can post --}}
                    @if (auth()->user()->author)
                        <a href="{{ url('/posts/create') }}" class="hover:underline">New post</a>
                    @endif

                    <span>{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-white/10 px-3 py-1 rounded hover:bg-white/20">Logout</button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-6">
        @if (session('status'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
                {{ session('status') }}
            </div>
        @endif

        {{-- each page puts its content here --}}
        @yield('content')
    </main>
</body>
</html>
