<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return "in CategoryController";
    }


    /**
     * Extragerea produselor si livrarea acestora
     */
    public function getAllCategory(Request $request)
    {
   
       return Category::all();
    }

   function addCategory(Request $request) {

      return Category::create($request->all());
  }

  function updateCategory(Request $request, $categoryId) {

   try {
       $category = Category::findOrFail($categoryId);
   } catch (ModelNotFoundException $e) {
       return response()->json([
           'message' => 'Category not found.'
       ], 403);
   }

   $category->update($request->all());

   return response()->json(['message' => 'Category updated successfully.']);
}

function deleteCategory(Request $request, $categoryId) {

   try {
       $category = Category::find($categoryId);
   } catch (ModelNotFoundException $e) {
       return response()->json([
           'message' => 'Category not found.'
       ], 403);
   }

   $category->delete();

   return response()->json(['message' => 'Category deleted successfully.']);
}


}
