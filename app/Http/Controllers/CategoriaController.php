<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
   
    public function index()
    {
          $categorias = Categoria::orderBy('descricao')->get();
        return view("categorias.index", compact('categorias'));
    }

   
    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request) {
       $dados = $request->validate([
        'descricao' => "required|string|max:60|"
       ]);
       Categoria::create($dados);
       return redirect()->route("categorias.index")->with("success","Categoria cadastrada com sucesso!");
    }

    
   
    public function edit(Categoria $categoria)
    {
        # Quando chamado busca a categoria no db, e retorna para view
        $categoria = Categoria::findOrFail($categoria->id);
        return view("categorias.edit", compact("categoria"));
    }

   
    public function update(Request $request, Categoria $categoria)
    {
        $dados = $request->validate([
            'descricao' => "required|string|max:60"
        ]);
        Categoria::findOrFail($categoria->id)->update($dados);
        return redirect()->route("categorias.index")->with("success","Categoria editada com sucesso!");
    }


    public function destroy(Categoria $categoria)
    {
         if ($categoria->produtos()->exists()) {
        return back()->with('erro', 'Não é possível excluir: existem produtos vinculados a esta categoria.');
    }

    if ($categoria->moveis()->exists()) {
        return back()->with('erro', 'Não é possível excluir: existem móveis vinculados a esta categoria.');
    }
        $categoria->delete();
        

        return redirect()->route("categorias.index")->with("success","Categoria Deletada com sucesso!");
    }
}
