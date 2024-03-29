<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\FoodValue;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Storage;

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
        $recipes = Recipe::where('user_id', 0)->latest()->paginate(5);

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
        $fooditems = FoodItem::where('user_id', 0)->latest()->get();

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

        $imageName = 'image_' . time() . '.png'; //generating unique file name;

        $request->image->move(public_path('images'), $imageName);

        Recipe::create([
            'title' => $request->title,
            'categories_id' => $request->categories_id,
            'description' => $request->description,
            'image' => $imageName,
            'user_id' => 0
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
                ->with('success','Το γεύμα δημιουργήθηκε επιτυχώς!');
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
        $fooditems = FoodItem::where('user_id', 0)->latest()->get();
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
        $imageName = 'image_' . time() . '.png'; //generating unique file name;


        if(!$request->image)
            $imageName = $recipe->image;
        else {
            $imageName = 'image_' . time() . '.png';
            if(\File::exists(public_path('images/'.$recipe->image))){
                \File::delete(public_path('images/'.$recipe->image));
            }
            $request->image->move(public_path('images'), $imageName);
        }
            

        $recipe->update([
            'title' => $request->title,
            'categories_id' => $request->categories_id,
            'description' => $request->description,
            'image' => $imageName,
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
                ->with('success','Το γευμα ενημερώθηκε επιτυχώς!');
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
        if(\File::exists(public_path('images/'.$recipe->image))){
            \File::delete(public_path('images/'.$recipe->image));
        }
        $recipe->delete();

        return  redirect()->route('recipes.index')
                ->with('success','Το γεύμα διαγράφηκε επιτυχώς!');
    }
}
