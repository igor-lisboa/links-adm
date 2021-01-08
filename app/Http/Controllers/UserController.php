<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $req)
    {
        try {
            $credentials = $req->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = User::findOrFail(Auth::user()->id);
                $user->update(["token" => Str::random(50)]);
                return response()->json([
                    "message" => "Usuário logado com sucesso.",
                    "data" => [
                        "user" => $user
                    ],
                    "success" => true
                ]);
            }
            throw new Exception("Credenciais inválidas.");
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

    public function register(Request $req)
    {
        try {
            $credentials = $req->only('email', 'password');
            $user = new User($credentials);
            $user->save();
            return $this->login($req);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Falha ao tentar registrar o usuário.",
                "data" => [
                    "exception" => $e
                ],
                "success" => false
            ]);
        }
    }
}
