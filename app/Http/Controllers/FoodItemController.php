<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodValue;
use App\Models\FoodCategory;
use App\Models\FoodRelation;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fooditems = FoodItem::latest()->get();

        foreach ($fooditems as $key => $fooditem) {

            $fooditem->carbon = str_replace('.', ',', $fooditem->carbon);
            $fooditem->protein = str_replace('.', ',', $fooditem->protein);
            $fooditem->fat = str_replace('.', ',', $fooditem->fat);
            $fooditem->portion_in_grams = str_replace('.', ',', $fooditem->portion_in_grams);
            $fooditem->kcal = str_replace('.', ',', $fooditem->kcal);
            $fooditem->serving_size = str_replace('.', ',', $fooditem->serving_size);

            $categories = [];
            foreach ($fooditem->foodRelations as $key2 => $relation) {
                array_push($categories, $relation->foodCategory->name);
            }
            $fooditem->categories = implode(', ', $categories);
        }

        return  view('fooditems.index', compact('fooditems'))
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
        $foodcategories = FoodCategory::latest()->get();
        return view('fooditems.create', compact('foodcategories'));
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
            'food_name' => 'required',
            'food_categories_id' => 'required',
            'carbon' => 'required',
            'protein' => 'required',
            'fat' => 'required',
            'portion_in_grams' => 'required',
            'kcal' => 'required',
            'serving_size' => 'required',
            'serving_prefix' => 'required',
        ]);

        $request->merge([
            'carbon' => str_replace(',', '.', $request->input('carbon')),
            'protein' => str_replace(',', '.', $request->input('protein')),
            'fat' => str_replace(',', '.', $request->input('fat')),
            'portion_in_grams' => str_replace(',', '.', $request->input('portion_in_grams')),
            'kcal' => str_replace(',', '.', $request->input('kcal')),
            'serving_size' => str_replace(',', '.', $request->input('serving_size')),
        ]);
        
        $food = FoodItem::create($request->all());

        $categories = $request->food_categories_id;
        foreach ($categories as $key => $category) {
            FoodRelation::create([
                'food_item_id' => $food->id,
                'food_category_id' => $category
            ]);
        }

        return  redirect()->route('fooditems.index')
                ->with('success','FoodItem created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Http\Response
     */
    public function show(FoodItem $fooditem)
    {
        //
        $foodcategories = FoodCategory::latest()->get();
        $fooditem->category_ids = $fooditem->foodRelations->pluck('food_category_id')->ToArray();
        return view('fooditems.show',compact('fooditem', 'foodcategories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodItem $fooditem)
    {
        //
        $fooditem->carbon = str_replace('.', ',', $fooditem->carbon);
        $fooditem->protein = str_replace('.', ',', $fooditem->protein);
        $fooditem->fat = str_replace('.', ',', $fooditem->fat);
        $fooditem->portion_in_grams = str_replace('.', ',', $fooditem->portion_in_grams);
        $fooditem->kcal = str_replace('.', ',', $fooditem->kcal);
        $fooditem->serving_size = str_replace('.', ',', $fooditem->serving_size);

        $foodcategories = FoodCategory::latest()->get();
        $fooditem->category_ids = $fooditem->foodRelations->pluck('food_category_id')->ToArray();
        return view('fooditems.edit',compact('fooditem', 'foodcategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodItem $fooditem)
    {
        //
        $request->validate([
            'food_name' => 'required',
            'food_categories_id' => 'required',
            'carbon' => 'required',
            'protein' => 'required',
            'fat' => 'required',
            'portion_in_grams' => 'required',
            'kcal' => 'required',
            'serving_size' => 'required',
            'serving_prefix' => 'required',
        ]);

        $request->merge([
            'carbon' => str_replace(',', '.', $request->input('carbon')),
            'protein' => str_replace(',', '.', $request->input('protein')),
            'fat' => str_replace(',', '.', $request->input('fat')),
            'portion_in_grams' => str_replace(',', '.', $request->input('portion_in_grams')),
            'kcal' => str_replace(',', '.', $request->input('kcal')),
            'serving_size' => str_replace(',', '.', $request->input('serving_size')),
        ]);

        $fooditem->update($request->all());

        $categories = $request->food_categories_id;
        foreach ($categories as $key => $category) {
            FoodRelation::updateOrCreate([
                'food_item_id' => $fooditem->id,
                'food_category_id' => $category
            ]);
        }

        $deleteCategories = FoodRelation::whereNotIn('food_category_id', $categories)->where('food_item_id', $fooditem->id)->get();
        foreach ($deleteCategories as $key => $category) {
            $category->delete();
        }


        return  redirect()->route('fooditems.index')
                ->with('success','FoodItem updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodItem $fooditem)
    {
        //
        $foodvalues = FoodValue::where('food_items_id', $fooditem->id)->get();
        $foodrelations = FoodRelation::where('food_item_id', $fooditem->id)->get();

        foreach($foodvalues as $foodvalue) {
            $foodvalue->delete();
        }

        foreach($foodrelations as $relation) {
            $relation->delete();
        }

        $fooditem->delete();

        return  redirect()->route('fooditems.index')
                ->with('success','FoodItem deleted successfully');
    }
}
