<?php

namespace App\Http\Controllers\CRUD;

use App\Models\CRUD\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRUD\StoreCityRequest;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();

        return view('crud.city.list')
            ->withCities($cities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crud.city.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        City::create($request->all());

        return Redirect()->route('city.index')
            ->with('success', 'City created successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param City $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return view('crud.city.show')
            ->withCity($city);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param City $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        return view('crud.city.edit')
            ->withCity($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCityRequest $request, $id)
    {
        $update = [
            'name' => $request->name,
            'population' => $request->population,
            'postal_code' => $request->postal_code,
            'region' => $request->region,
            'country' => $request->country,
        ];

        City::where('id', $id)->update($update);

        return Redirect()->route('city.index')
            ->with('success', 'City updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        City::where('id', $id)->delete();

        return Redirect()->route('city.index')
            ->with('success', 'City deleted successfully !');
    }
}
