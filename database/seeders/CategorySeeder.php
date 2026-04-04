<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Complaint\Models\ComplaintCategory;
use App\Domain\Society\Models\Society;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $societies = Society::all();

        if ($societies->isEmpty()) {
            $this->command->warn('No societies found. Please seed societies first.');
            return;
        }

        $categories = [
            'complaint' => [
                ['name' => 'Electricity', 'description' => 'Power failure, meter issues, or sparking'],
                ['name' => 'Water', 'description' => 'No water supply, leakage, or tank cleaning'],
                ['name' => 'Security', 'description' => 'Guard behavior, visitor issues, or gate lock'],
                ['name' => 'Cleaning', 'description' => 'Common area sweeping, garbage collection'],
                ['name' => 'Plumbing', 'description' => 'Tap leakage, pipe burst, or drainage block'],
                ['name' => 'Pest Control', 'description' => 'Mosquito fogging or termite treatment'],
            ],
            'notice' => [
                ['name' => 'Maintenance Bill', 'description' => 'Monthly billing updates and deadlines'],
                ['name' => 'Meeting', 'description' => 'AGM, SGM, or committee meetings'],
                ['name' => 'Event', 'description' => 'Festivals, sports day, or celebrations'],
                ['name' => 'Emergency', 'description' => 'Lift maintenance or water cut alerts'],
                ['name' => 'Rules', 'description' => 'Parking rules, pet policies, or renovation guidelines'],
            ],
            'document' => [
                ['name' => 'Bylaws', 'description' => 'Society constitution and registered rules'],
                ['name' => 'Audit Report', 'description' => 'Financial year audit statement'],
                ['name' => 'MOM', 'description' => 'Minutes of Meeting for committee sessions'],
                ['name' => 'NOC', 'description' => 'No Objection Certificate templates'],
                ['name' => 'Layout Plan', 'description' => 'Approved society maps and blueprints'],
            ],
            'vendor' => [
                ['name' => 'Electrician', 'description' => 'Licensed electrical contractors'],
                ['name' => 'Plumber', 'description' => 'General plumbing and drainage service'],
                ['name' => 'Security Agency', 'description' => 'Manpower provide for guarding'],
                ['name' => 'Housekeeping', 'description' => 'Contract-based cleaning staff'],
                ['name' => 'Lift Service', 'description' => 'AMC contractors for elevators'],
            ],
            'asset' => [
                ['name' => 'Generator', 'description' => 'DG sets and power backup equipment'],
                ['name' => 'Lift', 'description' => 'Passenger and service elevators'],
                ['name' => 'CCTV', 'description' => 'Surveillance cameras and DVR/NVR'],
                ['name' => 'Water Pump', 'description' => 'Borewell and overhead tank motors'],
                ['name' => 'Fire Safety', 'description' => 'Extinguishers, hydrants, and sensors'],
            ],
        ];

        foreach ($societies as $society) {
            foreach ($categories as $module => $moduleCategories) {
                foreach ($moduleCategories as $cat) {
                    ComplaintCategory::updateOrCreate(
                        [
                            'society_id' => $society->id,
                            'name' => $cat['name'],
                            'module' => $module
                        ],
                        [
                            'uuid' => (string) Str::uuid(),
                            'description' => $cat['description'],
                            'sla_hours' => 48,
                        ]
                    );
                }
            }
        }

        $this->command->info('Categories seeded successfully for ' . $societies->count() . ' societies.');
    }
}
