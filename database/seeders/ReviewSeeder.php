<?php

namespace Database\Seeders;

use Botble\RealEstate\Models\Account;
use Botble\RealEstate\Models\Project;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        Review::truncate();

        $accountsCount = Account::count();
        $projectsCount = Project::count();
        $propertiesCount = Property::count();

        foreach (range(1, 200) as $i) {
            $reviewable = fake()->randomElement([
                ['id' => rand(1, $projectsCount), 'type' => Project::class],
                ['id' => rand(1, $propertiesCount), 'type' => Property::class],
            ]);

            Review::create([
                'account_id' => rand(1, $accountsCount),
                'reviewable_type' => $reviewable['type'],
                'reviewable_id' => $reviewable['id'],
                'content' => fake()->realText(rand(30, 300)),
                'star' => rand(1, 5),
            ]);
        }
    }
}
