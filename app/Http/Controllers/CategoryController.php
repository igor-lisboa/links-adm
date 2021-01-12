<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            "message" => "Categorias retornadas com sucesso.",
            "data" => [
                "categories" => $this->getUserCategories()->get()
            ],
            "success" => true
        ]);
    }

    public function getUserCategories()
    {
        return Category::where('user_id', '=', request()->user()->id);
    }

    public function store(Request $req)
    {
        try {
            $category = new Category($req->all());
            $category->user_id = request()->user()->id;
            $category->save();
            return response()->json([
                "message" => "Categoria gravada com sucesso.",
                "data" => [
                    "category" => $category
                ],
                "success" => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Falha ao gravar Categoria.",
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
            $category = $this->getUserCategories()->where('id', '=', $id)->firstOrFail();
            $category->delete();
            return response()->json([
                "message" => "Categoria removida com sucesso.",
                "data" => [],
                "success" => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Falha ao remover Categoria.",
                "data" => [
                    "exception" => $e
                ],
                "success" => false
            ], 500);
        }
    }

    public function links()
    {
        try {
            $categories = $this->getUserCategories()->get();
            $links = [];
            foreach ($categories as $category) {
                $links[$category->name] = $category->links;
            }
            return response()->json([
                "message" => "Links listados com sucesso.",
                "data" => [
                    "links" => $links
                ],
                "success" => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Falha ao listas Links.",
                "data" => [
                    "exception" => $e
                ],
                "success" => false
            ], 500);
        }
    }
}
