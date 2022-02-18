<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use App\Http\Resources\FoodCategory as FoodCategoryResource;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = FoodCategory::query();
        $categories = $query->latest()->get();

        return FoodCategoryResource::collection($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function show(FoodCategory $foodCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodCategory $foodCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodCategory $foodCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodCategory  $foodCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodCategory $foodCategory)
    {
        //
    }
}
