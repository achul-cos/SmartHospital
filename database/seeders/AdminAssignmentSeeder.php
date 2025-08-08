<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\AdminAssignment;

class AdminAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all admins
        $admins = Admin::all();
        
        if ($admins->count() > 0) {
            foreach ($admins as $admin) {
                // Check if assignment already exists
                $existingAssignment = AdminAssignment::where('admin_id', $admin->id)->first();
                
                if (!$existingAssignment) {
                    AdminAssignment::create([
                        'admin_id' => $admin->id,
                        'status' => 'online',
                        'max_concurrent_chats' => 5,
                        'current_chats' => 0,
                        'specializations' => ['general', 'technical', 'support'],
                        'last_activity_at' => now(),
                    ]);
                } else {
                    // Update existing assignment to ensure it's online
                    $existingAssignment->update([
                        'status' => 'online',
                        'max_concurrent_chats' => 5,
                        'current_chats' => 0,
                        'last_activity_at' => now(),
                    ]);
                }
            }
        } else {
            // Create sample admin assignments if no admins exist
            AdminAssignment::create([
                'admin_id' => 1,
                'status' => 'online',
                'max_concurrent_chats' => 5,
                'current_chats' => 0,
                'specializations' => ['general', 'technical', 'support'],
                'last_activity_at' => now(),
            ]);
            
            AdminAssignment::create([
                'admin_id' => 2,
                'status' => 'online',
                'max_concurrent_chats' => 3,
                'current_chats' => 1,
                'specializations' => ['general', 'billing'],
                'last_activity_at' => now(),
            ]);
            
            AdminAssignment::create([
                'admin_id' => 3,
                'status' => 'busy',
                'max_concurrent_chats' => 4,
                'current_chats' => 3,
                'specializations' => ['technical', 'support'],
                'last_activity_at' => now(),
            ]);
        }
    }
}
