<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Vendora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/60 text-ink font-sans antialiased min-h-screen flex items-center justify-center px-6">

    <div class="w-full max-w-sm bg-white rounded-2xl border border-rose-100 p-8">
        <p class="font-display text-2xl text-ink text-center">Vendora Admin</p>
        <p class="text-sm text-mauve text-center mt-1">Sign in to manage the store</p>

        @if (session('login_error'))
            <div class="mt-6 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
                {{ session('login_error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-ink mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-ink mb-1">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full rounded-lg border border-rose-200 px-4 py-2.5 focus:outline-none focus:border-rose-400">
            </div>

            <label class="flex items-center gap-2 text-sm text-mauve">
                <input type="checkbox" name="remember" class="rounded border-rose-300">
                Remember me
            </label>

            <button type="submit"
                    class="w-full rounded-full bg-rose-600 text-white py-3 text-sm font-semibold hover:bg-rose-700 transition-colors">
                Sign in
            </button>
        </form>
    </div>

</body>
</html>