<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'accountant',
            'airport',
            'amusement_park',
            'art_gallery',
            'atm',
            'bakery',
            'bank',
            'bar',
            'beauty_salon',
            'bowling_alley',
            'bus_station',
            'cafe',
            'campground',
            'car_dealer',
            'car_wash',
            'casino',
            'church',
            'city_hall',
            'clothing_store',
            'college',
            'contractor',
            'convenience_store',
            'country',
            'courthouse',
            'delivery_service',
            'dentist',
            'department_store',
            'doctor',
            'dry_cleaner',
            'electrician',
            'electronics_store',
            'fast_food_restaurant',
            'financial_advisor',
            'fire_station',
            'gas_station',
            'grocery_or_supermarket',
            'gym',
            'hair_salon',
            'hospital',
            'hotel',
            'insurance_agency',
            'jewelry_store',
            'laundry_service',
            'lawyer',
            'library',
            'locality',
            'massage_therapist',
            'mosque',
            'motel',
            'movie_theater',
            'museum',
            'night_club',
            'park',
            'parking',
            'pharmacy',
            'plumber',
            'police_station',
            'postal_code',
            'real_estate_agency',
            'rest_area',
            'restaurant',
            'school',
            'shoe_store',
            'spa',
            'sports_complex',
            'stadium',
            'sublocality',
            'subway_station',
            'synagogue',
            'train_station'
        ];

        // Insert categories into the categories table
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
            ]);
        }
    }
}
