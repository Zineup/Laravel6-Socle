<?php

namespace App\Repositories\Frontend\CRUD;

use App\Models\CRUD\City;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CityRepository.
 */
class CityRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     *
     * @param  City  $model
     */
    public function __construct(City $model)
    {
        $this->model = $model;
    }

    /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getPaginated($paged = 25, $orderBy = 'created_at', $sort = 'desc'): LengthAwarePaginator
    {
        return $this->model
            ->orderBy($orderBy, $sort)
            ->paginate($paged);
    }

    /**
     * @param array $data
     *
     * @return City
     */
    public function create(array $data): City
    {
        $city = $this->model::create([
            'name' => $data['name'],
            'postal_code' => $data['postal_code'],
            'population' => $data['population'],
            'region' => $data['region'],
            'country' => $data['country'],
        ]);

        return $city;
    }

    /**
     * @param City  $city
     * @param array $data
     *
     * @return City
     */
    public function update(City $city, array $data)
    {
        $city->update([
            'name' => $data['name'],
            'postal_code' => $data['postal_code'],
            'population' => $data['population'],
            'region' => $data['region'],
            'country' => $data['country'],
        ]);

        return $city;
    }

    /**
     * @param int $id
     */
    public function deleteById($id)
    {
        City::where('id', $id)->delete();

        return true;
    }
}
