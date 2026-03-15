<?php

namespace App\Interfaces;

interface FlatRepositoryInterface
{
    public function getAllFlats();
    public function getFlatById($flatId);
    public function deleteFlat($flatId);
    public function createFlat($flatDetails);
    public function updateFlat($flatId, array $newDetails);
    public function enableRecord($flatId);
    public function disableRecord($flatId);
}