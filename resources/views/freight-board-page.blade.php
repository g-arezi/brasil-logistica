<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Brasil Logistica - Painel de Fretes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-900 text-slate-100 antialiased">
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800"></div>

    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-6 pt-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="rounded-md bg-slate-800 px-3 py-2 text-sm text-white hover:bg-slate-700">Home</a>
            @auth
                <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-500">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="rounded-md border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-slate-200 hover:bg-slate-700">Entrar</a>
                <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-500">Cadastrar</a>
            @endauth
        </div>
    </div>

    <livewire:freight-board />

    @livewireScripts
</body>
</html>

