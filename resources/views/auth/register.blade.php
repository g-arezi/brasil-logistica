<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">Criar conta</h1>
        <p class="mt-1 text-sm text-slate-300">Cadastre-se como motorista, transportadora ou agenciador.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" class="block mt-1 w-full border-slate-700 bg-slate-950 text-slate-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('E-mail')" />
            <x-text-input id="email" class="block mt-1 w-full border-slate-700 bg-slate-950 text-slate-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="profile_type" :value="__('Perfil')" />
            <select id="profile_type" name="profile_type" class="mt-1 block w-full rounded-md border-slate-700 bg-slate-950 text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="driver" @selected(old('profile_type', 'driver') === 'driver')>Motorista</option>
                <option value="transportadora" @selected(old('profile_type') === 'transportadora')>Transportadora</option>
                <option value="agenciador" @selected(old('profile_type') === 'agenciador')>Agenciador</option>
            </select>
            <x-input-error :messages="$errors->get('profile_type')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="document_number" :value="__('CPF/CNPJ')" />
            <x-text-input id="document_number" class="block mt-1 w-full border-slate-700 bg-slate-950 text-slate-100" type="text" name="document_number" :value="old('document_number')" required autocomplete="off" />
            <x-input-error :messages="$errors->get('document_number')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full border-slate-700 bg-slate-950 text-slate-100"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar senha')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full border-slate-700 bg-slate-950 text-slate-100"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Captcha -->
        <div class="mt-4">
            <x-input-label for="captcha" :value="'Para continuar, quanto é ' . $num1 . ' + ' . $num2 . ' ?'" />

            <x-text-input id="captcha" class="block mt-1 w-full border-slate-700 bg-slate-950 text-slate-100"
                            type="number"
                            name="captcha"
                            required />

            <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-slate-300 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Ja possui cadastro?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Criar conta') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
