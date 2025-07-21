<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('cursos')->get();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_completo' => 'required|string',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|string|unique:usuarios,cpf',
            'curso_ids' => 'array',
            'curso_ids.*' => 'exists:cursos,id',
        ]);

        $usuario = Usuario::create($request->only('nome_completo', 'data_nascimento', 'cpf'));

        if ($request->has('curso_ids')) {
            $usuario->cursos()->sync($request->curso_ids);
        }

        return response()->json($usuario->load('cursos'), 201);
    }

    public function show(Usuario $usuario)
    {
        $usuario->load('cursos');
        return response()->json($usuario);
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nome_completo' => 'sometimes|required|string',
            'data_nascimento' => 'sometimes|required|date',
            'cpf' => 'sometimes|required|string|unique:usuarios,cpf,' . $usuario->id,
            'curso_ids' => 'array',
            'curso_ids.*' => 'exists:cursos,id',
        ]);

        $usuario->update($request->only('nome_completo', 'data_nascimento', 'cpf'));

        if ($request->has('curso_ids')) {
            $usuario->cursos()->sync($request->curso_ids);
        }

        return response()->json($usuario->load('cursos'));
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json(null, 204);
    }
}
