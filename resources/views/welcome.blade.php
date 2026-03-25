<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BrasilLogistica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="mx-auto max-w-7xl px-6 py-8">
        <header class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="text-lg font-semibold">BrasilLogistica</a>
            <nav class="flex items-center gap-2">
                <a href="{{ route('freights.board') }}" class="rounded-md bg-slate-800 px-3 py-2 text-sm hover:bg-slate-700">Board de Fretes</a>
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
                <h2 class="text-xl font-semibold">Perfis suportados</h2>
                <ul class="mt-4 space-y-2 text-sm text-slate-300">
                    <li><span class="font-semibold text-slate-100">Motorista:</span> busca e candidatura em fretes.</li>
                    <li><span class="font-semibold text-slate-100">Transportadora:</span> publicacao e gestao de cargas.</li>
                    <li><span class="font-semibold text-slate-100">Agenciador:</span> intermediar oportunidades e operacao.</li>
                    <li><span class="font-semibold text-slate-100">Administrador:</span> governanca geral da plataforma.</li>
                </ul>
                <div class="mt-6 rounded-lg bg-slate-950 p-4 text-sm text-slate-300 space-y-1">
                    <p class="font-semibold mb-2">Contas de Demonstracao (Senha: password):</p>
                    <p>Empresa: <span class="text-cyan-300">empresa@demo.com</span></p>
                    <p>Transportadora: <span class="text-cyan-300">transportadora@demo.com</span></p>
                    <p>Agenciador: <span class="text-cyan-300">agenciador@demo.com</span></p>
                    <p>Administrador: <span class="text-cyan-300">admin@demo.com</span></p>
                    <p>Motorista: <span class="text-cyan-300">motorista@demo.com</span></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

