<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $req)
    {
        try {
            $user = User::where('email', '=', $req->email)->where('password', '=', $req->password)->firstOrFail();
            $user->update(["token" => Str::random(50)]);
            return response()->json([
                "message" => "Usuário logado com sucesso.",
                "data" => [
                    "user" => $user
                ],
                "success" => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Credenciais inválidas.",
                "data" => [
                    "exception" => $e
                ],
                "success" => false
            ], 401);
        }
    }

    public function logout()
    {
        try {
            $user = User::findOrFail(request()->user->id);
            $user->update(["token" => null]);
            return response()->json([
                "message" => "Usuário deslogado com sucesso.",
                "data" => [],
                "success" => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Erro ao tentar deslogar usuário.",
                "data" => [
                    "exception" => $e
                ],
                "success" => false
            ], 401);
        }
    }

    public function register(Request $req)
    {
        try {
            $this->validate($req, [
                'email' => [
                    'unique:users',
                    'required'
                ],
                'password' => [
                    'required'
                ]
            ], [
                'email.unique' => 'O e-mail informado já está sendo utilizado por um usuário cadastrado.',
                'email.required' => 'O e-mail deve ser informado.',
                'password.required' => 'A senha deve ser informada.'
            ]);

            $credentials = $req->only('email', 'password');
            $user = new User($credentials);
            $user->save();
            return $this->login($req);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Falha ao tentar registrar o usuário.",
                "errors" => [
                    "exception" => $e,
                    "errors" => (isset($e->validator) ? $e->validator->messages() : [])
                ],
                "success" => false
            ]);
        }
    }
}
