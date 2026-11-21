<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('fornecedores.index', compact('fornecedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fornecedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|min:3|max:35',
            'cnpj' => 'required|string|max:18',
            'email' => 'required|email|max:50',
            'telefone' => 'required|string|min:7|max:15',
            'cidade' => 'required|string|min:3|max:35',
            'uf' => 'required|string|size:2',
        ]);
        Fornecedor::create($dados);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor Criado com sucesso!');
    }

    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedores.edit', compact('fornecedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        $dados = $request->validate([
            'nome' => 'required|string|min:3|max:35',
            'cnpj' => 'required|string|max:18',
            'email' => 'required|email|max:50',
            'telefone' => 'required|string|min:7|max:15',
            'cidade' => 'required|string|min:3|max:35',
            'uf' => 'required|string|size:2',
        ]);

        $fornecedor->update($dados);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor editado com sucesso!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $fornecedor)
    {
        Fornecedor::findOrFail($fornecedor->id)->delete();

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor deletado com sucesso!');
    }
}
