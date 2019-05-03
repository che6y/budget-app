<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     */
    public function index()
    {
        $category = Category::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return new CategoryCollection($category);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return CategoryResource
     */
    public function store( Request $request )
    {
        $data = $request->validate([
            'title' => 'required|min:3|max:255',
            'icon'  => 'min:1|max:50'
        ]);
        $category = Category::create( $data );

        return new CategoryResource( $category );
    }

    /**
     * Display the specified purchase.
     *
     * @param  Category $category
     * @return CategoryResource
     */
    public function show( Category $category )
    {
        return new CategoryResource( $category );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return CategoryResource
     */
    public function update( Category $category, Request $request )
    {
        $data = $request->validate([
            'title' => 'required|min:3|max:255',
            'icon'  => 'min:1|max:50'
        ]);

        $category->update( $data );

        return new CategoryResource( $category );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Category $category )
    {
        $category->delete();

        return response(null, 204);
    }
}
