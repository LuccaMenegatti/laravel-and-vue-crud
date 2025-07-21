<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with('usuarios')->get();
        return response()->json($cursos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string',
            'tipo' => 'required|in:Graduação,Pós-Graduação',
        ]);

        $curso = Curso::create($request->only('descricao', 'tipo'));

        return response()->json($curso, 201);
    }

    public function show(Curso $curso)
    {
        $curso->load('usuarios');
        return response()->json($curso);
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'descricao' => 'sometimes|required|string',
            'tipo' => 'sometimes|required|in:Graduação,Pós-Graduação',
        ]);

        $curso->update($request->only('descricao', 'tipo'));

        return response()->json($curso);
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return response()->json(null, 204);
    }
}
