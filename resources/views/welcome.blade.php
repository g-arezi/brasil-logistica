<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Brasil Logistica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="mx-auto max-w-7xl px-6 py-8">
        <header class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="text-lg font-semibold">Brasil Logistica</a>
            <nav class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm hover:bg-indigo-500">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-md border border-slate-700 px-3 py-2 text-sm hover:bg-slate-800">Entrar</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm hover:bg-indigo-500">Criar conta</a>
                @endauth
            </nav>
        </header>

        <main class="mt-12 grid gap-8 lg:grid-cols-2 lg:items-center">
            <section>
                <p class="text-sm uppercase tracking-widest text-cyan-300">Marketplace de Fretes</p>
                <h1 class="mt-3 text-4xl font-bold leading-tight sm:text-5xl">Conecte motoristas, transportadoras e agenciadores em um unico ecossistema.</h1>
                <p class="mt-4 max-w-xl text-slate-300">Gerencie oportunidades por origem, destino, faixa de preco e tipo de veiculo com atualizacao em tempo real.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('freights.board') }}" class="rounded-lg bg-cyan-500 px-5 py-3 font-medium text-slate-950 hover:bg-cyan-400">Explorar fretes</a>
                    <a href="{{ route('register') }}" class="rounded-lg border border-slate-700 px-5 py-3 font-medium hover:bg-slate-900">Cadastrar perfil</a>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-cyan-900/20">
                <h2 class="text-xl font-semibold">Perfis suportados:</h2>
                <ul class="mt-4 space-y-2 text-sm text-slate-300">
                    <li><span class="font-semibold text-slate-100">Transportadora/Empresa/Agenciador(a)</span> Publicação e gestão de cargas.</li>
                    <li><span class="font-semibold text-slate-100">Motorista:</span> busca e candidatura em fretes.</li>



                <h2 class="text-xl font-semibold mt-6">Funcionalidades principais:</h2>

                <ul class="mt-4 space-y-2 text-sm text-slate-300">
                    <li><span class="font-semibold text-slate-100">Board de Fretes:</span> Listagem e filtro de oportunidades.</li>
                    <li><span class="font-semibold text-slate-100">Dashboard personalizado:</span> Gerenciamento de perfil e atividades.</li>
                    <li><span class="font-semibold text-slate-100">Avaliações e feedback:</span> Construcao de reputacao confiavel.</li>
                </ul>
                </ul>

            </section>
        </main>

        <div class="mt-16 flex justify-center pb-8">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo marca" class="h-32 w-auto object-contain drop-shadow-[0_0_15px_rgba(8,145,178,0.2)]">
        </div>
    </div>
</body>
</html>

