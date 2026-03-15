<?php

namespace App\Repositories;

use App\Interfaces\FlatRepositoryInterface;
use App\Models\Flat;

class FlatRepository implements FlatRepositoryInterface
{
    /**
     * The function getAllFlats() returns all active flats in the latest order.
     * 
     * @return Object a collection of active Flat models, ordered by the latest created date.
     */
    public function getAllFlats()
    {
        return Flat::active()->latest()->get();
    }

    /**
     * The function "getFlatById" retrieves an active flat by its society ID.
     * 
     * @param $id The $id parameter is the unique identifier of the flat in the database.
     * It is used to retrieve a specific flat from the database based on its ID.
     * 
     * @return Object a single active Flat model instance with the given $id. If no matching Flat model
     * is found, it will throw a ModelNotFoundException.
     */
    public function getFlatById($id)
    {
        return Flat::active()->findOrFail($id);
    }

    /**
     * The deleteFlat function deletes a flat from the database based on the given societyId.
     * 
     * @param Integer $id The $id parameter is the unique identifier of the flat that
     * you want to delete.
     */
    public function deleteFlat($id)
    {
        Flat::destroy($id);
    }

    /**
     * The function creates a new flat using the given society details.
     * 
     * @param Array societyDetails An array containing the details of the society, such as the society name,
     * address, number of floors, etc.
     * 
     * @return Object the result of the create method of the Flat model.
     */
    public function createFlat($societyDetails)
    {
        return Flat::create($societyDetails);
    }

    /**
     * The function updates the details of a flat in a society and returns the updated flat.
     * 
     * @param Integer id The id parameter is the unique identifier of the flat that needs to be updated. It is
     * used to find the specific flat in the database.
     * @param Array newDetails  is an array that contains the updated details of the flat. It
     * could include properties such as flat number, floor number, area, number of rooms, etc.
     * 
     * @return Object the updated flat object.
     */
    public function updateFlat($id, $newDetails)
    {
        $society = Flat::find($id);
        $society->update($newDetails);
        return $society;
    }

    /**
     * The function enables a record by updating its status to "1" in the database.
     * 
     * @param Integer id The parameter "id" is the unique identifier of the record that needs to be enabled.
     * 
     * @return Object the updated data object.
     */
    public function enableRecord($id)
    {
        $data = Flat::findOrFail($id);
        $data->status = "1";
        $data->save();
        return $data;
    }

    /**
     * The function disables a record by setting its status to "0" in a PHP application.
     * 
     * @param Integer id The parameter "id" is the unique identifier of the record that needs to be disabled.
     * 
     * @return Object the updated record with the status set to "0".
     */
    public function disableRecord($id)
    {
        $data = Flat::findOrFail($id);
        $data->status = "0";
        $data->save();
        return $data;
    }
}
