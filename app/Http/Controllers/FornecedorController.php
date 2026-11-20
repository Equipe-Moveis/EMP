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
        $fornecedores = Fornecedor::orderBy("nome")->get();
        return view("fornecedores.index", compact("fornecedores"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("fornecedores.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            "nome" => "required|min:3|max:30|string",
            "cnpj" => "required",
            "email" => "required|min:3|max:50",
            "telefone" => "required|min:7|max:11",
            "cidade" => "required|min:3|max:30|string",
            "uf" => "required",
        ]);
        Fornecedor::create($dados);
        return redirect()->route("fornecedores.index")->with("success","Fornecedor Criado com sucesso!");
    }


 
    public function edit(Fornecedor $fornecedor)
    {
        $fornecedor = Fornecedor::findOrFail( $fornecedor->id );
        return view("fornecedores.edit", compact("fornecedor"));

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $fornecedor)
    {
        //
    }
}
