<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return "in ProductController";
    }


    /**
     * Extragerea produselor si livrarea acestora
     */
    public function getAllProduct(Request $request)
    {
   
       return Product::all();
    }

    public function getOneProduct($product_id )
    {
        return Product::where('id', $product_id)->get() ;
    }

    /**
     * 
     */
    public function search($category_id)
    {
        return Product::where('category_id', 'like', $category_id.'%')->get() ;
    }


    function addProduct(Request $request) {

        return Product::create($request->all());
    }
  
    function updateProduct(Request $request, $productId) {
  
     try {
         $product = Product::findOrFail($productId);
     } catch (ModelNotFoundException $e) {
         return response()->json([
             'message' => 'Product not found.'
         ], 403);
     }
  
     $product->update($request->all());
  
     return response()->json(['message' => 'Product updated successfully.']);
  }
  
  function deleteProduct(Request $request, $productId) {
  
     try {
         $product = Product::find($productId);
     } catch (ModelNotFoundException $e) {
         return response()->json([
             'message' => 'Product not found.'
         ], 403);
     }
  
     $product->delete();
  
     return response()->json(['message' => 'Product deleted successfully.']);
  }

}
