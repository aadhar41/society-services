<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Domain\Society\Models\Society;
use App\Domain\Society\Models\Wing;
use App\Domain\Society\Models\Unit;
use App\Domain\Society\Models\ParkingSlot;
use App\Domain\Accounting\Models\Account;
use App\Domain\Accounting\Models\AccountGroup;
use App\Domain\Accounting\Models\Invoice;
use App\Domain\Accounting\Models\Payment;
use App\Domain\Complaint\Models\Complaint;
use App\Domain\Visitor\Models\Visitor;
use App\Domain\Booking\Models\Facility;
use App\Domain\Communication\Models\Notice;
use App\Domain\Communication\Models\Poll;
use App\Domain\Staff\Models\Staff;
use App\Domain\Vendor\Models\Vendor;
use App\Domain\Document\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class V2DataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting V2 Seeder...');
        
        // 1. Ensure Test User & Society
        $this->command->info('Setting up user and society...');
        $user = User::firstOrCreate(
            ['email' => 'aadhargaur41@gmail.com'],
            ['name' => 'Aadhar Gaur', 'password' => bcrypt('password')]
        );

        $society = Society::firstOrCreate(
            ['name' => 'Gaur Enclave'],
            [
                'registration_no' => 'REG12345',
                'address_line_1' => '123 Skyview Ave',
                'city' => 'Tech City',
                'state' => 'UP',
                'pincode' => '201301'
            ]
        );

        // Link User to Society if not linked
        if (!$user->societies()->where('erp_societies.id', $society->id)->exists()) {
            $user->societies()->attach($society->id);
        }

        // Set scope for some model creations that might need it
        // Or just set the IDs manually.

        $this->command->info('Infrastructure seeding...');
        $wingA = Wing::firstOrCreate(['society_id' => $society->id, 'name' => 'Wing A'], ['code' => 'A', 'total_floors' => 10]);
        $wingB = Wing::firstOrCreate(['society_id' => $society->id, 'name' => 'Wing B'], ['code' => 'B', 'total_floors' => 12]);

        for ($i = 101; $i <= 105; $i++) {
            Unit::firstOrCreate(
                ['society_id' => $society->id, 'wing_id' => $wingA->id, 'unit_number' => (string)$i],
                ['unit_type' => 'flat', 'area_sqft' => 1200, 'status' => true]
            );
        }

        for ($i = 1; $i <= 10; $i++) {
            ParkingSlot::firstOrCreate(
                ['society_id' => $society->id, 'slot_number' => 'P-' . $i],
                ['slot_type' => 'four_wheeler', 'status' => true, 'location' => 'Basement 1']
            );
        }

        $this->command->info('Accounting seeding...');
        $group = AccountGroup::firstOrCreate(['society_id' => $society->id, 'name' => 'Current Assets'], ['code' => 'ASSET01', 'nature' => 'asset', 'uuid' => Str::uuid()]);
        $account = Account::firstOrCreate(['group_id' => $group->id, 'name' => 'Maintenance Receivable'], ['code' => 'MAINT01', 'uuid' => Str::uuid(), 'society_id' => $society->id]);

        $unit = Unit::where('society_id', $society->id)->where('unit_number', '101')->first();
        if ($unit) {
            // Create a member for the unit first
            $member = \App\Domain\Member\Models\Member::firstOrCreate(
                ['society_id' => $society->id, 'unit_id' => $unit->id],
                [
                    'name' => 'John Doe',
                    'member_type' => 'owner',
                    'phone' => '9999988888',
                    'email' => 'john@example.com',
                    'is_primary' => true
                ]
            );

            Invoice::firstOrCreate(
                ['invoice_number' => 'INV-2026-001'],
                [
                    'society_id' => $society->id,
                    'unit_id' => $unit->id,
                    'member_id' => $member->id,
                    'total_amount' => 2500.00,
                    'net_amount' => 2500.00,
                    'balance_due' => 2500.00,
                    'billing_period_start' => now()->startOfMonth(),
                    'billing_period_end' => now()->endOfMonth(),
                    'due_date' => now()->addDays(10),
                    'status' => 'draft'
                ]
            );
            
            Payment::create([
                'society_id' => $society->id,
                'unit_id' => $unit->id,
                'member_id' => $member->id,
                'amount' => 1500.00,
                'payment_date' => now(),
                'payment_method' => 'upi',
                'status' => 'confirmed',
                'transaction_reference' => 'TXN' . Str::random(10)
            ]);
        }

        $this->command->info('Complaints seeding...');
        $member = \App\Domain\Member\Models\Member::where('society_id', $society->id)->first();
        Complaint::firstOrCreate(
            ['ticket_number' => 'TCK-001'],
            [
                'society_id' => $society->id,
                'member_id' => $member->id ?? $user->id,
                'unit_id' => $unit->id ?? null,
                'category_id' => \App\Domain\Complaint\Models\ComplaintCategory::firstOrCreate(
                    ['society_id' => $society->id, 'name' => 'Plumbing'],
                    ['description' => 'Water and pipe related issues']
                )->id,
                'title' => 'Elevator Malfunction',
                'description' => 'The elevator in Wing A is making loud noises and stopping between floors.',
                'priority' => 'urgent',
                'status' => 'open'
            ]
        );

        $this->command->info('Visitors seeding...');
        Visitor::create([
            'society_id' => $society->id,
            'name' => 'Rahul Sharma',
            'phone' => '9876543210',
            'purpose' => 'delivery',
            'unit_id' => $unit->id ?? null,
            'status' => 'checked_in',
            'check_in_at' => now()->subHours(2)
        ]);

        // 6. Facilities
        Facility::firstOrCreate(
            ['name' => 'Main Clubhouse'],
            [
                'society_id' => $society->id,
                'description' => 'Spacious clubhouse for events and gatherings.',
                'booking_fee' => 500.00,
                'status' => 'active'
            ]
        );

        // 7. Communication
        Notice::firstOrCreate(
            ['title' => 'Annual General Meeting'],
            [
                'society_id' => $society->id,
                'body' => 'The AGM is scheduled for next Sunday at 10 AM in the clubhouse.',
                'priority' => 'high',
                'category' => 'general',
                'published_at' => now(),
                'created_by' => $user->id
            ]
        );

        Poll::firstOrCreate(
            ['title' => 'Swimming Pool Timings'],
            [
                'society_id' => $society->id,
                'description' => 'Should we extend the pool timings until 10 PM during summer?',
                'options' => ['Yes', 'No', 'Maybe'],
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'created_by' => $user->id
            ]
        );

        // 8. Staff & Vendors
        Staff::firstOrCreate(
            ['name' => 'Amit Kumar'],
            [
                'society_id' => $society->id,
                'staff_type' => 'security',
                'phone' => '9000011111',
                'status' => 'active'
            ]
        );

        Vendor::firstOrCreate(
            ['name' => 'CleanPro Services'],
            [
                'society_id' => $society->id,
                'category' => 'housekeeping',
                'contact_person' => 'Suresh',
                'phone' => '9222233333',
                'email' => 'contact@cleanpro.com'
            ]
        );

        // 9. Documents
        Document::firstOrCreate(
            ['title' => 'Society Bylaws 2026'],
            [
                'society_id' => $society->id,
                'category' => 'legal',
                'file_type' => 'pdf',
                'file_path' => 'docs/bylaws.pdf'
            ]
        );

        $this->command->info('V2 Data Seeded successfully!');
    }
}
