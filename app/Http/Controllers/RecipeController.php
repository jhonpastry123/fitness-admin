<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\FoodValue;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $recipes = Recipe::latest()->paginate(5);

        return  view('recipes.index', compact('recipes'))
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
        $categories = Category::latest()->get();
        $fooditems = FoodItem::latest()->get();

        return view('recipes.create', compact('categories', 'fooditems'));
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
            'title' => 'required',
            'categories_id' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        //$image = base64_encode(file_get_contents($request->file('image')->pat‌​h()));
        $image = base64_encode(file_get_contents($request->image->path()));

        Recipe::create([
            'title' => $request->title,
            'categories_id' => $request->categories_id,
            'description' => $request->description,
            'image' => $image,
        ]);

        $food_id = explode(",", $request->food_id);
        
        $food_amount = explode(",", $request->food_amount);

        $recipe = Recipe::latest()->first();

        for ($i=0; $i < count($food_id); $i++) {
            # code...
            FoodValue::create([
                'recipes_id' => $recipe->id,
                'food_items_id' => $food_id[$i],
                'amount' => str_replace(',', '.', $food_amount[$i])
            ]);
        }

        return  redirect()->route('recipes.index')
                ->with('success','Recipe created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        //
        $foodvalues = FoodValue::where('recipes_id', $recipe->id)->latest()->get();
        return view('recipes.show',compact('recipe', 'foodvalues'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipe $recipe)
    {
        //
        $categories = Category::latest()->get();
        $fooditems = FoodItem::latest()->get();
        $foodvalues = FoodValue::where('recipes_id', $recipe->id)->latest()->get();

        return view('recipes.edit',compact('recipe', 'categories', 'fooditems', 'foodvalues'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
        $request->validate([
            'title' => 'required',
            'categories_id' => 'required',
            'description' => 'required',
        ]);

        //$image = base64_encode(file_get_contents($request->file('image')->pat‌​h()));
        if(!$request->image)
            $image = $recipe->image;
        else
            $image = base64_encode(file_get_contents($request->image->path()));

        $recipe->update([
            'title' => $request->title,
            'categories_id' => $request->categories_id,
            'description' => $request->description,
            'image' => $image,
        ]);
        
        $food_id = explode(",", $request->food_id);
        $food_amount = explode(",", $request->food_amount);
        $foodvalues = FoodValue::whereNotIn('food_items_id', $food_id)->where('recipes_id', $recipe->id)->get();

        for ($i=0; $i < count($foodvalues); $i++) {
            # code...
            $foodvalues[$i]->delete();
        }
        
        for ($i=0; $i < count($food_id); $i++) {
            if($food_id[$i] != "") {
                FoodValue::updateOrCreate([
                    'recipes_id' => $recipe->id,
                    'food_items_id' => $food_id[$i],
                ], ['amount' => $food_amount[$i]]);
            }
        }

        return  redirect()->route('recipes.index')
                ->with('success','Recipe updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        //
        $foodvalues = FoodValue::where('recipes_id', $recipe->id)->get();
        foreach($foodvalues as $foodvalue) {
            $foodvalue->delete();
        }
        $recipe->delete();

        return  redirect()->route('recipes.index')
                ->with('success','Recipe deleted successfully');
    }
}
