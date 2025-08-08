<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting; // Importe o modelo Setting
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Carbon\Carbon; // Importe a classe Carbon para gerenciar datas

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'restaurant_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'whatsapp_number' => ['required', 'string', 'max:20'],
        'opening_time' => ['required', 'date_format:H:i'],
        'closing_time' => ['required', 'date_format:H:i'],
    ]);

    $slug = Str::slug($request->restaurant_name);
    $originalSlug = $slug;
    $count = 1;
    while (User::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $user = User::create([
        'name' => $request->name,
        'restaurant_name' => $request->restaurant_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'slug' => $slug,
        'trial_ends_at' => Carbon::now()->addDays(4), // Define o final do período de teste
        'is_active' => false, // O usuário não está ativo até que o pagamento seja confirmado
    ]);

    $settings = new Setting([
        'whatsapp_number' => $request->whatsapp_number,
        'opening_time' => $request->opening_time,
        'closing_time' => $request->closing_time,
    ]);
    $user->setting()->save($settings);

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
    }
}