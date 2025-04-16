<?php

use App\Models\Block;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Flat;
use App\Models\Plot;
use App\Models\Society;
use App\Models\User;
use Carbon\Carbon;

/* Checking if the function getCountries exists, if it does not exist, it creates it. */
if (!function_exists('getCountries')) {
    /**
     * It returns a list of countries from the database, ordered by name, and returns the list as an
     * array of key-value pairs.
     * 
     * The key is the country ID, and the value is the country name.
     * 
     * The function is called in the view like this:
     * 
     * @return collection A collection of countries.
     */
    function getCountries()
    {
        return Country::active()->orderBy('name', 'asc')->get()->pluck("name", "id");
    }
}

/* Checking if the function getState exists, if it does not exist, it creates it. */
if (!function_exists('getState')) {
    /**
     * It returns a collection of states from the database, ordered by state_title, and then plucked to
     * return only the id and state_title.
     * 
     * @return collection A collection of states.
     */
    function getState()
    {
        return State::active()->orderBy('state_title', 'asc')->get()->pluck("state_title", "id");
    }
}

/* Checking if the function getCities exists, if it does not exist, it creates it. */
if (!function_exists('getCities')) {
    /**
     * It returns a list of cities from the database, ordered by name, and returns the list as an array
     * of key-value pairs.
     * 
     * @return collection A collection of cities.
     */
    function getCities()
    {
        return City::active()->orderBy('name', 'asc')->get()->pluck("name", "id");
    }
}

/* Checking if the function getSocieties exists, if it does not exist, it creates it. */
if (!function_exists('getSocieties')) {
    /**
     * It returns a list of all active societies, ordered by name, and returns the name and id of each
     * society.
     * 
     * @return collection A collection of societies.
     */
    function getSocieties()
    {
        return Society::active()->orderBy('name', 'asc')->get()->pluck("name", "id");
    }
}

/* Checking if the function getBlocks exists, if it does not exist, it creates it. */
if (!function_exists('getBlocks')) {
    /**
     * It returns a collection of all active blocks, ordered by name, and then plucked to return only
     * the name and id of each block.
     * 
     * @return collection A collection of Block objects.
     */
    function getBlocks()
    {
        return Block::active()->orderBy('name', 'asc')->get()->pluck("name", "id");
    }
}

/* Checking if the function getPlots exists, if it does not exist, it creates it. */
if (!function_exists('getPlots')) {
    /**
     * It returns a collection of all active plots, ordered by name, and then returns a collection of
     * the names and ids of those plots.
     * 
     * @return collection A collection of plots.
     */
    function getPlots()
    {
        return Plot::active()->orderBy('name', 'asc')->get()->pluck("name", "id");
    }
}

/* Checking if the function getFlats exists, if it does not exist, it creates it. */
if (!function_exists('getFlats')) {
    /**
     * It returns a collection of flats that are active, ordered by name, and plucked to only return
     * the name and id.
     * 
     * @return A collection of flats.
     */
    function getFlats()
    {
        return Flat::active()->orderBy('name', 'asc')->get()->pluck("name", "id");
    }
}

/* Checking if the function getPropertyTypes exists, if it does not exist, it creates it. */
if (!function_exists('getPropertyTypes')) {
    /**
     * It returns an array of property types
     */
    function getPropertyTypes()
    {
        return [1 => "self occupied", 2 => "Rented", 3 => "Locked", 4 => "Unsold", 5 => "ceiled"];
    }
}

/* Checking if the function getSocietyBlocks exists, if it does not exist, it creates it. */
if (!function_exists('getSocietyBlocks')) {
    /**
     * It returns a list of blocks for a given society
     * 
     * @param id The id of the society
     * 
     * @return collection A collection of blocks.
     */
    function getSocietyBlocks($id = "")
    {
        return Block::active()->orderBy('name', 'asc')->where(['society_id' => $id])->get()->pluck("name", "id");
    }
}

/* Checking if the function getBlockPlots exists, if it does not exist, it creates it. */
if (!function_exists('getBlockPlots')) {
    /**
     * It returns a list of plots that are active and ordered by name, where the block_id is equal to
     * the id passed to the function
     * 
     * @param id The id of the block
     * 
     * @return collection A collection of plots.
     */
    function getBlockPlots($id = "")
    {
        return Plot::active()->orderBy('name', 'asc')->where(['block_id' => $id])->get()->pluck("name", "id");
    }
}

/* Checking if the function getPlotsFlats exists, if it does not exist, it creates it. */
if (!function_exists('getPlotsFlats')) {
    /**
     * It returns a list of flats for a given plot
     * 
     * @param id The id of the plot
     * 
     * @return Object A collection of objects.
     */
    function getPlotsFlats($id = "")
    {
        return Flat::active()->orderBy('flat_no', 'asc')->where(['plot_id' => $id])->get(["id","name","flat_no"]);
    }
}

/* Checking if the function getMaintenanceTypes exists, if it does not exist, it creates it. */
if (!function_exists('getMaintenanceTypes')) {
    /**
     * It returns an array of maintenance types.
     * 
     * @return array An array of key value pairs.
     */
    function getMaintenanceTypes()
    {
        return [
            1 => "Monthly", 
            2 => "Lift", 
            3 => "donation", 
            4 => "contribution", 
            5 => "other"
        ];
    }
}

/* Checking if the function getPaymentStatus exists, if it does not exist, it creates it. */
if (!function_exists('getPaymentStatus')) {
    /**
     * It returns an array of payment statuses.
     * 
     * @return array An array of key value pairs.
     */
    function getPaymentStatus()
    {
        return [
            1 => "Complete", 
            2 => "Pending", 
            3 => "Extra"
        ];
    }
}

/* Checking if the function getPaymentStatus exists, if it does not exist, it creates it. */
if (!function_exists('getMonths')) {
    /**
     * It returns an array of months.
     * 
     * @return array An array of months.
     */
    function getMonths()
    {
        return [
            1 => "January", 
            2 => "February", 
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        ];
    }
}


/* The code is checking if the function `getFlatDetails` already exists. If it does not exist, it
creates the function. */
if (!function_exists('getFlatDetails')) {
    /**
     * The function "getFlatDetails" returns a collection of active flat names ordered by name.
     * 
     * @return Object collection of flat details, specifically the names and IDs of active flats, sorted in
     * ascending order by name.
     */
    function getFlatDetails()
    {
        return Flat::active()->orderBy('flat_no', 'asc')->get(["id","name","flat_no"]);
    }
}


if (! function_exists('time_since')) {
    function time_since($timestamp)
    {
        $now = Carbon::now();
        $timeSince = $now->diffForHumans($timestamp, [
            'parts' => 1,
            'syntax' => Carbon::DIFF_ABSOLUTE
        ]);

        return $timeSince;
    }
}