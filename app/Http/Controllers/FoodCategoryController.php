<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\FoodRelation;
use Illuminate\Http\Request;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $foodcategories = FoodCategory::latest()->paginate(5);

        return  view('foodcategories.index', compact('foodcategories'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('foodcategories.create');
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
        $request->validate([
            'name' => 'required',
        ]);

        FoodCategory::create($request->all());

        return  redirect()->route('foodcategories.index')
                ->with('success','Food Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FoodCategory  $foodcategory
     * @return \Illuminate\Http\Response
     */
    public function show(FoodCategory $foodcategory)
    {
        //
        return view('foodcategories.show',compact('foodcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FoodCategory  $foodcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodCategory $foodcategory)
    {
        //
        return view('foodcategories.edit',compact('foodcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FoodCategory  $foodcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodCategory $foodcategory)
    {
        //
        $request->validate([
            'name' => 'required',
        ]);

        $foodcategory->update($request->all());

        return  redirect()->route('foodcategories.index')
                ->with('success','Food Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FoodCategory  $foodcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodCategory $foodcategory)
    {
        //
        $foodRelations = FoodRelation::where('food_category_id', $foodcategory->id)->get();

        $fooditem_ids = [];
        foreach($foodRelations as $foodRelation) {
            if (!in_array($foodRelation->foodItem->id, $fooditem_ids)) {
                array_push($fooditem_ids, $foodRelation->foodItem->id);
            }
            $foodRelation->delete();
        }
        $fooditems = FoodItem::whereIn('id', $fooditem_ids)->get();
        foreach ($fooditems as $key => $fooditem) {
            $fooditem->delete();
        }

        $foodcategory->delete();

        return  redirect()->route('foodcategories.index')
                ->with('success','Food Category deleted successfully');
    }
}
