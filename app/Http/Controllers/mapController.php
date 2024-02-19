<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mapController extends Controller
{
    public function index()
    {
        $data = file_get_contents(storage_path('app/mock.txt'));
        $people = explode("\n", $data);

        $markers = [];

        foreach ($people as $person) {
            $info = explode(",", $person);

            if (count($info) > 1) {
                $markers[] = [
                    'id' => $info[0],
                    'first_name' => $info[1],
                    'last_name' => $info[2],
                    'gender' => $info[3],
                    'lat'    => $info[4],
                    'lon'    => $info[5]
                ];
            }
        }
        unset($markers[0]);

        return view('map', compact('people'));

        // return view('map', compact('markers'));
    }

    public function dashboard()
    {
        return view('dashboard');
    }
    public function countMaleFemale(Request $request)
    {

        $data = file_get_contents(storage_path('app/mock.txt'));
        $people = explode("\n", $data);

        $markers = [];
        $maleCount = 0;
        $femaleCount = 0;

        foreach ($people as $person) {
            $info = explode(",", $person);

            if (count($info) > 1) {

                $markers[] = [
                    'id' => $info[0],
                    'first_name' => $info[1],
                    'last_name' => $info[2],
                    'gender' => $info[3],
                    'lat'    => $info[4],
                    'lon'    => $info[5]
                ];

                $gender = trim($info[3]); // Assuming gender is the fourth element
                if ($gender === 'Male') {
                    $maleCount++;
                } elseif ($gender === 'Female') {
                    $femaleCount++;
                }
            }
        }
        unset($markers[0]);

        $perPage = 10; // Number of items per page
        $currentPage = $request->query('page', 1); // Get the current page number from the request query parameters

        $totalItems = count($markers);
        $totalPages = ceil($totalItems / $perPage);

        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($markers, $offset, $perPage);

        return view('mock_file_info', [
            'markers' => $paginatedData,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount
        ]);
    }

    public function filterPeople(Request $request)
    {
        $location =  $request->input('location'); // Location name (e.g., London, Paris, Kansas City)
        $radius = 2000; // Max radius in kilometers
        $data = file_get_contents(storage_path('app/mock.txt'));
        $people = explode("\n", $data);
        unset($people[0]);
        $radius = 2000;

        foreach ($people as $person) {
            $info = explode(",", $person);
            if (count($info) > 1) {
                $locationCoordinate = $this->getLocationCoordinates($location);
                $distance = $this->calculateDistance($locationCoordinate['latitude'], $locationCoordinate['longitude'], $info[4], $info[5]);
                if ($distance <= $radius) {
                    $filteredData[] = [
                        'id'  => $info[0],
                        'first_name' => $info[1],
                        'last_name' => $info[2],
                        'gender' => $info[3],
                        'lat' => $info[4],
                        'lon' => $info[5]
                    ];
                }
            }
        }

        return response()->json($filteredData);
    }
    public function searchName(Request $request)
    {
        $query = $request->input('query');

        $data = file_get_contents(storage_path('app/mock.txt'));
        $lines = explode("\n", $data);
        unset($lines[0]);

        $results = [];
        foreach ($lines as $line) {
            if ((strpos($line, $query) !== false)) {
                $results[] = $line;
            }
        }

        // Return search results as JSON response
        return response()->json($results);
    }
    //  function to calculate distance between two places
    public  function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lon3 = floatval($lon2) - $lon1;
        $lonDelta = deg2rad($lon3);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in kilometers

        return $distance;
    }

    // Helper function to retrieve latitude and longitude of a location
    private function getLocationCoordinates($location)
    {
        // Implement logic to fetch latitude and longitude of the location (e.g., using a geocoding API)
        // For demonstration purposes, return hardcoded coordinates for London
        switch ($location) {
            case 'London':
                return ['latitude' => 51.5074, 'longitude' => -0.1278];
            case 'Paris':
                return ['latitude' => 48.8566, 'longitude' => 2.3522];
            case 'Kansas City':
                return ['latitude' => 39.0997, 'longitude' => -94.5786];
            default:
                return ['latitude' => 0, 'longitude' => 0];
        }
    }
}
