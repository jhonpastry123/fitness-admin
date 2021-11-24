<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;
use App\Http\Resources\Sport as SportResource;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Sport::query();
        $sports = $query->latest()->get();

        return SportResource::collection($sports);
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

        Sport::create($request->all());

        return  redirect()->route('sports.index')
            ->with('success', 'Sport created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sport  $sport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sport $sport)
    {
        //
        $request->validate([
            'name' => 'required',
        ]);

        $sport->update($request->all());

        return  redirect()->route('sports.index')
            ->with('success', 'Sport updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sport  $sport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sport $sport)
    {
        //
        $sport->delete();

        return  redirect()->route('sports.index')
            ->with('success', 'Sport deleted successfully');
    }
}
