<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $busca  = $request->input('busca');
        $perfil = $request->input('perfil');

        $usuarios = User::query()
            ->when($busca, function ($query, $busca) {
                $query->where(function ($q) use ($busca) {
                    $q->where('name', 'like', "%{$busca}%")
                      ->orWhere('email', 'like', "%{$busca}%");
                });
            })
            ->when($perfil, fn ($query, $perfil) => $query->where('perfil', $perfil))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'busca', 'perfil'));
    }

    public function create(): View
    {
        return view('usuarios.create', ['usuario' => new User()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('usuarios.index')
            ->with('sucesso', 'Usuário cadastrado com sucesso.');
    }
    public function edit(User $usuario): View
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(UserRequest $request, User $usuario): RedirectResponse
    {
        $dados = $request->validated();

        // Senha em branco na edição = manter a atual.
        if (empty($dados['password'])) {
            unset($dados['password']);
        }

        // Proteção: o admin não pode tirar o próprio perfil de admin,
        // senão ele mesmo perderia o acesso a esta tela.
        if ($usuario->is($request->user()) && $dados['perfil'] !== 'admin') {
            return back()->withInput()
                ->withErrors(['perfil' => 'Você não pode remover o seu próprio perfil de administrador.']);
        }

        $usuario->update($dados);

        return redirect()->route('usuarios.index')
            ->with('sucesso', 'Usuário atualizado com sucesso.');
    }

    public function destroy(Request $request, User $usuario): RedirectResponse
    {
        // Não deixa o admin apagar a própria conta enquanto está logado nela.
        if ($usuario->is($request->user())) {
            return back()->with('erro', 'Você não pode excluir o seu próprio usuário.');
        }
        
        if ($usuario->movimentacoes()->exists()) {
            return back()->with('erro', 'Este usuário tem movimentações registradas e não pode ser excluído.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('sucesso', 'Usuário excluído com sucesso.');
    }
}