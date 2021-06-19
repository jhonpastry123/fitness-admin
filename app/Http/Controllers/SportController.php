<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use Illuminate\Http\Request;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sports = Sport::latest()->paginate(5);
        foreach ($sports as $sport) {
            $sport->coefficient = str_replace('.', ',', $sport->coefficient);
        }

        return  view('sports.index', compact('sports'))
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
        return view('sports.create');
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
            'coefficient' => 'required',
        ]);

        $request->merge([
            'coefficient' => str_replace(',', '.', $request->input('coefficient'))
        ]);

        Sport::create($request->all());

        return  redirect()->route('sports.index')
            ->with('success', 'Sport created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sport  $sport
     * @return \Illuminate\Http\Response
     */
    public function show(Sport $sport)
    {
        //
        return view('sports.show', compact('sport'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sport  $sport
     * @return \Illuminate\Http\Response
     */
    public function edit(Sport $sport)
    {
        //
        $sport->coefficient = str_replace('.', ',', $sport->coefficient);
        return view('sports.edit', compact('sport'));
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
            'coefficient' => 'required',
        ]);
        
        $request->merge([
            'coefficient' => str_replace(',', '.', $request->input('coefficient'))
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
