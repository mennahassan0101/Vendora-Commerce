<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-ink font-sans p-10">
    <div class="flex items-center justify-between mb-8">
        <h1 class="font-display text-3xl">Admin Dashboard</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="text-sm text-rose-600 hover:text-rose-700 font-semibold">Log out</button>
        </form>
    </div>
    <p class="text-mauve">Signed in as {{ auth()->user()->name }}. Dashboard stats coming next module.</p>
</body>
</html>