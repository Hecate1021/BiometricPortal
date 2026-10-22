<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>HR Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Tailwind CDN (Guarantees your beautiful styling works on every page) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Laravel Vite (Default Breeze Scripts) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50">
        <div class="min-h-screen bg-slate-50">
            
            <!-- 🌟 Loads your custom beautiful header 🌟 -->
            @include('layouts.header')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
        </div>
    </body>
</html>