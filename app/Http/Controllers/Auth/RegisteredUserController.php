<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domains\User\Enums\UserProfileType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'profile_type' => ['required', 'in:driver,transportadora,agenciador'],
            'document_number' => ['required', 'string', 'max:18', 'unique:'.User::class.',document_number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $profileType = UserProfileType::from((string) $request->string('profile_type'));

        $user = User::create([
            'name' => (string) $request->string('name'),
            'email' => (string) $request->string('email'),
            'password' => Hash::make($request->password),
            'profile_type' => $profileType,
            'document_number' => $this->normalizeDocument((string) $request->string('document_number')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    private function normalizeDocument(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }
}
