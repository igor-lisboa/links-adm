<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public function store(Request $req)
    {
        try {
            $link = new Link($req->all());
            $link->user_id = Auth::user()->id;
            $link->save();
            return response()->json([
                "message" => "Link gravado com sucesso.",
                "data" => [
                    "link" => $link
                ],
                "success" => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Falha ao gravar Link.",
                "data" => [
                    "exception" => $e
                ],
                "success" => false
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $link = Link::where('user_id', '=', Auth::user()->id)->where('id', '=', $id)->firstOrFail();
            $link->delete();
            return response()->json([
                "message" => "Link removido com sucesso.",
                "data" => [],
                "success" => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Falha ao remover Link.",
                "data" => [
                    "exception" => $e
                ],
                "success" => false
            ], 500);
        }
    }
}
