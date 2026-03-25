<!DOCTYPE html>
<html lang="pt-BR" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BrasilLogistica') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="min-h-screen bg-slate-950 text-slate-100">
            <div class="mx-auto grid min-h-screen max-w-7xl items-center gap-8 px-6 py-8 lg:grid-cols-2">
                <section class="hidden lg:block">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-lg font-semibold">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-cyan-500 text-slate-950">BL</span>
                        BrasilLogistica
                    </a>
                    <h1 class="mt-6 text-4xl font-bold leading-tight">Plataforma completa para conectar transporte e operacao logistica.</h1>
                    <p class="mt-4 text-slate-300">Acesse sua conta para gerenciar fretes por perfil de motorista, transportadora, agenciador ou administrador.</p>
                    <a href="{{ route('freights.board') }}" class="mt-6 inline-flex rounded-lg border border-slate-700 px-4 py-2 text-sm hover:bg-slate-900">
                        Ver board publico de fretes
                    </a>
                </section>

                <section class="w-full rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-cyan-900/20 sm:p-8">
                    <div class="mb-6 flex items-center justify-between">
                        <a href="{{ route('home') }}" class="text-sm text-slate-300 hover:text-white">Voltar para home</a>
                        <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300">Acesso seguro</span>
                    </div>
                    {{ $slot }}
                </section>
            </div>
        </div>
    </body>
</html>
