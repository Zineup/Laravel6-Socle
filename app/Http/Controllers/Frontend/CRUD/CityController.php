<?php

namespace App\Http\Controllers\Frontend\CRUD;

use App\Models\CRUD\City;
use App\Http\Controllers\Controller;
use App\Repositories\Frontend\CRUD\CityRepository;
use App\Http\Requests\Frontend\CRUD\StoreCityRequest;

class CityController extends Controller
{
    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * UserController constructor.
     *
     * @param CityRepository $cityRepository
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = $this->cityRepository->getPaginated(20, 'id', 'asc');

        return view('frontend.crud.city.list')
            ->withCities($cities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.crud.city.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCityRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $this->cityRepository->create($request->only(
            'name',
            'postal_code',
            'population',
            'region',
            'country',
        ));

        return Redirect()->route('frontend.crud.city.index')
            ->withFlashSuccess('City was successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param City $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return view('frontend.crud.city.show')
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
        return view('frontend.crud.city.edit')
            ->withCity($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreCityRequest $request
     * @param City $city
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCityRequest $request, City $city)
    {
        $this->cityRepository->update($city, $request->only(
            'name',
            'postal_code',
            'population',
            'region',
            'country',
        ));

        return Redirect()->route('frontend.crud.city.index')
            ->withFlashSuccess('City was successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cityRepository->deleteById($id);

        return Redirect()->route('frontend.crud.city.index')
            ->withFlashSuccess('City was successfully deleted.');
    }
}
