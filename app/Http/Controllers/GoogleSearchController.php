<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class GoogleSearchController extends Controller
{
    public function search(Request $request)
    {

        $query = $request->input('q');
        $searchEngineId = 'f08baf9c10f834ce8';
        $apiKey = 'AIzaSyDrZePepMW-vFN5S5GMW6-hXUoJXjnWuSc';

        $client = new Client();



        $response = $client->get('https://www.googleapis.com/customsearch/v1?key=AIzaSyDrZePepMW-vFN5S5GMW6-hXUoJXjnWuSc&cx=f08baf9c10f834ce8:omuauf_lfve&q=developer', [
            'query' => [
                'q' => $query,
                'key' => $apiKey,
                'cx' => $searchEngineId,
            ],
        ]);

        $results = json_decode($response->getBody()->getContents());

        return view('results', compact('results'));
    }

    public function searching(Request $request)
    {
        $apiKey = 'AIzaSyCkm4x5Qob3k-0XrfQjfzn4nPp2M99GqgE';
        $location = $request->input('location'); // You can pass location parameters from your Blade view
        $radius = 5000;
        $category = $request->input('category');

        $client = new Client();

        $response = $client->get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'query' => [
                'location' => $location,
                'radius' => $radius,
                'type' => $category,
                'key' => $apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        return response()->json($data);
    }
    public function getPlaceDetails(Request $request)
    {
        $apiKey = 'AIzaSyCkm4x5Qob3k-0XrfQjfzn4nPp2M99GqgE';
        $placeId = $request->input('place_id');

        $client = new Client();

        try {
            $response = $client->get("https://maps.googleapis.com/maps/api/place/details/json", [
                'query' => [
                    'place_id' => $placeId,
                    'key' => $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
