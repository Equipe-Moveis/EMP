<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credenciais = $request->only('email', 'password');

        if (Auth::attempt($credenciais, $request->boolean('lembrar'))) {
            // Gera um novo id de sessão depois do login. Isso evita "session fixation":
            // alguém que tivesse o id antigo da sessão não consegue usar a conta logada.
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Falhou: volta para o login com a mensagem de erro e o e-mail preenchido.
        return back()
            ->withErrors(['email' => 'E-mail ou senha incorretos.'])
            ->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}