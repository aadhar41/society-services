<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Society;
use App\Models\Block;
use App\Models\Flat;
use App\Models\Plot;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Str;

class FlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $propertyTypes = getPropertyTypes();
        $key1 = array_rand($propertyTypes, 1);
        return [
            'unique_code' => Str::random(10),
            'user_id' => User::pluck('id')->random(),
            'society_id' => Society::pluck('id')->random(),
            'block_id' => Block::pluck('id')->random(),
            'plot_id' => Plot::pluck('id')->random(),
            'name' => $this->faker->company(),
            'slug' => $this->faker->sentence(),
            'flat_no' => Str::random(3),
            'description' => $this->faker->realText($maxNbChars = 100, $indexSize = 2),
            'mobile_no' => $this->faker->phoneNumber(),
            'property_type' => "1",
            'tenant_name' => $this->faker->company(),
            'tenant_contact' => $this->faker->phoneNumber(),
            'status' => "1",
        ];
    }
}
