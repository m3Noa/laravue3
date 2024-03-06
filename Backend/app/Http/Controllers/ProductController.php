<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    private $DUMMY_DOMAIN = "https://dummyjson.com";

    public function get(Request $request)
    {
        try {
            $query = $request->all();
            $queryStr = http_build_query($query);
            $res = Http::get("$this->DUMMY_DOMAIN/products?$queryStr");
            if ($res->status() !== 200) {
                return response()->json([
                    'message' => "Error"
                ], $res->status());
            }

            return response()->json(json_decode($res->body()));
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Internal Server Error"
            ], 500);
        }
    }

    public function create(ProductRequest $request) {
        try {
            $res = Http::accept('application/json')->post("$this->DUMMY_DOMAIN/products/add", $request->all());
            if ($res->status() !== 200) {
                return response()->json([
                    'message' => "Error"
                ], $res->status());
            }
            return response()->json(json_decode($res->body()));
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Internal Server Error"
            ], 500);
        }
    }

    public function update(ProductRequest $request, int $productId) {
        try {
            $res = Http::accept('application/json')->put("$this->DUMMY_DOMAIN/products/$productId", $request->all());
            if ($res->status() !== 200) {
                return response()->json([
                    'message' => "Error"
                ], $res->status());
            }

            return response()->json(json_decode($res->body()));
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Internal Server Error"
            ], 500);
        }
    }

    public function delete(int $productId) {
        try {
            $res = Http::accept('application/json')->delete("$this->DUMMY_DOMAIN/products/$productId");
            if ($res->status() !== 200) {
                return response()->json([
                    'message' => "Error"
                ], $res->status());
            }

            return response()->json([
                'message' => 'Delete successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Internal Server Error"
            ], 500);
        }
    }
}
