<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
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

    public function links()
    {
        try {
            $categories = $this->getUserCategories()->get();
            $categ_links = [];
            foreach ($categories as $category) {
                $category->links;
                $categ_links[] = $category;
            }
            return response()->json([
                "message" => "Links listados com sucesso.",
                "data" => [
                    "categories_links" => $categ_links
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

    public function getUserCategories()
    {
        return Category::where('user_id', '=', request()->user->id);
    }

    public function store(Request $req)
    {
        try {
            $this->validate($req, [
                'name' => [
                    'required'
                ],
            ], [
                'name.required' => 'O nome da categoria deve ser informado.',
            ]);

            $category = new Category($req->all());
            $category->user_id = request()->user->id;
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
                    "exception" => $e,
                    "errors" => (isset($e->validator) ? $e->validator->messages() : [])
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
}
