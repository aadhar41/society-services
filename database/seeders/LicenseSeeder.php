<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    public function run(): void
    {
        $licenses = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'max_societies' => 1,
                'description' => 'Ideal for single society management.',
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'max_societies' => 5,
                'description' => 'For professionals managing multiple societies.',
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'max_societies' => 9999,
                'description' => 'Unlimited societies for large organizations.',
            ],
        ];

        foreach ($licenses as $license) {
            License::updateOrCreate(['slug' => $license['slug']], $license);
        }
    }
}
